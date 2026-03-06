<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * OrderModel — manages customer orders.
 */
class OrderModel extends Model
{
    protected $table         = 'orders';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['user_id', 'total', 'status', 'shipping_address', 'payment_method'];
    protected $useTimestamps = true;

    /** Get all orders for a specific user (order history page) */
    public function getOrdersByUser(int $userId): array
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /** Get one order WITH its line items (for order detail page) */
    public function getOrderWithItems(int $orderId): array
    {
        $order = $this->find($orderId);
        if (!$order) return [];

        $order['items'] = $this->db->table('order_items oi')
            ->select('oi.qty, oi.price, p.name, p.image_url')
            ->join('products p', 'p.id = oi.product_id')
            ->where('oi.order_id', $orderId)
            ->get()->getResultArray();

        return $order;
    }
}
