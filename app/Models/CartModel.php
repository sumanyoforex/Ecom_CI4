<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * CartModel stores cart items in the database.
 */
class CartModel extends Model
{
    protected $table = 'cart';
    protected $primaryKey = 'id';
    protected $allowedFields = ['session_id', 'user_id', 'product_id', 'qty'];
    protected $useTimestamps = true;

    public function getCartItems(string $sessionId, int $userId = 0): array
    {
        $builder = $this->db->table('cart c')
            ->select('c.id, c.product_id, c.qty, p.name, p.slug, p.image_url, p.stock,
                      COALESCE(p.sale_price, p.price) AS unit_price,
                      (c.qty * COALESCE(p.sale_price, p.price)) AS subtotal')
            ->join('products p', 'p.id = c.product_id')
            ->where('p.status', 'active');

        if ($userId > 0) {
            $builder->where('c.user_id', $userId);
        } else {
            $builder->where('c.session_id', $sessionId);
        }

        return $builder->get()->getResultArray();
    }

    public function clearCart(string $sessionId, int $userId = 0): void
    {
        if ($userId > 0) {
            $this->where('user_id', $userId)->delete();
        } else {
            $this->where('session_id', $sessionId)->delete();
        }
    }
}
