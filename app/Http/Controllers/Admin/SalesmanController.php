<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\StoreVisit;

class SalesmanController extends Controller
{
    public function createVisit()
    {
        $vendors = User::role('vendor')->orderBy('name', 'asc')->get();

        return view('salesman.visit_form', compact('vendors'));
    }

    public function storeVisit(Request $request)
    {
        // 1. Validation
        // $request->validate([
        //     'vendor_id' => 'required|exists:users,id',
        //     'purpose'   => 'required|string',
        //     'outcome'   => 'required|in:positive,neutral,negative',
        //     'notes'     => 'nullable|string',
        //     'rating'    => 'nullable|integer|between:1,5',
        //     'next_date' => 'required_if:follow_up_required,on|nullable|date|after_or_equal:today',
        // ]);

        $request->validate(
            [
                'vendor_id' => 'required|exists:users,id',
                'purpose'   => 'required|string',
                'outcome'   => 'required|in:positive,neutral,negative',
            ],
            [
                'vendor_id.required' => 'Please select a vendor.',
                'vendor_id.exists'   => 'Selected vendor is invalid.',

                'purpose.required'   => 'Purpose of visit is required.',
                'purpose.string'     => 'Purpose must be a valid text.',

                'outcome.required'   => 'Please select visit outcome.',
            ]
        );

        // 2. Data Preparation
        $data = [
            'salesman_id'        => auth()->id(),
            'vendor_id'          => $request->vendor_id,
            'purpose'            => $request->purpose,
            'notes'              => $request->notes,
            'feedback'           => $request->feedback,
            'rating'             => $request->rating,
            'outcome'            => $request->outcome,
            'follow_up_required' => $request->has('follow_up_required'),
            'next_follow_up_date' => $request->next_date,
            'location_address'   => $request->location_address,
        ];

        // 3. Create Record
        StoreVisit::create($data);
        return redirect()->route('admin.dashboard')->with('success', 'Store visit report has been logged successfully!');
    }

    public function editVisit($id)
    {
        $visit = StoreVisit::findOrFail($id);
        $vendors = User::role('vendor')->orderBy('name', 'asc')->get();

        return view('salesman.edit_visit', compact('visit', 'vendors'));
    }

    public function updateVisit(Request $request, $id)
    {
        $visit = StoreVisit::findOrFail($id);

        $request->validate(
            [
                'vendor_id' => 'required|exists:users,id',
                'purpose'   => 'required|string',
                'outcome'   => 'required|in:positive,neutral,negative',
            ],
            [
                'vendor_id.required' => 'Please select a vendor.',
                'vendor_id.exists'   => 'Selected vendor is invalid.',

                'purpose.required'   => 'Purpose of visit is required.',
                'purpose.string'     => 'Purpose must be a valid text.',

                'outcome.required'   => 'Please select visit outcome.',
            ]
        );

        $followUpRequired = $request->has('follow_up_required');

        $visit->update([
            'vendor_id'          => $request->vendor_id,
            'purpose'            => $request->purpose,
            'notes'              => $request->notes,
            'feedback'           => $request->feedback,
            'rating'             => $request->rating,
            'outcome'            => $request->outcome,
            'follow_up_required' => $followUpRequired,
            'next_follow_up_date' =>  $followUpRequired ? $request->next_follow_up_date : null,
            'location_address'   => $request->location_address,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Store visit report has been updated successfully!');
    }
}
