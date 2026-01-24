<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Room;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::all();
        return view('admin.rooms.index')->with(['rooms'=>$rooms]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $amenities = Amenity::where('status' , '=', '1')->get();
        $services = Service::where('status' , '=', '1')->get();

        return view('admin.rooms.create')->with(['amenities'=>$amenities,'services'=>$services]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $rules = [
            'title' => 'required|string|min:2',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|integer|min:0',
            'offer_price' => 'required|integer|min:0',
            'description' => 'required|string|min:50',
            'allowd_guests' => 'required|integer|min:0',
            'size' => 'required|integer|min:0',
            'bed_quantity' => 'required|integer|min:0',
            'bed_name' => 'required|string|min:1',
            'bed_price' => 'required|integer|min:0',
            'extra_bed_quantity' => 'required|integer|min:0',
            'amenities' => 'array',
            'services' => 'array',
            'featured_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'gallery_images' => 'required|min:1',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'tour_video' => 'mimetypes:video/mp4,video/x-m4v,video/x-msvideo,video/x-matroska,video/x-flv,video/quicktime|max:51200',
        ];

        $messages = [
            'title.required' => 'The title is required.',
            'title.string' => 'The title must be a valid string.',
            'title.min' => 'The title must be at least 2 characters.',
            'quantity.required' => 'Quantity is required.',
            'quantity.integer' => 'Quantity must be a valid number.',
            'quantity.min' => 'Quantity must be at least 1.',
            'price.required' => 'Price is required.',
            'price.integer' => 'Price must be a valid number.',
            'price.min' => 'Price cannot be negative.',
            'offer_price.required' => 'Offer price is required.',
            'offer_price.integer' => 'Offer price must be a valid number.',
            'offer_price.min' => 'Offer price cannot be negative.',
            'description.required' => 'Description is required.',
            'description.string' => 'Description must be a valid string.',
            'description.min' => 'Description must be at least 50 characters.',
            'allowd_guests.required' => 'Allowed guests field is required.',
            'allowd_guests.integer' => 'Allowed guests must be a number.',
            'allowd_guests.min' => 'Allowed guests cannot be negative.',
            'size.required' => 'Size is required.',
            'size.integer' => 'Size must be a valid number.',
            'size.min' => 'Size cannot be negative.',
            'bed_quantity.required' => 'Bed quantity is required.',
            'bed_quantity.integer' => 'Bed quantity must be a valid number.',
            'bed_quantity.min' => 'Bed quantity cannot be negative.',
            'bed_name.required' => 'Bed name is required.',
            'bed_name.string' => 'Bed name must be a valid string.',
            'bed_name.min' => 'Bed name must be at least 1 character.',
            'bed_price.required' => 'Bed price is required.',
            'bed_price.integer' => 'Bed price must be a valid number.',
            'bed_price.min' => 'Bed price cannot be negative.',
            'extra_bed_quantity.required' => 'Allowed extra beds quantity is required.',
            'extra_bed_quantity.integer' => 'Allowed extra beds quantity must be a valid number.',
            'extra_bed_quantity.min' => 'Allowed extra beds quantity cannot be negative.',
            'amenities.array' => 'Amenities must be an array.',
            'services.array' => 'Services must be an array.',
            'featured_image.required' => 'A featured image is required.',
            'featured_image.image' => 'The featured image must be a valid image file.',
            'featured_image.mimes' => 'The featured image must be a file of type: jpeg, png, jpg, webp.',
            'featured_image.max' => 'The featured image size must not exceed 2MB.',
            'gallery_images.required' => 'Gallery images are required.',
            'gallery_images.min' => 'Please upload at least one gallery image.',
            'gallery_images.*.image' => 'Each gallery image must be a valid image file.',
            'gallery_images.*.mimes' => 'Each gallery image must be a file of type: jpeg, png, jpg, webp.',
            'gallery_images.*.max' => 'Each gallery image must not exceed 2MB.',
            'tour_video.mimetypes' => 'Tour vedio must be a file of type: mp4, avi, mkv, flv, mov.',
            'tour_video.max' => 'The video must not be greater than 50MB.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{

            $featuredImageUrl = $request->hasFile('featured_image') ? $this->uploadmedia($request->file('featured_image')) : null;
            $tourVideoUrl = $request->hasFile('tour_video') ? $this->uploadmedia($request->file('tour_video')) : null;

            $slug = Str::slug($request->title);

            if (Room::where('slug', $slug)->exists()) {
                return redirect()->back()->withErrors(['general' => 'Room with the same name already exists.']);
            }

            $galleryImageUrls = [];
            if ($request->has('gallery_images')) {
                foreach ($request->file('gallery_images') as $galleryImage) {
                    if ($galleryImage->isValid()) {
                        $galleryImageUrls[] = $this->uploadmedia($galleryImage);
                    }
                }
            }

            $new_room = new Room();
            $new_room->name = $request->title;
            $new_room->slug = $slug;
            $new_room->quantity = $request->quantity;
            $new_room->size = $request->size;
            $new_room->price = $request->price;
            $new_room->offer_price = $request->offer_price;
            $new_room->description = $request->description;
            $new_room->allowd_guests = $request->allowd_guests;
            $new_room->beds = json_encode(["quentity" => $request->bed_quantity, "name" => $request->bed_name]);
            $new_room->bed_price = $request->bed_price;
            $new_room->extra_beds = $request->extra_bed_quantity;
            $new_room->amenities = json_encode($request->amenities);
            $new_room->service = json_encode($request->services);
            $new_room->features = $request->features ?? null;
            $new_room->feature_img = $featuredImageUrl;
            $new_room->gallery_img = json_encode($galleryImageUrls);
            $new_room->tour_video = $tourVideoUrl;
            $new_room->save();

            if($new_room){
                return redirect()->route('rooms.index');
            }else{
                return redirect()->back() ->withErrors(['general' => 'Unable to create or update the room.']);
            }
        }

    }

    /**
     * Upload image and return the url.
     */
    private function uploadmedia($file)
    {
        $filePath = "images/rooms/listings/";
        $directoryPath = public_path($filePath);

        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0777, true);
        }

        $fileName = md5(time().rand(100000,999999)).'.' . $file->getClientOriginalExtension();
        $file->move($directoryPath, $fileName);

        return $filePath . $fileName;
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
        $room = Room::findOrFail($id);
        $amenities = Amenity::where('status' , '=', '1')->get();
        $services = Service::where('status' , '=', '1')->get();

        return view('admin.rooms.edit')->with(['room'=>$room,'amenities'=>$amenities,'services'=>$services]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $rules = [
            'title' => 'required|string|min:2',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|integer|min:0',
            'offer_price' => 'required|integer|min:0',
            'description' => 'required|string|min:50',
            'allowd_guests' => 'required|integer|min:0',
            'size' => 'required|integer|min:0',
            'bed_quantity' => 'required|integer|min:0',
            'bed_name' => 'required|string|min:1',
            'bed_price' => 'required|integer|min:0',
            'extra_bed_quantity' => 'required|integer|min:0',
            'amenities' => 'array',
            'services' => 'array',
            'featured_image' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'gallery_images' => 'min:1',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'tour_video' => 'mimetypes:video/mp4,video/x-m4v,video/x-msvideo,video/x-matroska,video/x-flv,video/quicktime|max:51200',
        ];

        $messages = [
            'title.required' => 'The title is required.',
            'title.string' => 'The title must be a valid string.',
            'title.min' => 'The title must be at least 2 characters.',
            'quantity.required' => 'Quantity is required.',
            'quantity.integer' => 'Quantity must be a valid number.',
            'quantity.min' => 'Quantity must be at least 1.',
            'price.required' => 'Price is required.',
            'price.integer' => 'Price must be a valid number.',
            'price.min' => 'Price cannot be negative.',
            'offer_price.required' => 'Offer price is required.',
            'offer_price.integer' => 'Offer price must be a valid number.',
            'offer_price.min' => 'Offer price cannot be negative.',
            'description.required' => 'Description is required.',
            'description.string' => 'Description must be a valid string.',
            'description.min' => 'Description must be at least 50 characters.',
            'allowd_guests.required' => 'Allowed guests field is required.',
            'allowd_guests.integer' => 'Allowed guests must be a number.',
            'allowd_guests.min' => 'Allowed guests cannot be negative.',
            'size.required' => 'Size is required.',
            'size.integer' => 'Size must be a valid number.',
            'size.min' => 'Size cannot be negative.',
            'bed_quantity.required' => 'Bed quantity is required.',
            'bed_quantity.integer' => 'Bed quantity must be a valid number.',
            'bed_quantity.min' => 'Bed quantity cannot be negative.',
            'bed_name.required' => 'Bed name is required.',
            'bed_name.string' => 'Bed name must be a valid string.',
            'bed_name.min' => 'Bed name must be at least 1 character.',
            'bed_price.required' => 'Bed price is required.',
            'bed_price.integer' => 'Bed price must be a valid number.',
            'bed_price.min' => 'Bed price cannot be negative.',
            'extra_bed_quantity.required' => 'Allowed extra beds quantity is required.',
            'extra_bed_quantity.integer' => 'Allowed extra beds quantity must be a valid number.',
            'extra_bed_quantity.min' => 'Allowed extra beds quantity cannot be negative.',
            'amenities.array' => 'Amenities must be an array.',
            'services.array' => 'Services must be an array.',
            'featured_image.image' => 'The featured image must be a valid image file.',
            'featured_image.mimes' => 'The featured image must be a file of type: jpeg, png, jpg, webp.',
            'featured_image.max' => 'The featured image size must not exceed 2MB.',
            'gallery_images.min' => 'Please upload at least one gallery image.',
            'gallery_images.*.image' => 'Each gallery image must be a valid image file.',
            'gallery_images.*.mimes' => 'Each gallery image must be a file of type: jpeg, png, jpg, webp.',
            'gallery_images.*.max' => 'Each gallery image must not exceed 2MB.',
            'tour_video.mimetypes' => 'Tour vedio must be a file of type: mp4, avi, mkv, flv, mov.',
            'tour_video.max' => 'The video must not be greater than 50MB.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        $room = Room::find($id);
        if(!$room->id){
            return redirect()->back() ->withErrors(['general' => 'Unable to create or update the room.']);
        }elseif ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{

            if($request->hasFile('featured_image')){
                $featuredImageUrl = $this->uploadmedia($request->file('featured_image'));
            }
            if($request->hasFile('tour_video')){
                $tourVideoUrl = $this->uploadmedia($request->file('tour_video'));
            }

            $galleryImageUrls = json_decode($room->gallery_img);
            if ($request->has('gallery_images')) {
                foreach ($request->file('gallery_images') as $galleryImage) {
                    if ($galleryImage->isValid()) {
                        $galleryImageUrls[] = $this->uploadmedia($galleryImage);
                    }
                }
            }

            $room->update([
                'name' => $request->title,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'offer_price' => $request->offer_price,
                'description' => $request->description,
                'allowd_guests' => $request->allowd_guests,
                'size' => $request->size,
                'beds' => json_encode(["quentity" => $request->bed_quantity, 'name' => $request->bed_name]),
                'bed_price' => $request->bed_price,
                'extra_beds' => $request->extra_bed_quantity,
                'amenities' => json_encode($request->amenities),
                'service' => json_encode($request->services),
                'features' => $request->features ?? null,
                'feature_img' => $featuredImageUrl ?? $room->feature_img, 
                'gallery_img' => json_encode($galleryImageUrls),
                'tour_video' => $tourVideoUrl ?? $room->tour_video,
            ]);

            if($room){
                return redirect()->route('rooms.index');
            }else{
                return redirect()->back() ->withErrors(['general' => 'Unable to create or update the room.']);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return redirect()->route('rooms.index');
    }

    public function remove_room_media(Request $request){
        
        if($request->type != "tour" && $request->type != "gallery") {
            return response()->json(["error" => "Invalid type-".$request->type], 400);
        }        
        
        $room = Room::find($request->room);

        if(!isset($room->id)){
            return response()->json(["error" => "Room not found"], 400);
        }else{
            if($request->type == "tour"){
                $room->update(['tour_video' => null]);
                return response()->json(["success" => "Tour video removed successfully."]);
            }elseif($request->type == "gallery"){
                $galleryImages = json_decode($room->gallery_img);
                $filteredImages = array_filter($galleryImages, function ($image) use ($request) {
                    return $image !== $request->media;
                });
            
                $room->gallery_img = json_encode(array_values($filteredImages));
                $room->save();

                return response()->json(["success" => "Gallery image removed successfully."]);
            }else{
                return response()->json(["error" => "Unable to remove media. "], 400);
            }
        }
    }

    public function room_wise_services(Request $request){
        $room = Room::findOrFail($request->rid);
        $services = json_decode($room->service, true);
        
        return response()->json($services);
    }
}
