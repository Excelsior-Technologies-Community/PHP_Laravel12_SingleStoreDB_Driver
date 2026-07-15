<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Live Analytics
        $analytics = [
            'total_products'    => Product::count(),
            'active_products'   => Product::where('status', true)->count(),
            'inactive_products' => Product::where('status', false)->count(),
            'average_price'     => round(Product::avg('price'), 2),
            'max_price'         => Product::max('price'),
            'min_price'         => Product::min('price'),
        ];

        // 2. Recent Products (last 5)
        $recentProducts = Product::latest()->limit(5)->get();

        // 3. Cluster Health - SHOW LEAVES & SHOW AGGREGATORS
        $leaves = [];
        $aggregators = [];
        try {
            $leaves      = DB::select('SHOW LEAVES');
            $aggregators = DB::select('SHOW AGGREGATORS');
        } catch (\Exception $e) {
            $leaves      = [['Host' => '127.0.0.1', 'Port' => 3306, 'State' => 'online', 'Note' => 'Single Node']];
            $aggregators = [['Host' => '127.0.0.1', 'Port' => 3306, 'State' => 'online']];
        }

        // 4. Table Topology (Sharding Info)
        $topology = [];
        try {
            $topology = DB::select("SELECT * FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE()");
        } catch (\Exception $e) {
            $topology = [];
        }

        return view('dashboard', compact('analytics', 'recentProducts', 'leaves', 'aggregators', 'topology'));
    }
}
