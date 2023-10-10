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
           return Datatables::of($payment_plan)
               ->addIndexColumn()
               ->addColumn('action', function ($payment_plan) 
               {
                   return '<a href="' . url('admin/payment_plan/show/' . $payment_plan->id) . '" class="btn btn-info btn-sm">Details</a>';
               })
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\categories  $categories
     * @return \Illuminate\Http\Response
     */

    public function destroy(Request $request)
    {
        $lotTerms=lotTerms::find($request->id);
        $lotTerms->delete();
        return redirect()->route('payment_plan');
    }

   
}