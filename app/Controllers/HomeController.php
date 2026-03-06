<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CategoryModel;

/**
 * HomeController — renders the homepage.
 * Shows featured products and all categories.
 */
class HomeController extends BaseController
{
    public function index()
    {
        $productModel  = new ProductModel();
        $categoryModel = new CategoryModel();

        return view('shop/home', [
            'featured'   => $productModel->getFeatured(8),
            'categories' => $categoryModel->allCategories(),
        ]);
    }
}
