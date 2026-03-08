<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * DatabaseSeeder seeds all core tables in safe order.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call('CategorySeeder');
        $this->call('ProductSeeder');
        $this->call('CouponSeeder');
    }
}
