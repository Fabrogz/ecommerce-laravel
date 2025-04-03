<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalUsers = User::where('role', 'client')->count();
        $totalSales = Order::sum('total');
        
        // Productos más vendidos
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'totalProducts', 
            'totalOrders', 
            'totalUsers',
            'totalSales',
            'topProducts'
        ));
    }
    
    public function orders()
    {
        $orders = Order::with('user', 'items.product')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }
    
    public function users()
    {
        $users = User::where('role', 'client')->paginate(10);
        return view('admin.users.index', compact('users'));
    }
}