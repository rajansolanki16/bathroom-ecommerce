<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $faqs = Faq::all();
        return view('admin.settings.faq')->with(['faqs'=>$faqs]);
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
            'question' => 'required',
            'answer' => 'required',
            'status' => 'required|in:0,1',
        ];
        
        $messages = [
            'question.required' => 'The question is required.',
            'answer.required' => 'The answer is required.',
            'status.required' => 'The status is required.',
            'status.in' => 'The status must be either enable or disable.',
        ];
        

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            if($request->edit_faq_id != 0){
                $faq = Faq::find($request->edit_faq_id)->update(['question' =>$request->question,'answer' =>$request->answer,'status'=>$request->status ]);
            }else{
                $faq = new Faq;
                $faq->question = $request->question;
                $faq->answer = $request->answer;
                $faq->status = $request->status;
                $faq->save();
            }
        }

        if ($faq) {
            return redirect()->route('faqs.index');
        } else {
            return redirect()->back()
                ->withErrors(['question' => 'Unable to create or update the FAQ.'])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $faqs = Faq::where('status','=','1')->get();
        return view('faqs')->with(['faqs'=>$faqs]);
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $edit_faq = Faq::findOrFail($id);
        $faqs = Faq::all();
        
        return view('admin.settings.faq')->with(['faqs'=>$faqs,'edit_faq'=>$edit_faq ]);
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
        $faq = Faq::findOrFail($id);
        $faq->delete();

        return redirect()->route('faqs.index');
    }
}
