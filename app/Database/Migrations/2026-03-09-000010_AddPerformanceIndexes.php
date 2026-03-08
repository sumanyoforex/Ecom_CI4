<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Adds indexes for frequent product/cart/order lookup paths.
 */
class AddPerformanceIndexes extends Migration
{
    public function up(): void
    {
        $this->db->query('CREATE UNIQUE INDEX IF NOT EXISTS idx_products_slug ON products(slug)');
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_products_category_status ON products(category_id, status)');
        $this->db->query('CREATE UNIQUE INDEX IF NOT EXISTS idx_categories_slug ON categories(slug)');
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_cart_user_product ON cart(user_id, product_id)');
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_cart_session_product ON cart(session_id, product_id)');
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_order_items_order_id ON order_items(order_id)');
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_users_email ON users(email)');
    }

    public function down(): void
    {
        $this->db->query('DROP INDEX IF EXISTS idx_users_email');
        $this->db->query('DROP INDEX IF EXISTS idx_order_items_order_id');
        $this->db->query('DROP INDEX IF EXISTS idx_cart_session_product');
        $this->db->query('DROP INDEX IF EXISTS idx_cart_user_product');
        $this->db->query('DROP INDEX IF EXISTS idx_categories_slug');
        $this->db->query('DROP INDEX IF EXISTS idx_products_category_status');
        $this->db->query('DROP INDEX IF EXISTS idx_products_slug');
    }
}
