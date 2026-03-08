<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');
        $data = [
            [
                'code' => 'WELCOME10',
                'description' => '10% off for new customers',
                'type' => 'percent',
                'value' => 10,
                'min_order_amount' => 50,
                'max_discount_amount' => 25,
                'usage_limit' => null,
                'used_count' => 0,
                'starts_at' => null,
                'expires_at' => null,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'SHIPFREE',
                'description' => 'Flat $7 discount to offset shipping',
                'type' => 'fixed',
                'value' => 7,
                'min_order_amount' => 40,
                'max_discount_amount' => null,
                'usage_limit' => null,
                'used_count' => 0,
                'starts_at' => null,
                'expires_at' => null,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        $this->db->table('coupons')->ignore(true)->insertBatch($data);
    }
}
