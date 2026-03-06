<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration: Create 'categories' table
 * Run with: php spark migrate
 */
class CreateCategories extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'        => ['type' => 'INTEGER', 'auto_increment' => true],
            'name'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'slug'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'image_url' => ['type' => 'TEXT'],
            'created_at'=> ['type' => 'DATETIME', 'null' => true],
            'updated_at'=> ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('categories');
    }

    public function down(): void
    {
        $this->forge->dropTable('categories');
    }
}
