<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class CartController extends Controller
{
    private $apiUrl = 'https://api.exchangerate-api.com/v4/latest/USD';

    public function index()
    {
        if (!session()->get('isLoggedIn')) return redirect()->to('/login');

        $db = \Config\Database::connect();
        $userId = session()->get('id');
        $session = session();

        // --- CURRENCY LOGIC START ---
        // 1. Check if user is requesting a change via URL (e.g. ?currency=EUR)
        if ($this->request->getVar('currency')) {
            $currency = $this->request->getVar('currency');
            $session->set('currency', $currency); // Save to session
        } else {
            // 2. Otherwise, load from session, or default to USD
            $currency = $session->get('currency') ?? 'USD';
        }

        // 3. Fetch API Rates if not USD
        $rate = 1;
        if ($currency !== 'USD') {
            $apiData = $this->fetchExchangeRates();
            if (isset($apiData['rates'][$currency])) {
                $rate = $apiData['rates'][$currency];
            }
        }
        // --- CURRENCY LOGIC END ---

        // Fetch Cart Items
        $builder = $db->table('cart_items');
        $builder->select('cart_items.*, products.name, products.price_usd, products.image_url');
        $builder->join('products', 'products.id = cart_items.product_id');
        $builder->where('user_id', $userId);
        $cartItems = $builder->get()->getResultArray();

        // Calculate Totals (Base USD)
        $totalUSD = 0;
        foreach ($cartItems as $item) {
            $totalUSD += ($item['price_usd'] * $item['quantity']);
        }

        $data = [
            'cartItems' => $cartItems,
            'totalUSD'  => $totalUSD,
            'currency'  => $currency, // Pass to view
            'rate'      => $rate,     // Pass to view
            'title'     => 'My Cart'
        ];

        return view('cart', $data);
    }

    public function add()
    {
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Please login to add items to cart.');
            return redirect()->to('/login');
        }

        $userId = session()->get('id');
        $productId = $this->request->getPost('product_id');
        
        $db = \Config\Database::connect();
        $builder = $db->table('cart_items');

        // Check if item already exists
        $exists = $builder->where(['user_id' => $userId, 'product_id' => $productId])->get()->getRow();

        if ($exists) {
            $builder->where('id', $exists->id);
            $builder->update(['quantity' => $exists->quantity + 1]);
        } else {
            $builder->insert([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => 1
            ]);
        }

        session()->setFlashdata('success', 'Item added to cart.');
        return redirect()->back();
    }

    public function remove($id)
    {
        if (!session()->get('isLoggedIn')) return redirect()->to('/login');
        
        $db = \Config\Database::connect();
        $builder = $db->table('cart_items');
        $builder->where('id', $id)->delete();

        return redirect()->to('/cart');
    }

    /**
     * Private helper to fetch rates (Same as in ProductController)
     */
    private function fetchExchangeRates()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}