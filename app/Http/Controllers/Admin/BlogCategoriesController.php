<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blog_categories = BlogCategories::all();
        return view('admin.blogs.category')->with(['blog_categories'=>$blog_categories]);
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
        $rules = [
            'category' => 'required|min:3|unique:blog_categories,name,' . $request->edit_category_id,
        ];
        $messages = [
            'category.required' => 'The category field is required.',
            'category.min' => 'The category must be at least 3 characters.',
            'category.unique' => 'The category already exists. Please choose a different name.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            if($request->edit_category_id != 0){
                $blog_categories = BlogCategories::find($request->edit_category_id)->update(['name' =>$request->category ]);
            }else{
                $blog_categories = new BlogCategories;
                $blog_categories->name = $request->category;
                $blog_categories->save();
            }

            if ($blog_categories) {
                return redirect()->route('blog_categories.index');
            } else {
                return redirect()->back()
                    ->withErrors(['category' => 'Unable to create or update the category.'])
                    ->withInput();
            }

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
        $edit_category = BlogCategories::findOrFail($id);
        $blog_categories = BlogCategories::all();
        
        return view('admin.blogs.category')->with(['blog_categories'=>$blog_categories ,'edit_category' =>$edit_category ]);
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
        $category = BlogCategories::findOrFail($id);
        $category->delete();

        return redirect()->route('blog_categories.index');
    }
}
