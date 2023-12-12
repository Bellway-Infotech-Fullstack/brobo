<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;


class FAQController extends Controller
{
    public function add_new()
    {
        $faqs = Faq::latest()->paginate(config('default_pagination'));        
        return view('admin-views.faq.index', compact('faqs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|unique:faqs',
            'answer' => 'required',
        ]);
        $faq = new Faq();
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->save();   

       

        Toastr::success("Faq added succesfully");
        return back();
    }

    public function edit($id)
    {
        $faq = Faq::where(['id' => $id])->first();
        return view('admin-views.faq.edit', compact('faq'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|unique:faqs,question,'.$id,
            'answer' => 'required',
        ]);
      

        $faqData = Faq::find($id);
        $faqData->question = $request->question;
        $faqData->answer = $request->answer;
        $faqData->save();   



        Toastr::success("Faq updated succesfully");
        return back();
    }

    public function status(Request $request)
    {
        $coupon = Faq::find($request->id);
        $coupon->status = $request->status;
        $coupon->save();
        Toastr::success("Faq status updated sucessfully");
        return back();
    }

    public function delete(Request $request)
    {
        $coupon = Faq::find($request->id);
        $coupon->delete();
        Toastr::success("Faq deleted sucessfully");
        return back();
    }

    public function search(Request $request){
        $key = explode(' ', $request['search']);
        $faqs= Faq::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('question', 'like', "%{$value}%");
            }
        })->limit(50)->get();
        return response()->json([
            'view'=>view('admin-views.faq.partials._table',compact('faqs'))->render(),
            'count'=>$faqs->count()
        ]);
    }
}
