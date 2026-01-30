<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\WishList;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishListController extends Controller 
{
    //
   public function index()
    {
        if (!Auth::check()) {
            $ids = collect(json_decode(request()->cookie('guest_wishlist', '[]'), true));
            $wishlists = Product::whereIn('id', $ids)->get();
            return view('user.wishlist.index', [
                'wishlists' => $wishlists,
                'isGuest'   => true
            ]);
        }

        $wishlists = WishList::with('product')->where('user_id', Auth::id())->get();
        return view('user.wishlist.index', [
            'wishlists' => $wishlists,
            'isGuest'   => false
        ]);
    }
    public function toggle(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        if (!Auth::check()) {
            $wishlist = collect(
                json_decode($request->cookie('guest_wishlist', '[]'), true)
            );

            if ($wishlist->contains($request->product_id)) {
                $wishlist = $wishlist->reject(fn ($id) => $id == $request->product_id)->values();
                $status = 'removed';
            } else {
                $wishlist->push($request->product_id);
                $status = 'added';
            }

            return response()->json([
                'status' => $status,
                'count'  => $wishlist->count(),
            ])->cookie(
                'guest_wishlist',
                json_encode($wishlist->values()),
                60 * 24 * 7
            );
        }

        $userId = Auth::id();
        $wishlist = Wishlist::where('user_id', $userId)
            ->where('product_id', $request->product_id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            $status = 'removed';
        } else {
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $request->product_id,
            ]);
            $status = 'added';
        }

        return response()->json([
            'status' => $status,
            'count'  => Wishlist::where('user_id', $userId)->count(),
        ]);
    }
    public function showadmin()
    {
        $wishlists = WishList::with(['user', 'product'])
            ->latest()
            ->get();

        return view('admin.wishlist.index', compact('wishlists'));
    }
    public function deleteById(Request $request, int $productId): JsonResponse
    {
        if (!Auth::check()) {

            $wishlist = collect(json_decode($request->cookie('guest_wishlist', '[]'), true));

            if (!$wishlist->contains($productId)) {
                return response()->json(['status' => 'error'], 404);
            }

            $wishlist = $wishlist
                ->reject(fn ($id) => (int)$id === (int)$productId)
                ->values();

            return response()->json([
                'status' => 'removed',
                'count'  => $wishlist->count(),
            ])->cookie( 'guest_wishlist', json_encode($wishlist->values()), 60 * 24 * 7);
        }

        $wishlist = WishList::where('user_id', auth()->id())
            ->where('product_id', $productId)
            ->first();

        if (!$wishlist) {
            return response()->json(['status' => 'error'], 404);
        }

        $wishlist->delete();

        return response()->json([
            'status' => 'removed',
            'count'  => WishList::where('user_id', auth()->id())->count(),
        ]);
    }
}
