<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Stores customer ratings/reviews for purchased products.
 */
class CreateProductRatingsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'INTEGER', 'auto_increment' => true],
            'user_id' => ['type' => 'INTEGER'],
            'order_id' => ['type' => 'INTEGER'],
            'product_id' => ['type' => 'INTEGER'],
            'rating' => ['type' => 'INTEGER', 'constraint' => 1],
            'review' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('product_ratings');

        $this->db->query('CREATE INDEX IF NOT EXISTS idx_product_ratings_product ON product_ratings(product_id)');
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_product_ratings_user ON product_ratings(user_id)');
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_product_ratings_order ON product_ratings(order_id)');
        $this->db->query('CREATE UNIQUE INDEX IF NOT EXISTS uq_product_ratings_order_product_user ON product_ratings(order_id, product_id, user_id)');
    }

    public function down(): void
    {
        $this->db->query('DROP INDEX IF EXISTS uq_product_ratings_order_product_user');
        $this->db->query('DROP INDEX IF EXISTS idx_product_ratings_order');
        $this->db->query('DROP INDEX IF EXISTS idx_product_ratings_user');
        $this->db->query('DROP INDEX IF EXISTS idx_product_ratings_product');
        $this->forge->dropTable('product_ratings');
    }
}
