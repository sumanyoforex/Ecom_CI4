<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * CategorySeeder inserts/updates storefront categories.
 * Idempotent: running multiple times will not duplicate categories.
 */
class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics',      'slug' => 'electronics',      'image_url' => 'https://picsum.photos/seed/electronics/600/420'],
            ['name' => 'Clothing',         'slug' => 'clothing',         'image_url' => 'https://picsum.photos/seed/clothing/600/420'],
            ['name' => 'Books',            'slug' => 'books',            'image_url' => 'https://picsum.photos/seed/books/600/420'],
            ['name' => 'Home & Garden',    'slug' => 'home-garden',      'image_url' => 'https://picsum.photos/seed/home-garden/600/420'],
            ['name' => 'Sports',           'slug' => 'sports',           'image_url' => 'https://picsum.photos/seed/sports/600/420'],
            ['name' => 'Beauty',           'slug' => 'beauty',           'image_url' => 'https://picsum.photos/seed/beauty/600/420'],
            ['name' => 'Toys & Games',     'slug' => 'toys-games',       'image_url' => 'https://picsum.photos/seed/toys-games/600/420'],
            ['name' => 'Office Supplies',  'slug' => 'office-supplies',  'image_url' => 'https://picsum.photos/seed/office-supplies/600/420'],
            ['name' => 'Grocery',          'slug' => 'grocery',          'image_url' => 'https://picsum.photos/seed/grocery/600/420'],
            ['name' => 'Pet Care',         'slug' => 'pet-care',         'image_url' => 'https://picsum.photos/seed/pet-care/600/420'],
        ];

        $table = $this->db->table('categories');
        $now = date('Y-m-d H:i:s');
        $inserted = 0;
        $updated = 0;

        foreach ($categories as $cat) {
            $existing = $table->where('slug', $cat['slug'])->get()->getRowArray();

            if ($existing) {
                $table->where('id', (int)$existing['id'])->update([
                    'name' => $cat['name'],
                    'image_url' => $cat['image_url'],
                    'updated_at' => $now,
                ]);
                $updated++;
                continue;
            }

            $table->insert([
                'name' => $cat['name'],
                'slug' => $cat['slug'],
                'image_url' => $cat['image_url'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $inserted++;
        }

        echo "CategorySeeder: {$inserted} inserted, {$updated} updated.\n";
    }
}
