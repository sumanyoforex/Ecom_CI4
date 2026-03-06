<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/** Migration: Create 'orders' table */
class CreateOrders extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'               => ['type' => 'INTEGER', 'auto_increment' => true],
            'user_id'          => ['type' => 'INTEGER'],
            'total'            => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            // pending → processing → shipped → delivered → cancelled
            'status'           => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'pending'],
            'shipping_address' => ['type' => 'TEXT'],
            'payment_method'   => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'COD'],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('orders');
    }

    public function down(): void
    {
        $this->forge->dropTable('orders');
    }
}
