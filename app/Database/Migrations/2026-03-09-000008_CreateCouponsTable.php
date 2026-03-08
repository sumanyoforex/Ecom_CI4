<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Adds coupon support for marketing campaigns.
 */
class CreateCouponsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'INTEGER', 'auto_increment' => true],
            'code' => ['type' => 'VARCHAR', 'constraint' => 50],
            'description' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'type' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'percent'],
            'value' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'min_order_amount' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'max_discount_amount' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true],
            'usage_limit' => ['type' => 'INTEGER', 'null' => true],
            'used_count' => ['type' => 'INTEGER', 'default' => 0],
            'starts_at' => ['type' => 'DATETIME', 'null' => true],
            'expires_at' => ['type' => 'DATETIME', 'null' => true],
            'is_active' => ['type' => 'INTEGER', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('code');
        $this->forge->createTable('coupons');

        $this->db->query('CREATE INDEX IF NOT EXISTS idx_coupons_active_dates ON coupons(is_active, starts_at, expires_at)');
    }

    public function down(): void
    {
        $this->db->query('DROP INDEX IF EXISTS idx_coupons_active_dates');
        $this->forge->dropTable('coupons');
    }
}
