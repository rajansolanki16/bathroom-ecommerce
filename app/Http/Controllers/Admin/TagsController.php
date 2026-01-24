<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Tag;

class TagsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $tags = Tag::all();
        return view('admin.tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.tags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $rules = [
            'name' => 'required|min:3'
        ];
        $messages = [
            'name.required' => 'The tags field is required.',
            'name.min' => 'The tags must be at least 3 characters.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $tags = new Tag;
        $tags->name = $request->name;
        $tags->slug = Str::slug($request->name);
        $tags->save();
        if ($tags) {
            return redirect()->route('tags.index');
        } else {
            return redirect()->back()
                ->withErrors(['tags' => 'Unable to create the tags.'])
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
        $tag = Tag::findOrFail($id);
        return view('admin.tags.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $rules = [
            'name' => 'required|min:3'
        ];
        $messages = [
            'name.required' => 'The tags field is required.',
            'name.min' => 'The tags must be at least 3 characters.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);   
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $tags = Tag::findOrFail($id);
        $tags->name = $request->name;
        $tags->slug = Str::slug($request->name);
        $tags->save();
        if ($tags) {
            return redirect()->route('tags.index');
        } else {
            return redirect()->back()
                ->withErrors(['tags' => 'Unable to update the tags.'])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $tag = Tag::findOrFail($id);
        $tag->delete();
        return redirect()->route('tags.index');
    }
}
