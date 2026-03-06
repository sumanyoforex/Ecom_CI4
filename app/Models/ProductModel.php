<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * ProductModel — all DB operations for the products table.
 */
class ProductModel extends Model
{
    protected $table         = 'products';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'category_id', 'name', 'slug', 'description',
        'price', 'sale_price', 'stock', 'image_url', 'status'
    ];
    protected $useTimestamps = true;

    /** Get a product by URL slug */
    public function findBySlug(string $slug): ?array
    {
        return $this->where('slug', $slug)->first();
    }

    /**
     * Get products for the shop listing.
     * Supports optional filters: category, search keyword.
     */
    public function getProducts(int $categoryId = 0, string $search = ''): array
    {
        $builder = $this->where('status', 'active');

        if ($categoryId > 0) {
            $builder->where('category_id', $categoryId);
        }

        if ($search !== '') {
            $builder->like('name', $search);
        }

        return $builder->orderBy('created_at', 'DESC')->findAll();
    }

    /** Get 8 featured products for the homepage */
    public function getFeatured(int $limit = 8): array
    {
        return $this->where('status', 'active')
                    ->orderBy('created_at', 'DESC')
                    ->findAll($limit);
    }
}
