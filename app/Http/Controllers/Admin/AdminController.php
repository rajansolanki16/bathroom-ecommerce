<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Models\StoreVisit;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    public function show_admin(Request $request)
    {
        $user = Auth::user();

        /* =======================
        ADMIN DASHBOARD STATS
        ======================= */
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('total');
        $totalProducts = Product::count();
        $totalBrands = Brand::count();
        $totalCategories = Category::count();
        $totalUsers = User::whereDoesntHave(
            'roles',
            fn($q) =>
            $q->where('name', 'admin')
        )->count();

        $recentOrders = Order::with('user', 'items')
            ->latest()
            ->limit(10)
            ->get();

        $monthlyRevenue = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        $monthlyOrders = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $topBrands = Brand::withCount('products')
            ->orderByDesc('products_count')
            ->limit(5)
            ->get();

        $ordersByStatus = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        $lowStockProducts = Product::where('stock', '<', 10)
            ->orderBy('stock')
            ->limit(5)
            ->get();

        /* =======================
        SALESMAN VISIT STATS
        ======================= */
        $totalVisits = StoreVisit::where('salesman_id', Auth::id())->count();

        $todayVisits = StoreVisit::where('salesman_id', Auth::id())
            ->whereDate('created_at', today())
            ->count();

        /* =======================
        VENDOR DROPDOWN
        ======================= */
        $vendors = User::whereHas(
            'roles',
            fn($q) =>
            $q->where('name', 'vendor')
        )->select('id', 'name')->get();

        /* =======================
        STORE VISIT QUERY
        ======================= */
        $recentVisitsQuery = StoreVisit::with('vendor')
            ->where('salesman_id', Auth::id())
            ->latest();

        // Vendor filter
        if ($request->filled('vendor_id')) {
            $recentVisitsQuery->where('vendor_id', $request->vendor_id);
        }
        // Date filters
        if ($request->filled('from_date')) {
            $recentVisitsQuery->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $recentVisitsQuery->whereDate('created_at', '<=', $request->to_date);
        }

        // Search (vendor / purpose / outcome)
        if ($request->filled('search')) {
            $search = $request->search;

            $recentVisitsQuery->where(function ($q) use ($search) {
                $q->where('purpose', 'like', "%{$search}%")
                    ->orWhere('outcome', 'like', "%{$search}%")
                    ->orWhereHas('vendor', function ($vendor) use ($search) {
                        $vendor->where('name', 'like', "%{$search}%");
                    });
            });
        }

        /* =======================
        PAGINATION
        ======================= */
        $recentVisits = $recentVisitsQuery
            ->paginate(5)
            ->withQueryString();

        $totalVendors = User::whereHas(
            'roles',
            fn($q) =>
            $q->where('name', 'vendor')
        )->count();
        /* =======================
        VIEW
        ======================= */
        return view('admin.dashboard', compact(
            'user',
            'totalOrders',
            'totalRevenue',
            'totalProducts',
            'totalBrands',
            'totalCategories',
            'totalUsers',
            'recentOrders',
            'monthlyRevenue',
            'monthlyOrders',
            'topBrands',
            'ordersByStatus',
            'lowStockProducts',
            'totalVisits',
            'todayVisits',
            'totalVendors',
            'recentVisits',
            'vendors'
        ));
    }

    // public function show_users()
    // {
    //     $users = User::whereDoesntHave('roles', fn($q) => $q->where('name', 'admin'))->get();
    //     return view('admin.users.all')->with(["users" => $users]);
    // }
}
