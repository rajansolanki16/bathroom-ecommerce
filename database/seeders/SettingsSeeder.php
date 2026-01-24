<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['page' => 'general', "type" => "img", 'name' => 'Site icon', 'slug' => 'site_icon', 'value' => 'https://cdn-icons-png.freepik.com/512/6660/6660308.png'],
            ['page' => 'general', "type" => "img", 'name' => 'Site light logo', 'slug' => 'site_logo_light', 'value' => 'https://cdn-icons-png.freepik.com/512/6660/6660308.png'],
            ['page' => 'general', "type" => "img", 'name' => 'Site dark logo', 'slug' => 'site_logo_dark', 'value' => 'https://cdn-icons-png.freepik.com/512/6660/6660308.png'],
            ['page' => 'general', "type" => "text", 'name' => 'Site logo text', 'slug' => 'logo_text', 'value' => 'null'],
            ['page' => 'general', "type" => "text", 'name' => 'Site title', 'slug' => 'site_title', 'value' => 'null'],
            ['page' => 'general', "type" => "social", 'name' => 'Site Social link', 'slug' => 'site_social_links', 'value' => '[]'],
            ['page' => 'general', "type" => "text", 'name' => 'Site copyright text', 'slug' => 'site_copyright_text', 'value' => 'null'],
            ['page' => 'general', "type" => "text", 'name' => 'Hotel address', 'slug' => 'admin_address', 'value' => 'null'],
            ['page' => 'general', "type" => "text", 'name' => 'Hotel phone', 'slug' => 'admin_phone', 'value' => 'null'],
            ['page' => 'general', "type" => "text", 'name' => 'Hotel email', 'slug' => 'admin_email', 'value' => 'null'],
            ['page' => 'general', "type" => "map_link", 'name' => 'Hotel Location Link', 'slug' => 'map_link', 'value' => 'null'],
            ['page' => 'general', "type" => "textarea", 'name' => 'Hotel surroundings', 'slug' => 'hotel_surroundings', 'value' => 'null'],
            ['page' => 'general', "type" => "textarea", 'name' => 'Hotel Rules', 'slug' => 'hotel_rules', 'value' => 'null'],
            ['page' => 'about',   "type" => "text", 'name' => 'Welcome title', 'slug' => 'about_welcome_title', 'value' => 'null'],
            ['page' => 'about',   "type" => "textarea", 'name' => 'Welcome description', 'slug' => 'about_welcome_description', 'value' => 'null'],
            ['page' => 'about',   "type" => "img", 'name' => 'Welcome Image 1', 'slug' => 'about_welcome_img_1', 'value' => 'null'],
            ['page' => 'about',   "type" => "img", 'name' => 'Welcome Image 2', 'slug' => 'about_welcome_img_2', 'value' => 'null'],
            ['page' => 'about',   "type" => "text", 'name' => 'Welcome Counter 1 count', 'slug' => 'about_welcome_counter_count_1', 'value' => 'null'],
            ['page' => 'about',   "type" => "text", 'name' => 'Welcome Counter 1 text', 'slug' => 'about_welcome_counter_text_1', 'value' => 'null'],
            ['page' => 'about',   "type" => "text", 'name' => 'Welcome Counter 2 count', 'slug' => 'about_welcome_counter_count_2', 'value' => 'null'],
            ['page' => 'about',   "type" => "text", 'name' => 'Welcome Counter 2 text', 'slug' => 'about_welcome_counter_text_2', 'value' => 'null'],
            ['page' => 'about',   "type" => "json", 'name' => 'Amenities', 'slug' => 'about_amenities', 'value' => '[]'],
            ['page' => 'about',   "type" => "img", 'name' => 'Amenity image 1', 'slug' => 'about_amenity_img_1', 'value' => 'null'],
            ['page' => 'about',   "type" => "img", 'name' => 'Amenity image 2', 'slug' => 'about_amenity_img_2', 'value' => 'null'],
            ['page' => 'home',   "type" => "img", 'name' => 'Background Image', 'slug' => 'home_bg_img', 'value' => 'null'],
            ['page' => 'home',   "type" => "img", 'name' => 'Top Section Background Image', 'slug' => 'home_top_section', 'value' => 'null'],
            ['page' => 'home',   "type" => "text", 'name' => 'Top Section Heading', 'slug' => 'home_top_heading', 'value' => 'null'],
            ['page' => 'home',   "type" => "textarea", 'name' => 'Top Section Description', 'slug' => 'home_top_description', 'value' => 'null'],
            ['page' => 'home',   "type" => "text", 'name' => 'Top Section Button Link', 'slug' => 'home_top_btn_link', 'value' => 'null'],
            ['page' => 'home',   "type" => "text", 'name' => 'Top Section Button Text', 'slug' => 'home_top_btn_text', 'value' => 'null'],
            ['page' => 'home',   "type" => "text", 'name' => 'Top Section Counter 1 Count', 'slug' => 'home_top_counter_1_count', 'value' => 'null'],
            ['page' => 'home',   "type" => "text", 'name' => 'Top Section Counter 1 Text', 'slug' => 'home_top_counter_1_text', 'value' => 'null'],
            ['page' => 'home',   "type" => "text", 'name' => 'Top Section Counter 2 Count', 'slug' => 'home_top_counter_2_count', 'value' => 'null'],
            ['page' => 'home',   "type" => "text", 'name' => 'Top Section Counter 2 Text', 'slug' => 'home_top_counter_2_text', 'value' => 'null'],
            ['page' => 'home',   "type" => "text", 'name' => 'Top Section Counter 3 Count', 'slug' => 'home_top_counter_3_count', 'value' => 'null'],
            ['page' => 'home',   "type" => "text", 'name' => 'Top Section Counter 3 Text', 'slug' => 'home_top_counter_3_text', 'value' => 'null'],
            ['page' => 'home',   "type" => "img", 'name' => 'Middle Section Image 1', 'slug' => 'home_middle_img_1', 'value' => 'null'],
            ['page' => 'home',   "type" => "img", 'name' => 'Middle Section Image 2', 'slug' => 'home_middle_img_2', 'value' => 'null'],
            ['page' => 'home',   "type" => "text", 'name' => 'Middle Section Heading', 'slug' => 'home_middle_heading', 'value' => 'null'],
            ['page' => 'home',   "type" => "textarea", 'name' => 'Middle Section Description', 'slug' => 'home_middle_description', 'value' => 'null'],
            ['page' => 'home',   "type" => "json", 'name' => 'Reviews Area', 'slug' => 'home_review_area', 'value' => '[]'],


            ['page' => 'user-all',   "type" => "text", 'name' => 'Home Meta Title', 'slug' => 'page_home_meta_title', 'value' => 'null'],
            ['page' => 'user-all',   "type" => "text", 'name' => 'Home Meta Description', 'slug' => 'page_home_meta_description', 'value' => 'null'],

            ['page' => 'user-all',   "type" => "text", 'name' => 'Single Blog Meta Title', 'slug' => 'page_blog_meta_title', 'value' => 'null'],
            ['page' => 'user-all',   "type" => "text", 'name' => 'Single Blog Meta Description', 'slug' => 'page_blog_meta_description', 'value' => 'null'],

            ['page' => 'user-all',   "type" => "text", 'name' => 'Single Room meta Description', 'slug' => 'page_room_meta_description', 'value' => 'null'],
            ['page' => 'user-all',   "type" => "textarea", 'name' => 'Single Room Page Description', 'slug' => 'page_room_description', 'value' => 'null'],
            
            ['page' => 'user-all',   "type" => "text", 'name' => 'Rooms Meta Title', 'slug' => 'page_rooms_meta_title', 'value' => 'null'],
            ['page' => 'user-all',   "type" => "text", 'name' => 'Rooms Meta Description', 'slug' => 'page_rooms_meta_description', 'value' => 'null'],
            ['page' => 'user-all',   "type" => "textarea", 'name' => 'Rooms Page Description', 'slug' => 'page_rooms_description', 'value' => 'null'],

            ['page' => 'user-all',   "type" => "text", 'name' => 'Cart Meta Title', 'slug' => 'page_cart_meta_title', 'value' => 'null'],
            ['page' => 'user-all',   "type" => "text", 'name' => 'Cart Meta Description', 'slug' => 'page_cart_meta_description', 'value' => 'null'],
            ['page' => 'user-all',   "type" => "textarea", 'name' => 'Cart Page Description', 'slug' => 'page_cart_description', 'value' => 'null'],

            ['page' => 'user-all',   "type" => "text", 'name' => 'Checkout Meta Title', 'slug' => 'page_checkout_meta_title', 'value' => 'null'],
            ['page' => 'user-all',   "type" => "text", 'name' => 'Checkout Meta Description', 'slug' => 'page_checkout_meta_description', 'value' => 'null'],
            ['page' => 'user-all',   "type" => "textarea", 'name' => 'Checkout Page Description', 'slug' => 'page_checkout_description', 'value' => 'null'],

            ['page' => 'user-all',   "type" => "text", 'name' => 'Blogs Meta Title', 'slug' => 'page_blogs_meta_title', 'value' => 'null'],
            ['page' => 'user-all',   "type" => "text", 'name' => 'Blogs Meta Description', 'slug' => 'page_blogs_meta_description', 'value' => 'null'],
            ['page' => 'user-all',   "type" => "textarea", 'name' => 'Blogs Page Description', 'slug' => 'page_blogs_description', 'value' => 'null'],

            ['page' => 'user-all',   "type" => "text", 'name' => 'About-us Meta Title', 'slug' => 'page_about_meta_title', 'value' => 'null'],
            ['page' => 'user-all',   "type" => "text", 'name' => 'About-us Meta Description', 'slug' => 'page_about_meta_description', 'value' => 'null'],
            ['page' => 'user-all',   "type" => "textarea", 'name' => 'About-us Page Description', 'slug' => 'page_about_description', 'value' => 'null'],

            ['page' => 'user-all',   "type" => "text", 'name' => 'Contact-us Meta Title', 'slug' => 'page_contact_meta_title', 'value' => 'null'],
            ['page' => 'user-all',   "type" => "text", 'name' => 'Contact-us Meta Description', 'slug' => 'page_contact_meta_description', 'value' => 'null'],
            ['page' => 'user-all',   "type" => "textarea", 'name' => 'Contact-us Page Description', 'slug' => 'page_contact_description', 'value' => 'null'],

            ['page' => 'user-all',   "type" => "text", 'name' => 'FAQs Meta Title', 'slug' => 'page_faq_contact_meta_title', 'value' => 'null'],
            ['page' => 'user-all',   "type" => "text", 'name' => 'FAQs Meta Description', 'slug' => 'page_faq_contact_meta_description', 'value' => 'null'],
            ['page' => 'user-all',   "type" => "textarea", 'name' => 'FAQs Page Description', 'slug' => 'page_faq_contact_description', 'value' => 'null'],

            ['page' => 'user-all',   "type" => "text", 'name' => 'My-Account Meta Title', 'slug' => 'page_account_meta_title', 'value' => 'null'],
            ['page' => 'user-all',   "type" => "text", 'name' => 'My-Account Meta Description', 'slug' => 'page_account_meta_description', 'value' => 'null'],
            ['page' => 'user-all',   "type" => "textarea", 'name' => 'My-Account Page Description', 'slug' => 'page_account_description', 'value' => 'null'],

            ['page' => 'user-all',   "type" => "code", 'name' => 'Header Custom Scripts', 'slug' => 'page_custom_script_header', 'value' => 'null'],
            ['page' => 'user-all',   "type" => "code", 'name' => 'Body Custom Scripts', 'slug' => 'page_custom_script_body', 'value' => 'null'],
            ['page' => 'user-all',   "type" => "code", 'name' => 'Footer Custom Scripts', 'slug' => 'page_custom_scrip_footer', 'value' => 'null'],



            // ['page' => '',   "type" => "", 'name' => '', 'slug' => '', 'value' => 'null'],
        ];

        foreach ($data as $item) {
            if (!DB::table('settings')->where('slug', $item['slug'])->exists()) {
                DB::table('settings')->insert($item);
            }
            if (DB::table('settings')->where('slug', $item['slug'])->first()->name != $item['name']) {
                DB::table('settings')->where('slug', $item['slug'])->update(['name' => $item['name']]);
            }
            if (DB::table('settings')->where('slug', $item['slug'])->first()->page != $item['page']) {
                DB::table('settings')->where('slug', $item['slug'])->update(['page' => $item['page']]);
            }
            if (DB::table('settings')->where('slug', $item['slug'])->first()->type != $item['type']) {
                DB::table('settings')->where('slug', $item['slug'])->update(['type' => $item['type']]);
            }
        }

        if (DB::table('settings')->where('slug', 'site_logo')->exists()) {
            DB::table('settings')->where('slug', 'site_logo')->delete();
        }
    }
}
