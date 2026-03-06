<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * CartModel — stores cart items in the database.
 *
 * Items are linked by session_id (for guests) or user_id (for logged-in users).
 * When a user logs in, their guest cart can be merged with their account.
 */
class CartModel extends Model
{
    protected $table         = 'cart';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['session_id', 'user_id', 'product_id', 'qty'];
    protected $useTimestamps = true;

    /**
     * Get cart items with product details for a given session or user.
     * Uses a JOIN so we get product name, price, image in one query.
     */
    public function getCartItems(string $sessionId, int $userId = 0): array
    {
        $builder = $this->db->table('cart c')
            ->select('c.id, c.qty, p.name, p.slug, p.image_url,
                      COALESCE(p.sale_price, p.price) AS unit_price,
                      (c.qty * COALESCE(p.sale_price, p.price)) AS subtotal')
            ->join('products p', 'p.id = c.product_id');

        if ($userId > 0) {
            $builder->where('c.user_id', $userId);
        } else {
            $builder->where('c.session_id', $sessionId);
        }

        return $builder->get()->getResultArray();
    }

    /** Clear all cart items for a user/session after checkout */
    public function clearCart(string $sessionId, int $userId = 0): void
    {
        if ($userId > 0) {
            $this->where('user_id', $userId)->delete();
        } else {
            $this->where('session_id', $sessionId)->delete();
        }
    }
}
