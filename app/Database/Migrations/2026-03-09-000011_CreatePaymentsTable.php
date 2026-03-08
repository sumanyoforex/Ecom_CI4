<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Stores payment attempts per order.
 */
class CreatePaymentsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'INTEGER', 'auto_increment' => true],
            'order_id' => ['type' => 'INTEGER'],
            'provider' => ['type' => 'VARCHAR', 'constraint' => 40, 'default' => 'mockpay'],
            'provider_payment_id' => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'amount' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'currency' => ['type' => 'VARCHAR', 'constraint' => 10, 'default' => 'USD'],
            'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'pending'],
            'payment_url' => ['type' => 'TEXT', 'null' => true],
            'raw_payload' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('payments');

        $this->db->query('CREATE INDEX IF NOT EXISTS idx_payments_order_id ON payments(order_id)');
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_payments_status ON payments(status)');
    }

    public function down(): void
    {
        $this->db->query('DROP INDEX IF EXISTS idx_payments_status');
        $this->db->query('DROP INDEX IF EXISTS idx_payments_order_id');
        $this->forge->dropTable('payments');
    }
}
