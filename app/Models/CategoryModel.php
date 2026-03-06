<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * CategoryModel — all DB operations for product categories.
 * e.g. "Electronics", "Clothing", "Books"
 */
class CategoryModel extends Model
{
    protected $table         = 'categories';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['name', 'slug', 'image_url'];
    protected $useTimestamps = true;

    /** Get a category by its URL slug */
    public function findBySlug(string $slug): ?array
    {
        return $this->where('slug', $slug)->first();
    }

    /** Get all categories for nav/dropdown */
    public function allCategories(): array
    {
        return $this->orderBy('name', 'ASC')->findAll();
    }
}
