<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttributeValue;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AttributeValueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attribute = ProductAttribute::with('values');
        return view('admin.productattribute.edit', compact('attribute'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'value' => [
                'required',
                Rule::unique('attribute_values')->where(function ($query) use ($request) {
                    return $query->where('product_attribute_id', $request->product_attribute_id);
                })
            ]
        ], [
            'value.required' => 'The attribute value field is required.',
            'value.unique' => 'This attribute value already exists for this attribute.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }


        $attributeValue = new AttributeValue();
        $attributeValue->product_attribute_id = $request->product_attribute_id;
        $attributeValue->value = $request->value;
        $attributeValue->slug = Str::slug($request->value);
        $attributeValue->save();

        $attributeValue->load('attribute');

        return response()->json([
            'id' => $attributeValue->id,
            'value' => $attributeValue->value,
            'attribute' => $attributeValue->attribute->name,
        ]);
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
        $value = AttributeValue::findOrFail($id);

        return response()->json([
            'id' => $value->id,
            'value' => $value->value
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $value = AttributeValue::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'value' => 'required|unique:attribute_values,value,' . $id . ',id,product_attribute_id,' . $value->product_attribute_id
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $value->update([
            'value' => $request->value,
            'slug' => Str::slug($request->value)
        ]);

        $value->load('attribute');

        return response()->json([
            'id' => $value->id,
            'value' => $value->value,
            'attribute' => $value->attribute->name
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        AttributeValue::findOrFail($id)->delete();

        return response()->json([
            'status' => true
        ]);
    }
}
