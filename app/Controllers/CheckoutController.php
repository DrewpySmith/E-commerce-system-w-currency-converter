<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Database;

class CheckoutController extends Controller
{
    public function index()
    {
        if (!session()->get('isLoggedIn')) return redirect()->to('/login');

        $db = Database::connect();
        $userId = session()->get('id');

        $builder = $db->table('cart_items');
        $builder->select('cart_items.*, products.name, products.price_usd');
        $builder->join('products', 'products.id = cart_items.product_id');
        $builder->where('user_id', $userId);
        $items = $builder->get()->getResultArray();

        $totalUSD = 0;
        foreach ($items as $it) {
            $totalUSD += $it['price_usd'] * $it['quantity'];
        }

        return view('checkout/index', [
            'title' => 'Checkout',
            'items' => $items,
            'totalUSD' => $totalUSD,
        ]);
    }

    public function place()
    {
        if (!session()->get('isLoggedIn')) return redirect()->to('/login');

        $db = Database::connect();
        $userId = session()->get('id');

        // fetch cart
        $builder = $db->table('cart_items');
        $builder->select('cart_items.*, products.name, products.price_usd');
        $builder->join('products', 'products.id = cart_items.product_id');
        $builder->where('user_id', $userId);
        $items = $builder->get()->getResultArray();
        if (empty($items)) {
            session()->setFlashdata('error', 'Cart is empty.');
            return redirect()->to('/cart');
        }

        // Build a short-lived signature to avoid duplicate orders from quick double-submits
        $sigParts = [];
        foreach ($items as $it) {
            $sigParts[] = $it['product_id'] . 'x' . (int)$it['quantity'];
        }
        sort($sigParts);
        $signature = sha1(implode('|', $sigParts));
        $now = time();
        $lastSig = session()->get('last_order_sig');
        $lastWhen = (int) (session()->get('last_order_time') ?? 0);
        if ($lastSig === $signature && ($now - $lastWhen) < 10) {
            // Considered duplicate within 10s window; redirect to success without re-inserting
            return redirect()->to('/checkout/success');
        }

        $db->transStart();
        $totalUSD = 0;
        foreach ($items as $it) {
            $totalUSD += $it['price_usd'] * $it['quantity'];
        }

        // insert order
        $orders = $db->table('orders');
        $orders->insert([
            'user_id' => $userId,
            'total_usd' => $totalUSD,
            'status' => 'paid',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $orderId = $db->insertID();

        // insert order_items
        $orderItems = $db->table('order_items');
        foreach ($items as $it) {
            $orderItems->insert([
                'order_id' => $orderId,
                'product_id' => $it['product_id'],
                'price_usd' => $it['price_usd'],
                'quantity' => $it['quantity'],
            ]);
        }

        // clear cart
        $db->table('cart_items')->where('user_id', $userId)->delete();

        $db->transComplete();

        // Remember signature for a short period to avoid accidental duplicates
        session()->set('last_order_sig', $signature);
        session()->set('last_order_time', time());

        return redirect()->to('/checkout/success');
    }

    public function success()
    {
        return view('checkout/success', ['title' => 'Order Placed']);
    }
}
