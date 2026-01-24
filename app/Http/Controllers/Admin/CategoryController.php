<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
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
        //
        $rules = [
            'name' => [
                'required',
                'min:3',
                Rule::unique('categories')->where(function ($query) use ($request) {
                    return $query->where('parent_id', $request->parent_id);
                }),
            ],
        ];

        $messages = [
            'name.required' => 'The category field is required.',
            'name.min' => 'The category must be at least 3 characters.',
            'name.unique' => 'This category already exists under the same parent. Please choose a different name.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $categories = new Category;
        $categories->name = $request->name;
        $categories->slug = Str::slug($request->name);
        $categories->parent_id = $request->parent_id;
        $categories->save();

        if ($categories) {
            return redirect()->route('categories.index');
        } else {
            return redirect()->back()
                ->withErrors(['category' => 'Unable to create or update the category.'])
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
        $category = Category::findOrFail($id);
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('admin.category.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $category = Category::findOrFail($id);
        $rules = [
            'name' => [
                'required',
                'min:3',
                Rule::unique('categories')
                    ->ignore($category->id) // ignore current category
                    ->where(function ($query) use ($request) {
                        return $query->where('parent_id', $request->parent_id);
                    }),
            ],
        ];
        $messages = [
            'name.required' => 'The category field is required.',
            'name.min' => 'The category must be at least 3 characters.',
            'name.unique' => 'The category already exists. Please choose a different name.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $category = Category::findOrFail($id);
        $category->update([
            'name'      => $request->name,
            'slug'      => Str::slug($request->name),
            'parent_id' => $request->parent_id,
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
        $category->delete();
        return redirect()->route('categories.index');
    }
}
