<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductAttributeController extends Controller
{
    /**
     * Display a listing of the resource.      
     */
    public function index()
    {
        //
        $attributes = ProductAttribute::all();
        return view('admin.productattribute.index', compact('attributes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.productattribute.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $rules = [
            'name' => 'required|min:3|unique:product_attribute,name'
        ];
        $messages = [
            'name.required' => 'The product attribute name field is required.',
            'name.min' => 'The product attribute name must be at least 3 characters.',
            'name.unique' => 'The product attribute name has already been taken.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $attribute = new ProductAttribute;
        $attribute->name = $request->name;
        $attribute->slug = Str::slug($request->name);
        $attribute->save();
        if ($attribute) {
            return redirect()->route('product_attributes.index');
        } else {
            return redirect()->back()
                ->withErrors(['product_attributes' => 'Unable to create the product attribute.'])
                ->withInput();
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
        $attribute = ProductAttribute::with('values')->findOrFail($id);
        return view('admin.productattribute.edit', compact('attribute'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $rules = [
            'name' => 'required|min:3|unique:product_attribute,name,' . $id
        ];
        $messages = [
            'name.required' => 'The product attribute name field is required.',
            'name.min' => 'The product attribute name must be at least 3 characters.',
            'name.unique' => 'The product attribute name has already been taken.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $attribute = ProductAttribute::findOrFail($id);
        $attribute->name = $request->name;
        $attribute->slug = Str::slug($request->name);
        $attribute->save();
        if ($attribute) {
            return redirect()->route('product_attributes.index');
        } else {
            return redirect()->back()
                ->withErrors(['product_attributes' => 'Unable to update the product attribute.'])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $attribute = ProductAttribute::findOrFail($id);
        $attribute->delete();
        return redirect()->route('product_attributes.index');
    }
}
