<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;

/**
 * HomeController renders the public landing page.
 */
class HomeController extends BaseController
{
    public function index()
    {
        $cache = cache();
        $cacheKey = 'landing_v2';
        $cached = $cache->get($cacheKey);

        if (!$cached) {
            $productModel = new ProductModel();
            $categoryModel = new CategoryModel();

            $cached = [
                'featured' => $productModel->getFeatured(8),
                'categories' => $categoryModel->allCategories(),
            ];

            $cache->save($cacheKey, $cached, 300);
        }

        return view('home/index', [
            'featured' => $cached['featured'],
            'categories' => $cached['categories'],
            'title' => 'ShopCI4 | Smart Shopping',
        ]);
    }
}


