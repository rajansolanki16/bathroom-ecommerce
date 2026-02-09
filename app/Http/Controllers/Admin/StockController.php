<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        // $stocks = Stock::with('product')->get();

        $start_date = $request->input('from_date');
        $end_date = $request->input('to_date');

        $stocks = Stock::with('product')
            ->when($start_date && $end_date, function ($query) use ($start_date, $end_date) {
                $query->whereDate('created_at', '>=', $start_date)
                      ->whereDate('created_at', '<=', $end_date);
            })
            ->get();
        return view('admin.stock.index', compact('stocks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $products = Product::all();
        return view('admin.stock.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'products' => 'required|array|min:1',
                'products.*.product_id' => 'required|exists:products,id',
                'products.*.quantity' => 'required|integer',
            ],
            [

                'products.required' => 'At least one product is required.',
                'products.array' => 'Products must be an array.',
                'products.min' => 'At least one product is required.',

                'products.*.product_id.required' => 'Product is required for all rows.',
                'products.*.product_id.exists' => 'The selected product does not exist.',

                'products.*.quantity.required' => 'Quantity is required for all rows.',
                'products.*.quantity.integer' => 'Quantity must be a number.',

            ]
        );
        foreach ($request->products as $item) {
            Stock::create([
                'product_id' => $item['product_id'],
                'quantity'   => $item['quantity'],
                'notes'      => $item['notes'] ?? null,
            ]);
        }


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
        // $stock = Stock::findOrFail($id);
        // return view('admin.stock.edit', compact('stock'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        // $request->validate(
        //     [
        //         'product_name' => 'required|string|min:3',
        //         'quantity'     => 'required|integer|min:0',
        //         'unit'         => 'required|string',
        //     ],
        //     [
        //         'product_name.required' => 'The product name field is required.',
        //         'product_name.min'      => 'The product name must be at least 3 characters.',
        //         'quantity.required'     => 'The quantity field is required.',
        //         'quantity.integer'      => 'The quantity must be a number.',
        //         'unit.required'         => 'The unit field is required.',
        //     ]
        // );

        // $stock = Stock::findOrFail($id);

        // $stock->update([
        //     'product_name' => $request->product_name,
        //     'quantity'     => $request->quantity,
        //     'unit'         => $request->unit,
        //     'notes'        => $request->notes,
        // ]);

        // return redirect()
        //     ->route('stocks.index')
        //     ->with('success', 'Stock updated successfully');
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
