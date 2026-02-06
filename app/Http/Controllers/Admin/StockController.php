<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $stocks = Stock::all();
        return view('admin.stock.index', compact('stocks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.stock.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate(
            [
                'product_name' => 'required|string',
                'quantity'     => 'required|integer|min:0',
                'unit'         => 'required|string',
            ],
            [
                'product_name.required' => 'Product name is required.',
                'quantity.required'     => 'Quantity is required.',
                'unit.required'         => 'Unit is required.',
            ]
        );
        Stock::create([
            'product_name' => $request->product_name,
            'quantity'     => $request->quantity,
            'unit'         => $request->unit,
            'notes'        => $request->notes,

        ]);

        return redirect()
            ->route('stocks.index')
            ->with('success', 'Stock created successfully');
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
        $stock = Stock::findOrFail($id);
        return view('admin.stock.edit', compact('stock'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate(
            [
                'product_name' => 'required|string|min:3',
                'quantity'     => 'required|integer|min:0',
                'unit'         => 'required|string',
            ],
            [
                'product_name.required' => 'The product name field is required.',
                'product_name.min'      => 'The product name must be at least 3 characters.',
                'quantity.required'     => 'The quantity field is required.',
                'quantity.integer'      => 'The quantity must be a number.',
                'unit.required'         => 'The unit field is required.',
            ]
        );

        $stock = Stock::findOrFail($id);

        $stock->update([
            'product_name' => $request->product_name,
            'quantity'     => $request->quantity,
            'unit'         => $request->unit,
            'notes'        => $request->notes,
        ]);

        return redirect()
            ->route('stocks.index')
            ->with('success', 'Stock updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $stock = Stock::findOrFail($id);
        $stock->delete();
        return redirect()->route('stocks.index')
            ->with('success', 'stock deleted successfully');
    }
}
