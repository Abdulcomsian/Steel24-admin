<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\lotTerms;
use DataTables;
class PaymentPlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin.auth:admin');
    }
   
    public function index(Request $request)
    {
        $payment_plan = lotTerms::all();
        if ($request->ajax()) 
        {
          return DataTables::of($payment_plan)
                  ->addIndexColumn()
                  ->rawColumns(['action'])
                  ->make(true);
       }
        return view('admin.paymeny_plan.index')
            ->with('payment_plan', $payment_plan);
    }

    public function create()
    {
        $parentpayment_plan  = lotTerms::all();
        return view('admin.paymeny_plan.create', compact('parentpayment_plan'));
    }

    public function store(Request $request)
    {
        lotTerms::create($request->validate([
            'Payment_Terms' => 'required',
            'Price_Bases' => 'required',
            'Texes_and_Duties' => 'required',
            'Commercial_Terms' => 'required',
            'Test_Certificate' => 'required',
        ]));
        return redirect('admin/payment_plan');
    }

    public function show(lotTerms $lotTerms, $id)
    {
        $lotTerms  = lotTerms::find($id);
        return view('admin.paymeny_plan.show', compact('lotTerms'));
    }


    public function edit(lotTerms $lotTerms, $id)
    {
        $lotTerms  = lotTerms::find($id);
        return view('admin.paymeny_plan.edit', compact('lotTerms'));
    }

    public function update(Request $request)
    {
        $lotTerms  = lotTerms::find($request->lotid);
        $lotTerms->update($request->validate([
            'Payment_Terms' => 'required',
            'Price_Bases' => 'required',
            'Texes_and_Duties' => 'required',
            'Commercial_Terms' => 'required',
            'Test_Certificate' => 'required',
        ]));
        return redirect('admin/payment_plan/show/'. $request->lotid);
// dd($lotTerms->id);
        // return redirect("admin/payment_plan/show/.$lotTerms->id");

     
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\categories  $categories
     * @return \Illuminate\Http\Response
     */

    public function destroy(lotTerms $lotTerms)
    {
        $lotTerms->delete();
    }
   
}