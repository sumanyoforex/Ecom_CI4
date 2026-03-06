<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * CategorySeeder — inserts 6 product categories.
 * Images from picsum.photos (free, no API key needed).
 */
class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics',  'slug' => 'electronics',  'image_url' => 'https://picsum.photos/seed/electronics/400/300'],
            ['name' => 'Clothing',     'slug' => 'clothing',     'image_url' => 'https://picsum.photos/seed/clothing/400/300'],
            ['name' => 'Books',        'slug' => 'books',        'image_url' => 'https://picsum.photos/seed/books/400/300'],
            ['name' => 'Home & Garden','slug' => 'home-garden',  'image_url' => 'https://picsum.photos/seed/home/400/300'],
            ['name' => 'Sports',       'slug' => 'sports',       'image_url' => 'https://picsum.photos/seed/sports/400/300'],
            ['name' => 'Beauty',       'slug' => 'beauty',       'image_url' => 'https://picsum.photos/seed/beauty/400/300'],
        ];

        $now = date('Y-m-d H:i:s');
        foreach ($categories as &$cat) {
            $cat['created_at'] = $now;
            $cat['updated_at'] = $now;
        }

        $this->db->table('categories')->insertBatch($categories);
        echo "CategorySeeder: " . count($categories) . " categories inserted.\n";
    }
}
