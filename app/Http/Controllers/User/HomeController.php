<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    //
    public function index()
    {
        return view('user.home');
    }
    public function list(Request $request)
    {
        if (Auth::check()) {
            $products = Product::with('categories')
                ->withCount([
                    'wishlists as is_wishlisted' => function ($q) {
                        $q->where('user_id', Auth::id());
                    }
                ])->paginate(4);
        } else {
            $products = Product::with('categories')
                ->paginate(4);
        }

        if ($request->ajax()) {
            return response()->json([
                'html' => view('components.product-card', compact('products'))->render(),
                'pagination' => $products->links('pagination::bootstrap-4')->render(), 
            ]);
        }

        return view('user.home', compact('products'));
    }
}
