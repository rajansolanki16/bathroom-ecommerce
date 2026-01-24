<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Blogs;
use App\Models\BlogCategories;
use Illuminate\Support\Str;
// use Spatie\Permission\Models\Role;


class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $order = $request->input('order', 'desc');
        $perPage = $request->input('perPage', 5);

        $baseQuery = Blogs::query();
        $baseQuery->orderBy('created_at', $order);

        $blog = $baseQuery->with('categories')->paginate($perPage);
        $total_blogs = Blogs::count();
        
        return view('admin.blogs.index', compact('blog', 'total_blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $blog_categories = BlogCategories::all();

        return view('admin.blogs.create', compact('blog_categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|max:5000',
            'image' => 'required|image',
            'category' => 'required|array',
            'category.*' => 'exists:blog_categories,id',
        ]);

        $blog = new Blogs();
        $blog->title = $request->title;
        $blog->description = $request->description;
        $slug = Str::slug($request->title);
        $count = Blogs::where('slug', $slug)->count();
        $blog->slug = $count ? "{$slug}-{$count}" : $slug;

        if ($request->hasFile('image')) {
            $imagefile = $request->file('image');
            $imageName = time() . '.' . $imagefile->getClientOriginalExtension();
            $imageupload = 'images/blog/' . $imageName; // New path inside public/images/blog/
            $imagefile->move(public_path('images/blog/'), $imageName);
            $blog->image = $imageupload;
        }

        $blog->save();
        $blog->categories()->attach($request->input('category'));
        return redirect()->route('blogs.index')->with('success', 'Blog created successfully!');
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
    public function edit(string $blog_id)
    {
        $blog = Blogs::findOrFail($blog_id);
        $blog_categories = BlogCategories::all();

        return view('admin.blogs.edit', compact('blog', 'blog_categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $blog_id)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|max:5000',
            'image' => 'image',
            'category' => 'required|array',
            'category.*' => 'exists:blog_categories,id',
        ]);

        $blog = Blogs::findOrFail($blog_id);
        if ($request->filled('title')) {
            $blog->title = $request->input('title');
        }
        if ($request->filled('description')) {
            $blog->description = $request->input('description');
        }

        if ($request->hasFile('image')) {
            if (!empty($blog->image) && file_exists(public_path($blog->image))) {
                unlink(public_path($blog->image));
            }

            $imagefile = $request->file('image');
            $imageName = time() . '.' . $imagefile->getClientOriginalExtension();
            $imageupload = 'images/blog/' . $imageName;
            $imagefile->move(public_path('images/blog/'), $imageName);
            $blog->image = $imageupload;
        }

        $blog->save();
        $blog->categories()->sync($request->input('category'));
        return redirect()->route('blogs.index')->with('success', 'Blog updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $blog_id)
    {
        $blog = Blogs::findOrFail($blog_id);
        $filePath = public_path($blog->image);

        if (!empty($blog->image) && file_exists($filePath)) {
            unlink($filePath);
        }

        $blog->delete();

        return redirect()->route('blogs.index')->with('success', 'Blog deleted successfully!');
    }

    public function view_blog(Request $request)
    {
        $blogs = Blogs::all();

        return view('blogs', compact('blogs'));
    }

    public function blog_list($slug)
    {
        $blog = Blogs::where('slug', $slug)->firstOrFail();
        $recentBlogs = Blogs::orderBy('created_at', 'desc')->take(5)->get();

        return view('blog', compact('blog', 'recentBlogs'));
    }
}
