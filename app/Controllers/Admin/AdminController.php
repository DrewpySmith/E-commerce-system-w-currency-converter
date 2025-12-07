<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use Config\Database;

class AdminController extends Controller
{
    public function index()
    {
        $db = Database::connect();

        // KPIs (basic examples, adjust per your schema)
        $totalProducts = $db->table('products')->countAllResults();
        $totalUsers    = $db->table('users')->countAllResults();

        // Sales aggregates (guard if orders table doesn't exist)
        $tables = array_map('strtolower', $db->listTables());
        // Date range (defaults to last 30 days)
        $startParam = $this->request->getGet('start');
        $endParam   = $this->request->getGet('end');
        $startDate  = ($startParam && preg_match('/^\d{4}-\d{2}-\d{2}$/', $startParam)) ? $startParam : date('Y-m-d', strtotime('-29 days'));
        $endDate    = ($endParam && preg_match('/^\d{4}-\d{2}-\d{2}$/', $endParam)) ? $endParam : date('Y-m-d');
        if (in_array('orders', $tables)) {
            $startDT = $startDate . ' 00:00:00';
            $endDT   = date('Y-m-d 00:00:00', strtotime($endDate . ' +1 day'));

            // KPIs within range and status paid
            $totalOrders = $db->table('orders')
                ->where('status', 'paid')
                ->where('created_at >=', $startDT)
                ->where('created_at <', $endDT)
                ->countAllResults();

            $sumRevenueRow = $db->table('orders')
                ->selectSum('total_usd', 'sum')
                ->where('status', 'paid')
                ->where('created_at >=', $startDT)
                ->where('created_at <', $endDT)
                ->get()->getRow('sum');
            $sumRevenue = (float) ($sumRevenueRow ?? 0);

            $avgOrder = $totalOrders > 0 ? ($sumRevenue / $totalOrders) : 0.0;

            $activeCustomers = $db->table('orders')
                ->select('user_id')
                ->distinct()
                ->where('status', 'paid')
                ->where('created_at >=', $startDT)
                ->where('created_at <', $endDT)
                ->countAllResults();

            // Daily series
            $labels = [];
            $ordersSeries = [];
            $revenueSeries = [];

            $agg = $db->table('orders')
                ->select("DATE(created_at) as d, COUNT(*) as c, SUM(total_usd) as s", false)
                ->where('status', 'paid')
                ->where('created_at >=', $startDT)
                ->where('created_at <', $endDT)
                ->groupBy('DATE(created_at)', false)
                ->orderBy('DATE(created_at)', 'ASC', false)
                ->get()->getResultArray();

            $map = [];
            foreach ($agg as $row) {
                $map[$row['d']] = ['c' => (int)$row['c'], 's' => (float)$row['s']];
            }
            $cursor = strtotime($startDate);
            $endTs  = strtotime($endDate);
            while ($cursor <= $endTs) {
                $d = date('Y-m-d', $cursor);
                $labels[] = date('M d', $cursor);
                $ordersSeries[] = isset($map[$d]) ? $map[$d]['c'] : 0;
                $revenueSeries[] = isset($map[$d]) ? $map[$d]['s'] : 0.0;
                $cursor = strtotime('+1 day', $cursor);
            }

            // Top products and recent orders
            $topProducts = $db->table('order_items oi')
                ->select('p.name as product, SUM(oi.quantity) as qty, SUM(oi.price_usd * oi.quantity) as revenue', false)
                ->join('orders o', 'o.id = oi.order_id')
                ->join('products p', 'p.id = oi.product_id', 'left')
                ->where('o.status', 'paid')
                ->where('o.created_at >=', $startDT)
                ->where('o.created_at <', $endDT)
                ->groupBy('oi.product_id')
                ->orderBy('qty', 'DESC')
                ->limit(5)
                ->get()->getResultArray();

            $recentOrders = $db->table('orders')
                ->select('id, user_id, total_usd, status, created_at')
                ->where('status', 'paid')
                ->where('created_at >=', $startDT)
                ->where('created_at <', $endDT)
                ->orderBy('created_at', 'DESC')
                ->limit(10)
                ->get()->getResultArray();
        } else {
            $totalOrders = 0;
            $sumRevenue = 0.0;
            $avgOrder = 0.0;
            $activeCustomers = 0;
            $labels = [];
            $ordersSeries = [];
            $revenueSeries = [];
            $topProducts = [];
            $recentOrders = [];
        }

        $data = [
            'title' => 'Admin Dashboard',
            'totalProducts' => $totalProducts,
            'totalUsers' => $totalUsers,
            'totalOrders' => $totalOrders,
            'sumRevenue' => $sumRevenue,
            'avgOrder' => $avgOrder ?? 0,
            'activeCustomers' => $activeCustomers ?? 0,
            'chartLabels' => $labels,
            'chartOrders' => $ordersSeries,
            'chartRevenue' => $revenueSeries,
            'topProducts' => $topProducts,
            'recentOrders' => $recentOrders,
            'start' => $startDate,
            'end' => $endDate,
        ];

        return view('admin/dashboard', $data);
    }
}
