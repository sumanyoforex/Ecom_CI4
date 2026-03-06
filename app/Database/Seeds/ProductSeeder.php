<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * ProductSeeder — inserts 18 sample products.
 * All images come from picsum.photos (free, no attribution needed).
 * category_id matches the order they were inserted in CategorySeeder:
 *   1=Electronics, 2=Clothing, 3=Books, 4=Home&Garden, 5=Sports, 6=Beauty
 */
class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $now      = date('Y-m-d H:i:s');
        $products = [
            // Electronics (cat 1)
            ['category_id'=>1,'name'=>'Wireless Headphones','slug'=>'wireless-headphones','description'=>'Premium sound with 30hr battery.','price'=>99.99,'sale_price'=>79.99,'stock'=>50,'image_url'=>'https://picsum.photos/seed/headphones/600/400','status'=>'active'],
            ['category_id'=>1,'name'=>'Bluetooth Speaker','slug'=>'bluetooth-speaker','description'=>'Portable waterproof speaker.','price'=>59.99,'sale_price'=>null,'stock'=>30,'image_url'=>'https://picsum.photos/seed/speaker/600/400','status'=>'active'],
            ['category_id'=>1,'name'=>'Smart Watch','slug'=>'smart-watch','description'=>'Track fitness & notifications.','price'=>199.99,'sale_price'=>159.99,'stock'=>20,'image_url'=>'https://picsum.photos/seed/smartwatch/600/400','status'=>'active'],
            // Clothing (cat 2)
            ['category_id'=>2,'name'=>'Classic White T-Shirt','slug'=>'white-tshirt','description'=>'100% cotton, unisex.','price'=>19.99,'sale_price'=>null,'stock'=>200,'image_url'=>'https://picsum.photos/seed/tshirt/600/400','status'=>'active'],
            ['category_id'=>2,'name'=>'Slim Fit Jeans','slug'=>'slim-jeans','description'=>'Stretch denim for all-day comfort.','price'=>49.99,'sale_price'=>39.99,'stock'=>80,'image_url'=>'https://picsum.photos/seed/jeans/600/400','status'=>'active'],
            ['category_id'=>2,'name'=>'Leather Jacket','slug'=>'leather-jacket','description'=>'Genuine leather, timeless style.','price'=>149.99,'sale_price'=>null,'stock'=>15,'image_url'=>'https://picsum.photos/seed/jacket/600/400','status'=>'active'],
            // Books (cat 3)
            ['category_id'=>3,'name'=>'Clean Code','slug'=>'clean-code','description'=>'Robert Martin\'s guide to writing readable code.','price'=>29.99,'sale_price'=>24.99,'stock'=>100,'image_url'=>'https://picsum.photos/seed/cleancode/600/400','status'=>'active'],
            ['category_id'=>3,'name'=>'The Pragmatic Programmer','slug'=>'pragmatic-programmer','description'=>'A classic software engineering book.','price'=>34.99,'sale_price'=>null,'stock'=>75,'image_url'=>'https://picsum.photos/seed/pragmatic/600/400','status'=>'active'],
            ['category_id'=>3,'name'=>'Design Patterns','slug'=>'design-patterns','description'=>'Gang of Four patterns explained.','price'=>39.99,'sale_price'=>32.99,'stock'=>60,'image_url'=>'https://picsum.photos/seed/designpat/600/400','status'=>'active'],
            // Home & Garden (cat 4)
            ['category_id'=>4,'name'=>'Scented Candle Set','slug'=>'scented-candles','description'=>'6 lavender & vanilla candles.','price'=>24.99,'sale_price'=>null,'stock'=>120,'image_url'=>'https://picsum.photos/seed/candles/600/400','status'=>'active'],
            ['category_id'=>4,'name'=>'Indoor Plant Kit','slug'=>'plant-kit','description'=>'Grow herbs on your windowsill.','price'=>34.99,'sale_price'=>28.99,'stock'=>40,'image_url'=>'https://picsum.photos/seed/plants/600/400','status'=>'active'],
            ['category_id'=>4,'name'=>'Throw Pillow Set','slug'=>'throw-pillows','description'=>'Set of 4 decorative pillows.','price'=>44.99,'sale_price'=>null,'stock'=>55,'image_url'=>'https://picsum.photos/seed/pillows/600/400','status'=>'active'],
            // Sports (cat 5)
            ['category_id'=>5,'name'=>'Yoga Mat','slug'=>'yoga-mat','description'=>'Non-slip 6mm thick mat.','price'=>29.99,'sale_price'=>null,'stock'=>90,'image_url'=>'https://picsum.photos/seed/yogamat/600/400','status'=>'active'],
            ['category_id'=>5,'name'=>'Resistance Bands Set','slug'=>'resistance-bands','description'=>'5 levels, home workout ready.','price'=>19.99,'sale_price'=>14.99,'stock'=>150,'image_url'=>'https://picsum.photos/seed/bands/600/400','status'=>'active'],
            ['category_id'=>5,'name'=>'Running Shoes','slug'=>'running-shoes','description'=>'Lightweight cushioned soles.','price'=>89.99,'sale_price'=>69.99,'stock'=>45,'image_url'=>'https://picsum.photos/seed/shoes/600/400','status'=>'active'],
            // Beauty (cat 6)
            ['category_id'=>6,'name'=>'Vitamin C Serum','slug'=>'vitamin-c-serum','description'=>'Brightening face serum, 30ml.','price'=>39.99,'sale_price'=>null,'stock'=>70,'image_url'=>'https://picsum.photos/seed/serum/600/400','status'=>'active'],
            ['category_id'=>6,'name'=>'Lip Gloss Collection','slug'=>'lip-gloss','description'=>'12 shades, long lasting.','price'=>22.99,'sale_price'=>18.99,'stock'=>110,'image_url'=>'https://picsum.photos/seed/lipgloss/600/400','status'=>'active'],
            ['category_id'=>6,'name'=>'Face Moisturizer SPF50','slug'=>'face-moisturizer','description'=>'Daily SPF50 moisturizer, all skin types.','price'=>34.99,'sale_price'=>null,'stock'=>85,'image_url'=>'https://picsum.photos/seed/moisturizer/600/400','status'=>'active'],
        ];

        foreach ($products as &$p) {
            $p['created_at'] = $now;
            $p['updated_at'] = $now;
        }

        $this->db->table('products')->insertBatch($products);
        echo "ProductSeeder: " . count($products) . " products inserted.\n";
    }
}
