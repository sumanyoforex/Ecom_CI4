<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/** Migration: Create 'order_items' table (line items inside an order) */
class CreateOrderItems extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'         => ['type' => 'INTEGER', 'auto_increment' => true],
            'order_id'   => ['type' => 'INTEGER'],
            'product_id' => ['type' => 'INTEGER'],
            'qty'        => ['type' => 'INTEGER'],
            // Price is stored at time of purchase (price may change later)
            'price'      => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('order_items');
    }

    public function down(): void
    {
        $this->forge->dropTable('order_items');
    }
}
