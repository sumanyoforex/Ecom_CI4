<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\PaymentModel;

class PaymentController extends BaseController
{
    public function mockSuccess(int $orderId)
    {
        $uid = (int)session()->get('user_id');
        $orderModel = new OrderModel();
        $order = $orderModel->find($orderId);

        if (!$order || (int)$order['user_id'] !== $uid) {
            return redirect()->to('/account')->with('error', 'Order not found.');
        }

        if (($order['payment_status'] ?? '') === 'paid') {
            return redirect()->to('/order/' . $orderId)->with('success', 'Payment already marked as paid.');
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $orderModel->update($orderId, [
                'payment_status' => 'paid',
                'status' => in_array($order['status'], ['pending', 'confirmed'], true) ? 'confirmed' : $order['status'],
                'transaction_ref' => 'MOCK-' . strtoupper(bin2hex(random_bytes(4))),
            ]);

            (new PaymentModel())->insert([
                'order_id' => $orderId,
                'provider' => 'mockpay',
                'provider_payment_id' => 'mock_' . bin2hex(random_bytes(5)),
                'amount' => (float)$order['total'],
                'currency' => (string)env('PAYMENT_CURRENCY', 'USD'),
                'status' => 'paid',
                'payment_url' => null,
                'raw_payload' => json_encode(['source' => 'mock_success_button']),
            ]);

            $db->table('order_status_logs')->insert([
                'order_id' => $orderId,
                'from_status' => $order['status'],
                'to_status' => in_array($order['status'], ['pending', 'confirmed'], true) ? 'confirmed' : $order['status'],
                'note' => 'Payment marked as paid via mock success flow',
                'changed_by' => (string)session()->get('user_name'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Unable to mark payment as paid.');
            }

            $db->transCommit();

            return redirect()->to('/order/' . $orderId)->with('success', 'Payment completed successfully.');
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->to('/order/' . $orderId)->with('error', 'Unable to process payment at this time.');
        }
    }
}
