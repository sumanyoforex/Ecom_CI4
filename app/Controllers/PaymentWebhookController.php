<?php

namespace App\Controllers;

use App\Libraries\NotificationService;
use App\Libraries\PaymentGateway;
use App\Models\OrderModel;
use App\Models\PaymentModel;
use App\Models\WebhookEventModel;

class PaymentWebhookController extends BaseController
{
    public function handle()
    {
        $raw = (string)$this->request->getBody();
        $payload = json_decode($raw, true);

        if (!is_array($payload)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid JSON payload']);
        }

        $provider = strtolower((string)($payload['provider'] ?? env('PAYMENT_PROVIDER', 'mockpay')));
        $eventId = (string)($payload['event_id'] ?? '');
        $eventType = (string)($payload['event_type'] ?? 'payment.unknown');

        if ($eventId === '') {
            return $this->response->setStatusCode(422)->setJSON(['error' => 'event_id is required']);
        }

        $gateway = new PaymentGateway();
        $signature = $this->request->getHeaderLine('X-Payment-Signature');
        if (!$gateway->verifyWebhookSignature($raw, $signature)) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Invalid webhook signature']);
        }

        $events = new WebhookEventModel();
        $existing = $events->where('provider', $provider)->where('event_id', $eventId)->first();
        if ($existing) {
            return $this->response->setJSON(['status' => 'duplicate_ignored']);
        }

        $events->insert([
            'provider' => $provider,
            'event_id' => $eventId,
            'event_type' => $eventType,
            'payload' => $raw,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $orderNumber = (string)($payload['data']['order_number'] ?? '');
        $paymentStatus = strtolower((string)($payload['data']['status'] ?? 'pending'));
        $providerPaymentId = (string)($payload['data']['provider_payment_id'] ?? '');
        $amount = (float)($payload['data']['amount'] ?? 0);

        $order = null;
        if ($orderNumber !== '') {
            $order = (new OrderModel())->where('order_number', $orderNumber)->first();
        }

        if (!$order) {
            $events->where('provider', $provider)->where('event_id', $eventId)->set(['processed_at' => date('Y-m-d H:i:s')])->update();
            return $this->response->setJSON(['status' => 'ignored', 'message' => 'Order not found']);
        }

        $orderUpdate = [];
        $newOrderStatus = $order['status'];
        if ($paymentStatus === 'paid') {
            $orderUpdate['payment_status'] = 'paid';
            $orderUpdate['transaction_ref'] = $providerPaymentId ?: ($order['transaction_ref'] ?? null);
            if (in_array($order['status'], ['pending', 'confirmed'], true)) {
                $newOrderStatus = 'confirmed';
                $orderUpdate['status'] = 'confirmed';
            }
        } elseif ($paymentStatus === 'failed') {
            $orderUpdate['payment_status'] = 'failed';
        } elseif ($paymentStatus === 'refunded') {
            $orderUpdate['payment_status'] = 'refunded';
            $newOrderStatus = 'refunded';
            $orderUpdate['status'] = 'refunded';
        }

        if ($orderUpdate !== []) {
            (new OrderModel())->update((int)$order['id'], $orderUpdate);
        }

        (new PaymentModel())->insert([
            'order_id' => (int)$order['id'],
            'provider' => $provider,
            'provider_payment_id' => $providerPaymentId,
            'amount' => $amount > 0 ? $amount : (float)$order['total'],
            'currency' => (string)env('PAYMENT_CURRENCY', 'USD'),
            'status' => $paymentStatus,
            'payment_url' => null,
            'raw_payload' => $raw,
        ]);

        if ($newOrderStatus !== $order['status']) {
            \Config\Database::connect()->table('order_status_logs')->insert([
                'order_id' => (int)$order['id'],
                'from_status' => $order['status'],
                'to_status' => $newOrderStatus,
                'note' => 'Updated via payment webhook: ' . $paymentStatus,
                'changed_by' => 'payment-webhook',
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        $events->where('provider', $provider)->where('event_id', $eventId)->set(['processed_at' => date('Y-m-d H:i:s')])->update();

        $recipient = \Config\Database::connect()->table('users')->select('email')->where('id', (int)$order['user_id'])->get()->getRowArray();
        if (!empty($recipient['email'])) {
            (new NotificationService())->sendEmail(
                (int)$order['user_id'],
                (string)$recipient['email'],
                'Payment update for ' . ($order['order_number'] ?: ('#' . $order['id'])),
                'Your payment status is now: ' . strtoupper($paymentStatus)
            );
        }

        return $this->response->setJSON(['status' => 'processed']);
    }
}
