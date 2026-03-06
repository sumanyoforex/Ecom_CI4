<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CategoryModel;

/**
 * ShopController — product listing and product detail pages.
 */
class ShopController extends BaseController
{
    /** GET /shop — show all products with optional filters */
    public function index()
    {
        $categoryModel = new CategoryModel();
        $productModel  = new ProductModel();

        $categoryId = (int)$this->request->getGet('category');
        $search     = $this->request->getGet('search') ?? '';

        return view('shop/listing', [
            'categories'       => $categoryModel->allCategories(),
            'products'         => $productModel->getProducts($categoryId, $search),
            'activeCategoryId' => $categoryId,
            'search'           => $search,
        ]);
    }

    /** GET /shop/{slug} — single product detail */
    public function detail(string $slug)
    {
        $product = (new ProductModel())->findBySlug($slug);

        if (!$product) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Product not found: $slug");
        }

        return view('shop/detail', ['product' => $product]);
    }
}
