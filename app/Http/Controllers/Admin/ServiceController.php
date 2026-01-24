<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::all();

        return view('admin.rooms.service')->with(['services'=>$services]);

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
            'service' => 'required|min:2',
            // 'quantity' => 'required|integer|min:0',
            'price' => 'required|integer|min:0',
        ];

        $messages = [
            'service.required' => 'The name is required.',
            'service.min' => 'The name must be at least 2 characters.',
            // 'quantity.required' => 'The quantity is required.',
            // 'quantity.integer' => 'The quantity must be a valid integer.',
            // 'quantity.min' => 'The quantity must be at least 0.',
            'price.required' => 'The price is required.',
            'price.integer' => 'The price must be a valid integer.',
            'price.min' => 'The price must be at least 0.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            if($request->edit_service_id != 0){
                $service = Service::find($request->edit_service_id)->update(['name' =>$request->service,'quantity' =>$request->quantity,'price' =>$request->price,'status'=>$request->status ]);
            }else{
                $service = new Service;
                $service->name = $request->service;
                $service->status = $request->status;
                // $service->quantity = $request->quantity;
                $service->quantity = 1;
                $service->price = $request->price;
                $service->save();
            }

            if ($service) {
                return redirect()->route('services.index');
            } else {
                return redirect()->back()
                    ->withErrors(['general' => 'Unable to create or update the service.'])
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
        $edit_service = Service::findOrFail($id);
        $services = Service::all();
        
        return view('admin.rooms.service')->with(['services'=>$services, 'edit_service'=>$edit_service]);

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
        $service = Service::findOrFail($id);
        $service->delete();

        return redirect()->route('services.index');
    }
}
