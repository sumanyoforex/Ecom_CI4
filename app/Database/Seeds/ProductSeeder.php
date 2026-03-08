<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * ProductSeeder inserts/updates a startup-sized catalog.
 * Idempotent: products are upserted by slug.
 */
class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categoryMap = $this->fetchCategoryMap();

        $catalog = [
            'electronics' => [
                'Wireless Headphones', 'Bluetooth Speaker', 'Smart Watch', '4K Streaming Stick',
                'Noise Cancelling Earbuds', 'Portable Power Bank 20000mAh', 'Mechanical Keyboard',
            ],
            'clothing' => [
                'Classic White T-Shirt', 'Slim Fit Jeans', 'Leather Jacket', 'Running Hoodie',
                'Cotton Polo Shirt', 'Casual Chino Pants', 'Everyday Sneakers',
            ],
            'books' => [
                'Clean Code', 'The Pragmatic Programmer', 'Design Patterns', 'Atomic Habits',
                'Deep Work', 'Zero to One', 'Thinking, Fast and Slow',
            ],
            'home-garden' => [
                'Scented Candle Set', 'Indoor Plant Kit', 'Throw Pillow Set', 'Non-Stick Cookware Set',
                'Air Purifier', 'Ergonomic Desk Lamp', 'Memory Foam Pillow',
            ],
            'sports' => [
                'Yoga Mat', 'Resistance Bands Set', 'Running Shoes', 'Adjustable Dumbbells',
                'Smart Jump Rope', 'Hydration Water Bottle', 'Cycling Helmet',
            ],
            'beauty' => [
                'Vitamin C Serum', 'Lip Gloss Collection', 'Face Moisturizer SPF50', 'Hyaluronic Acid Serum',
                'Nourishing Hair Mask', 'Matte Finish Foundation', 'Reusable Makeup Brush Set',
            ],
            'toys-games' => [
                'Magnetic Building Tiles', 'Remote Control Drift Car', 'STEM Robot Kit', 'Family Strategy Board Game',
                '3D Puzzle Set', 'Action Figure Pack', 'Arcade Mini Console',
            ],
            'office-supplies' => [
                'Executive Office Chair', 'Adjustable Laptop Stand', 'Wireless Presenter', 'A4 Premium Paper Pack',
                'Gel Ink Pen Set', 'Noise Reduction Headset', 'Whiteboard Starter Kit',
            ],
            'grocery' => [
                'Organic Basmati Rice 5kg', 'Cold Brew Coffee Beans', 'Almond Butter Jar', 'Mixed Dry Fruits Box',
                'Green Tea Collection', 'Protein Oats Blend', 'Dark Chocolate Snack Pack',
            ],
            'pet-care' => [
                'Orthopedic Pet Bed', 'Automatic Pet Feeder', 'Interactive Cat Toy', 'Dog Grooming Kit',
                'Pet Travel Water Bottle', 'Natural Pet Shampoo', 'Training Treat Variety Box',
            ],
        ];

        $descriptions = [
            'Premium quality build for everyday reliability.',
            'Customer favorite with excellent value and performance.',
            'Designed for comfort, durability, and modern lifestyle.',
            'High-demand pick with fast-moving inventory.',
            'Startup bestseller with strong repeat purchase rate.',
            'Balanced performance and price for daily use.',
            'Curated product selected for quality-first shoppers.',
        ];

        $table = $this->db->table('products');
        $now = date('Y-m-d H:i:s');
        $inserted = 0;
        $updated = 0;

        foreach ($catalog as $categorySlug => $names) {
            if (!isset($categoryMap[$categorySlug])) {
                continue;
            }

            $categoryId = (int)$categoryMap[$categorySlug];

            foreach ($names as $idx => $name) {
                $slug = $this->slugify($name);
                $seed = str_replace('-', '_', $slug);

                $basePrice = $this->priceFor($categorySlug, $idx);
                $discountEligible = (($idx + strlen($categorySlug)) % 3) !== 0;
                $salePrice = $discountEligible ? round($basePrice * (0.78 + (($idx % 3) * 0.04)), 2) : null;

                $row = [
                    'category_id' => $categoryId,
                    'name' => $name,
                    'slug' => $slug,
                    'description' => $descriptions[$idx % count($descriptions)],
                    'price' => $basePrice,
                    'sale_price' => $salePrice,
                    'stock' => 25 + (($idx + $categoryId) * 9 % 180),
                    'image_url' => "https://picsum.photos/seed/{$seed}/800/600",
                    'status' => 'active',
                    'updated_at' => $now,
                ];

                $existing = $table->where('slug', $slug)->get()->getRowArray();
                if ($existing) {
                    $table->where('id', (int)$existing['id'])->update($row);
                    $updated++;
                    continue;
                }

                $row['created_at'] = $now;
                $table->insert($row);
                $inserted++;
            }
        }

        echo "ProductSeeder: {$inserted} inserted, {$updated} updated (target catalog: 70 products).\n";
    }

    /** @return array<string,int> */
    private function fetchCategoryMap(): array
    {
        $rows = $this->db->table('categories')->select('id, slug')->get()->getResultArray();
        $map = [];
        foreach ($rows as $row) {
            $map[(string)$row['slug']] = (int)$row['id'];
        }
        return $map;
    }

    private function slugify(string $name): string
    {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug) ?? '';
        return trim($slug, '-');
    }

    private function priceFor(string $categorySlug, int $index): float
    {
        $bases = [
            'electronics' => 59,
            'clothing' => 24,
            'books' => 16,
            'home-garden' => 22,
            'sports' => 26,
            'beauty' => 18,
            'toys-games' => 20,
            'office-supplies' => 14,
            'grocery' => 9,
            'pet-care' => 17,
        ];

        $base = $bases[$categorySlug] ?? 20;
        $multiplier = 1 + (($index % 5) * 0.34);
        return round($base * $multiplier, 2);
    }
}
