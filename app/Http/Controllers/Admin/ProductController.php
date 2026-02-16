<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\ProductType;
use App\Enums\ProductVisibility;
use App\Enums\ProductStatus;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new ProductsImport, $request->file('import_file'));
            return back()->with('success', 'Products imported successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error during import: ' . $e->getMessage());
        }
    }

    public function index(Request $request)
    {
        $query = Product::with('categories');

        if ($request->filled('search')) {
            $query->where('product_title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::whereNull('parent_id')->with('children')->orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('admin.products.create', [
            'productTypes'       => ProductType::cases(),
            'productStatuses'    => ProductStatus::cases(),
            'productVisibilities' => ProductVisibility::cases(),
            'categories'         => Category::whereNull('parent_id')->with('children')->orderBy('name')->get(),
            'allTags'            => Tag::orderBy('name')->get(),
            'allbrands'          => Brand::orderBy('name')->get(),
            'allcolors'          => Color::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('=== STORE METHOD STARTED ===', [
            'all_inputs' => $request->except(['product_image', 'gallery_images']),
            'has_files' => count($request->files->all()) > 0,
        ]);

        try {
            $validated = $request->validate([
                'title'                     => 'required|string|max:150',
                'sku_number'                => 'nullable|string|max:64|unique:products,sku_number',
                'meta_title'                => 'nullable|string|max:160',
                'meta_description'          => 'nullable|string|max:160',
                'meta_keywords'             => 'nullable|string',

                'categories'                => 'required|array',
                'categories.*'              => 'exists:categories,id',

                'tags'                      => 'nullable|array',
                'tags.*'                    => 'exists:tags,id',

                'short_description'         => 'required|string|max:500',
                'product_decscription'      => 'nullable|string',

                'brand_id'                  => 'required|exists:brands,id',

                'price'                     => 'nullable|numeric|min:0',
                'discount'                  => 'nullable|numeric|min:0',

                'sell_price'                => 'nullable|numeric|min:0|lte:price',
                'sell_price_start_date'     => 'nullable|date',
                'sell_price_end_date'       => 'nullable|date|after_or_equal:sell_price_start_date',

                'weight'                    => 'nullable|numeric|min:0',
                'length'                    => 'nullable|numeric|min:0',
                'width'                     => 'nullable|numeric|min:0',
                'height'                    => 'nullable|numeric|min:0',

                'media_library_main_image_id' => 'required|exists:media,id',

                'product_image'             => 'nullable|image|mimes:jpg,jpeg,png,webp',
                'gallery_images.*'          => 'nullable|image|mimes:jpg,jpeg,png,webp',
            ], [

                // Title
                'title.required' => 'Product title is required.',
                'title.max'      => 'Product title cannot exceed 150 characters.',

                // SKU
                'sku_number.unique' => 'This SKU already exists. Please use a different SKU.',

                // Categories
                'categories.required' => 'Please select at least one category.',
                'categories.array'    => 'Invalid category selection.',
                'categories.*.exists' => 'Selected category is invalid.',

                // Tags
                'tags.*.exists' => 'Selected tag is invalid.',

                // Short description
                'short_description.required' => 'Short description is required.',
                'short_description.max'      => 'Short description cannot exceed 500 characters.',

                // Brand
                'brand_id.required' => 'Please select a brand.',
                'brand_id.exists'   => 'Selected brand is invalid.',

                // Price
                'price.required' => 'Price is required.',
                'price.numeric'  => 'Price must be a number.',
                'price.min'      => 'Price must be at least 0.',

                // Discount
                'discount.numeric' => 'Discount must be a number.',
                'discount.min'     => 'Discount cannot be negative.',

                // Sell price
                'sell_price.numeric' => 'Sell price must be a number.',
                'sell_price.lte'     => 'Sell price must be less than or equal to the original price.',

                // Sell price dates
                'sell_price_start_date.date' => 'Start date must be a valid date.',
                'sell_price_end_date.date'   => 'End date must be a valid date.',
                'sell_price_end_date.after_or_equal' => 'End date must be after or equal to start date.',

                // Dimensions
                'weight.numeric' => 'Weight must be a number.',
                'length.numeric' => 'Length must be a number.',
                'width.numeric'  => 'Width must be a number.',
                'height.numeric' => 'Height must be a number.',

                // Main image
                'media_library_main_image_id.required' => 'Main product image is required.',
                'media_library_main_image_id.exists'   => 'Selected main image is invalid.',

                // Product image
                'product_image.image' => 'Product image must be an image file.',
                'product_image.mimes' => 'Product image must be JPG, JPEG, PNG, or WEBP.',

                // Gallery images
                'gallery_images.*.image' => 'Each gallery file must be an image.',
                'gallery_images.*.mimes' => 'Gallery images must be JPG, JPEG, PNG, or WEBP.',
            ]);

            Log::info('=== INITIAL VALIDATION PASSED ===', ['validated' => $validated]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('=== INITIAL VALIDATION FAILED ===', [
                'errors' => $e->errors(),
            ]);
            throw $e;
        }

        /* ===============================
        Create Product
        =============================== */

        Log::info('=== CREATING PRODUCT ===');

        $product = new Product();

        $product->product_title        = $validated['title'];
        $product->slug                 = Str::slug($validated['title']);
        $product->meta_title           = $validated['meta_title'] ?? null;
        $product->meta_description     = $validated['meta_description'] ?? null;
        $product->meta_keywords        = $validated['meta_keywords'] ?? null;
        $product->sku_number           = $validated['sku_number'] ?? null;
        $product->short_description    = $validated['short_description'];
        $product->product_decscription = $request->product_decscription;

        $product->exchangeable         = $request->boolean('exchangeable');
        $product->refundable           = $request->boolean('refundable');
        $product->free_shipping        = $request->boolean('free_shipping');

        $product->brand_id             = $request->brand_id ?? null;
        $product->color_id             = $request->color_id ?? null;
        // Main and gallery IDs can come as CSV string (from JS) or array; normalize here
        $product->media_library_main_image_id = $request->input('media_library_main_image_id') ?? null;
        $galleryIds = $request->input('media_library_gallery_image_ids');
        if (is_string($galleryIds) && strlen($galleryIds)) {
            $galleryArr = array_values(array_filter(array_map('trim', explode(',', $galleryIds))));
        } elseif (is_array($galleryIds)) {
            $galleryArr = $galleryIds;
        } else {
            $galleryArr = [];
        }
        $product->media_library_gallery_image_ids = !empty($galleryArr) ? json_encode($galleryArr) : null;

        $product->stock                = $request->stock_status ?? 0;

        $product->price                = $validated['price'] ?? 0;
        $product->discount             = $validated['discount'] ?? 0;

        $product->sell_price           = $validated['sell_price'] ?? null;
        $product->sell_price_start_date = $validated['sell_price_start_date'] ?? null;
        $product->sell_price_end_date  = $validated['sell_price_end_date'] ?? null;

        $product->weight               = $validated['weight'] ?? null;
        $product->length               = $validated['length'] ?? null;
        $product->width                = $validated['width'] ?? null;
        $product->height               = $validated['height'] ?? null;
        

        $product->status               = $request->status ?? 1;
        $product->visibility           = $request->visibility ?? 1;

        if ($request->hasFile('product_image')) {
            $product
                ->addMedia($request->file('product_image'))
                ->toMediaCollection('main_image');
        }

        // Gallery images (multiple)
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $product
                    ->addMedia($image)
                    ->toMediaCollection('gallery');
            }
        }

        try {
            $product->save();
            Log::info('=== PRODUCT SAVED SUCCESSFULLY ===', ['product_id' => $product->id]);

            // If media library selections were used, copy those media to this product's collections
            if ($product->media_library_main_image_id) {
                $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($product->media_library_main_image_id);
                if ($media) {
                    // ensure single file collection is cleared and then copy
                    $product->clearMediaCollection('main_image');
                    $media->copy($product, 'main_image');
                }
            }

            if ($product->media_library_gallery_image_ids) {
                $galleryIds = is_string($product->media_library_gallery_image_ids) ? json_decode($product->media_library_gallery_image_ids, true) : $product->media_library_gallery_image_ids;
                if (is_array($galleryIds)) {
                    // optional: clear existing gallery
                    // $product->clearMediaCollection('gallery');
                    foreach ($galleryIds as $gid) {
                        $m = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($gid);
                        if ($m) {
                            $m->copy($product, 'gallery');
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('=== PRODUCT SAVE FAILED ===', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
        /* ===============================
        Sync Relations
        =============================== */
        if (!empty($validated['categories'])) {
            $product->categories()->sync($validated['categories']);
        }

        if ($request->filled('tags')) {
            $product->tags()->sync($request->tags);
        }


        Log::info('=== STORE METHOD COMPLETED SUCCESSFULLY ===', ['product_id' => $product->id]);
        return redirect()
            ->route('products.index')
            ->with('success', 'Product created successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['categories', 'brand', 'tags', 'media']);
        return view('admin.products.show', compact('product'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $galleryImages = $product->getMedia('gallery');

        if (empty($product->media_library_main_image_id)) {
            $mainMedia = $product->getFirstMedia('main_image');
            if ($mainMedia) {
                $libraryMedia = \Spatie\MediaLibrary\MediaCollections\Models\Media::where('model_type', 'App\\Models\\Setting')
                    ->where('collection_name', 'uploads')
                    ->where('file_name', $mainMedia->file_name)
                    ->first();

                if ($libraryMedia) {
                    $product->media_library_main_image_id = $libraryMedia->id;
                    $product->save();
                }
            }
        }

        // Convert gallery IDs from JSON to CSV format for the hidden input
        $galleryIds = '';
        if ($product->media_library_gallery_image_ids) {
            $decoded = json_decode($product->media_library_gallery_image_ids, true);
            if (is_array($decoded)) {
                $galleryIds = implode(',', $decoded);
            }
        }

        return view('admin.products.edit', [
            'product'            => $product,
            'categories'         => Category::whereNull('parent_id')->with('children')->orderBy('name')->get(),
            'productTypes'       => ProductType::cases(),
            'productStatuses'    => ProductStatus::cases(),
            'productVisibilities' => ProductVisibility::cases(),
            'allTags'            => Tag::orderBy('name')->get(),
            'allbrands'          => Brand::orderBy('name')->get(),
            'allcolors'          => Color::orderBy('name')->get(),
            'galleryImages'      => $galleryImages,
            'galleryIds'         => $galleryIds,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        Log::info('Product update request received', [
            'product_id' => $product->id,
            'request_data' => $request->all(),
        ]);

        /* ===============================
        BASE VALIDATION
        =============================== */
        $validator = Validator::make($request->all(), [
            'title'             => 'required|string|max:255',
            'meta_title'        => 'nullable|string|max:160',
            'meta_description'  => 'nullable|string|max:160',
            'meta_keywords'     => 'nullable|string',
            'categories'        => 'required|array',
            'short_description' => 'required|string',
            'price'             => 'nullable|numeric',
            'stock'             => 'nullable|integer',
            'brand_id'         => 'required|exists:brands,id',
            'media_library_main_image_id' => 'required|exists:media,id',
            'media_library_gallery_image_ids' => 'nullable',
            'media_library_gallery_image_ids.*' => 'exists:media,id',
            'product_image'     => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'gallery_images.*'  => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ], [

            // Title
            'title.required' => 'Product title is required.',
            'title.max'      => 'Product title cannot exceed 255 characters.',

            // Categories
            'categories.required' => 'Please select at least one category.',
            'categories.array'    => 'Invalid category format.',

            // Short description
            'short_description.required' => 'Short description is required.',

            // Price
            'price.numeric' => 'Price must be a valid number.',

            // Stock
            'stock.integer' => 'Stock must be a valid integer value.',

            // Brand
            'brand_id.required' => 'Please select a brand.',
            'brand_id.exists'   => 'Selected brand is invalid.',

            // Main image
            'media_library_main_image_id.required' => 'Main product image is required.',
            'media_library_main_image_id.exists'   => 'Selected main image is invalid.',

            // Gallery IDs
            'media_library_gallery_image_ids.*.exists' => 'One or more selected gallery images are invalid.',

            // Product image upload
            'product_image.image' => 'Product image must be an image file.',
            'product_image.mimes' => 'Product image must be JPG, JPEG, PNG, or WEBP.',

            // Gallery uploads
            'gallery_images.*.image' => 'Each gallery file must be an image.',
            'gallery_images.*.mimes' => 'Gallery images must be JPG, JPEG, PNG, or WEBP.',
        ]);

        if ($validator->fails()) {
            Log::error('Product base validation failed', [
                'product_id' => $product->id,
                'errors' => $validator->errors()->toArray(),
            ]);

            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        Log::info('Product base validation passed', [
            'product_id' => $product->id,
            'validated_data' => $validated,
        ]);

        /* ===============================
        CONDITIONAL SIMPLE PRODUCT VALIDATION
        =============================== */
        $simpleValidator = Validator::make($request->all(), [
            'price' => 'nullable|numeric',
            'stock' => 'nullable|integer',
        ]);

        if ($simpleValidator->fails()) {
            Log::error('Simple product validation failed', [
                'product_id' => $product->id,
                'errors' => $simpleValidator->errors()->toArray(),
            ]);

            return back()->withErrors($simpleValidator)->withInput();
        }

        Log::info('Simple product validation passed', [
            'product_id' => $product->id,
        ]);

        /* ===============================
        PRODUCT UPDATE
        =============================== */
        $product->update([
            'product_title'        => $validated['title'],
            'slug'                 => Str::slug($validated['title']),
            'meta_title'           => $validated['meta_title'] ?? null,
            'meta_description'     => $validated['meta_description'] ?? null,
            'meta_keywords'        => $validated['meta_keywords'] ?? null,
            'short_description'    => $validated['short_description'],
            'product_decscription' => $request->product_decscription ?? $product->product_decscription,
            'exchangeable'         => $request->boolean('exchangeable'),
            'refundable'           => $request->boolean('refundable'),
            'free_shipping'        => $request->boolean('free_shipping'),
            'brand_id'             => $request->brand_id,
            'color_id'             => $request->color_id,
            'status'               => $request->status ?? $product->status,
            'visibility'           => $request->visibility ?? $product->visibility,
            'sell_price'           => $request->sell_price,
            'sell_price_start_date' => $request->sell_price_start_date,
            'sell_price_end_date'  => $request->sell_price_end_date,
            'weight'               => $request->weight,
            'length'               => $request->length,
            'width'                => $request->width,
            'stock'                => $validated['stock'] ?? $product->stock,
            'height'               => $request->height,
            'media_library_main_image_id' =>
            $validated['media_library_main_image_id'] ?? $product->media_library_main_image_id,
            'media_library_gallery_image_ids' => $this->formatGalleryIds($validated['media_library_gallery_image_ids'] ?? null, $product->media_library_gallery_image_ids),
        ]);

        Log::info('Product main data updated', ['product_id' => $product->id]);

        /* ===============================
        MEDIA LIBRARY LOGS
        =============================== */
        if (!empty($validated['media_library_main_image_id'])) {
            Log::info('Updating main image from media library', [
                'product_id' => $product->id,
                'media_id' => $validated['media_library_main_image_id'],
            ]);
        }

        if (!empty($validated['media_library_gallery_image_ids'])) {
            Log::info('Updating gallery images from media library', [
                'product_id' => $product->id,
                'media_ids' => $validated['media_library_gallery_image_ids'],
            ]);
        }
       
        $product->update([
           // 'stock'    => $validated['stock'],
            'price'    => $validated['price'],
            'discount' => $request->discount ?? $product->discount,
        ]);

        // ADD THIS HERE
        if ($request->has('categories')) {
            $product->categories()->sync($request->categories);
        } else {
            $product->categories()->detach();
        }
        
        if ($request->has('tags')) {
            $product->tags()->sync($request->tags);
        } else {
            $product->tags()->detach();
        }

        Log::info('Product update completed successfully', [
            'product_id' => $product->id,
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }


    public function userShow(string $slug)
    {
        $product = Product::where('slug', $slug)
            ->where('status', 1)
            ->where('visibility', 1)
            ->with([
                'categories',
                'tags',
            ])
            ->firstOrFail();

        return view('user.product.show', compact('product'));
    }

    /**
     * Format gallery IDs from CSV or array to JSON
     */
    private function formatGalleryIds($newIds, $currentIds)
    {
        if (empty($newIds)) {
            return $currentIds;
        }

        $ids = [];
        if (is_string($newIds)) {
            $ids = array_filter(array_map('trim', explode(',', $newIds)));
        } elseif (is_array($newIds)) {
            $ids = array_filter($newIds);
        }
        return !empty($ids) ? json_encode(array_values($ids)) : null;
    }
}
