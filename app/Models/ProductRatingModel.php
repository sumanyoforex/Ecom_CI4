<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductRatingModel extends Model
{
    protected $table = 'product_ratings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'order_id', 'product_id', 'rating', 'review'];
    protected $useTimestamps = true;

    /** @return array<int, array<string, mixed>> */
    public function getOrderRatingsByUser(int $orderId, int $userId): array
    {
        return $this->where('order_id', $orderId)
            ->where('user_id', $userId)
            ->findAll();
    }

    public function upsertRating(int $userId, int $orderId, int $productId, int $rating, string $review = ''): void
    {
        $existing = $this->where('user_id', $userId)
            ->where('order_id', $orderId)
            ->where('product_id', $productId)
            ->first();

        $payload = [
            'rating' => $rating,
            'review' => $review !== '' ? $review : null,
        ];

        if ($existing) {
            $this->update((int)$existing['id'], $payload);
            return;
        }

        $payload['user_id'] = $userId;
        $payload['order_id'] = $orderId;
        $payload['product_id'] = $productId;

        $this->insert($payload);
    }

    /** @return array{avg_rating: float, rating_count: int} */
    public function getProductRatingStats(int $productId): array
    {
        $row = $this->select('COALESCE(AVG(rating), 0) AS avg_rating, COUNT(*) AS rating_count')
            ->where('product_id', $productId)
            ->first() ?? [];

        return [
            'avg_rating' => isset($row['avg_rating']) ? round((float)$row['avg_rating'], 1) : 0.0,
            'rating_count' => (int)($row['rating_count'] ?? 0),
        ];
    }

    /** @return array<int, array<string, mixed>> */
    public function getRecentReviewsForProduct(int $productId, int $limit = 8): array
    {
        return $this->db->table('product_ratings pr')
            ->select('pr.rating, pr.review, pr.created_at, u.name AS user_name')
            ->join('users u', 'u.id = pr.user_id', 'left')
            ->where('pr.product_id', $productId)
            ->orderBy('pr.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }
}
