<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/** Migration: Create 'cart' table (persistent cart storage) */
class CreateCart extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'         => ['type' => 'INTEGER', 'auto_increment' => true],
            // session_id for guests, user_id for logged-in users
            'session_id' => ['type' => 'VARCHAR', 'constraint' => 128, 'null' => true],
            'user_id'    => ['type' => 'INTEGER', 'null' => true],
            'product_id' => ['type' => 'INTEGER'],
            'qty'        => ['type' => 'INTEGER', 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('cart');
    }

    public function down(): void
    {
        $this->forge->dropTable('cart');
    }
}
