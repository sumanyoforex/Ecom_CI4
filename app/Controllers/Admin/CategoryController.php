<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;

/**
 * Admin\CategoryController — full CRUD for categories.
 */
class CategoryController extends BaseController
{
    private CategoryModel $categories;

    public function __construct()
    {
        $this->categories = new CategoryModel();
    }

    /** GET /admin/categories */
    public function index()
    {
        return view('admin/category/index', [
            'categories' => $this->categories->allCategories(),
        ]);
    }

    /** GET /admin/categories/create */
    public function create()
    {
        return view('admin/category/create', ['category' => null]);
    }

    /** POST /admin/categories/store */
    public function store()
    {
        $data         = $this->request->getPost();
        $data['slug'] = url_title($data['name'], '-', true);
        $this->categories->insert($data);
        return redirect()->to('/admin/categories')->with('success', 'Category created.');
    }

    /** GET /admin/categories/edit/{id} */
    public function edit(int $id)
    {
        return view('admin/category/edit', ['category' => $this->categories->find($id)]);
    }

    /** POST /admin/categories/update/{id} */
    public function update(int $id)
    {
        $data         = $this->request->getPost();
        $data['slug'] = url_title($data['name'], '-', true);
        $this->categories->update($id, $data);
        return redirect()->to('/admin/categories')->with('success', 'Category updated.');
    }

    /** GET /admin/categories/delete/{id} */
    public function delete(int $id)
    {
        $this->categories->delete($id);
        return redirect()->to('/admin/categories')->with('success', 'Category deleted.');
    }
}


