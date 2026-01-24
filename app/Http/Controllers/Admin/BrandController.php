<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class BrandController extends Controller
{
    /**
     * Display a listing of brands.
     */
    public function index()
    {
        $brands = Brand::paginate(15);
        return view('admin.brand.index', compact('brands'));
    }

    /**
     * Show the form for creating a new brand.
     */
    public function create()
    {
        return view('admin.brand.create');
    }

    /**
     * Store a newly created brand in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|min:3|unique:brands,name',
            'description' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
        ];

        $messages = [
            'name.required' => 'The brand name field is required.',
            'name.min' => 'The brand name must be at least 3 characters.',
            'name.unique' => 'This brand name already exists.',
            'logo.image' => 'The logo must be an image file.',
            'logo.mimes' => 'The logo must be a jpeg, png, jpg, or gif image.',
            'logo.max' => 'The logo size must not exceed 2MB.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        $brand->description = $request->description;
        $brand->is_active = $request->has('is_active') ? true : false;

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logoPath = $logo->storeAs('brands', $logoName, 'public');
            $brand->logo = $logoPath;
        }

        $brand->save();

        return redirect()->route('brands.index')
            ->with('success', 'Brand created successfully');
    }

    /**
     * Display the specified brand.
     */
    public function show(Brand $brand)
    {
        return view('admin.brand.show', compact('brand'));
    }

    /**
     * Show the form for editing the specified brand.
     */
    public function edit(Brand $brand)
    {
        return view('admin.brand.edit', compact('brand'));
    }

    /**
     * Update the specified brand in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'min:3',
                Rule::unique('brands')->ignore($brand->id),
            ],
            'description' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
        ];

        $messages = [
            'name.required' => 'The brand name field is required.',
            'name.min' => 'The brand name must be at least 3 characters.',
            'name.unique' => 'This brand name already exists.',
            'logo.image' => 'The logo must be an image file.',
            'logo.mimes' => 'The logo must be a jpeg, png, jpg, or gif image.',
            'logo.max' => 'The logo size must not exceed 2MB.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        $brand->description = $request->description;
        $brand->is_active = $request->has('is_active') ? true : false;

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
                Storage::disk('public')->delete($brand->logo);
            }

            $logo = $request->file('logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logoPath = $logo->storeAs('brands', $logoName, 'public');
            $brand->logo = $logoPath;
        }

        $brand->save();

        return redirect()->route('brands.index')
            ->with('success', 'Brand updated successfully');
    }

    /**
     * Remove the specified brand from storage.
     */
    public function destroy(Brand $brand)
    {
        // Delete logo if exists
        if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
            Storage::disk('public')->delete($brand->logo);
        }

        $brand->delete();
        return redirect()->route('brands.index')
            ->with('success', 'Brand deleted successfully');
    }
}
