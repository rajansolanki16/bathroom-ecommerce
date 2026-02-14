<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $colors = Color::when($request->filled('enabled'), function ($query) use ($request) {
            $query->where('show_on_home', $request->enabled);
        })->latest()->get();

        return view('admin.color.index', compact('colors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.color.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate(
            [
                'name' => 'required|string|min:3|regex:/^[a-zA-Z ]+$/|unique:colors,name',
                'show_on_home' => $request->has('show_on_home') ? 1 : 0,

            ],
            [
                'name.required' => 'The color name field is required.',
                'name.min'      => 'The color name must be at least 3 characters.',
                'name.regex' => 'The color name must be take  only alphabets.',
            ]
        );

        Color::create([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
        ]);

        return redirect()->route('colors.index')
            ->with('success', 'color created successfully');
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
        $color = Color::findOrFail($id);
        return view('admin.color.edit', compact('color'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'name' => 'required|string|min:3|regex:/^[a-zA-Z ]+$/|unique:colors,name,' . $id,
        ]);
        $color = Color::findOrFail($id);
        $color->update([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
        ]);

        return redirect()->route('colors.index')
            ->with('success', 'color updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $color = Color::findOrFail($id);
        $color->delete();
        return redirect()->route('colors.index')
            ->with('success', 'color deleted successfully');
    }

    public function toggleHome(Request $request)
    {
        $request->validate([
            'color_id' => 'required|exists:colors,id',
        ]);

        $color = Color::findOrFail($request->color_id);

        $color->update([
            'show_on_home' => ! $color->show_on_home
        ]);

        return response()->json([
            'status' => true,
            'show_on_home' => $color->show_on_home,
        ]);
    }
}
