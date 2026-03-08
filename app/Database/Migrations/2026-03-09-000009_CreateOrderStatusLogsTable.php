<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Tracks order status transitions for operational visibility.
 */
class CreateOrderStatusLogsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'INTEGER', 'auto_increment' => true],
            'order_id' => ['type' => 'INTEGER'],
            'from_status' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'to_status' => ['type' => 'VARCHAR', 'constraint' => 30],
            'note' => ['type' => 'TEXT', 'null' => true],
            'changed_by' => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('order_status_logs');

        $this->db->query('CREATE INDEX IF NOT EXISTS idx_order_status_logs_order_id ON order_status_logs(order_id)');
    }

    public function down(): void
    {
        $this->db->query('DROP INDEX IF EXISTS idx_order_status_logs_order_id');
        $this->forge->dropTable('order_status_logs');
    }
}
