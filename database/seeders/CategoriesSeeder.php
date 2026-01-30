<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Bath' => [
                'Faucets','Showers','Wellness','Sanitaryware','Flushing Systems','Cabinets','Bath Accessories','Decorative Bowls','Water Heating Solutions','Water Treatment'
            ],
            'Kitchen' => [
                'Sinks','Faucets','Appliances','Counter Tops','Kitchen Tiles','Modular Kitchens','Kitchen Hardware','Kitchen Accessories'
            ],
            'Tiles & Flooring' => [
                'Wall Tiles','Floor Tiles','Terrace','Elevation','Exclusive','Parking / Outdoor','Swimming Pool','Beading & Borders','Flooring Solutions','Adhesives, Grouting & Accessories'
            ],
            'Lighting & Fans' => [
                'Fans','Decorative Lighting','Commercial Lights','Outdoor Lighting','Facade Lighting','Smart Lighting','Bulb, Batons & Tubes Lights','Lighting Tools','Electrical Tools','Lighting Accessories','Electrical Accessories'
            ],
            'Electricals' => [
                'Wire & Cables','Switches & Sockets','Pumps & Motors','Circuits Breakers','Home Automation','Distribution Board','Conduit Boxes & Fitting','Water Heating Solutions','Water Treatment','Tools & Accessories'
            ],
            'Plumbing' => [
                'Pipes','Fittings','Water Tanks','Pumps & Motors','Plumbing Tools','Plumbing Acessories'
            ],
            'Paints & Adhesives' => [
                'Adhesives','Chemicals','Decorative Paints','Interior Paints','Exterior Paints','Putty & Primer','Metal Paints','Painting Tools','Painting Accessories','Water Proofing Solutions'
            ],
            'Steel' => ['Sheet','Tube']
        ];

        foreach ($categories as $parentName => $subs) {
            $parent = Category::firstOrCreate(
                ['name' => $parentName, 'parent_id' => null],
                ['slug' => Str::slug($parentName), 'is_visible' => 1]
            );

            foreach ($subs as $sub) {
                Category::firstOrCreate(
                    ['name' => $sub, 'parent_id' => $parent->id],
                    ['slug' => Str::slug($sub), 'is_visible' => 1]
                );
            }
        }
    }
}
