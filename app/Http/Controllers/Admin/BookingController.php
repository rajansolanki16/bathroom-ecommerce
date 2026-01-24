<?php

namespace App\Http\Controllers\Admin;

use DateTime;
use App\Mail\BookingMail;
use App\Mail\PasswordResetMail;
use App\Http\Controllers\Controller;
use App\Models\AbandonedCart;
use App\Models\Booking;
use App\Models\Country;
use App\Models\Room;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;

class BookingController extends Controller
{
    public function show_bookings()
    {
        $bookings = Booking::orderBy('updated_at', 'desc')->get();
        return view('admin.bookings.booking')->with(["bookings" => $bookings]);
    }

    public function show_single_booking($id)
    {
        $booking = Booking::findOrFail($id);
        $transaction = Transaction::where("transaction_id", $booking->transaction_id)->first();
        return view('admin.bookings.single')->with(["booking" => $booking, "transaction" => $transaction]);
    }

    public function show_transactions()
    {
        $transactions = Transaction::orderBy('updated_at', 'desc')->get();
        return view('admin.bookings.transactions')->with(["transactions" => $transactions]);
    }

    public function show_offline_booking()
    {
        $pay_methods = ['CASH'];
        $services = Service::where('status', 1)->get();
        $rooms = Room::select('id', 'slug', 'name')->get();

        return view('admin.bookings.offline_booking')
            ->with([
                'pay_methods' => $pay_methods,
                'rooms' => $rooms,
                'services' => $services,
            ]);
    }

    public function change_pay_status(Request $request , int $bid){
        $booking = Booking::findOrFail($bid);
        Transaction::where('transaction_id', $booking->transaction_id)->update(['status'=>($request->pay_status)]);
        
        return redirect()->route('view.booking',$booking->id);
    }

    public function store_offline_booking(Request $request)
    {
        $rules = [
            'guest_full_name' => 'required|string',
            'guest_email' => 'required|email',
            'guest_phone' => 'required',
            'guest_address' => 'nullable|string',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after_or_equal:check_in',
            'adults' => 'required|integer|min:0',
            'children' => 'nullable|integer|min:0',
            'room_count' => 'required|integer|min:0',
            'room_type' => 'required|exists:rooms,slug',
            'extra_beds' => 'required|integer|min:0',
            'services' => 'nullable',
            'pay_method' => 'required|string',
            'total_amount' => 'required|numeric|min:0',
            'guest_note' => 'nullable|string|max:1000',
        ];

        $messages = [
            'guest_full_name.required' => 'Guest full name is required.',
            'guest_email.required' => 'Guest email is required.',
            'guest_email.email' => 'Please enter a valid email address.',
            'guest_phone.required' => 'Guest phone number is required.',
            'check_in.required' => 'Check-in date is required.',
            'check_in.date' => 'Check-in must be a valid date.',
            'check_out.required' => 'Check-out date is required.',
            'check_out.date' => 'Check-out must be a valid date.',
            'check_out.after' => 'Check-out date must be after the check-in date.',
            'adults.required' => 'Please specify the number of adults.',
            'adults.integer' => 'Adults must be a valid number.',
            'adults.min' => 'Adult count cannot be negative.',
            'children.integer' => 'Children count must be a valid number.',
            'children.min' => 'Children count cannot be negative.',
            'room_count.required' => 'Please specify the number of rooms.',
            'room_count.integer' => 'Room count must be a valid number.',
            'room_count.min' => 'Room count cannot be negative.',
            'room_type.required' => 'Please select a valid room type.',
            'extra_beds.required' => 'Please enter a valid number or 0.',
            'extra_beds.integer' => 'Quantity should 0 or more',
            'pay_method.required' => 'Please select a payment method.',
            'total_amount.required' => 'Total amount is required.',
            'total_amount.numeric' => 'Total amount must be a valid number.',
            'total_amount.min' => 'Total amount count cannot be negative.',
            'guest_note.string' => 'Guest note must be a valid text.',
            'guest_note.max' => 'Guest note cannot exceed 1000 characters.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

            $is_availavle = $this->check_room_availability($request->room_type, $request->room_count, $request->check_in, $request->check_out);
            if (!$is_availavle) {
                return redirect()->back()->withErrors(['check_out' => 'Not enough rooms available between this dates.'])->withInput();
            }
            $room = Room::where('slug', '=', $request->room_type)->first();

            $guest_info["name"] = $request->guest_full_name;
            $guest_info["email"] = $request->guest_email;
            $guest_info["phone"] = $request->guest_phone;
            $guest_info["address"] = $request->guest_address ? $request->guest_address : "";

            $transaction = new Transaction;
            $transaction->amount = $request->total_amount;
            $transaction->method = $request->pay_method;
            $transaction->status = 1;
            $transaction->transaction_id = Str::uuid();
            $transaction->save();

            $booking = new Booking;
            $booking->type = "OFFLINE";
            $booking->check_in = $request->check_in;
            $booking->check_out = $request->check_out;
            $booking->adults = $request->adults;
            $booking->children = $request->children;
            $booking->room_count = $request->room_count;
            $booking->room_id = $room->id;
            $booking->extra_beds = $request->extra_beds;
            $booking->services = (isset($request->services) && count($request->services) > 0) ? json_encode($request->services) : json_encode([]);
            $booking->total_cost = $request->total_amount;
            $booking->transaction_id = $transaction->transaction_id;
            $booking->customer_note = (isset($request->guest_note) && strlen($request->guest_note) > 0) ? $request->guest_note : null;
            $booking->customer_details = json_encode($guest_info) ?? "\"{}\"";
            $booking->save();

            return redirect()->route('view.bookings');
        }
    }

    public function edit_booking($id)
    {
        $booking = Booking::findOrFail($id);
        $rooms = Room::all();
        $transection = Transaction::where("transaction_id", $booking->transaction_id)->first();
        $services = Service::where('status', 1)->get();
        $pay_methods = ['CASH'];

        return view('admin.bookings.edit')
            ->with([
                'booking' => $booking,
                'transection' => $transection,
                'rooms' => $rooms,
                'services' => $services,
                'pay_methods' => $pay_methods,
            ]);
    }

    public function save_edit_booking(Request $request, int $id)
    {

        $rules = [
            'guest_full_name' => 'required|string',
            'guest_email' => 'required|email',
            'guest_phone' => 'required',
            'guest_address' => 'nullable|string',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'required|integer|min:0',
            'children' => 'nullable|integer|min:0',
            'room_count' => 'required|integer|min:0',
            'room_type' => 'required|integer',
            'services' => 'nullable',
            'guest_note' => 'nullable|string|max:1000',
        ];

        $messages = [
            'guest_full_name.required' => 'Guest full name is required.',
            'guest_email.required' => 'Guest email is required.',
            'guest_email.email' => 'Please enter a valid email address.',
            'guest_phone.required' => 'Guest phone number is required.',
            'check_in.required' => 'Check-in date is required.',
            'check_in.date' => 'Check-in must be a valid date.',
            'check_out.required' => 'Check-out date is required.',
            'check_out.date' => 'Check-out must be a valid date.',
            'check_out.after' => 'Check-out date must be after the check-in date.',
            'adults.required' => 'Please specify the number of adults.',
            'adults.integer' => 'Adults must be a valid number.',
            'adults.min' => 'Adult count cannot be negative.',
            'children.integer' => 'Children count must be a valid number.',
            'children.min' => 'Children count cannot be negative.',
            'room_count.required' => 'Please specify the number of rooms.',
            'room_count.integer' => 'Room count must be a valid number.',
            'room_count.min' => 'Room count cannot be negative.',
            'room_type.required' => 'Please select a room type.',
            'room_type.integer' => 'Room type must be a valid ID.',
            'guest_note.string' => 'Guest note must be a valid text.',
            'guest_note.max' => 'Guest note cannot exceed 1000 characters.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        $booking = Booking::find($request->id);

        if (!$booking) {
            return redirect()->back()->withErrors(['general' => 'Unable to update the booking.']);
        } elseif ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

            $is_availavle = $this->check_room_availability($request->room_type, $request->room_count, $request->check_in, $request->check_out);
            if (!$is_availavle) {
                return redirect()->back()->withErrors(['check_out' => 'Not enough rooms available between this dates.'])->withInput();
            }

            if ($booking->type == "OFFLINE") {
                $guest_info["name"] = $request->guest_full_name;
                $guest_info["email"] = $request->guest_email;
                $guest_info["phone"] = $request->guest_phone;
                $guest_info["address"] = $request->guest_address ? $request->guest_address : "";
                $booking->customer_details = json_encode($guest_info);
            }

            $booking->check_in = $request->check_in;
            $booking->check_out = $request->check_out;
            $booking->adults = $request->adults;
            $booking->children = $request->children;
            $booking->room_count = $request->room_count;
            $booking->room_id = $request->room_type;
            $booking->services = (isset($request->services) && count($request->services) > 0) ? json_encode($request->services) : "[]";
            $booking->customer_note = (isset($request->guest_note) && strlen($request->guest_note) > 0) ? $request->guest_note : null;
            $booking->save();

            return redirect()->route('view.bookings');
        }
    }

    public function checkRoomAvailability(Request $request)
    {
        $rules = [
            'room' => 'required|exists:rooms,slug',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after_or_equal:check_in',
            'quantity' => 'required|integer|min:1',
        ];

        $messages = [
            "room.*" => "Unable to find room.",
            "check_in.required" => "Check In date is required.",
            "check_in.date" => "Check In must be a valid date.",
            "check_in.after_or_equal" => "Check In must not be older than today.",
            "check_out.required" => "Check Out date is required.",
            "check_out.date" => "Check Out must be a valid date.",
            "check_out." => "Check Out must not be older than Check In date.",
            "quantity.required" => "Room Quantity is required",
            "quantity.integer" => "Room Quantity must be in a valid number",
            "quantity.min" => "At least 1 room require for process booking "
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $errorMessages = $validator->errors()->all();
            $firstError = $errorMessages[0] ?? 'Validation failed.';

            return response()->json([
                'status' => 0,
                'message' => $firstError
            ]);
        }

        $room_slug = $request->room;
        $checkIn = $request->check_in;
        $checkOut = $request->check_out;
        $requestedQuantity = $request->quantity;

        $is_availavle = $this->check_room_availability($room_slug, $requestedQuantity, $checkIn, $checkOut);
        if (!$is_availavle) {
            return response()->json(['status' => 0, 'message' => 'Not enough rooms available between this dates.']);
        }

        return response()->json(['status' => 1, 'message' => 'Booking Allowed. Rooms are available.']);
    }

    public function book_stay(Request $request)
    {
        $rules = [
            'check_in' => 'required|date|after_or_equal:today',
            'room' => 'required|exists:rooms,slug',
            'check_out' => 'required|date|after:check_in',
            'quantity' => 'required|integer|min:1',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'extra_beds' => 'nullable|integer|min:0',
            'services' => 'nullable',
        ];

        $messages = [
            "room.*" => "Unable to find room.",
            "check_in.required" => "Check In date is required.",
            "check_in.date" => "Check In must be a valid date.",
            "check_in.after_or_equal" => "Check In must not be older than today.",
            "check_out.required" => "Check Out date is required.",
            "check_out.date" => "Check Out must be a valid date.",
            "check_out." => "Check Out cannot not be older or same date than Check In date.",
            "quantity.required" => "Room Quantity is required",
            "quantity.integer" => "Room Quantity must be in a valid number",
            "quantity.min" => "At least 1 room require for process booking ",
            "adults.required" => "At least 1 adult person is required",
            "adults.integer" => "Adults must be in a valid number",
            "adults.min" => "At least 1 adult person is required ",
            "children.integer" => "Children must be in a valid number",
            "children.min" => "Children count cannot be negative",
            "extra_beds.integer" => "Extra Beds must be in a valid number",
            "extra_beds.min" => "Extra Beds count cannot be negative",
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

            $is_availavle = $this->check_room_availability($request->room, $request->quantity, $request->check_in, $request->check_out);
            if (!$is_availavle) {
                return redirect()->back()->withErrors(['general' => 'Not enough rooms available between this dates.'])->withInput();
            }

            $room = Room::where('slug', '=', $request->room)->first();
            $ac = new AbandonedCart;

            if ($request->quantity > $room->quantity) {
                return redirect()->back()->withErrors(['general' => 'Not enough rooms available between this dates.'])->withInput();
            }
            if ($request->adults > ($room->quantity * $room->allowd_guests)) {
                return redirect()->back()->withErrors(['general' => 'Only' . $room->allowd_guests . 'Adults are allowd per room.'])->withInput();
            }
            if ($request->children > ($room->quantity * 3)) {
                return redirect()->back()->withErrors(['general' => 'Only 3 children are allowd per room.'])->withInput();
            }
            if ($request->extra_beds >  $room->extra_beds) {
                return redirect()->back()->withErrors(['general' => 'Only' . $room->extra_beds . ' allowd for this room'])->withInput();
            }

            if (Auth::check()) {
                $user = Auth::user();
                $ac->user_id = $user->id;
            }

            $ac->check_in = $request->check_in;
            $ac->check_out = $request->check_out;
            $ac->adults = $request->adults;
            $ac->children = $request->children ?? 0;
            $ac->room_id = $room->id;
            $ac->room_count = $request->quantity;
            $ac->extra_beds = $request->extra_beds ?? 0;
            $ac->services = (isset($request->services) && count($request->services) > 0) ? json_encode($request->services) : "[]";

            $check_in = new DateTime($request->check_in);
            $check_out = new DateTime($request->check_out);

            $interval = $check_in->diff($check_out);
            $days = $interval->days;

            $total = $room->offer_price * $request->quantity;

            if ($request->extra_beds > 0) {
                $total += $room->bed_price * $request->extra_beds;
            }

            if (isset($request->services) && count($request->services) > 0) {
                foreach ($request->services as $sid) {
                    $service = Service::find($sid);
                    if (isset($service->id) && $service->status == 1) {
                        $total += $service->price;
                    } else {
                        return redirect()->back()->withErrors(['general' => 'One of your selected services we cannot provide. ']);
                    }
                }
            }

            $grand_total = $total * $days;
            $ac->total_cost = $grand_total;
            $ac->save();

            if ($ac->id) {
                Session::put('abandoned_cart', $ac->id);
                return redirect()->route('view.cart');
            } else {
                return redirect()->back()->withErrors(['general' => 'Unable to process your request.']);
            }
        }
    }

    public function show_cart()
    {
        $acid = Session::get('abandoned_cart');

        if (!$acid) {
            return redirect()->back()->withErrors(['general' => 'Unable to process your request.']);
        }

        try {
            $services = [];
            $ac = AbandonedCart::findOrFail($acid);
            $room = Room::find($ac->room_id);

            $sids = json_decode($ac->services);
            if (count($sids) > 0) {
                $services = Service::whereIn('id', $sids)->get();
            }

            return view('cart')->with(['ac' => $ac, 'room' => $room, 'services' => $services]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['general' => 'Unable to process your request.']);
        }
    }

    public function store_cart(Request $request)
    {

        $rules = [
            'count' => 'required|integer|min:1',
        ];

        $messages = [
            "count.required" => "Room count is required",
            "count.integer" => "Room count must be in a valid number",
            "count.min" => "At least 1 room require for process booking ",
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $acid = Session::get('abandoned_cart');

            if ($acid) {
                $ac = AbandonedCart::find($acid);
                $room = Room::findOrFail($ac->room_id);

                if ($ac->room_count != $request->count) {
                    $is_availavle = $this->check_room_availability($room->slug, $request->count, $ac->check_in, $ac->check_out);
                    if (!$is_availavle) {
                        return redirect()->back()->withErrors(['general' => 'Not enough rooms available between this dates.']);
                    }

                    $rp = $room->offer_price;
                    $checkInDate = new DateTime($ac->check_in);
                    $checkOutDate = new DateTime($ac->check_out);
                    $interval = $checkInDate->diff($checkOutDate);
                    $dayGap = $interval->days + 1;
                    $days = $dayGap > 0 ? $dayGap : 1;
                    $room_charges = $rp * $days;
                    $twrc = $ac->total_cost - $room_charges;
                    $total = $twrc + ($request->count * $rp * $days);

                    $ac->total_cost = $total;
                    $ac->room_count = $request->count;
                    $ac->save();
                }
                return redirect()->route('view.checkout');
            } else {
                return redirect()->back()->withErrors(['general' => 'Unable to process your request.']);
            }
        }
    }

    public function remove_item($id)
    {
        $acid = Session::get('abandoned_cart');

        if ($acid = $id) {
            AbandonedCart::find($acid)->delete();
            Session::forget('abandoned_cart');
            return response()->json([
                'status' => 'success',
                'redirect_url' => route('view.rooms')
            ]);
        }
    }

    public function show_checkout()
    {

        $acid = Session::get('abandoned_cart');

        if (!$acid) {
            return redirect()->back()->withErrors(['general' => 'Unable to process your request.']);
        }

        try {
            $ac = AbandonedCart::findOrFail($acid);
            $room = Room::find($ac->room_id);

            $countries = Country::select('c_code', 'c_name')->distinct('c_name')->get()->sortBy(function ($country) {
                return (int) filter_var($country->c_code, FILTER_SANITIZE_NUMBER_INT);
            });

            if (Auth::user()) {
                $user = Auth::user();
                $sid = Country::where('s_name', '=', $user->state)->where('c_name', '=', $user->country)->first();

                return view('checkout')->with(['user' => $user, 'countries' => $countries, 'sid' => $sid, 'ac' => $ac, 'room' => $room]);
            }

            return view('checkout')->with(['countries' => $countries, 'ac' => $ac, 'room' => $room]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['general' => 'Unable to process your request.']);
        }
    }

    public function checkout(Request $request)
    {
        if (Auth::user()) {
            $rules = [
                'gateway' => 'required|in:CASHFREE,PAYUMONEY,CASH',
            ];
        } else {
            $rules = [
                'name' => 'required|min:2',
                'email' => 'required|email|unique:users,email',
                'mobile' => 'required|min:10',
                'country' => 'required',
                'state' => 'required',
                'gateway' => 'required|in:CASHFREE,PAYUMONEY,CASH',
            ];
        }
        
        $messages = [
            'name.required' => 'The name field is required.',
            'name.min' => 'The name must be at least 2 characters.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'Email already exists in our system, please login with the same email.',
            'mobile.required' => 'The mobile field is required.',
            'mobile.min' => 'The mobile must be at least 10 characters.',
            'country.required' => 'The country code field is required.',
            'state.required' => 'The state field is required.',
            'gateway.required' => 'Please select any one payment method.',
            'gateway.in' => 'Invalid payment method selected.',
        ];
        
        $validator = Validator::make($request->all(), $rules, $messages);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $acid = Session::get('abandoned_cart');
            
            if (!$acid) {
                return redirect()->back()->withErrors(['general' => 'Unable to process your request.']);
            }
            
            try {
                
                $ac = AbandonedCart::findOrFail($acid);
                $room = Room::find($ac->room_id);
                
                if (Auth::user()) {
                    $user = Auth::user();
                } else {
                    $otp = rand(100000, 999999);
                    $token = Str::random(32);
                    $state_name = Country::find($request->state);

                    $user = new User;
                    $user->name = $request->name;
                    $user->email = $request->email;
                    $user->mobile = $request->mobile;
                    $user->state = $state_name->s_name;
                    $user->country = $request->country;
                    $user->otp = $otp;
                    $user->token = $token;
                    $user->password = Hash::make(Carbon::now());
                    $user->otp_expires_at = Carbon::now()->addMinutes(30);
                    $user->save();
                    $user->assignRole('user');

                    Auth::login($user);
                    Mail::to($user->email)->send(new PasswordResetMail($token));
                }
                
                $transaction = new Transaction;
                $transaction->user_id = $user->id;
                $transaction->amount = $ac->total_cost;
                $transaction->method = $request->gateway;
                $transaction->status = 0;
                $transaction->transaction_id = Str::uuid();
                $transaction->save();
                
                $booking = new Booking;
                $booking->type = "WEBSITE";
                $booking->user_id = $user->id;
                $booking->check_in = $ac->check_in;
                $booking->check_out = $ac->check_out;
                $booking->adults = $ac->adults;
                $booking->children = $ac->children;
                $booking->room_id = $ac->room_id;
                $booking->room_count = $ac->room_count;
                $booking->extra_beds = $ac->extra_beds;
                $booking->services = $ac->services ?? json_encode("[]", JSON_UNESCAPED_SLASHES);
                $booking->total_cost = $ac->total_cost;
                $booking->customer_details = json_encode("{}", JSON_UNESCAPED_SLASHES);
                $booking->customer_note = (isset($request->guest_note) && strlen($request->guest_note) > 0) ? $request->guest_note : json_encode("{}", JSON_UNESCAPED_SLASHES);
                $booking->transaction_id = $transaction->transaction_id;

                if($transaction->method == 'CASH'){
                    $booking->save();
                    Transaction::where('transaction_id','=' , $booking->transaction_id)->update(['status'=> 2]);
                    Mail::to($user->email)->send(new BookingMail($booking->id));

                    return redirect()->route('view.home')->with([
                        'success' => true,
                        'message' => 'we sent you an email for your payment status'
                    ]);

                } elseif ($transaction->method == 'CASHFREE') {
                    $booking->save();

                    if(env('PAYMENTS_MODE')) {
                        $orderData = [
                            "order_id" => $transaction->transaction_id,
                            "order_amount" => $ac->total_cost,
                            "order_currency" => "INR",
                            "customer_details" => [
                                "customer_id" => $transaction->transaction_id,
                                "customer_phone" => $user->mobile,
                            ],
                            "order_meta" => [
                                "return_url" => route('cashfree.success', $transaction->transaction_id),
                                "notify_url" => route('cashfree.callback'),
                                "payment_methods" =>  env('CASHFREE_PAYMENT_METHODS', ''),
                                ]
                        ];
                            
                        $apiEndpoint = (env('PAYMENTS_MODE') === "PRODUCTION") ? env('CASHFREE_BASE_URL') : env('CASHFREE_SANDBOX_URL');
                        $apiKey = env('CASHFREE_APP_ID');
                        $apiSecret = env('CASHFREE_SECRET_KEY');
                        
                        $response = Http::withHeaders([
                            'Content-Type' => 'application/json',
                            'x-client-id' => $apiKey,
                            'x-client-secret' => $apiSecret,
                            'x-api-version' => env('CASHFREE_API_VERSION'),
                        ])->post($apiEndpoint, $orderData);
                            
                        $responseData = $response->json();

                        if (isset($responseData['payment_session_id']) && !empty($responseData['payment_session_id'])) {
                            $paymentSessionId = $responseData['payment_session_id'];
                            $mode = (env('PAYMENTS_MODE') === "PRODUCTION") ? 'production' : 'sandbox';

                            $booking->save();

                            return view('payments.cashfree_checkout', [
                                'paymentSessionId' => $paymentSessionId,
                                'mode' => $mode
                            ]);
                        } else {
                            return redirect()->back()->withErrors(['general' => 'Unable to process your request.']);
                        }
                    }else{
                        return redirect()->back()->withErrors(['general' => 'Unable to process your request.']);
                    }
                } elseif ($transaction->method == 'PAYUMONEY') {
                    $booking->save();
                    
                    $MERCHANT_KEY = env('PAYU_MERCHANT_KEY');
                    $SALT = env('PAYU_MERCHANT_SALT');
                    
                    $transaction_id = $transaction->transaction_id;
                    $amount = $ac->total_cost;
                    $product_info = "Booking: " . $booking->id;
                    $customer_name = $user->name;
                    $customer_email = $user->email;
                    
                    $hash = hash('sha512', $MERCHANT_KEY . '|' . $transaction_id . '|' . $amount . '|' . $product_info . '|' . $customer_name . '|' . $customer_email . '|' . "" . '|' . "" . '|' . "" . '|' . "" . '|' . "" . '||||||' . $SALT);
                    
                    $url = (env('PAYMENTS_MODE') === "PRODUCTION") ? env('PAYU_BASE_URL') : env('PAYU_SANDBOX_URL');
                    
                    $data = [];
                    $data['surl'] = route('payu.success',$transaction_id);
                    $data['furl'] = route('payu.fail',$transaction_id);
                    $data['key'] = $MERCHANT_KEY;
                    $data['txnid'] = $transaction_id;
                    $data['amount'] = $amount;
                    $data['productinfo'] = $product_info;
                    $data['firstname'] = $customer_name;
                    $data['lastname'] = '';
                    $data['email'] = $customer_email;
                    $data['hash'] = $hash;

                    return view('payments.payu_checkout', compact('data', 'url'));
                } else {
                    return redirect()->back()->withErrors(['general' => 'Unable to process your request.']);
                }
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['general' => 'Unable to process your request.']);
            }
        }
    }

    public function CashFreeSuccess(Request $request, $tid)
    {
        
        $transaction = Transaction::where('transaction_id', $tid)->first();
        
        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }
        
        if (empty($transaction->mail_status)) {

            $transaction->status = 2;
            $transaction->save();
            $booking = Booking::where('transaction_id', $tid)->first();

            if ($booking->user_id != null) {
                $user = User::find($booking->user_id);
                $user_email = $user->email;
            } else {
                $guest = json_decode($booking->customer_details);
                $user_email = $guest->email;
            }
            Mail::to($user_email)->send(new BookingMail($booking->id));
        }

        return redirect()->route('view.home')->with([
            'success' => true,
            'message' => 'we sent you an email for your payment status'
        ]);
    }

    public function CashFreeCallback(Request $request)
    {
        try {
            $inputData = file_get_contents('php://input');
            $decodedData = json_decode($inputData, true);
            Storage::put('webhook_logs/webhook_log.txt', now() . " - " . $inputData . PHP_EOL, 'public');

            if (!isset($decodedData['data']['order']['order_id']) || !isset($decodedData['data']['payment']['payment_status'])) {
                throw new Exception('Invalid webhook data');
            }

            $tid = $decodedData['data']['order']['order_id'];
            $paymentStatus = $decodedData['data']['payment']['payment_status'];

            $transaction = Transaction::where('transaction_id', $tid)->first();
            if (!$transaction) {
                throw new Exception("Transaction not found for ID: $tid");
            }

            $booking = Booking::where('transaction_id', $tid)->first();
            $user = User::find($booking->user_id);

            switch ($paymentStatus) {
                case 'SUCCESS':
                case 'FLAGGED':
                    if (empty($transaction->mail_status) || $transaction->mail_status != '1') {
                        $transaction->mail_status = 1;
                        $transaction->status = 1;
                        $transaction->save();
                        Mail::to($user->email)->send(new BookingMail($booking->id));
                    }
                    break;

                case 'FAILED':
                case 'CANCELLED':
                case 'INCOMPLETE':
                case 'VOID':
                case 'USER_DROPPED':
                    if (empty($transaction->mail_status) || $transaction->mail_status != '0') {
                        $transaction->mail_status = 0;
                        $transaction->status = 0;
                        $transaction->save();
                        Mail::to($user->email)->send(new BookingMail($booking->id));
                    }
                    break;

                case 'PENDING':
                    if (empty($transaction->mail_status) || $transaction->mail_status != '2') {
                        $transaction->mail_status = 2;
                        $transaction->status = 2;
                        $transaction->save();
                        Mail::to($user->email)->send(new BookingMail($booking->id));
                    }
                    break;

                default:
                    if (empty($transaction->mail_status)) {
                        $transaction->mail_status = 0;
                        $transaction->status = 0;
                        $transaction->save();
                        Mail::to($user->email)->send(new BookingMail($booking->id));
                    }
                    break;
            }

        } catch (Exception $e) {
            Storage::append('webhook_logs/webhook_error_log.txt', now() . " - ERROR: " . $e->getMessage() . " - Data: " . $inputData . PHP_EOL, 'public');
        }
    }

    public function PayUSuccess(Request $request, $tid){
        try {
            if($request->status = "success"){
                $transaction = Transaction::where('transaction_id', $request->txnid)->first(); 
                $booking = Booking::where('transaction_id', $transaction->transaction_id)->first();
                $user = User::find($booking->user_id);

                $transaction->mail_status = 1;
                $transaction->status = 1;
                $transaction->save();
                Mail::to($user->email)->send(new BookingMail($booking->id));
            }
        }
        catch (Exception $e) {
            Storage::append('webhook_logs/webhook_error_log.txt', now() . " - ERROR: " . $e->getMessage() . " - Data: " . $request . PHP_EOL);
        } 

        return redirect()->route('view.home')->with([
            'success' => true,
            'message' => 'we sent you an email for your payment status'
        ]);
    }

    public function PayUfail(Request $request, $tid){
        $transaction = Transaction::where('transaction_id', $tid)->first();        
        $booking = Booking::where('transaction_id', $transaction->transaction_id)->first();
        $user = User::find($booking->user_id);

        $transaction->mail_status = 0;
        $transaction->status = 0;
        $transaction->save();
        Mail::to($user->email)->send(new BookingMail($booking->id));
        return redirect()->route('view.home')->with([
            'success' => true,
            'message' => 'we sent you an email for your payment status'
        ]);
    }


    public function quick_book(Request $request)
    {
        $rules = [
            'room_type' => 'required|exists:rooms,slug',
            'quantity' => 'required|integer|min:1',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after_or_equal:check_in',
        ];

        $messages = [
            "room_type.required" => "Please select valid room from list.",
            "room_type.exists" => "This room does not exists.",
            "check_in.required" => "Check In date is required.",
            "check_in.date" => "Check In must be a valid date.",
            "check_in.after_or_equal" => "Check In must not be older than today.",
            "check_out.required" => "Check Out date is required.",
            "check_out.date" => "Check Out must be a valid date.",
            "check_out." => "Check Out must not be older than Check In date.",
            "quantity.required" => "Room Quantity is required",
            "quantity.integer" => "Room Quantity must be in a valid number",
            "quantity.min" => "At least 1 room require for process booking "
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

            $is_availavle = $this->check_room_availability($request->room_type, $request->quantity, $request->check_in, $request->check_out);
            if (!$is_availavle) {
                return redirect()->back()->withErrors(['quick_reserve' => 'Not enough rooms available between this dates.'])->withInput();
            }

            $booking_session = Session::get('find_booking', []);

            if (!empty($booking_session)) {
                $booking_session = array_filter($booking_session, function ($session_room) use ($request) {
                    return $session_room['room_type'] !== $request->room_type;
                });
            }

            $booking_session[] = [
                "room_type" => $request->room_type,
                "quantity"  => $request->quantity,
                "check_in"  => $request->check_in,
                "check_out" => $request->check_out
            ];

            Session::put('find_booking', array_values($booking_session));

            return redirect()->route("view.room", $request->room_type);
        }
    }

    private function check_room_availability($room_slug, int $quantity, $checkIn, $checkOut)
    {

        $room = Room::where('slug', '=', $room_slug)->first();
        $totalRooms = $room->quantity;

        $bookings = Booking::where('room_id', $room->id)->get();

        $reservedRooms = [];

        foreach ($bookings as $booking) {
            $transaction = Transaction::where('transaction_id', '=', $booking->transaction_id)->first();

            if ($transaction->status == 1) {
                $period = CarbonPeriod::create(
                    $booking->check_in->format('Y-m-d'),
                    $booking->check_out->format('Y-m-d')
                );

                foreach ($period as $date) {
                    $dateString = $date->format('Y-m-d');
                    $reservedRooms[$dateString] = ($reservedRooms[$dateString] ?? 0) + $booking->room_count;
                }
            }
        }

        $requestedPeriod = CarbonPeriod::create($checkIn, $checkOut);
        foreach ($requestedPeriod as $date) {
            $dateString = $date->format('Y-m-d');
            $roomsBooked = $reservedRooms[$dateString] ?? 0;

            if ($roomsBooked + $quantity > $totalRooms) {
                return false;
            }
        }
        return true;
    }
}
