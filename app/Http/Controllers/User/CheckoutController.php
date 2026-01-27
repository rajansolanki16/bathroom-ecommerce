<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Coupon;
use App\Mail\OrderMail;


class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $cart = $this->getCart($request);

        if ($cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        $subtotal = $cart->sum(fn ($i) => $i['price'] * $i['quantity']);
        $discount = 0;

        if ($coupon = session('applied_coupon')) {
            if ($coupon['type'] === 'percentage') {
                $discount = ($subtotal * $coupon['amount']) / 100;
            } else {
                $discount = min($coupon['amount'], $subtotal);
            }
        }

        $total = max(0, $subtotal - $discount);

        return view('user.checkout.index', compact(
            'cart', 'subtotal', 'discount', 'total'
        ));
    }
    
    public function placeOrder(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'phone'   => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        $cart = $this->getCart($request);

        if ($cart->isEmpty()) {
            return back()->with('error', 'Your cart is empty');
        }

        DB::transaction(function () use ($request, $cart) {

            /* ---------------- CALCULATE TOTAL ---------------- */
            $subtotal = $cart->sum(fn ($i) => $i['price'] * $i['quantity']);
            $discount = 0;
            $couponId = null;

            if ($coupon = session('applied_coupon')) {
                $couponId = $coupon['id'];

                if ($coupon['type'] === 'percentage') {
                    $discount = ($subtotal * $coupon['amount']) / 100;
                } else {
                    $discount = min($coupon['amount'], $subtotal);
                }

                // Increase coupon usage
                Coupon::where('id', $couponId)->increment('used');
            }

            $total = max(0, $subtotal - $discount);

            /* ---------------- CREATE ORDER ---------------- */
            $order = Order::create([
                'user_id'   => Auth::id(), 
                'name'      => $request->name,
                'email'     => $request->email,
                'phone'     => $request->phone,
                'address'   => $request->address,
                'subtotal'  => $subtotal,
                'discount'  => $discount,
                'total'     => $total,
                'coupon_id' => $couponId,
                'status'    => 'pending',
            ]);

            /* ---------------- CREATE ORDER ITEMS ---------------- */
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['id'],
                    'price'      => $item['price'],
                    'quantity'   => $item['quantity'],
                ]);
            }

            /* ---------------- CLEAR CART ---------------- */
            if (Auth::check() && auth()->user()->cart) {
                auth()->user()->cart->items()->delete();
            }

            session()->forget('applied_coupon');

            /* ---------------- SEND ADMIN EMAIL NOTIFICATION ---------------- */
            // Load the order with its items
            $order->load('items.product');
            
            // Send email to admin 
            $adminEmail = getSetting('admin_email');
            Mail::to($adminEmail)->send(new OrderMail($order));
        });

        return redirect()
            ->route('checkout.success')
            ->withCookie(cookie()->forget('guest_cart'));
    }


    private function getCart(Request $request)
    {
        // STEP 1: Get raw cart
        if (Auth::check()) {
            $cart = auth()->user()->cart->items->map(fn ($i) => [
                'id'       => $i->product_id,
                'quantity' => $i->quantity,
            ]);
        } else {
            $cart = collect(json_decode($request->cookie('guest_cart', '[]'), true));
        }

        if ($cart->isEmpty()) {
            return collect();
        }

        // STEP 2: Fetch products safely
        $products = Product::whereIn('id', $cart->pluck('id'))
            ->get()
            ->keyBy('id');

        // STEP 3: Normalize cart
        return $cart->map(function ($item) use ($products) {

            $product = $products->get($item['id']);

            if (!$product) return null;

            $price = $product->sell_price ?? $product->price;

            return [
                'id'       => $product->id,
                'name'     => $product->product_title,
                'price'    => $price,
                'quantity' => $item['quantity'],
                'image'    => $product->product_image,
            ];
        })->filter()->values();
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $coupon = Coupon::where('code', strtoupper($request->code))
            ->whereDate('start_date', '<=', now())
            ->whereDate('expiry_date', '>=', now())
            ->first();

        if (!$coupon) {
            return back()->with('error', 'Invalid or expired coupon');
        }

        if ($coupon->max_usage && $coupon->used >= $coupon->max_usage) {
            return back()->with('error', 'Coupon usage limit reached');
        }

        session()->put('applied_coupon', [
            'id'     => $coupon->id,
            'code'   => $coupon->code,
            'type'   => $coupon->type,
            'amount' => $coupon->amount,
        ]);

        return back()->with('success', 'Coupon applied successfully');
    }

    public function removeCoupon()
    {
        session()->forget('applied_coupon');
        return back()->with('success', 'Coupon removed');
    }

    public function success()
    {
        return view('user.checkout.success');
    }
}