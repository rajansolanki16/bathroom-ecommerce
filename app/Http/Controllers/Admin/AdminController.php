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

        $totalOrders = Order::count();
        $totalRevenue = Order::sum('total');
        $totalProducts = Product::count();
        $totalBrands = Brand::count();
        $totalCategories = Category::count();
        $totalUsers = User::whereDoesntHave('roles', fn($q) => $q->where('name', 'admin'))->count();

        $recentOrders = Order::with('user', 'items')
            ->latest()
            ->limit(10)
            ->get();

        $monthlyRevenue = Order::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total');

        $monthlyOrders = Order::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $topBrands = Brand::withCount('products')
            ->orderBy('products_count', 'desc')
            ->limit(5)
            ->get();

        $ordersByStatus = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        $lowStockProducts = Product::where('stock', '<', 10)
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get();

        // Store Visit data for salesman
        // $totalVisits = StoreVisit::count();
        // $todayVisits = StoreVisit::whereDate('created_at', today())->count();
        // $recentVisitsQuery = StoreVisit::with(['vendor', 'salesman'])
        //     ->latest();

        // Store Visit data - filtered by logged-in user
        $visitsQuery = StoreVisit::query();

        // Filter based on user role
        if ($user->hasRole('salesman')) {
            $visitsQuery->where('salesman_id', $user->id);
        } elseif ($user->hasRole('vendor')) {
            $visitsQuery->where('vendor_id', $user->id);
        }
        
        $totalVisits = StoreVisit::when($user->hasRole('salesman'), fn($q) => $q->where('salesman_id', $user->id))
            ->when($user->hasRole('vendor'), fn($q) => $q->where('vendor_id', $user->id))
            ->count();

        $todayVisits = StoreVisit::when($user->hasRole('salesman'), fn($q) => $q->where('salesman_id', $user->id))
            ->when($user->hasRole('vendor'), fn($q) => $q->where('vendor_id', $user->id))
            ->whereDate('created_at', today())
            ->count();

        $recentVisitsQuery = StoreVisit::when($user->hasRole('salesman'), fn($q) => $q->where('salesman_id', $user->id))
            ->when($user->hasRole('vendor'), fn($q) => $q->where('vendor_id', $user->id))
            ->with(['vendor', 'salesman'])
            ->latest();

        //filter vendors for dropdown
        $vendors = User::whereHas('roles', fn($q) => $q->where('name', 'vendor'))->get();
        if ($request->filled('vendor_id')) {
            $recentVisitsQuery->where('vendor_id', $request->vendor_id);
        }
        // From date filter
        if ($request->filled('from_date')) {
            $recentVisitsQuery->whereDate('created_at', '>=', $request->from_date);
        }
        // To date filter
        if ($request->filled('to_date')) {
            $recentVisitsQuery->whereDate('created_at', '<=', $request->to_date);
        }
        $recentVisits = $recentVisitsQuery
            ->take(5)
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
                "totalVisits" => $totalVisits,
                "todayVisits" => $todayVisits,
                "recentVisits" => $recentVisits,
                "vendors" => $vendors,
            ]);
    }

    public function show_users()
    {
        $users = User::whereDoesntHave('roles', fn($q) => $q->where('name', 'admin'))->get();
        return view('admin.users.all')->with(["users" => $users]);
    }
}
