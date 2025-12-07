<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\ProductModel;

class ProductsController extends Controller
{
    public function index()
    {
        $model = new ProductModel();
        $products = $model->orderBy('created_at', 'DESC')->findAll();
        return view('admin/products/index', ['products' => $products, 'title' => 'Products']);
    }

    public function create()
    {
        helper(['form']);
        return view('admin/products/create', ['title' => 'Create Product']);
    }

    public function store()
    {
        $model = new ProductModel();
        $rules = ['name' => 'required|min_length[3]', 'price_usd' => 'required|numeric'];
        if ($this->validate($rules)) {
            $model->save([
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'price_usd' => $this->request->getPost('price_usd'),
                'image_url' => $this->request->getPost('image_url'),
            ]);
            return redirect()->to('/admin/products');
        }
        return view('admin/products/create', ['validation' => $this->validator, 'title' => 'Create Product']);
    }

    public function edit($id)
    {
        helper(['form']);
        $model = new ProductModel();
        $product = $model->find($id);
        if (!$product) return redirect()->to('/admin/products');
        return view('admin/products/edit', ['product' => $product, 'title' => 'Edit Product']);
    }

    public function update($id)
    {
        $model = new ProductModel();
        $rules = ['name' => 'required|min_length[3]', 'price_usd' => 'required|numeric'];
        if ($this->validate($rules)) {
            $model->update($id, [
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'price_usd' => $this->request->getPost('price_usd'),
                'image_url' => $this->request->getPost('image_url'),
            ]);
            return redirect()->to('/admin/products');
        }
        return $this->edit($id);
    }

    public function delete($id)
    {
        $model = new ProductModel();
        $model->delete($id);
        return redirect()->to('/admin/products');
    }
}
