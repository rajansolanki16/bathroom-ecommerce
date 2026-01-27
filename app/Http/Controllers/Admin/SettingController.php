<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce;
use Illuminate\Http\Request;
use App\Models\Amenity;
use App\Models\Countries;
use App\Models\Country;
use App\Models\Setting;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function show_general()
    {
        $settings = Setting::where('page', '=', 'general')->get();
        return view('admin.settings.general')->with(["settings" => $settings]);
    }

    public function show_about_us(Request $request)
    {
        $settings = Setting::where('page', '=', 'about')->get();
        $amenities = Amenity::all();
        return view('admin.settings.about')->with(["settings" => $settings, 'amenities' => $amenities]);
    }

    public function show_home(Request $request)
    {
        $settings = Setting::where('page', '=', 'home')->get();
        return view('admin.settings.home')->with(["settings" => $settings]);
    }

    public function show_pages(Request $request)
    {
        $settings = Setting::where('page', '=', 'user-all')->get();
        return view('admin.settings.pages')->with(["settings" => $settings]);
    }

    public function show_env()
    {
        $envPath = base_path('.env');
        $envContent = File::exists($envPath) ? File::get($envPath) : '';

        $envSettings = [];
        foreach (explode("\n", $envContent) as $line) {
            if (!empty($line) && strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $envSettings[$key] = trim($value, '"');
            }
        }

        return view('admin.settings.env', compact('envSettings'));
    }

    public function save_env(Request $request)
    {

        if ($request->save_env != '1') {
            return redirect()->route('view.settings.env');
        }


        $envPath = base_path('.env');
        $backupFolder = base_path('ENV backups');

        if (!File::exists($backupFolder)) {
            File::makeDirectory($backupFolder, 0755, true);
        }

        $latestBackup = collect(File::files($backupFolder))
            ->filter(fn($file) => str_contains($file->getFilename(), '.env('))
            ->sortByDesc(fn($file) => $file->getMTime()) // Sort by modified time
            ->first();

        $shouldBackup = true;
        if ($latestBackup) {
            $lastModifiedTime = Carbon::createFromTimestamp($latestBackup->getMTime());
            if (Carbon::now()->diffInSeconds($lastModifiedTime) <= 5) {
                $shouldBackup = false;
            }
        }

        if ($shouldBackup && File::exists($envPath)) {
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $backupPath = $backupFolder . "/.env($timestamp)";
            File::copy($envPath, $backupPath);
        }

        $envContent = '';
        foreach ($request->env as $key => $value) {
            $envContent .= $key . '="' . trim($value) . "\"\n";
        }

        File::put($envPath, $envContent);

        return redirect()->route('view.settings.env');
    }


    public function save_general(Request $request)
    {

        $filePath = 'images/settings/';

        if (isset($request->logo_text) && strlen($request->logo_text) > 0) {
            Setting::where('slug', '=', 'logo_text')->update(['value' => $request->logo_text]);
        }
        if (isset($request->site_title) && strlen($request->site_title) > 0) {
            Setting::where('slug', '=', 'site_title')->update(['value' => $request->site_title]);
        }
        if (isset($request->admin_address) && strlen($request->admin_address) > 0) {
            Setting::where('slug', '=', 'admin_address')->update(['value' => $request->admin_address]);
        }
        if (isset($request->admin_phone) && strlen($request->admin_phone) > 0) {
            Setting::where('slug', '=', 'admin_phone')->update(['value' => $request->admin_phone]);
        }
        if (isset($request->admin_email) && strlen($request->admin_email) > 0) {
            Setting::where('slug', '=', 'admin_email')->update(['value' => $request->admin_email]);
        }
        if (isset($request->otp_expiry_time) && strlen($request->otp_expiry_time) > 0) {
            Setting::where('slug', '=', 'otp_expiry_time')->update(['value' => $request->otp_expiry_time]);
        }
        if (isset($request->otp_vendor_expiry_time) && strlen($request->otp_vendor_expiry_time) > 0) {
            Setting::where('slug', '=', 'otp_vendor_expiry_time')->update(['value' => $request->otp_vendor_expiry_time]);
        }
        if (isset($request->otp_max_attempts) && strlen($request->otp_max_attempts) > 0) {
            Setting::where('slug', '=', 'otp_max_attempts')->update(['value' => $request->otp_max_attempts]);
        }
        if (isset($request->site_copyright_text) && strlen($request->site_copyright_text) > 0) {
            Setting::where('slug', '=', 'site_copyright_text')->update(['value' => $request->site_copyright_text]);
        }
        if (isset($request->map_link) && strlen($request->map_link) > 0) {
            Setting::where('slug', '=', 'map_link')->update(['value' => $request->map_link]);
        }
        if (isset($request->hotel_surroundings) && strlen($request->hotel_surroundings) > 0) {
            Setting::where('slug', '=', 'hotel_surroundings')->update(['value' => $request->hotel_surroundings]);
        }
        if (isset($request->hotel_rules) && strlen($request->hotel_rules) > 0) {
            Setting::where('slug', '=', 'hotel_rules')->update(['value' => $request->hotel_rules]);
        }

        if (isset($request->site_icon) && $request->hasFile('site_icon')) {
            $file = $request->file('site_icon');
            $fileName = $file->getClientOriginalName();
            $directoryPath = public_path($filePath);
            if (!file_exists($directoryPath)) {
                mkdir($directoryPath, 0777, true);
            }
            $file->move($directoryPath, $fileName);
            $fileUrl = $filePath . $fileName;
            Setting::where('slug', '=', 'site_icon')->update(['value' => $fileUrl]);
        }
        if (isset($request->site_logo_light) && $request->hasFile('site_logo_light')) {
            $file = $request->file('site_logo_light');
            $fileName = $file->getClientOriginalName();
            $directoryPath = public_path($filePath);
            if (!file_exists($directoryPath)) {
                mkdir($directoryPath, 0777, true);
            }
            $file->move($directoryPath, $fileName);
            $fileUrl = $filePath . $fileName;
            Setting::where('slug', '=', 'site_logo_light')->update(['value' => $fileUrl]);
        }
        if (isset($request->site_logo_dark) && $request->hasFile('site_logo_dark')) {
            $file = $request->file('site_logo_dark');
            $fileName = $file->getClientOriginalName();
            $directoryPath = public_path($filePath);
            if (!file_exists($directoryPath)) {
                mkdir($directoryPath, 0777, true);
            }
            $file->move($directoryPath, $fileName);
            $fileUrl = $filePath . $fileName;
            Setting::where('slug', '=', 'site_logo_dark')->update(['value' => $fileUrl]);
        }

        if (isset($request->settings_social_link_edited) && $request->settings_social_link_edited == "true") {

            $existingSetting = Setting::where('slug', '=', 'site_social_links')->first();
            $existingLinks = $existingSetting ? json_decode($existingSetting->value, true) : [];

            $existingLinksAssoc = [];
            foreach ($existingLinks as $link) {
                $existingLinksAssoc[$link['id']] = $link;
            }

            $updatedLinks = [];

            foreach ($request->all() as $key => $value) {
                if (preg_match('/^url_(\d+)$/', $key, $matches)) {
                    $counter = $matches[1];
                    $idKey = 'social_link_id_' . $counter;
                    $iconKey = 'icon_' . $counter;

                    $isNew = !isset($request->$idKey);
                    $id = $isNew ? rand(1000000000, 9999999999) : $request->$idKey;

                    if ($isNew || $request->hasFile($iconKey)) {
                        $iconFile = $request->file($iconKey);
                        $fileName = time() . '_' . $iconFile->getClientOriginalName();
                        $iconFile->move(public_path($filePath), $fileName);
                        $iconPath = $filePath . $fileName;
                    } else {
                        $iconPath = $existingLinksAssoc[$id]['icon'] ?? '';
                    }

                    $updatedLinks[] = [
                        'id' => $id,
                        'icon' => $iconPath,
                        'link' => $value
                    ];

                    unset($existingLinksAssoc[$id]);
                }
            }

            foreach ($existingLinksAssoc as $deletedLink) {
                if (file_exists(public_path($deletedLink['icon']))) {
                    unlink(public_path($deletedLink['icon']));
                }
            }

            Setting::where('slug', '=', 'site_social_links')->update([
                'value' => json_encode($updatedLinks, JSON_UNESCAPED_SLASHES)
            ]);
        }

        return redirect()->route('view.settings.general');
    }

    public function save_about_us(Request $request)
    {
        $filePath = 'images/settings/';
        $directoryPath = public_path($filePath);

        if (isset($request->about_welcome_title) && strlen($request->about_welcome_title) > 0) {
            Setting::where('slug', '=', 'about_welcome_title')->update(['value' => $request->about_welcome_title]);
        }
        if (isset($request->about_welcome_description) && strlen($request->about_welcome_description) > 0) {
            Setting::where('slug', '=', 'about_welcome_description')->update(['value' => $request->about_welcome_description]);
        }
        if (isset($request->about_welcome_counter_text_1) && strlen($request->about_welcome_counter_text_1) > 0) {
            Setting::where('slug', '=', 'about_welcome_counter_text_1')->update(['value' => $request->about_welcome_counter_text_1]);
        }
        if (isset($request->about_welcome_counter_text_2) && strlen($request->about_welcome_counter_text_2) > 0) {
            Setting::where('slug', '=', 'about_welcome_counter_text_2')->update(['value' => $request->about_welcome_counter_text_2]);
        }
        if (isset($request->about_welcome_counter_count_1) && strlen($request->about_welcome_counter_count_1) > 0) {
            Setting::where('slug', '=', 'about_welcome_counter_count_1')->update(['value' => $request->about_welcome_counter_count_1]);
        }
        if (isset($request->about_welcome_counter_count_2) && strlen($request->about_welcome_counter_count_2) > 0) {
            Setting::where('slug', '=', 'about_welcome_counter_count_2')->update(['value' => $request->about_welcome_counter_count_2]);
        }
        if (isset($request->about_amenities)) {
            Setting::where('slug', '=', 'about_amenities')->update(['value' => json_encode($request->about_amenities)]);
        } else {
            Setting::where('slug', '=', 'about_amenities')->update(['value' => json_encode([])]);
        }

        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0777, true);
        }

        foreach ($request->all() as $slug => $value) {
            if ($value instanceof \Illuminate\Http\UploadedFile) {
                $fileName = time() . '_' . $value->getClientOriginalName();
                $value->move($directoryPath, $fileName);
                $fileUrl = $filePath . $fileName;
                Setting::where('slug', $slug)->update(['value' => $fileUrl]);
            }
        }

        return redirect()->route('view.settings.about');
    }

    public function save_home(Request $request)
    {

        $filePath = 'images/settings/';
        $directoryPath = public_path($filePath);

        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0777, true);
        }

        foreach ($request->all() as $slug => $value) {
            if ($value instanceof \Illuminate\Http\UploadedFile) {
                $fileName = time() . '_' . $value->getClientOriginalName();
                $value->move($directoryPath, $fileName);
                $fileUrl = $filePath . $fileName;
                Setting::where('slug', $slug)->update(['value' => $fileUrl]);
            }
        }

        if (isset($request->home_top_heading) && strlen($request->home_top_heading) > 0) {
            Setting::where('slug', '=', 'home_top_heading')->update(['value' => $request->home_top_heading]);
        }
        if (isset($request->home_top_description) && strlen($request->home_top_description) > 0) {
            Setting::where('slug', '=', 'home_top_description')->update(['value' => $request->home_top_description]);
        }
        if (isset($request->home_top_btn_link) && strlen($request->home_top_btn_link) > 0) {
            Setting::where('slug', '=', 'home_top_btn_link')->update(['value' => $request->home_top_btn_link]);
        }
        if (isset($request->home_top_btn_text) && strlen($request->home_top_btn_text) > 0) {
            Setting::where('slug', '=', 'home_top_btn_text')->update(['value' => $request->home_top_btn_text]);
        }
        if (isset($request->home_top_counter_1_count) && strlen($request->home_top_counter_1_count) > 0) {
            Setting::where('slug', '=', 'home_top_counter_1_count')->update(['value' => $request->home_top_counter_1_count]);
        }
        if (isset($request->home_top_counter_1_text) && strlen($request->home_top_counter_1_text) > 0) {
            Setting::where('slug', '=', 'home_top_counter_1_text')->update(['value' => $request->home_top_counter_1_text]);
        }
        if (isset($request->home_top_counter_2_count) && strlen($request->home_top_counter_2_count) > 0) {
            Setting::where('slug', '=', 'home_top_counter_2_count')->update(['value' => $request->home_top_counter_2_count]);
        }
        if (isset($request->home_top_counter_2_text) && strlen($request->home_top_counter_2_text) > 0) {
            Setting::where('slug', '=', 'home_top_counter_2_text')->update(['value' => $request->home_top_counter_2_text]);
        }
        if (isset($request->home_top_counter_3_count) && strlen($request->home_top_counter_3_count) > 0) {
            Setting::where('slug', '=', 'home_top_counter_3_count')->update(['value' => $request->home_top_counter_3_count]);
        }
        if (isset($request->home_top_counter_3_text) && strlen($request->home_top_counter_3_text) > 0) {
            Setting::where('slug', '=', 'home_top_counter_3_text')->update(['value' => $request->home_top_counter_3_text]);
        }
        if (isset($request->home_middle_heading) && strlen($request->home_middle_heading) > 0) {
            Setting::where('slug', '=', 'home_middle_heading')->update(['value' => $request->home_middle_heading]);
        }
        if (isset($request->home_middle_description) && strlen($request->home_middle_description) > 0) {
            Setting::where('slug', '=', 'home_middle_description')->update(['value' => $request->home_middle_description]);
        }

        if (isset($request->home_review_area_edited) && $request->home_review_area_edited == "true") {
            $home_reviews = [];

            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'name_') === 0) {
                    $index = substr($key, 5);

                    if (isset($request->{'rate_' . $index}) && isset($request->{'review_' . $index})) {
                        $home_reviews[] = [
                            'name' => $value,
                            'rate' => $request->{'rate_' . $index},
                            'review' => $request->{'review_' . $index},
                        ];
                    }
                }
            }

            $home_reviewJson = json_encode($home_reviews, JSON_UNESCAPED_SLASHES);
            Setting::where('slug', '=', 'home_review_area')->update(['value' => $home_reviewJson]);
        }

        return redirect()->route('view.settings.home');
    }

    public function save_pages(Request $request)
    {

        foreach ($request->all() as $key => $value) {
            if ($key == 'page_custom_script_header' || $key == 'page_custom_script_body' || $key == 'page_custom_scrip_footer') {
                $decodedValues = [];
                $charArray = explode("-", $value);
                $decodedText = implode("", array_map("chr", $charArray));
                Setting::where('slug', $key)->update(['value' => $decodedText]);
            } else {
                Setting::where('slug', $key)->exists()
                    ? Setting::where('slug', $key)->update(['value' => $value])
                    : null;
            }
        }

        return redirect()->route('view.settings.pages');
    }

    public function show_ecommerce()
    {

        $countries = Countries::select('name', 'id')
            ->groupBy('name', 'id')
            ->orderBy('name')
            ->get();

        return view('admin.settings.ecommerce', compact('countries'));
    }
    public function store_ecommerce(Request $request)
    {

        $rules = [
            'store_address' => 'required|string',
            'store_city' => 'required|string',
            'store_country' => 'required|exists:country,id',
            'weight_unit' => 'required|string',
            'dimension_unit' => 'required|string',
        ];
        $messages = [
            'store_address.required' => 'The store address field is required.',
            'store_city.required' => 'The store city field is required.',
            'store_country.required' => 'The store country field is required.',
            'weight_unit.required' => 'The weight unit field is required.',
            'dimension_unit.required' => 'The dimension unit field is required.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        Ecommerce::create([
            'currency_symbol'   => $request->currency_symbol,
            'currency_word'     => $request->currency_word,
            'store_address'     => $request->store_address,
            'store_city'        => $request->store_city,
            'store_country'     => $request->store_country,
            'store_postal_code' => $request->store_postal_code,
            'weight_unit'       => $request->weight_unit,
            'dimension_unit'    => $request->dimension_unit,
        ]);

        return redirect()
            ->route('view.settings.ecommerce')
            ->with('success', 'E-commerce settings saved successfully.');
    }
}
