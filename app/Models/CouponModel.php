<?php

namespace App\Models;

use CodeIgniter\Model;

class CouponModel extends Model
{
    protected $table = 'coupons';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'code',
        'description',
        'type',
        'value',
        'min_order_amount',
        'max_discount_amount',
        'usage_limit',
        'used_count',
        'starts_at',
        'expires_at',
        'is_active',
    ];
    protected $useTimestamps = true;

    public function findValidCoupon(string $rawCode): ?array
    {
        $code = strtoupper(trim($rawCode));
        if ($code === '') {
            return null;
        }

        $now = date('Y-m-d H:i:s');
        $coupon = $this->where('code', $code)
            ->where('is_active', 1)
            ->groupStart()
                ->where('starts_at IS NULL')
                ->orWhere('starts_at <=', $now)
            ->groupEnd()
            ->groupStart()
                ->where('expires_at IS NULL')
                ->orWhere('expires_at >=', $now)
            ->groupEnd()
            ->first();

        if (!$coupon) {
            return null;
        }

        if (!empty($coupon['usage_limit']) && (int)$coupon['used_count'] >= (int)$coupon['usage_limit']) {
            return null;
        }

        return $coupon;
    }

    public function calculateDiscount(array $coupon, float $subtotal): float
    {
        if ($subtotal <= 0) {
            return 0.0;
        }

        if ($subtotal < (float)$coupon['min_order_amount']) {
            return 0.0;
        }

        $discount = 0.0;
        if ($coupon['type'] === 'fixed') {
            $discount = (float)$coupon['value'];
        } else {
            $discount = ($subtotal * (float)$coupon['value']) / 100;
        }

        if (!empty($coupon['max_discount_amount'])) {
            $discount = min($discount, (float)$coupon['max_discount_amount']);
        }

        return round(max(0, min($discount, $subtotal)), 2);
    }
}
