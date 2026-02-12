<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return $this->list(request());
    }

    public function list(Request $request)
    {
        $query = Product::with('categories');

        // Wishlist check for logged-in user
        if (Auth::check()) {
            $query->withCount([
                'wishlists as is_wishlisted' => function ($q) {
                    $q->where('user_id', Auth::id());
                }
            ]);
        }

        $products = $query->paginate(4);
        // AJAX request for pagination
        if ($request->ajax()) {
            return response()->json([
                'html' => view('components.product-card', compact('products'))->render(),
                'pagination' => $products->links('pagination::bootstrap-4')->render(),
            ]);
        }

        return view('view.home', compact('products'));
    }
}
