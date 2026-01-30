<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check()) {

            $cart = [];

            $dbCart = Auth::user()->cart;

            if ($dbCart) {
                foreach ($dbCart->items as $item) {
                    $cart[] = [
                        'id'       => $item->product_id,
                        'name'     => $item->product->name,
                        'price'    => $item->price,
                        'quantity' => $item->quantity,
                        'image'    => $item->product->getFirstMediaUrl('main_image') ?: ($item->product->product_image ? asset('storage/'.$item->product->product_image) : asset('assets/images/no-image.png')),
                    ];
                }
            }

            return view('user.cart.index', compact('cart'));
        }

        //  Guest user (COOKIE BASED)
        $cookieCart = json_decode($request->cookie('guest_cart', '[]'), true);

        $cart = [];

        foreach ($cookieCart as $item) {

            if (!isset($item['id'])) continue;

            $product = Product::find($item['id']);
            if (!$product) continue;

            $cart[] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'price'    => $product->price,
                'quantity' => max(1, (int) ($item['quantity'] ?? 1)),
                'image'    => $product->getFirstMediaUrl('main_image') ?: ($product->product_image ? asset('storage/'.$product->product_image) : asset('assets/images/no-image.png')),
            ];
        }

        return view('user.cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        if ($product->stock <= 0) {
            return response()->json(['message' => 'Out of stock'], 422);
        }

        /* ---------- GUEST ---------- */
        if (!Auth::check()) {

            $cart = collect(
                json_decode($request->cookie('guest_cart', '[]'), true)
            );

            $item = $cart->firstWhere('id', $product->id);

            if ($item) {
                $cart = $cart->map(fn ($i) =>
                    $i['id'] == $product->id
                        ? ['id' => $i['id'], 'quantity' => $i['quantity'] + 1]
                        : $i
                );
            } else {
                $cart->push(['id' => $product->id, 'quantity' => 1]);
            }

            return response()->json([
                'status' => 'added',
                'count'  => $cart->sum('quantity')
            ])->cookie(
                'guest_cart',
                json_encode($cart->values()),
                60 * 24 * 7
            );
        }

        /* ---------- LOGGED IN ---------- */
        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);

        $item = CartItem::firstOrCreate(
            ['cart_id' => $cart->id, 'product_id' => $product->id],
            ['quantity' => 0, 'price' => $product->price]
        );

        $item->increment('quantity');

        return response()->json(['status' => 'added','count'  => $cart->items()->sum('quantity')]);
    }

    public function remove(Request $request, int $productId)
    {
        /* ---------- GUEST ---------- */
        if (!Auth::check()) {

            $cart = collect(
                json_decode($request->cookie('guest_cart', '[]'), true)
            )->reject(fn ($i) => $i['id'] == $productId)
            ->values();

            if ($cart->isEmpty()) {
                return response()->json([
                    'status'     => 'success',
                    'count'      => 0,
                    'grandTotal' => 0
                ])->cookie('guest_cart', json_encode([]), 60 * 24 * 7, '/');
            }

            $products = Product::whereIn('id', $cart->pluck('id'))->get();

            $grandTotal = $products->sum(function ($product) use ($cart) {
                $qty = $cart->firstWhere('id', $product->id)['quantity'] ?? 1;
                return $product->price * $qty;
            });

            return response()->json([
                'status'     => 'success',
                'count'      => $cart->sum('quantity'),
                'grandTotal' => $grandTotal
            ])->cookie(
                'guest_cart',
                json_encode($cart->values()->all()),
                60 * 24 * 7,
                '/'
            );
        }

        /* ---------- LOGGED IN ---------- */
        $cart = Cart::where('user_id', auth()->id())->first();

        CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->delete();

        $cart->load('items'); 

        return response()->json([
            'status'     => 'success',
            'count'      => $cart->items->sum('quantity'),
            'grandTotal' => $cart->items->sum(fn ($i) => $i->price * $i->quantity),
        ]);
    }

    public function update(Request $request, int $productId)
    {
        $qty = max(1, (int)$request->quantity);

        /* ---------- GUEST ---------- */
        if (!Auth::check()) {

            $cart = collect(
                json_decode($request->cookie('guest_cart', '[]'), true)
            )->map(
                fn($i) =>
                $i['id'] == $productId  ? ['id' => $i['id'], 'quantity' => $qty]  : $i
            );

            $product = Product::findOrFail($productId);

            $grandTotal = Product::whereIn('id', $cart->pluck('id'))
                ->get()
                ->sum(
                    fn($p) => ($cart->firstWhere('id', $p->id)['quantity'] ?? 1) * $p->price
                );

            return response()->json([
                'status'     => 'success',
                'count'      => $cart->sum('quantity'),
                'itemTotal'  => $product->price * $qty,
                'grandTotal' => $grandTotal
            ])->cookie('guest_cart', json_encode($cart->values()), 60 * 24 * 7);
        }

        /* ---------- LOGGED IN ---------- */
        $cart = Cart::where('user_id', auth()->id())->first();

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        $item->update(['quantity' => $qty]);
        $items = $cart->items()->get();

        return response()->json([
            'status'     => 'success',
            'count'      => $cart->items()->sum('quantity'),
            'itemTotal'  => $item->price * $qty,
            'grandTotal' => $items->sum(fn($i) => $i->price * $i->quantity)
        ]);
    }
}
