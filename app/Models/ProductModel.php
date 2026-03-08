<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Product model with storefront filtering and sorting support.
 */
class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'sale_price',
        'stock',
        'image_url',
        'status',
    ];
    protected $useTimestamps = true;

    public function findBySlug(string $slug): ?array
    {
        return $this->where('slug', $slug)->first();
    }

    public function getProducts(int $categoryId = 0, string $search = '', string $sort = 'newest', float $minPrice = 0, float $maxPrice = 0): array
    {
        $builder = $this->where('status', 'active');

        if ($categoryId > 0) {
            $builder->where('category_id', $categoryId);
        }

        if ($search !== '') {
            $builder->like('name', $search);
        }

        if ($minPrice > 0) {
            $builder->where('COALESCE(sale_price, price) >=', $minPrice);
        }

        if ($maxPrice > 0) {
            $builder->where('COALESCE(sale_price, price) <=', $maxPrice);
        }

        switch ($sort) {
            case 'price_asc':
                $builder->orderBy('COALESCE(sale_price, price)', 'ASC', false);
                break;
            case 'price_desc':
                $builder->orderBy('COALESCE(sale_price, price)', 'DESC', false);
                break;
            case 'name_asc':
                $builder->orderBy('name', 'ASC');
                break;
            default:
                $builder->orderBy('created_at', 'DESC');
                break;
        }

        return $builder->findAll();
    }

    public function getFeatured(int $limit = 8): array
    {
        return $this->where('status', 'active')
            ->orderBy('created_at', 'DESC')
            ->findAll($limit);
    }
}
