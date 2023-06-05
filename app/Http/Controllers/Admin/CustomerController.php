<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;


use App\Models\Customer;
use App\Models\customerBalance;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = Customer::all();
        if ($request->ajax()) {
            return Datatables::of($users)
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.customers.index')
            ->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Customer::create($request->all());
        return redirect()->route('admin.customers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        $customerBalance = customerBalance::where('customerId', $customer->id)->orderBy('id', 'desc')->first();
        return view('admin.customers.show', compact('customer', 'customerBalance'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $customer->update($request->all());

        return redirect('admin/customers/' . $customer->id);
    }

    public function status(Request $request, $id, $type)
    {
        $status = Customer::find($id);
        $status->isApproved = $type;
        $status->save();
        $request->session()->flash("massege", "status updated successfuly");
        return back();
    }

    public function activecustomers(Request $request, $id)
    {
        $status = Customer::find($id);
        $status->isApproved = $status->isApproved == 1 ? 0 : 1;
        $status->save();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect('admin/customers');
    }


    function customersbalancehistory($custoemrId)
    {
        $lastBalance =  customerBalance::where('customerId', $custoemrId)->orderBy('id', 'desc')->first();
        $balanceHistory =  DB::select('SELECT customer_balances.*,lots.id as lotid, lots.title as lottitle from customer_balances
        LEFT JOIN lots on lots.id= customer_balances.lotid 
        WHERE customerId = ' . $custoemrId . ' ORDER BY id DESC;');

        return view('admin.customers.balancehistory', compact('lastBalance', 'balanceHistory'));

        return view();
    }
}
