<?php

namespace App\Http\Controllers;

use App\Models\AbandonedCart;
use App\Models\Booking;
use App\Models\Country;
use App\Models\Transaction;
use App\Models\User;
use App\Mail\OTPMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\WishList;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    public function view_signup()
    {
        $countries = Country::select('c_code', 'c_name')
            ->distinct('c_name')
            ->get()
            ->sortBy(function ($country) {
                return (int) filter_var($country->c_code, FILTER_SANITIZE_NUMBER_INT);
            });

        return view('auth.signup', compact('countries'));
    }

    public function signup(Request $request)
    {
        $rules = [
            'name' => 'required|min:2',
            'email' => 'required|email|unique:users,email',
            'country' => 'required',
            'mobile' => 'required|min:10|unique:users,mobile',
            'state' => 'required',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password',
        ];

        $messages = [
            'name.required' => 'The name field is required.',
            'name.min' => 'The name must be at least 2 characters.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'Email already exists.',
            'country.required' => 'The country code field is required.',
            'mobile.required' => 'The mobile field is required.',
            'mobile.min' => 'The mobile must be at least 10 characters.',
            'mobile.unique' => 'Mobile number already exists.',
            'state.required' => 'The state field is required.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 6 characters.',
            'password_confirmation.required' => 'The password confirmation field is required.',
            'password_confirmation.same' => 'The password confirmation must match the password.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

            $state_name = Country::find($request->state);
            
            $otp = rand(100000, 999999);
            $token = Str::random(32);

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->state = $state_name->s_name;
            $user->country = $request->country;
            $user->otp = $otp;
            $user->token = $token;
            $user->password = Hash::make($request->password);
            $user->otp_expires_at = Carbon::now()->addMinutes(30);
            $user->save();
            $user->assignRole('user');

            $mailData = [
                'email' => $user->email,
                'otp' => $otp,
                'user_name' => $user->name,
            ];

            Mail::to($user->email)->send(new OTPMail($mailData));
            return redirect()->route('view.otp_verify', $token)->with('message', 'User Registered Successfully. Check your mail for verification!')->with("email", $user->email);
        }
    }

    public function view_otp_verify($token)
    {
        $user = User::where('token', $token)->first();

        if (isset($user->email)) {
            return view('auth.otp-verify')->with("email", $user->email);
        } else {
            return redirect()->route('view.signup')->with([
                'success' => false,
                'message' => 'invalid request'
            ]);
        }
    }

    public function otp_verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric|digits:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || $user->otp != $request->otp) {
            return back()->withErrors(['otp' => 'Invalid OTP. Please try again.']);
        }

        if (Carbon::now()->greaterThan($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'OTP has expired. Please request a new one.']);
        }

        $is_password_reset = null;
        if ($user->remember_token == 1) {
            $is_password_reset = true;
        }

        $user->otp = null;
        $user->otp_expires_at = null;
        $user->email_verified_at = Carbon::now();
        $user->remember_token = null;
        if (!$is_password_reset) {
            $user->token = null;
        }
        $user->save();

        if ($is_password_reset) {
            return redirect()->route('view.new_password', ['token' => $user->token]);
        } else {
            return redirect()->route('login')->with([
                'success' => true,
                'message' => 'Email and OTP verified successfully.'
            ]);
        }
    }

    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|string',
            'password' => 'required|string|min:6',
        ];

        $messages = [
            'email.required' => 'The email or username field is required.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 6 characters.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $login_input = $request->input('email');
            $password = $request->input('password');
            $remember = $request->has('remember');

            // Check if input is email or username
            $user = User::where('email', $login_input)
                        ->orWhere('username', $login_input)
                        ->first();
            
            if ($user && is_null($user->email_verified_at)) {
                return back()->withErrors(['email' => 'Your email is not verified. Please try to reset your password.']);
            }

            // Attempt login with email or username
            if (Auth::attempt(['email' => $login_input, 'password' => $password], $remember) || 
                Auth::attempt(['username' => $login_input, 'password' => $password], $remember)) {
                $user = Auth::user();
                
                if ($user->hasRole('admin')) {
                    return redirect()->route('view.admin.dashboard');
                } else {
                    return redirect()->route('view.home');
                }
            }
            
            return redirect()->back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
        }
    }
    public function logout(Request $request)
    {
        $user = Auth::user()->load('cart.items');
        $wishlistProductIds = Wishlist::where('user_id', $user->id)
            ->pluck('product_id')
            ->toArray();

        $cartItems = [];

        if ($user->cart) {
            foreach ($user->cart->items as $item) {
                $cartItems[] = [
                    'id'       => $item->product_id,
                    'quantity' => $item->quantity,
                ];
            }
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->withCookie(cookie('guest_wishlist', json_encode($wishlistProductIds), 60 * 24 * 7, '/'))
            ->withCookie(cookie('guest_cart', json_encode($cartItems), 60 * 24 * 7, '/'));
    }
    public function forgot_password(Request $request)
    {

        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'This email is not registered.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
        ]);

        $user = User::where('email', $request->email)->first();

        $otp = rand(100000, 999999);
        $token = Str::random(32);

        $user->otp = $otp;
        $user->token = $token;
        $user->remember_token = 1;
        $user->otp_expires_at = Carbon::now()->addMinutes(30);
        $user->save();

        $mailData = [
            'email' => $user->email,
            'otp' => $otp,
            'user_name' => $user->name,
        ];
        Mail::to($user->email)->send(new OTPMail($mailData));

        return redirect()->route('view.otp_verify', $token)->with('message', 'Otp Sent Successfully. Check your mail for verification!')->with("email", $user->email);
    }

    public function new_password(Request $request)
    {
        $user = User::where('token', $request->token)->first();
        if (!$user) {
            return redirect()->route('login')->with('message', 'Invalid request.');
        }

        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $user->password = Hash::make($request->password);
        $user->token = null;
        $user->email_verified_at = Carbon::now();
        $user->save();

        Auth::login($user);

        return redirect()->route('view.home')->with([
            'success' => true,
            'message' => 'Password changed successfully.'
        ]);
    }

    public function getStates(Request $request)
    {
        $country_code = $request->country_code;
        $country_name = $request->country_name;

        $states = Country::where('c_code', $country_code)
            ->where('c_name', $country_name)
            ->pluck('s_name', 'id');

        return response()->json($states);
    }

    public function my_account()
    {
        $user = Auth::user();

        if ($user->id) {
            $countries = Country::select('c_code', 'c_name')->distinct('c_name')->get()->sortBy(function ($country) {
                return (int) filter_var($country->c_code, FILTER_SANITIZE_NUMBER_INT);
            });
            $sid = Country::where('s_name', '=', $user->state)->where('c_name', '=', $user->country)->first();

            return view('account')->with(['countries' => $countries, 'user' => $user, 'sid' => $sid]);
        } else {
            return redirect()->back();
        }
    }

    public function profile_update(Request $request)
    {
        $rules = [
            'name' => 'required|min:2',
            'country' => 'required',
            'mobile' => 'required|min:10',
            'state' => 'required',
        ];

        $messages = [
            'name.required' => 'The name field is required.',
            'name.min' => 'The name must be at least 2 characters.',
            'country.required' => 'The country code field is required.',
            'mobile.required' => 'The mobile field is required.',
            'mobile.min' => 'The mobile must be at least 10 characters.',
            'state.required' => 'The state field is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $u = Auth::user();
            $user = User::find($u->id);
            if (!$user) {
                return redirect()->back()->withErrors(['email' => "Please login again to update the profile."])->withInput();
            }

            $state_name = Country::find($request->state);

            $user->name = $request->name;
            $user->mobile = $request->mobile;
            $user->state = $state_name->s_name;
            $user->country = $request->country;
            $user->save();
            return redirect()->route('view.my_account');
        }
    }

    public function mergeGuestStorage(Request $request)
    {
        $userId = Auth::id();

        /* ================= WISHLIST ================= */
        $guestWishlist = json_decode($request->cookie('guest_wishlist', '[]'), true);

        foreach ($guestWishlist ?? [] as $productId) {
            if (!Product::where('id', $productId)->exists()) continue;

            Wishlist::firstOrCreate([
                'user_id'    => $userId,
                'product_id' => $productId,
            ]);
        }

        /* ================= CART ================= */
        $guestCart = json_decode($request->cookie('guest_cart', '[]'), true);

        if (!empty($guestCart)) {

            $cart = Cart::firstOrCreate(['user_id' => $userId]);

            foreach ($guestCart as $item) {

                if (!isset($item['id'])) continue;

                $product = Product::find($item['id']);
                if (!$product) continue;

                $qty = max(1, (int)($item['quantity'] ?? 1));

                $cartItem = CartItem::where('cart_id', $cart->id)
                    ->where('product_id', $product->id)
                    ->first();

                /* ---------- PRODUCT NOT IN CART ---------- */
                if (!$cartItem) {
                    CartItem::create([
                        'cart_id'    => $cart->id,
                        'product_id' => $product->id,
                        'quantity'   => $qty,
                        'price'      => $product->price,
                    ]);
                    continue;
                }

                if ($cartItem->quantity == $qty) {
                    continue;
                }

                $cartItem->quantity += $qty;
                $cartItem->save();
            }
        }

        return response()->json(['status' => 'merged'])
            ->cookie('guest_wishlist', '', -1, '/')
            ->cookie('guest_cart', '', -1, '/');
    }

    
}
