<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentOptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $paymentOptions = PaymentOption::all();
        return view('admin.paymentoptions.index', compact('paymentOptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.paymentoptions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $rules = [
            'payment_type' => ['required','regex:/^[A-Za-z\s]+$/','unique:payment_options,payment_type',],
        ];
        $messages = [
            'payment_type.required' => 'The payment type field is required.',
            'payment_type.regex' => 'The payment type must be a string.',
            'payment_type.unique'=>'This payment type is already exists'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $paymentOption = new PaymentOption();
        $paymentOption->payment_type = $request->payment_type;
        $paymentOption->is_active = $request->has('is_active') ? 1 : 0;
        $paymentOption->save(); 
        return redirect()->route('paymentoptions.index')->with('success', 'Payment option created successfully.');
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
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
