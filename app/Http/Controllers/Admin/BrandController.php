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
    public function index(Request $request)
    {
        $brands = Brand::when($request->filled('search'), function ($query) use ($request) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('slug', 'like', '%' . $request->search . '%');
            });
        })
            ->when($request->filled('enabled'), function ($query) use ($request) {
                $query->where('show_on_home', $request->enabled);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

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
        $request->validate(
            [
                'name' => 'required|string|min:3|regex:/^[a-zA-Z ]+$/|unique:brands,name',
                'description' => 'nullable|string|max:1000',
                'media_library_logo_id' => 'required|exists:media,id',
                'show_on_home' => 'nullable|boolean',
            ],
            [
                'name.required' => 'Brand name is required.',
                'name.min' => 'Brand name must be at least 3 characters.',
                'name.regex' => 'Brand name may only contain letters and spaces.',
                'name.unique' => 'This brand name already exists. Please choose another.',

                'description.max' => 'Description cannot exceed 1000 characters.',

                'media_library_logo_id.required' => 'Please select a brand banner image from the media library.',
                'media_library_logo_id.exists' => 'The selected image is invalid. Please choose a valid media file.',

                'show_on_home.boolean' => 'Invalid value for Show on Home Page.',
            ]
        );

        $brand = Brand::create([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'media_library_logo_id' => $request->media_library_logo_id,
            'show_on_home' => $request->boolean('show_on_home'),
        ]);

        return redirect()->route('brands.index')
            ->with('success', 'Brand created successfully.');
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
        $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'min:3',
                    'regex:/^[a-zA-Z ]+$/',
                    Rule::unique('brands')->ignore($brand->id),
                ],
                'media_library_logo_id' => 'required|exists:media,id',
                'description' => 'nullable|string|max:1000',
                'show_on_home' => 'nullable|boolean',
            ],
            [
                'name.required' => 'Brand name is required.',
                'name.min' => 'Brand name must be at least 3 characters.',
                'name.regex' => 'Brand name may only contain letters and spaces.',
                'name.unique' => 'This brand name already exists. Please choose another.',

                'description.max' => 'Description cannot exceed 1000 characters.',

                'show_on_home.boolean' => 'Invalid value for Show on Home Page.',

                'media_library_logo_id.required' => 'Please select a brand banner image from the media library.',
                'media_library_logo_id.exists' => 'The selected image is invalid. Please choose a valid media file.',
            ]
        );

        // Handle remove logo
        if ($request->remove_logo) {
            $brand->media_library_logo_id = null;
        }

        // Handle new selected logo
        if ($request->filled('media_library_logo_id')) {
            $brand->media_library_logo_id = $request->media_library_logo_id;
        }

        $brand->update([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'is_active'   => $request->boolean('is_active'),
            'show_on_home' => $request->boolean('show_on_home'),
        ]);

        return redirect()->route('brands.index')
            ->with('success', 'Brand updated successfully.');
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

    public function toggleHome(Request $request)
    {
        $request->validate([
            'brand_id' => 'required|exists:brands,id',
        ]);

        $brand = Brand::findOrFail($request->brand_id);

        $brand->show_on_home = ! $brand->show_on_home;
        $brand->save();

        return response()->json([
            'status' => true,
            'show_on_home' => $brand->show_on_home
        ]);
    }
}
