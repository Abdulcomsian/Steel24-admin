<?php

namespace App\Http\Controllers\Admin;


use App\Models\payments;
use Illuminate\Http\Request;
use App\Models\categories;
use App\Models\materials;
use Illuminate\Support\Facades\Auth;
use DataTables;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('admin.auth:admin');
    }

    public function index(Request $request)
    {

        // $payments =  DB::select('SELECT payments.* ,lots.title as lotTitle,customers.name as customerName from payments LEFT JOIN lots on lots.id = payments.lotId LEFT JOIN customers on payments.customerId = customers.id GROUP by payments.lotId;');

        // if ($request->ajax()) {
        //     return Datatables::of($payments)
        //         ->addIndexColumn()
        //         ->rawColumns(['action'])
        //         ->make(true);
        // }
        // return view('admin.payments.index', compact('payments'));




        $payments = DB::select('SELECT payments.*, lots.title as lotTitle, customers.name as customerName, payments.Date as paymentDate FROM payments LEFT JOIN lots on lots.id = payments.lotId LEFT JOIN customers on payments.customerId = customers.id GROUP by payments.lotId;');

        if ($request->ajax()) 
        {
            return Datatables::of($payments)
                ->addIndexColumn()
                ->addColumn('action', function($row) 
                {
                    return '<a href="'.url('admin/payments/'.$row->lotId).'" class="btn btn-info btn-sm">Details</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.payments.index', compact('payments'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\payments  $payments
     * @return \Illuminate\Http\Response
     */

    public function show($payments)
    {
        $paymentDetails = DB::select('
        SELECT payments.*, customers.* , lots.* from payments 
        LEFT JOIN customers on customers.id = payments.customerId 
        LEFT JOIN lots on lots.id = payments.lotId 
        WHERE payments.lotId = ' . $payments . ' ORDER BY payments.id DESC;');

        // dd($paymentDetails);
        return view('admin.payments.show', compact('paymentDetails'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\payments  $payments
     * @return \Illuminate\Http\Response
     */

    public function edit(payments $payments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\payments  $payments
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, payments $payments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\payments  $payments
     * @return \Illuminate\Http\Response
     */

    public function destroy(payments $payments)
    {
        //
    }
}
