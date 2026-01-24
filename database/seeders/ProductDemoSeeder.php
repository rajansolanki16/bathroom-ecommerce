<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Tag;
use App\Models\ProductAttribute;
use App\Models\AttributeValue;

class ProductDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Categories
        $categories = ['Clothing', 'Electronics', 'Home'];
        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name], ['slug' => \Str::slug($name)]);
        }

        // Tags
        $tags = ['New', 'Featured', 'Sale'];
        foreach ($tags as $name) {
            Tag::firstOrCreate(['name' => $name], ['slug' => \Str::slug($name)]);
        }

        // Attributes and values (Color, Size)
        $color = ProductAttribute::firstOrCreate(['name' => 'Color']);
        $sizes = ProductAttribute::firstOrCreate(['name' => 'Size']);

        $colors = ['Red','Blue','Green'];
        foreach ($colors as $c) {
            AttributeValue::firstOrCreate(['product_attribute_id' => $color->id, 'value' => $c], ['slug' => \Str::slug($c)]);
        }

        $sizeValues = ['S','M','L'];
        foreach ($sizeValues as $s) {
            AttributeValue::firstOrCreate(['product_attribute_id' => $sizes->id, 'value' => $s], ['slug' => \Str::slug($s)]);
        }
    }
}
