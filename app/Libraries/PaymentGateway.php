<?php

namespace App\Libraries;

class PaymentGateway
{
    private string $provider;
    private string $webhookSecret;

    public function __construct()
    {
        $this->provider = strtolower((string)env('PAYMENT_PROVIDER', 'mockpay'));
        $this->webhookSecret = (string)env('PAYMENT_WEBHOOK_SECRET', '');
    }

    public function provider(): string
    {
        return $this->provider;
    }

    public function createPaymentIntent(string $orderNumber, float $amount): array
    {
        $intentId = 'pay_' . bin2hex(random_bytes(6));

        return [
            'provider' => $this->provider,
            'provider_payment_id' => $intentId,
            'status' => 'initiated',
            'payment_url' => base_url('account?pay=' . urlencode($orderNumber)),
            'amount' => round($amount, 2),
            'currency' => (string)env('PAYMENT_CURRENCY', 'USD'),
        ];
    }

    public function verifyWebhookSignature(string $payload, ?string $signature): bool
    {
        if (ENVIRONMENT !== 'production' && $this->webhookSecret === '') {
            return true;
        }

        if ($this->webhookSecret === '' || !$signature) {
            return false;
        }

        $expected = hash_hmac('sha256', $payload, $this->webhookSecret);

        return hash_equals($expected, $signature);
    }
}
