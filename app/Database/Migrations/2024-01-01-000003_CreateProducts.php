<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/** Migration: Create 'products' table */
class CreateProducts extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'          => ['type' => 'INTEGER', 'auto_increment' => true],
            'category_id' => ['type' => 'INTEGER'],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 200],
            'slug'        => ['type' => 'VARCHAR', 'constraint' => 200],
            'description' => ['type' => 'TEXT', 'null' => true],
            'price'       => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'sale_price'  => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true],
            'stock'       => ['type' => 'INTEGER', 'default' => 0],
            'image_url'   => ['type' => 'TEXT'],
            // 'active' = visible in shop, 'draft' = hidden
            'status'      => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'active'],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('products');
    }

    public function down(): void
    {
        $this->forge->dropTable('products');
    }
}
