<?php

namespace App\Http\Controllers\Admin;

use App\Events\MessageEvent;
use App\Http\Controllers\Api\V1\Admin\LotsContoller;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LiveLotsController;
use App\Models\BidsOfLots;
use App\Models\categories;
use App\Models\Customer;
use App\Models\customerBalance;
use App\Models\lot_materials;
use App\Models\lots;
use App\Models\lotTerms;
use App\Models\materials;
use App\Models\new_maerials_2;
use App\Models\newMaterial;
use App\Models\payments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Support\Facades\DB;

use Kreait\Firebase;
use Kreait\Firebase\Factory;

use function PHPSTORM_META\elementType;

class LotsController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin.auth:admin');
    }

    public function index(Request $request)
    {
        $lots = lots::all();
        if ($request->ajax()) {
            return Datatables::of($lots)
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->addColumn('status', function ($row) {
                    if ($row->status) {
                        return '<span class="badge badge-primary">Active</span>';
                    } else {
                        return '<span class="badge badge-danger">Deactive</span>';
                    }
                })
                ->filter(function ($instance) use ($request) {
                    if ($request->get('status') == '0' || $request->get('status') == '1') {
                        $instance->where('status', $request->get('status'));
                    }
                    if (!empty($request->get('search'))) {
                        $instance->where(function ($w) use ($request) {
                            $search = $request->get('search');
                            $w->orWhere('name', 'LIKE', "%$search%")
                                ->orWhere('email', 'LIKE', "%$search%");
                        });
                    }
                })
                ->make(true);
        }
        return view('admin.lots.index')
            ->with('lots', $lots);

            
    }
    public function analyzeUrl(Request $request)
    {
        // dd($request->url);

        event(new MessageEvent($request->url, $user_id = 2));
        return view('admin.home');
        // return \Illuminate\Support\Facades\Redirect::back();

    }

    public function create()
    {
        $addForm = true;
        // $materials = materials::all();
        $lots = false;
        $categorys =  categories::all();
        return view('admin.lots.create', compact('addForm',  'lots', 'categorys'));
    }

    public function store(Request $request)
    {
        $userDetails = Auth::guard('admin')->user();
        $this->validate($request, [
            'title' => 'required',
            'description' => 'nullable',
            "Seller" => "required",
            "Plant" => "nullable",
            "materialLocation" => "nullable",
            "Quantity" => "required",
            "StartDate" => "required",
            "EndDate" => "required",
            // "material" => "required",
            "Price" => "required",
            'categoryId' => "required",
            "participate_fee" => "required"
        ]);
        $input = $request->only([
            'title',
            'description',
            "Seller",
            "Plant",
            "materialLocation",
            "Quantity",
            "StartDate",
            "EndDate",
            // "material",
            "Price",
            "categoryId",
            "participate_fee"
        ]);
        $input['lot_status'] = 'upcoming';
        $input['uid'] = $userDetails->id;
        $data = lots::create($input);
        // $data->materials()->attach(array_key_exists('material', $input) ? $input['material'] : []);

        return redirect('/admin/addmaterialslots/' . $data->id);
    }

    public function creatematerialslots(lots $lots)
    {
        return view('admin.materials.create', compact('lots'));
    }

    public function addmaterialslots(lots $lots, Request $request)
    {


        $data = $request->validate([
            "lotid" => 'required',
            "Product" => 'required',
            "Thickness" => 'required',
            "Width" => 'required',
            "Length" => 'required',
            "Weight" => 'required',
            "Grade" => 'required',
            "Remark" => 'required',
            "images" => 'nullable',
            "materialqnt" => 'required',
        ]);

        for ($index = 0; $index < $data['materialqnt']; $index++) {

            $material['lotid'] = $data["lotid"];
            $material['Product'] = $data["Product"][$index];
            $material['Thickness'] = $data["Thickness"][$index];
            $material['Width'] = $data["Width"][$index];
            $material['Length'] = $data["Length"][$index];
            $material['Weight'] = $data["Weight"][$index];
            $material['Grade'] = $data["Grade"][$index];
            $material['Remark'] = $data["Remark"][$index];
            if (isset($data['images'][$index])) {
                $imgName = time() . rand(1, 100) . '.' . $data['images'][$index]->extension();
                $data['images'][$index]->move(public_path('files'), $imgName);
                $material['images'] = $imgName;
            }

            new_maerials_2::create($material);
        }

        // $data = [];
        // $requestindex = (int)$request->materialqnt;
        // for ($index = 0; $index < $requestindex; $index++) {
        //     $material = [];
        //     foreach ($request->all() as $title =>  $valuearray) {
        //         if (is_array($valuearray) &&  isset($valuearray[$index]) && $title != 'images') {
        //             $material[$title] = $valuearray[$index];
        //         }
        //         // dump($material[$title]);
        //     }
        //     $imgName = '';
        //     if ($request['images'][$index]) {
        //         $imgName = time() . rand(1, 100) . '.' . $request['images'][$index]->extension();
        //         $request['images'][$index]->move(public_path('files'), $imgName);
        //     }
        //     array_push($data, ["lotid" => $lots->id, "materialid" => $index, "materialdata" => json_encode($material), "image" => $imgName]);
        // }
        // newMaterial::insert($data); // Eloquent
        return redirect('/admin/lots/' . $lots->id);
    }

    public function updatematerialslots(lots $lots, Request $request)
    {

        $data = $request->validate([
            "lotid" => 'required',
            "id" => 'required',
            "Product" => 'required',
            "Thickness" => 'required',
            "Width" => 'required',
            "Length" => 'required',
            "Weight" => 'required',
            "Grade" => 'required',
            "Remark" => 'required',
            "images" => 'nullable',
            "materialqnt" => 'required',
        ]);
        // dd($data);

        for ($index = 0; $index < $data['materialqnt']; $index++) {

            $material['lotid'] = $data["lotid"];
            $material['id'] = $data["id"][$index];
            $material['Product'] = $data["Product"][$index];
            $material['Thickness'] = $data["Thickness"][$index];
            $material['Width'] = $data["Width"][$index];
            $material['Length'] = $data["Length"][$index];
            $material['Weight'] = $data["Weight"][$index];
            $material['Grade'] = $data["Grade"][$index];
            $material['Remark'] = $data["Remark"][$index];
            // dd($data['images']);
            if (isset($data['images'][$index])) {
                $imgName = time() . rand(1, 100) . '.' . $data['images'][$index]->extension();
                $data['images'][$index]->move(public_path('files'), $imgName);
                $material['images'] = $imgName;
            }
            // dd($$material['id']);
            new_maerials_2::updateOrCreate(['id' => $material["id"]], $material);
            new_maerials_2::where('id', $material['id'])->update($material);
        }
        // $data = [];
        // $requestindex = (int)$request->materialqnt;
        // for ($index = 0; $index < $requestindex; $index++) {
        //     $material = [];
        //     foreach ($request->all() as $title =>  $valuearray) {
        //         if (is_array($valuearray) &&  isset($valuearray[$index]) && $title != 'images') {
        //             $material[$title] = $valuearray[$index];
        //         }
        //         // dump($material[$title]);
        //     }
        //     // $imgName = '';
        //     // if ($request['images'][$index]) {
        //     //     $imgName = time() . rand(1, 100) . '.' . $request['images'][$index]->extension();
        //     //     $request['images'][$index]->move(public_path('files'), $imgName);
        //     // }
        //     array_push($data, ["lotid" => $lots->id, "materialid" => $index, "materialdata" => json_encode($material)]);
        // }
        // foreach ($data as $mtrl) {
        //     newMaterial::where([['lotid', $mtrl['lotid']], ['materialid', $mtrl['materialid']]])->update(['materialdata' => $mtrl['materialdata']]);
        // }
        return redirect('/admin/lots/' . $lots->id);
    }

    // public function createlotsterms(lots $lots)
    // {
    //     return view('admin.lots.addLotsTerms', compact('lots'));
    // }

    public function createLotTerms()
        {
            // $payment_plan = lots::
            return view('admin.lots.addLotsTerms');
        }
        
        public function payment_plan(Request $request)
        {
            $paymentTerms = lotTerms::all();

            return view('admin.lots.payment_plan')->with(compact('paymentTerms'));
        }
        public function storepaymentplan(Request $request)
        {

            // Validate the form data
            $data = $request->validate([
                'Payment_Terms' => 'required',
                'Price_Bases' => 'required',
                'Texes_and_Duties' => 'required',
                'Commercial_Terms' => 'required',
                'Test_Certificate' => 'required',
            ]);
        
        lotTerms::create($data);
        return redirect('admin/payment_plan');
        }
        

    public function storelotsterms(lots $lots, Request $request)
    {
        $data = $request->validate([
            'Payment_Terms' => 'required',
            'Price_Bases' => 'required',
            'Texes_and_Duties' => 'required',
            'Commercial_Terms' => 'required',
            'Test_Certificate' => 'required',
        ]);

        $data['lotid'] = $lots->id;
        lotTerms::create($data);
        return redirect('/admin/lots/' . $lots->id);
    }

    public function updatelotsterms(lots $lots, Request $request)
    {
        $data = $request->validate([
            'Payment_Terms' => 'required',
            'Price_Bases' => 'required',
            'Texes_and_Duties' => 'required',
            'Commercial_Terms' => 'required',
            'Test_Certificate' => 'required',
        ]);

        $result =  lotTerms::where('lotid', $lots->id)->update($data);
        if (!$result) {
            $data['lotid'] =  $lots->id;
            lotTerms::create($data);
        }

        return redirect('/admin/lots/' . $lots->id);
    }

    public  function materialslots(lots $lots)
    {

        $materialilist = new_maerials_2::where('lotid', $lots->id)->get();

        // $materialilist = [];
        // $material_keys = null;
        // $materialidata =  newMaterial::where("lotid", $lots->id)->get();

        // if (count($materialidata)) {
        //     foreach ($materialidata as  $material) {

        //         array_push($materialilist, [(array) json_decode($material->materialdata), $material->image],);
        //     }
        //     $material_keys = array_keys((array) $materialilist[0][0]);
        // }
        // $newmateriali = [];
        // foreach ($materialidata as $materiali) {
        //     array_push($newmateriali, ['data' => (array) json_decode($materiali->materialdata), "image" => $materiali->image, "materialid" => $materiali->materialid]);
        // }
        // // dd($newmateriali);
        return view('admin.materials.edit', compact('lots', 'materialilist'));
    }

    public function lotsterms(lots $lots)
    {
        $lotTerms = lotTerms::where('lotid', $lots->id)->first();
        return view('admin.lots.editLotsTerms', compact('lots', 'lotTerms'));
    }

    public function show(lots $lots)
    {
        // $materialilist = [];
        // $material_keys = null;
        // $materialidata =  newMaterial::where("lotid", $lots->id)->get();

        // if (count($materialidata)) {
        //     foreach ($materialidata as  $material) {

        //         array_push($materialilist, [(array) json_decode($material->materialdata), $material->image],);
        //     }
        //     $material_keys = array_keys((array) $materialilist[0][0]);
        // }
        $materialilist = new_maerials_2::where('lotid', $lots->id)->get();
        return view('admin.lots.show', compact('lots', 'materialilist'));
    }

    public function edit(lots $lots)
    {
        $addForm = false;
        $materials = materials::all();
        $lot_materials = lot_materials::where('lots_id', $lots->id)->get();
        $live = false;
        $categorys =  categories::all();
        return view('admin.lots.edit', compact('addForm', 'lots', 'materials', 'lot_materials', 'live', 'categorys'));
    }

    public function editLive(lots $lots)
    {
        $addForm = false;
        $materials = materials::all();
        $lot_materials = lot_materials::where('lots_id', $lots->id)->get();
        $live = true;
        $categorys =  categories::all();
        return view('admin.lots.edit', compact('addForm', 'lots', 'materials', 'lot_materials', 'live', 'categorys'));
    }

    public function update(Request $request, lots $lots)
    {

        $userDetails = Auth::guard('admin')->user();
        $data = $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            "Seller" => "required",
            "Plant" => "nullable",
            "materialLocation" => "nullable",
            "Quantity" => "required",
            "StartDate" => "required",
            "EndDate" => "required",
            "material" => "nullable",
            'categoryId' => "required",
            "Price" => "required",
            "participate_fee" => 'required'
        ]);


        $lots->materials()->sync(array_key_exists('material', $data) ? $data['material'] : []);

        $data['uid'] = $userDetails->id;
        $lots->update($data);

        $firebase = (new Factory)
            ->withServiceAccount(__DIR__ . '/lotbids-7751a-firebase-adminsdk-2kxk6-5db00e2535.json')
            ->withDatabaseUri('https://lotbids-7751a-default-rtdb.europe-west1.firebasedatabase.app/');
        $database = $firebase->createDatabase();
        if ($lots->lot_status == 'live') {
            $lots->ParticipateUsers = customerBalance::where([['lotId', $lots->id], ['status', '!=', '1']])->groupBy('customerId')->pluck('customerId')->toArray();;
            $database->getReference('TodaysLots/liveList/' . $lots->id)->set($lots);
        } else if ($lots->lot_status == 'upcoming') {
            $database->getReference('TodaysLots/upcoming/' . $lots->id)->set($lots);
        }

        $liveLots = DB::select("SELECT lots.* ,categories.title as categoriesTitle FROM `lots` 
        LEFT JOIN categories on categories.id  = lots.categoryId
        WHERE (date(lots.EndDate) = CURDATE()) and lots.id  > $lots->id");

        foreach ($liveLots as $lot) {
            // $lot = lots::where('id', $lot->id)->update(['EndDate' => Carbon::parse($lot->EndDate)->addMinutes(3)]);
            $lot = lots::where('id', $lot->id)->update(['EndDate' => Carbon::parse($lot->EndDate)->addMinutes(3)]);
        }
        // Have to Brodcast with
        // $response = ["sucess" => true, 'lots' => $$lot, "message"=>"Lot Details Updated"];
        LiveLotsController::pushonfirbase();
        if ($request->live) {
            return redirect('/admin/live_lots_bids/' . $lots->id);
        } else {
            return redirect('/admin/lots/' . $lots->id);
        }
    }

    public function destroy(lots $lots)
    {
        $lots->delete();
        return redirect('admin/lots');
    }

    public function complete_index(Request $request)
    {

        $livelots = DB::select("SELECT * FROM `lots` WHERE lot_status != 'live' ORDER by EndDate DESC;");

        if ($request->ajax()) {
            return Datatables::of($livelots)
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.lots.completeIndex')->with('livelots', $livelots);
    }

    public function liveLots()
    {
        $liveLots = lots::whereDate('date', \Carbon\Carbon::today())->get();

        return (view('lots.liveLots', compact('liveLots')));
    }

    public function liveLotDetails(lots $lots)
    {
        return (view('admin.lots.liveLotsDetails', compact('lots')));
    }

    public function completelotbids(lots $lots)
    {
        $lotbids = DB::select('
        SELECT bids_of_lots.id,bids_of_lots.amount,bids_of_lots.created_at as bidTime,
        lots.title as lotTitle,lots.description as lotdescription,lots.Price as lotstartAmount,lots.StartDate as lotStartDate,
        customers.id as customerId,customers.name as customerName
        FROM bids_of_lots
        LEFT JOIN customers on customers.id = bids_of_lots.customerId
        LEFT JOIN lots on lots.id = bids_of_lots.lotId
        WHERE lotId =' . $lots->id . ' ORDER BY bids_of_lots.amount DESC');
        $paymentRequest = payments::where('lotId', $lots->id)->get();
        return (view('admin.lots.completeLotsDetails', compact('lots', 'lotbids', 'paymentRequest')));
    }

    public function sendEmail(Request $request)
    {
        $users = Customer::all()->get();
        dd($users);
        Mail::to($users)->send(new UserEmail());
        return response()->json(['success' => 'Send email successfully.']);
    }

    public function livelotstatus($id, $status)
    {
        $lot = lots::where('id', $id)->update(['lot_status' => $status]);
        $lot = lots::find($id);

        if ($status == 'live') {
            $lastBid = DB::select('SELECT bids_of_lots.*, customers.id as customerId,customers.name as customerName
            FROM bids_of_lots
            LEFT JOIN customers on customers.id = bids_of_lots.customerId
            WHERE lotId =' . $id . ' and bids_of_lots.id =  (SELECT max(id) from bids_of_lots WHERE lotid = ' . $id . ') ORDER BY bids_of_lots.amount DESC;');

            $lot->lastBid = $lastBid[0];
        }


        return redirect('/admin/live_lots_bids/' . $id);
    }

    public function createPayment(Request $request)
    {
        $data = $request->validate([
            'lotid' => 'required',
            'customerVisible' => 'nullable',
        ]);
        $lastBid = BidsOfLots::where('lotId', $data['lotid'])->orderBy('id', 'desc')->first();
        $lotDetails = lots::where('id', $data['lotid'])->first();
        if ($lastBid) {
            payments::create([
                'lotId' => $data['lotid'],
                'customerId' => $lastBid->customerId,
                'total_amount' => $lastBid->amount,
                'paid_amount' => $lotDetails->participate_fee,
                'remaining_amount' => $lastBid->amount - $lotDetails->participate_fee,
                'customerVisible' => isset($data['customerVisible']) ?  1 : 0,
            ]);

            lots::where('id', $data['lotid'])->update(['lot_status' => 'sold']);

            // $otherCustomers = DB::select("SELECT * from customer_balances WHERE lotid = " . $data['lotid'] . " and customerId !=" . $lastBid->customerId . "; ");
            // // dd($otherCustomers);
            // foreach ($otherCustomers as $customer) {
            //     customerBalance::create([
            //         'customerId' => $customer->customerId,
            //         'balanceAmount' => $customer->finalAmount,
            //         'action' => 'Participate Fees Back',
            //         'actionAmount' => $lotDetails->participate_fee,
            //         'finalAmount' => $customer->finalAmount + $lotDetails->participate_fee,
            //         'lotid' => $lotDetails->id,
            //         'status' => 0,
            //         'date' => Carbon::today(),
            //     ]);
            // }
            $firebase = (new Factory)
                ->withServiceAccount(__DIR__ . '/lotbids-7751a-firebase-adminsdk-2kxk6-5db00e2535.json')
                ->withDatabaseUri('https://lotbids-7751a-default-rtdb.europe-west1.firebasedatabase.app/');
            $database = $firebase->createDatabase();

            $database->getReference('TodaysLots/liveList/' . $lotDetails->id)->remove();
        }
        return back();
    }

    public function addTimeInLive(Request $request, $lotid)
    {
        $liveLots = DB::select("SELECT lots.* ,categories.title as categoriesTitle FROM `lots` 
        LEFT JOIN categories on categories.id  = lots.categoryId
        WHERE (date(lots.EndDate) = CURDATE()) and lots.lot_status IN ('live','Restart') and lots.id  >= $lotid");

        $firebase = (new Factory)
            ->withServiceAccount(__DIR__ . '/lotbids-7751a-firebase-adminsdk-2kxk6-5db00e2535.json')
            ->withDatabaseUri('https://lotbids-7751a-default-rtdb.europe-west1.firebasedatabase.app/');
        $database = $firebase->createDatabase();

        foreach ($liveLots as $lot) {
            // $lot = lots::where('id', $lot->id)->update(['EndDate' => Carbon::parse($lot->EndDate)->addMinutes(3)]);
            lots::where('id', $lot->id)->update(['EndDate' => Carbon::parse($lot->EndDate)->addMinutes($request->time)]);
            $EndDate = lots::where('id', $lot->id)->pluck('EndDate')->first();
            $database->getReference('TodaysLots/liveList/' . $lot->id . '/EndDate')->set($EndDate);
        }

        return back();
    }
}
