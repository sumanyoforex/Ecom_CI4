<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Adds production-grade order tracking and totals breakdown fields.
 */
class AddProductionOrderFields extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('orders', [
            'order_number' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'subtotal' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'discount_total' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'shipping_total' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'tax_total' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'coupon_code' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'payment_status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'pending'],
            'transaction_ref' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
        ]);

        $this->db->query('CREATE INDEX IF NOT EXISTS idx_orders_user_status ON orders(user_id, status)');
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_orders_created_at ON orders(created_at)');
        $this->db->query('CREATE UNIQUE INDEX IF NOT EXISTS idx_orders_order_number ON orders(order_number)');
    }

    public function down(): void
    {
        $this->db->query('DROP INDEX IF EXISTS idx_orders_order_number');
        $this->db->query('DROP INDEX IF EXISTS idx_orders_created_at');
        $this->db->query('DROP INDEX IF EXISTS idx_orders_user_status');

        $this->forge->dropColumn('orders', [
            'order_number',
            'subtotal',
            'discount_total',
            'shipping_total',
            'tax_total',
            'coupon_code',
            'payment_status',
            'transaction_ref',
        ]);
    }
}
