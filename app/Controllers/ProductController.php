<?php

namespace App\Controllers;

use App\Models\ProductModel;
use CodeIgniter\Controller;

class ProductController extends Controller
{
    private $apiUrl = 'https://api.exchangerate-api.com/v4/latest/USD';

    public function index()
    {
        $model = new ProductModel();
        $session = session();

        // --- UPDATED CURRENCY LOGIC ---
        // 1. If URL has currency, update session. 
        if ($this->request->getVar('currency')) {
            $currency = $this->request->getVar('currency');
            $session->set('currency', $currency);
        } else {
            // 2. Else get from session or default
            $currency = $session->get('currency') ?? 'USD';
        }
        // ------------------------------

        $products = $model->findAll();

        $rate = 1; 
        if ($currency !== 'USD') {
            $apiData = $this->fetchExchangeRates();
            if (isset($apiData['rates'][$currency])) {
                $rate = $apiData['rates'][$currency];
            }
        }

        $data = [
            'products' => $products,
            'currency' => $currency,
            'rate'     => $rate,
            'title'    => 'GlobalShop Catalog'
        ];

        return view('catalog', $data);
    }

    private function fetchExchangeRates()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    // ... create/store methods remain the same ...
    public function create()
    {
        helper(['form']);
        return view('product_create');
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
            ]);
            return redirect()->to('/');
        } else {
            return view('product_create', ['validation' => $this->validator]);
        }
    }
}