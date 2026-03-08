<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * OrderModel manages customer orders and detailed retrieval.
 */
class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'order_number',
        'subtotal',
        'discount_total',
        'shipping_total',
        'tax_total',
        'total',
        'status',
        'shipping_address',
        'payment_method',
        'coupon_code',
        'payment_status',
        'transaction_ref',
    ];
    protected $useTimestamps = true;

    public function getOrdersByUser(int $userId): array
    {
        return $this->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    public function getOrderWithItems(int $orderId): array
    {
        $order = $this->find($orderId);
        if (!$order) {
            return [];
        }

        $order['items'] = $this->db->table('order_items oi')
            ->select('oi.product_id, oi.qty, oi.price, p.name, p.image_url')
            ->join('products p', 'p.id = oi.product_id')
            ->where('oi.order_id', $orderId)
            ->get()
            ->getResultArray();

        $order['status_logs'] = $this->db->table('order_status_logs')
            ->where('order_id', $orderId)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResultArray();

        return $order;
    }

    public function generateOrderNumber(): string
    {
        return 'ORD-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
    }
}
