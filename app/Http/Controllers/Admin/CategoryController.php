<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $parentCategories = Category::whereNull('parent_id')->get();
        $categories = Category::all();
        return view('admin.category.index', compact('parentCategories', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $parentCategories = Category::whereNull('parent_id')->get();

        return view('admin.category.create', compact('parentCategories',));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => [
                'required',
                'min:3',
                Rule::unique('categories')->where(fn ($q) =>
                    $q->where('parent_id', $request->parent_id)
                ),
            ],
            'media_library_logo_id' => 'nullable|exists:media,id',
        ];

        $messages = [
            'name.required' => 'The category field is required.',
            'name.min' => 'The category must be at least 3 characters.',
            'name.unique' => 'This category already exists under the same parent.',
        ];

        $validated = $request->validate($rules, $messages);

        $category = Category::create([
            'name'       => $validated['name'],
            'slug'       => Str::slug($validated['name']),
            'parent_id'  => $request->parent_id,
            'is_visible' => $request->boolean('is_visible'),
            'media_library_logo_id' => $request->media_library_logo_id,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully');
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
        $category = Category::findOrFail($id);
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('admin.category.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $rules = [
            'name' => [
                'required',
                'min:3',
                Rule::unique('categories')
                    ->ignore($category->id)
                    ->where(fn ($q) =>
                        $q->where('parent_id', $request->parent_id)
                    ),
            ],
            'media_library_logo_id' => 'nullable|exists:media,id',
        ];

        $validated = $request->validate($rules);

        $category->update([
            'name'       => $validated['name'],
            'slug'       => Str::slug($validated['name']),
            'parent_id'  => $request->parent_id,
            'is_visible' => $request->boolean('is_visible'),
            'media_library_logo_id' => $request->media_library_logo_id,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $category = Category::findOrFail($id);
        
        // Delete image if exists
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }
        
        $category->delete();
        return redirect()->route('categories.index');
    }
}
