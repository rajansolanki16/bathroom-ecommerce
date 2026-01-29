<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Coupon;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index()
    {
        //
        $coupons = Coupon::all();
        return view('admin.coupon.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.coupon.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'code' => 'required',
            'type' => 'required|in:percentage,fixed',
            'amount' => 'required',
        ];
        $messages = [
            'code.required' => 'The coupon code field is required.',
            'type.required' => 'The discount type field is required.',
            'amount.required' => 'The amount field is required.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $coupon = new Coupon;
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->amount = $request->amount;
        $coupon->description = $request->description;
        $coupon->start_date = $request->start_date;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->max_usage = $request->max_usage;
        $coupon->save();
        if ($coupon) {
            return redirect()->route('coupons.index')->with('success', 'Coupon created successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to create coupon. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $coupon = Coupon::findOrFail($id);
        return view('admin.coupon.edit', compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $rules = [
            'code' => 'required',
            'type' => 'required|in:percentage,fixed',
            'amount' => 'required',
        ];
        $messages = [
            'code.required' => 'The coupon code field is required.',
            'type.required' => 'The discount type field is required.',
            'amount.required' => 'The amount field is required.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $coupon = Coupon::findOrFail($id);
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->amount = $request->amount;
        $coupon->discount_amount = $request->discount_amount;
        $coupon->description = $request->description;
        $coupon->start_date = $request->start_date;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->max_usage = $request->max_usage;
        $coupon->save();
        if ($coupon) {
            return redirect()->route('coupons.index')->with('success', 'Coupon updated successfully.');
        } else {

            return redirect()->back()->with('error', 'Failed to update coupon. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        return redirect()->route('coupons.index')->with('success', 'Coupon deleted successfully.');
    }
}
