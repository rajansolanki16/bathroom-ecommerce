<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Amenity;
use Illuminate\Support\Facades\Validator;

class AmenityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $amenities = Amenity::all();
        return view('admin.rooms.amenity')->with(['amenities'=>$amenities]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|min:2'
        ];
        $messages = [
            'name.required' => 'The name is required.',
            'name.min' => 'The name must be at least 2 characters.',
        ];

        if($request->edit_amenity_id == 0){
            $rules['icon'] = 'required|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp,tiff,tif,ico|max:2048';
            $messages['icon.required'] = 'The icon is required while creating.';
            $messages['icon.mimes'] = 'The icon must be in jpeg, png, jpg, gif, svg, webp, bmp, tiff, tif or ico format.';
            $messages['icon.max'] = 'The icon size must not exceed 2MB.';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            if($request->edit_amenity_id != 0){
                if(isset($request->icon) && $request->hasFile('icon')){
                    $file = $request->file('icon');
                    $fileName = $file->getClientOriginalName();
                    $filePath = 'images/rooms/amenities/';
                    $directoryPath = public_path($filePath);
                    if (!file_exists($directoryPath)) {
                        mkdir($directoryPath, 0777, true);
                    }
                    $file->move($directoryPath, $fileName);
                    $fileUrl = $filePath . $fileName;
                   

                    $room_amenity = Amenity::find($request->edit_amenity_id)->update(['name' =>$request->name ,'icon' =>$fileUrl,'status'=>$request->status]);
                }else{
                    $room_amenity = Amenity::find($request->edit_amenity_id)->update(['name' =>$request->name,'status'=>$request->status ]);
                }
            }else{

                $file = $request->file('icon');
                $fileName = $file->getClientOriginalName();
                $filePath = 'images/rooms/amenities/';
                $directoryPath = public_path($filePath);
                if (!file_exists($directoryPath)) {
                    mkdir($directoryPath, 0777, true);
                }
                $file->move($directoryPath, $fileName);
                $fileUrl = $filePath . $fileName;
                $room_amenity = new Amenity;
                $room_amenity->name = $request->name;
                $room_amenity->status = $request->status;
                $room_amenity->icon = $fileUrl;
                $room_amenity->save();
            }

            if ($room_amenity) {
                return redirect()->route('amenities.index');
            } else {
                return redirect()->back()
                    ->withErrors(['category' => 'Unable to create or update the category.'])
                    ->withInput();
            }

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
        $edit_amenity = Amenity::findOrFail($id);

        $amenities = Amenity::all();
        return view('admin.rooms.amenity')->with(['amenities'=>$amenities,'edit_amenity'=>$edit_amenity ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $amenity = Amenity::findOrFail($id);
        $amenity->delete();

        return redirect()->route('amenities.index');
    }
}
