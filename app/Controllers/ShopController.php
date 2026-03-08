<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;

/**
 * Product listing and product detail pages.
 */
class ShopController extends BaseController
{
    public function index()
    {
        $categoryModel = new CategoryModel();
        $productModel = new ProductModel();

        $categoryId = (int)$this->request->getGet('category');
        $search = trim((string)$this->request->getGet('search'));
        $sort = trim((string)$this->request->getGet('sort'));
        $minPrice = (float)$this->request->getGet('min_price');
        $maxPrice = (float)$this->request->getGet('max_price');

        $allowedSort = ['newest', 'price_asc', 'price_desc', 'name_asc'];
        if (!in_array($sort, $allowedSort, true)) {
            $sort = 'newest';
        }

        $cache = cache();
        $params = [
            'cat' => $categoryId,
            'q' => $search,
            'sort' => $sort,
            'min' => $minPrice,
            'max' => $maxPrice,
        ];
        $cacheKey = 'shop_' . sha1(json_encode($params));

        $products = $cache->get($cacheKey);
        if ($products === null) {
            $products = $productModel->getProducts($categoryId, $search, $sort, $minPrice, $maxPrice);
            $cache->save($cacheKey, $products, 120);
        }

        $categories = $cache->get('categories_all');
        if ($categories === null) {
            $categories = $categoryModel->allCategories();
            $cache->save('categories_all', $categories, 300);
        }

        return view('shop/listing', [
            'categories' => $categories,
            'products' => $products,
            'activeCategoryId' => $categoryId,
            'search' => $search,
            'sort' => $sort,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
        ]);
    }

    public function detail(string $slug)
    {
        $cache = cache();
        $cacheKey = 'product_slug_' . sha1($slug);
        $product = $cache->get($cacheKey);

        if ($product === null) {
            $product = (new ProductModel())->findBySlug($slug);
            if ($product !== null) {
                $cache->save($cacheKey, $product, 180);
            }
        }

        if (!$product) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Product not found: ' . $slug);
        }

        return view('shop/detail', ['product' => $product]);
    }
}
