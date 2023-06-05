<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Auction;
use Illuminate\Http\Request;
use DataTables;

class AuctionController extends Controller
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
          $auctions = Auction::all();
          if ($request->ajax()) {
            return Datatables::of($auctions)
                    ->addIndexColumn()
                    ->rawColumns(['action'])
                    ->make(true);
        }
          return view('admin.auctions.index')
              ->with('auctions', $auctions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $materials = null;
        $categorys = \App\Models\lots::all();
        return view('admin.auctions.create',compact('materials', 'categorys'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Auction::create($request->all());

        return redirect()->route('admin.auctions.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Http\Response
     */
    public function show(Auction $auction)
    {
        return view('admin.auctions.show', compact('auction'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Http\Response
     */
    public function edit(Auction $auction)
    {
        $categorys = \App\Models\lots::all();
        return view('admin.auctions.edit', compact('auction','categorys'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Auction $auction)
    {
        $auction->update($request->all());

        return redirect()->route('admin.auctions.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Auction $auction)
    {
        $auction->delete();

        return redirect()->route('admin.auctions.index');
    }
}
