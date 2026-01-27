<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Order;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function show_admin(){
        $user = Auth::user();

        // Get total statistics
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('total');
        $totalProducts = Product::count();
        $totalBrands = Brand::count();
        $totalCategories = Category::count();
        $totalUsers = User::whereDoesntHave('roles', fn($q) => $q->where('name', 'admin'))->count();

        // Get recent orders (last 10)
        $recentOrders = Order::with('user', 'items')
            ->latest()
            ->limit(10)
            ->get();

        // Get monthly revenue
        $monthlyRevenue = Order::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total');

        // Get monthly order count
        $monthlyOrders = Order::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // Get top brands
        $topBrands = Brand::withCount('products')
            ->orderBy('products_count', 'desc')
            ->limit(5)
            ->get();

        // Get order status breakdown
        $ordersByStatus = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        // Get low stock products
        $lowStockProducts = Product::where('stock', '<', 10)
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get();

        return view('admin.dashboard')
                ->with([
                    "user" => $user,
                    "totalOrders" => $totalOrders,
                    "totalRevenue" => $totalRevenue,
                    "totalProducts" => $totalProducts,
                    "totalBrands" => $totalBrands,
                    "totalCategories" => $totalCategories,
                    "totalUsers" => $totalUsers,
                    "recentOrders" => $recentOrders,
                    "monthlyRevenue" => $monthlyRevenue,
                    "monthlyOrders" => $monthlyOrders,
                    "topBrands" => $topBrands,
                    "ordersByStatus" => $ordersByStatus,
                    "lowStockProducts" => $lowStockProducts,
                ]);
    }

    public function show_users(){
        $users = User::whereDoesntHave('roles', fn($q) => $q->where('name', 'admin'))->get();
        return view('admin.users.all')->with(["users"=>$users]);
    }
}
