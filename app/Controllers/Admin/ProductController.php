<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\CategoryModel;

/**
 * Admin\ProductController — full CRUD for products.
 */
class ProductController extends BaseController
{
    private ProductModel  $products;
    private CategoryModel $categories;

    public function __construct()
    {
        $this->products   = new ProductModel();
        $this->categories = new CategoryModel();
    }

    /** GET /admin/products */
    public function index()
    {
        return view('admin/product/index', [
            'products' => $this->products->orderBy('created_at','DESC')->findAll(),
        ]);
    }

    /** GET /admin/products/create */
    public function create()
    {
        return view('admin/product/create', [
            'categories' => $this->categories->allCategories(),
            'product'    => null,
        ]);
    }

    /** POST /admin/products/store */
    public function store()
    {
        $data = $this->request->getPost();

        // Auto-generate slug from name
        $data['slug'] = url_title($data['name'], '-', true);

        if (!$this->products->insert($data)) {
            return redirect()->back()->withInput()->with('errors', $this->products->errors());
        }

        return redirect()->to('/admin/products')->with('success', 'Product created.');
    }

    /** GET /admin/products/edit/{id} */
    public function edit(int $id)
    {
        return view('admin/product/edit', [
            'categories' => $this->categories->allCategories(),
            'product'    => $this->products->find($id),
        ]);
    }

    /** POST /admin/products/update/{id} */
    public function update(int $id)
    {
        $data = $this->request->getPost();
        $data['slug'] = url_title($data['name'], '-', true);
        $this->products->update($id, $data);
        return redirect()->to('/admin/products')->with('success', 'Product updated.');
    }

    /** GET /admin/products/delete/{id} */
    public function delete(int $id)
    {
        $this->products->delete($id);
        return redirect()->to('/admin/products')->with('success', 'Product deleted.');
    }
}


