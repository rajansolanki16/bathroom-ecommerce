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
use App\Models\Product;
use App\Models\Tag;
use App\Models\ProductAttribute;
use App\Models\ProductVariant;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $attributes = ProductAttribute::with('values')->get();
        $attributesJson = $attributes->map(function ($a) {
            return [
                'id' => $a->id,
                'name' => $a->name,
                'values' => $a->values->map(function ($v) {
                    return ['id' => $v->id, 'value' => $v->value];
                }),
            ];
        })->toJson();

        return view('admin.products.create', [
            'productTypes'       => ProductType::cases(),
            'productStatuses'    => ProductStatus::cases(),
            'productVisibilities' => ProductVisibility::cases(),
            'categories'         => Category::orderBy('name')->get(),
            'allTags'            => Tag::orderBy('name')->get(),
            'allbrands'          => Brand::orderBy('name')->get(),
            'attributes'         => $attributes,
            'attributesJson'     => $attributesJson,
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
                'title'                     => 'required|string|max:255',
                'sku_number'                => 'nullable|string|max:255|unique:products,sku_number',
                'meta_title'                => 'nullable|string|max:160',
                'meta_description'          => 'nullable|string|max:160',
                'meta_keywords'             => 'nullable|string',

                'categories'                => 'nullable|array',
                'categories.*'              => 'exists:categories,id',

                'tags'                      => 'nullable|array',
                'tags.*'                    => 'exists:tags,id',

                'product_type'              => 'required|integer',
                'short_description'         => 'required|string',
                'product_decscription'      => 'required',

                'price'                     => 'required|numeric|min:0',
                'discount'                  => 'nullable|numeric|min:0',

                'sell_price'                => 'nullable|numeric|min:0',
                'sell_price_start_date'     => 'nullable|date',
                'sell_price_end_date'       => 'nullable|date|after_or_equal:sell_price_start_date',

                'weight'                    => 'nullable|numeric|min:0',
                'length'                    => 'nullable|numeric|min:0',
                'width'                     => 'nullable|numeric|min:0',
                'height'                    => 'nullable|numeric|min:0',

                'product_image'             => 'nullable|image|mimes:jpg,jpeg,png,webp',
                'gallery_images.*'          => 'nullable|image|mimes:jpg,jpeg,png,webp',
            ]);

            Log::info('=== INITIAL VALIDATION PASSED ===', ['validated' => $validated]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('=== INITIAL VALIDATION FAILED ===', [
                'errors' => $e->errors(),
            ]);
            throw $e;
        }

        $productType = $validated['product_type'] ?? $request->input('product_type');
        Log::info('=== PRODUCT TYPE CHECK ===', ['product_type' => $productType, 'is_simple' => $productType == ProductType::SIMPLE->value]);

        if ($productType == ProductType::SIMPLE->value || $productType == ProductType::SIMPLE) {
            Log::info('=== SIMPLE PRODUCT VALIDATION STARTED ===');
            try {
                $validatedSimple = $request->validate([
                    'price'    => 'required|numeric|min:0',
                    'product_image' => 'required|image|mimes:jpg,jpeg,png,webp',
                ]);
                Log::info('=== SIMPLE PRODUCT VALIDATION PASSED ===');
                $validated = array_merge($validated, $validatedSimple);
            } catch (\Illuminate\Validation\ValidationException $e) {
                Log::error('=== SIMPLE PRODUCT VALIDATION FAILED ===', ['errors' => $e->errors()]);
                throw $e;
            }
        }

        if ($productType == ProductType::VARIANTS->value || $productType == ProductType::VARIANTS) {
            Log::info('=== VARIANTS PRODUCT VALIDATION STARTED ===');
            try {
                $validatedVariants = $request->validate([
                    'product_attributes' => 'required|array|min:1',
                    // 'product_attributes.*' => 'exists:product_attributes,id',
                    'variants' => 'required|array|min:1',
                    'variants.*.values' => 'required|array|min:1',
                    'variants.*.values.*' => 'exists:attribute_values,id',
                    'variants.*.sku' => 'nullable|string|max:255',
                    'variants.*.price' => 'nullable|numeric|min:0',
                    'variants.*.stock' => 'nullable|integer|min:0',
                    'variants.*.sell_price' => 'nullable|numeric|min:0',
                    'variants.*.shipping' => 'nullable|string|max:255',
                    'variants.*.shipping_address' => 'nullable|string|max:255',
                    'variants.*.general_info' => 'nullable|string',
                    'variants.*.weight' => 'nullable|numeric|min:0',
                    'variants.*.length' => 'nullable|numeric|min:0',
                    'variants.*.width' => 'nullable|numeric|min:0',
                    'variants.*.height' => 'nullable|numeric|min:0',
                    'variants.*.image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
                    'variants.*.exchangeable' => 'nullable|boolean',
                    'variants.*.refundable' => 'nullable|boolean',
                    'variants.*.free_shipping' => 'nullable|boolean',
                ], [
                    'variants.*.price.numeric' => 'Price must be a valid number',
                    'variants.*.price.min' => 'Price must be greater than or equal to 0',
                    'variants.*.stock.integer' => 'Stock must be a whole number',
                    'variants.*.stock.min' => 'Stock must be greater than or equal to 0',
                    'variants.*.sell_price.numeric' => 'Sell Price must be a valid number',
                    'variants.*.sell_price.min' => 'Sell Price must be greater than or equal to 0',
                    'variants.*.weight.numeric' => 'Weight must be a valid number',
                    'variants.*.weight.min' => 'Weight must be greater than or equal to 0',
                    'variants.*.length.numeric' => 'Length must be a valid number',
                    'variants.*.length.min' => 'Length must be greater than or equal to 0',
                    'variants.*.width.numeric' => 'Width must be a valid number',
                    'variants.*.width.min' => 'Width must be greater than or equal to 0',
                    'variants.*.height.numeric' => 'Height must be a valid number',
                    'variants.*.height.min' => 'Height must be greater than or equal to 0',
                    'variants.*.image.image' => 'Image must be a valid image file',
                    'variants.*.image.mimes' => 'Image must be a file of type: jpg, jpeg, png, webp',
                ]);

                Log::info('=== VARIANTS PRODUCT VALIDATION PASSED ===');
                $validated = array_merge($validated, $validatedVariants);
            } catch (\Illuminate\Validation\ValidationException $e) {
                Log::error('=== VARIANTS PRODUCT VALIDATION FAILED ===', ['errors' => $e->errors()]);
                throw $e;
            }
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

        $product->product_type         = $validated['product_type'];
        $product->short_description    = $validated['short_description'];
        $product->product_decscription = $request->product_decscription;

        $product->exchangeable         = $request->boolean('exchangeable');
        $product->refundable           = $request->boolean('refundable');
        $product->free_shipping        = $request->boolean('free_shipping');

        $product->brand_id             = $request->brand_id ?? null;

        if ($product->product_type == ProductType::SIMPLE->value || $product->product_type == ProductType::SIMPLE) {
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
        } else {
            $product->stock                = 0;
            $product->price                = $validated['price'];
            $product->discount             = 0;
            $product->sell_price           = null;
            $product->sell_price_start_date = null;
            $product->sell_price_end_date  = null;
            $product->weight               = null;
            $product->length               = null;
            $product->width                = null;
            $product->height               = null;
        }

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

        if ($request->filled('product_attributes')) {
            $product->attributes()->sync($request->product_attributes);
        }

        if ($product->product_type == ProductType::VARIANTS->value || $product->product_type == ProductType::VARIANTS) {
            Log::info('=== HANDLING VARIANTS ===', ['variant_count' => count($request->variants ?? [])]);
            if ($request->filled('product_attributes')) {
                $product->attributes()->sync($request->product_attributes);
                Log::info('=== ATTRIBUTES SYNCED ===');
            }

            $product->variants()->delete();

            if ($request->filled('variants') && is_array($request->variants)) {
                foreach ($request->variants as $idx => $variant) {
                    $values = $variant['values'] ?? [];

                    $pvData = [
                        'product_id' => $product->id,
                        'sku'        => $variant['sku'] ?? null,
                        'price'      => $variant['price'] ?? null,
                        'stock'      => $variant['stock'] ?? 0,
                        'sell_price' => $variant['sell_price'] ?? null,
                        'shipping'   => $variant['shipping'] ?? null,
                        'weight'     => $variant['weight'] ?? null,
                        'length'     => $variant['length'] ?? null,
                        'width'      => $variant['width'] ?? null,
                        'height'     => $variant['height'] ?? null,
                        'status'     => $variant['status'] ?? $product->status,
                        'visibility' => $variant['visibility'] ?? $product->visibility,
                        'exchangeable' => $variant['exchangeable'] ?? $product->exchangeable,
                        'refundable' => $variant['refundable'] ?? $product->refundable,
                        'free_shipping' => $variant['free_shipping'] ?? $product->free_shipping,
                        'shipping_address' => $variant['shipping_address'] ?? null,
                        'general_info' => $variant['general_info'] ?? null,
                    ];

                    // handle variant image file input
                    if ($request->hasFile("variants.$idx.image")) {
                        $file = $request->file("variants.$idx.image");
                        if ($file && $file->isValid()) {
                            $pvData['image'] = $file->store('products/variants', 'public');
                        }
                    }

                    try {
                        $pv = ProductVariant::create($pvData);
                        Log::info("=== VARIANT $idx CREATED ===", ['variant_id' => $pv->id]);
                    } catch (\Exception $e) {
                        Log::error("=== VARIANT $idx CREATION FAILED ===", [
                            'error' => $e->getMessage(),
                            'data' => $pvData,
                        ]);
                        throw $e;
                    }

                    if (!empty($values)) {
                        $pv->attributeValues()->sync($values);
                    }
                }
            }
            Log::info('=== ALL VARIANTS CREATED SUCCESSFULLY ===');
        } else {
            $product->variants()->delete();
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
        $product->load('categories');
        return view('admin.products.show', compact('product'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {

        $attributes = ProductAttribute::with('values')->get();

        $attributesJson = $attributes->map(function ($a) {
            return [
                'id' => $a->id,
                'name' => $a->name,
                'values' => $a->values->map(function ($v) {
                    return ['id' => $v->id, 'value' => $v->value];
                }),
            ];
        })->toJson();

        $product->load(['variants' => function ($query) {
            $query->with('attributeValues');
        }]);

        $variantsData = $product->variants->map(function ($variant) {
            return [
                'id' => $variant->id,
                'name' => $variant->attributeValues->pluck('value')->join(' / ') ?: 'Variant #' . $variant->id,
                'values' => $variant->attributeValues->pluck('id')->toArray(),
                'sku' => $variant->sku,
                'price' => $variant->price,
                'stock' => $variant->stock,
                'sell_price' => $variant->sell_price,
                'shipping' => $variant->shipping,
                'shipping_address' => $variant->shipping_address,
                'general_info' => $variant->general_info,
                'weight' => $variant->weight,
                'length' => $variant->length,
                'width' => $variant->width,
                'height' => $variant->height,
                'exchangeable' => $variant->exchangeable,
                'refundable' => $variant->refundable,
                'free_shipping' => $variant->free_shipping,
                'image' => $variant->image,
            ];
        });

        $variantsJson = json_encode($variantsData);

        return view('admin.products.edit', [
            'product'            => $product,
            'categories'         => Category::all(),
            'productTypes'       => ProductType::cases(),
            'productStatuses'    => ProductStatus::cases(),
            'productVisibilities' => ProductVisibility::cases(),
            'allTags'            => Tag::orderBy('name')->get(),
            'allbrands'          => Brand::orderBy('name')->get(),
            'attributes'         => $attributes,
            'attributesJson'     => $attributesJson,
            'variantsJson'       => $variantsJson,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'meta_title'        => 'nullable|string|max:160',
            'meta_description'  => 'nullable|string|max:160',
            'meta_keywords'     => 'nullable|string',
            'categories'        => 'nullable|array',
            'product_type'      => 'required',
            'short_description' => 'required|string',
            'price'             => 'nullable|numeric',
            'stock'             => 'nullable|integer',
            'product_image'     => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'gallery_images.*'  => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        $prodType = $validated['product_type'];

        if ($prodType == ProductType::SIMPLE->value) {
            $request->validate([
                'price' => 'required|numeric',
                'stock' => 'required|integer',
            ]);
        }

        /* ===============================
        MAIN IMAGE (Spatie)
        =============================== */
        if ($request->hasFile('product_image')) {
            $product
                ->clearMediaCollection('product_image')
                ->addMedia($request->file('product_image'))
                ->toMediaCollection('product_image');
        }

        /* ===============================
        GALLERY IMAGES (Spatie)
        (append only, not delete)
        =============================== */
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $product
                    ->addMedia($image)
                    ->toMediaCollection('gallery');
            }
        }

        /* ===============================
        PRODUCT DATA
        =============================== */
        $product->update([
            'product_title'        => $validated['title'],
            'slug'                 => Str::slug($validated['title']),
            'meta_title'           => $validated['meta_title'] ?? null,
            'meta_description'     => $validated['meta_description'] ?? null,
            'meta_keywords'        => $validated['meta_keywords'] ?? null,
            'product_type'         => $validated['product_type'],
            'short_description'    => $validated['short_description'],
            'product_decscription' => $request->product_decscription ?? $product->product_decscription,
            'exchangeable'         => $request->boolean('exchangeable'),
            'refundable'           => $request->boolean('refundable'),
            'free_shipping'        => $request->boolean('free_shipping'),
            'brand_id'             => $request->brand_id,
            'status'               => $request->status ?? $product->status,
            'visibility'           => $request->visibility ?? $product->visibility,
            'sell_price'           => $request->sell_price,
            'sell_price_start_date'=> $request->sell_price_start_date,
            'sell_price_end_date'  => $request->sell_price_end_date,
            'weight'               => $request->weight,
            'length'               => $request->length,
            'width'                => $request->width,
            'height'               => $request->height,
        ]);

        if ($product->product_type == ProductType::SIMPLE->value) {
            $product->update([
                'stock'    => $validated['stock'],
                'price'    => $validated['price'],
                'discount' => $request->discount ?? $product->discount,
            ]);
        } else {
            $product->update([
                'stock'    => 0,
                'price'    => 0,
                'discount' => 0,
            ]);
        }

        /* ===============================
        RELATIONS
        =============================== */
        $product->categories()->sync($validated['categories'] ?? []);
        $product->tags()->sync($request->tags ?? []);

        /* ===============================
        VARIANTS
        =============================== */
        if ($product->product_type == ProductType::VARIANTS->value) {
            if ($request->filled('product_attributes')) {
                $product->attributes()->sync($request->product_attributes);
            }

            $product->variants()->delete();

            foreach ($request->variants ?? [] as $idx => $variant) {

                $pv = ProductVariant::create([
                    'product_id'     => $product->id,
                    'sku'            => $variant['sku'] ?? null,
                    'price'          => $variant['price'] ?? null,
                    'stock'          => $variant['stock'] ?? 0,
                    'sell_price'     => $variant['sell_price'] ?? null,
                    'shipping'       => $variant['shipping'] ?? null,
                    'weight'         => $variant['weight'] ?? null,
                    'length'         => $variant['length'] ?? null,
                    'width'          => $variant['width'] ?? null,
                    'height'         => $variant['height'] ?? null,
                    'status'         => $variant['status'] ?? $product->status,
                    'visibility'     => $variant['visibility'] ?? $product->visibility,
                    'exchangeable'   => $variant['exchangeable'] ?? 0,
                    'refundable'     => $variant['refundable'] ?? 0,
                    'free_shipping'  => $variant['free_shipping'] ?? 0,
                ]);

                if (!empty($variant['values'])) {
                    $pv->attributeValues()->sync($variant['values']);
                }

                if ($request->hasFile("variants.$idx.image")) {
                    $pv
                        ->addMedia($request->file("variants.$idx.image"))
                        ->toMediaCollection('variant_image');
                }
            }
        } else {
            $product->variants()->delete();
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */

    public function updateVariants(Request $request, Product $product)
    {
        $variants = $request->variants ?? [];

        foreach ($variants as $variantData) {

            $variant = $product->variants()
                ->updateOrCreate(
                    ['id' => $variantData['id'] ?? null],
                    [
                        'sku'           => $variantData['sku'] ?? null,
                        'price'         => $variantData['price'] ?? 0,
                        'stock'         => $variantData['stock'] ?? 0,
                        'sell_price'    => $variantData['sell_price'] ?? null,
                        'weight'        => $variantData['weight'] ?? null,
                        'length'        => $variantData['length'] ?? null,
                        'width'         => $variantData['width'] ?? null,
                        'height'        => $variantData['height'] ?? null,
                        'exchangeable'  => (int) ($variantData['exchangeable'] ?? 0),
                        'refundable'    => (int) ($variantData['refundable'] ?? 0),
                        'free_shipping' => (int) ($variantData['free_shipping'] ?? 0),
                    ]
                );

            if (!empty($variantData['values'])) {
                $variant->attributeValues()->sync($variantData['values']);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Variants updated successfully'
        ]);
    }


    public function removeVariant(Request $request, Product $product)
    {
        $variantId = $request->variant_id;

        $variant = ProductVariant::where('id', $variantId)
            ->where('product_id', $product->id)
            ->firstOrFail();

        if ($variant->image && Storage::disk('public')->exists($variant->image)) {
            Storage::disk('public')->delete($variant->image);
        }

        $variant->delete();

        return response()->json([
            'success' => true,
            'variant_id' => $variantId
        ]);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }

    public function generateVariants(Request $request)
    {
        $attributes = $request->input('attributes');

        if (empty($attributes)) {
            return response()->json(['html' => '']);
        }

        $lists = [];

        foreach ($attributes as $attr) {
            $lists[] = collect($attr['values'])->map(function ($valId) use ($attr) {
                return [
                    'attribute_id' => $attr['attribute_id'],
                    'value_id'     => $valId,
                    'value_name'   => $attr['values_map'][$valId] ?? ''
                ];
            })->toArray();
        }

        // Cartesian product
        $variants = collect($lists)->reduce(function ($carry, $item) {
            if (empty($carry)) return array_map(fn($i) => [$i], $item);

            $result = [];
            foreach ($carry as $c) {
                foreach ($item as $i) {
                    $result[] = array_merge($c, [$i]);
                }
            }
            return $result;
        }, []);

        return response()->json([
            'html' => view('admin.products.partials.variants-html', compact('variants'))->render()
        ]);
    }


    public function userShow(string $slug)
    {
        $product = Product::where('slug', $slug)
            ->where('status', 1)
            ->where('visibility', 1)
            ->with([
                'categories',
                'tags',
                'variants.attributeValues.attribute'
            ])
            ->firstOrFail();

        return view('user.product.show', compact('product'));
    }
}
