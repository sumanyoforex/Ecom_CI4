<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;
use App\Models\ProductRatingModel;

/**
 * Product listing and product detail pages.
 */
class ShopController extends BaseController
{
    private const DEFAULT_SORT = 'newest';

    private const ALLOWED_SORT = ['newest', 'price_asc', 'price_desc', 'name_asc'];

    public function index(
        ?string $categorySegment = null,
        ?string $searchSegment = null,
        ?string $sortSegment = null,
        ?string $minSegment = null,
        ?string $maxSegment = null
    ) {
        $isSegmentRequest = $categorySegment !== null
            || $searchSegment !== null
            || $sortSegment !== null
            || $minSegment !== null
            || $maxSegment !== null;

        if ($isSegmentRequest) {
            $filters = $this->normalizeFilters($categorySegment, $searchSegment, $sortSegment, $minSegment, $maxSegment);
        } else {
            $filters = $this->normalizeFilters(
                (string)$this->request->getGet('category'),
                (string)$this->request->getGet('search'),
                (string)$this->request->getGet('sort'),
                (string)$this->request->getGet('min_price'),
                (string)$this->request->getGet('max_price')
            );

            if ($this->hasLegacyQueryFilters()) {
                $redirectUrl = $this->isDefaultFilters($filters)
                    ? base_url('shop')
                    : $this->buildFilterUrl(
                        $filters['categoryId'],
                        $filters['search'],
                        $filters['sort'],
                        $filters['minPrice'],
                        $filters['maxPrice']
                    );

                return redirect()->to($redirectUrl);
            }
        }

        $categoryModel = new CategoryModel();
        $productModel = new ProductModel();

        $cache = cache();
        $params = [
            'cat' => $filters['categoryId'],
            'q' => $filters['search'],
            'sort' => $filters['sort'],
            'min' => $filters['minPrice'],
            'max' => $filters['maxPrice'],
        ];
        $cacheKey = 'shop_' . sha1(json_encode($params));

        $products = $cache->get($cacheKey);
        if ($products === null) {
            $products = $productModel->getProducts(
                $filters['categoryId'],
                $filters['search'],
                $filters['sort'],
                $filters['minPrice'],
                $filters['maxPrice']
            );
            $cache->save($cacheKey, $products, 120);
        }

        $categories = $cache->get('categories_all');
        if ($categories === null) {
            $categories = $categoryModel->allCategories();
            $cache->save('categories_all', $categories, 300);
        }

        return view('shop/index', [
            'categories' => $categories,
            'products' => $products,
            'activeCategoryId' => $filters['categoryId'],
            'search' => $filters['search'],
            'sort' => $filters['sort'],
            'minPrice' => $filters['minPrice'],
            'maxPrice' => $filters['maxPrice'],
        ]);
    }

    public function applyFilters()
    {
        $filters = $this->normalizeFilters(
            (string)$this->request->getPost('category'),
            (string)$this->request->getPost('search'),
            (string)$this->request->getPost('sort'),
            (string)$this->request->getPost('min_price'),
            (string)$this->request->getPost('max_price')
        );

        if ($this->isDefaultFilters($filters)) {
            return redirect()->to(base_url('shop'));
        }

        return redirect()->to($this->buildFilterUrl(
            $filters['categoryId'],
            $filters['search'],
            $filters['sort'],
            $filters['minPrice'],
            $filters['maxPrice']
        ));
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

        $ratings = new ProductRatingModel();
        $ratingStats = $ratings->getProductRatingStats((int)$product['id']);
        $reviews = $ratings->getRecentReviewsForProduct((int)$product['id']);

        return view('shop/detail', [
            'product' => $product,
            'ratingStats' => $ratingStats,
            'reviews' => $reviews,
        ]);
    }

    private function hasLegacyQueryFilters(): bool
    {
        return $this->request->getGet('category') !== null
            || $this->request->getGet('search') !== null
            || $this->request->getGet('sort') !== null
            || $this->request->getGet('min_price') !== null
            || $this->request->getGet('max_price') !== null;
    }

    /**
     * @return array{categoryId:int,search:string,sort:string,minPrice:float,maxPrice:float}
     */
    private function normalizeFilters(
        ?string $categoryRaw,
        ?string $searchRaw,
        ?string $sortRaw,
        ?string $minRaw,
        ?string $maxRaw
    ): array {
        $categoryRaw = trim((string)$categoryRaw);
        $searchRaw = trim((string)$searchRaw);
        $sortRaw = trim((string)$sortRaw);
        $minRaw = trim((string)$minRaw);
        $maxRaw = trim((string)$maxRaw);

        $categoryId = is_numeric($categoryRaw) ? max(0, (int)$categoryRaw) : 0;

        if ($searchRaw === '-' || $searchRaw === '') {
            $search = '';
        } else {
            $search = trim(rawurldecode($searchRaw));
            if (strlen($search) > 150) {
                $search = substr($search, 0, 150);
            }
        }

        $sort = in_array($sortRaw, self::ALLOWED_SORT, true) ? $sortRaw : self::DEFAULT_SORT;

        if ($minRaw === '-' || $minRaw === '') {
            $minPrice = 0.0;
        } else {
            $minPrice = is_numeric($minRaw) ? max(0.0, (float)$minRaw) : 0.0;
        }

        if ($maxRaw === '-' || $maxRaw === '') {
            $maxPrice = 0.0;
        } else {
            $maxPrice = is_numeric($maxRaw) ? max(0.0, (float)$maxRaw) : 0.0;
        }

        return [
            'categoryId' => $categoryId,
            'search' => $search,
            'sort' => $sort,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
        ];
    }

    /**
     * @param array{categoryId:int,search:string,sort:string,minPrice:float,maxPrice:float} $filters
     */
    private function isDefaultFilters(array $filters): bool
    {
        return $filters['categoryId'] === 0
            && $filters['search'] === ''
            && $filters['sort'] === self::DEFAULT_SORT
            && $filters['minPrice'] <= 0
            && $filters['maxPrice'] <= 0;
    }

    private function buildFilterUrl(int $categoryId, string $search, string $sort, float $minPrice, float $maxPrice): string
    {
        $safeSort = in_array($sort, self::ALLOWED_SORT, true) ? $sort : self::DEFAULT_SORT;
        $searchSegment = $search === '' ? '-' : rawurlencode($search);

        return base_url('shop/filters/'
            . max(0, $categoryId) . '/'
            . $searchSegment . '/'
            . $safeSort . '/'
            . $this->toPriceSegment($minPrice) . '/'
            . $this->toPriceSegment($maxPrice));
    }

    private function toPriceSegment(float $price): string
    {
        if ($price <= 0) {
            return '0';
        }

        $formatted = number_format($price, 2, '.', '');
        $formatted = rtrim(rtrim($formatted, '0'), '.');

        return $formatted === '' ? '0' : $formatted;
    }
}


