<?php

namespace App\Http\Controllers;

use App\Models\BidsOfLots;
use App\Models\customerBalance;
use App\Models\lots;
use App\Models\payments;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

use Kreait\Firebase\Factory;

class LiveLotsController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin.auth:admin');
    }

    // Live Lots List
    public function live_index(Request $request)
    {
        $livelots = DB::select("SELECT * FROM `lots` WHERE (date(StartDate) = CURDATE() or date(EndDate) = CURDATE() OR date(ReStartDate) = CURDATE() OR date(ReEndDate) = CURDATE()) and lot_status IN ('live','upcoming','Restart') ORDER BY LiveSequenceNumber;");
        $categories = DB::select("SELECT categories.id, categories.title FROM categories LEFT JOIN lots on lots.categoryId = categories.id WHERE (date(lots.StartDate) = CURDATE() OR date(lots.ReStartDate) = CURDATE()) AND lots.lot_status IN ('live','upcoming','Restart') GROUP by categories.id;");


        return view('admin.lots.liveIndex', compact('categories', 'livelots'));
    }

    // Live Lots Details and Bids
    public function liveLotBids(lots $lots)
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
        return (view('admin.lots.liveLotsDetails', compact('lots', 'lotbids', 'paymentRequest')));
    }

    // Start Lot Make status Live
    public function startLots($id,)
    {
        $lot = lots::where('id', $id)->update(['lot_status' => 'live']);
        payments::where('lotId', $id)->delete();
        $this->pushonfirbase();

        return redirect('/admin/live_lots_bids/' . $id);
    }

    // End Lot Make status end Or Expire
    public function endlot($id)
    {
        lots::where('id', $id)->update(['lot_status' => 'STA']);
        $lastBid = BidsOfLots::where('lotId', $id)->orderBy('id', 'desc')->first();
        $customers = [];
        if ($lastBid) {
            $customers = DB::select("SELECT * from customer_balances WHERE lotid = " . $id . " and customerId !=" . $lastBid->customerId . " and status != 1 ;");
        } else {
            $customers = DB::select("SELECT * from customer_balances WHERE lotid = " . $id . " and status != 1; ");
            lots::where('id', $id)->update(['lot_status' => 'expired']);
        }
        // dd($customers);
        $lotDetails = lots::where('id', $id)->get();
        foreach ($customers as $customer) {
            customerBalance::where(['id' => $customer->id])->update(['status' => 1]);
            customerBalance::create([
                'customerId' => $customer->customerId,
                'balanceAmount' => $customer->finalAmount,
                'action' => 'Participate Fees Back',
                'actionAmount' => $lotDetails[0]->participate_fee,
                'finalAmount' => $customer->finalAmount + $lotDetails[0]->participate_fee,
                'lotid' => $lotDetails[0]->id,
                'status' => 1,
                'date' => Carbon::today(),
            ]);
        }
        // customerBalance::where(['lotid' => $id, 'action' => 'Participate Fees'])->update(['status' => 1]);
        // dd($lastBid->customerId);
        // if ($lastBid) {
        //     customerBalance::where(['lotid' => $id, 'customerId' != $lastBid->customerId])->update(['status' => 1]);
        // } else {
        //     customerBalance::where(['lotid' => $id])->update(['status' => 1]);
        // }

        $firebase = (new Factory)
            ->withServiceAccount(json_encode([
                "type" => "service_account",
                "project_id" => "steel24-a898f",
                "private_key_id" => "154e3c7d3ecb2b8fc1245ce9955d87ba8084ce77",
                "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCJOy/8yIPhlpv2\nwGNffZVU8vTSNYwEqUDy6aHF/TLcJsHnoLfAcMdXvts0Cq4vIOw3FRRQrVq70bIa\n7dIQcyXkHvb8z/lsvvb/cI4VMpW3EgWQ+bo8m4DYY4kWDqGxs5stD119G14/q9iX\nCDhSiRbetygKGH5zxXJllsa5MFKAXNj9QD7mksJllEZV4Q/d+2z4HkfxggTK/KZy\nlEU1scb4P/U1mWukY4C/LgugJicQhIMtGDt6PaFHm5ZssQ2vZ2lumuMhnRIzQ/e9\nWkPbTJESeUoprZHJxPfSPbtRika9NYp3/YntHh1Y3aszGpzLx/VOeBmKo5SWIjOs\nSUj2gY3fAgMBAAECggEABsJfjrfhpw7gB7taKa3p2RFOdbwldWVQyaYwTaw3ARj3\nnA0Sf+wOJYhFC78q7S9V8zCam46uVWnyt9jW6/CAAUh1KeakhnKxf8tvdCPVs/qz\nQ3zJa4rNQdtFOUznMfWCwylqlWrvrXstY+MHwyj1c2raEgU61UD4bYCLsTtsFN5r\naXTa9NLEaExb+5PIaubtE9uJHESn/XJhTBXEhT8dt6YFr6jPBil7Ak4wJqsr+82l\nMILMSbPGl9F3Hd/LP+WRxPvjrHSU6U46vocZhEHCohMx08srMsl4NO6vt57Ty19h\nCaakYiM4PciJTwVl24yqrP4oszexdggIRzkT+0wJAQKBgQDACgw3LLMID+AP8tHZ\nnQwmxJ1YiWbhUliORGzCsontc9hz9N57BBE2GwqGAEmD+RaNFllbt1aGg+v3dFMl\nycKIc83Q4c+J85X2tNwXh94niQLF5Wl2lHgdioTSh8WNSZVh/0cekN7wh8zJBRKY\nkT59J7HM92gDrABYuGUDaofD/wKBgQC28AKCrQxb4EUpYsLC42fSMY066+5js3Tt\neguLyj4jNhIQ0nSe3TBBdWlvCJl+Drw8qKt1xM0RmNsqSLYBTK1PzkITLG6S4nvg\n7vf713Q116wipE5WX8FakMWL+cjlDdy3JAoBWHi4og4ZuqoMZr+JyM9dR2KH5UFl\nARuIIla2IQKBgQCC8BbmI98qNxDSLwEwfGlFobebH4x7Q5dH4ZW6pttugRdr8OEl\nRV+q4YMqXNXDWzoqFrv00iv36ckhXzo2QLwYJ8WEkALfD6wHm8eZb7VkhYHThxmC\nlbbUhZcMqTBkpnBpchJ+385yeFWEFqZYSmguE7uigmp0XnmaBJgzXRaW5wKBgBdf\nQKLbYwnV9GAeOw3VKe2D4SxW+kUIp3azsgfxFdE/1j0J9lZZohGq44aJDbs6PLhv\nQECynRSTd+TGF2LBHh9lFbIHajUf9H2/ajVlyHYckOR4I34Li9N7TZHdntoM1Fcd\npp2XZQ0Jv01wOMuO0QfUfRHIzgDYvGsgIhlZccShAoGAdctj7iBKhoTC3KecFUxy\nNHS1D+x/1VuZgUN1mKRhWjhQV1CyR8ao7O7om1at6IRJ8a3O7u2CZMdIB21pgBE2\n4lsMScCSl5Fr5UEMNuEELK3tMfuEVSt709HrtVrnzKU+LABQMJYrACWA3n6kUTRx\nFGWSMAzCBURToznCQEl88yc=\n-----END PRIVATE KEY-----\n",
                "client_email" => "firebase-adminsdk-1jmgy@steel24-a898f.iam.gserviceaccount.com",
                "client_id" => "106107898058399972491",
                "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
                "token_uri" => "https://oauth2.googleapis.com/token",
                "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
                "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-1jmgy%40steel24-a898f.iam.gserviceaccount.com"

            ]))->withDatabaseUri('https://steel24-a898f-default-rtdb.firebaseio.com/');
        $database = $firebase->createDatabase();
        $database->getReference('TodaysLots/liveList/' . $id)->remove();
        // $this->pushonfirbase();
        return redirect('/admin/live_lots_bids/' . $id);
    }

    // End Lot Make status end Or Expire
    public function expireLot($id)
    {
        $lastBid = BidsOfLots::where('lotId', $id)->orderBy('id', 'desc')->first();

        $customers = DB::select("SELECT * from customer_balances WHERE lotid = " . $id . " and status != 1; ");
        lots::where('id', $id)->update(['lot_status' => 'expired']);
        $lotDetails = lots::where('id', $id)->get();
        // dd($customers);
        foreach ($customers as $customer) {
            customerBalance::where(['id' => $customer->id])->update(['status' => 1]);
            customerBalance::create([
                'customerId' => $customer->customerId,
                'balanceAmount' => $customer->finalAmount,
                'action' => 'Participate Fees Back',
                'actionAmount' => $lotDetails[0]->participate_fee,
                'finalAmount' => $customer->finalAmount + $lotDetails[0]->participate_fee,
                'lotid' => $lotDetails[0]->id,
                'status' => 1,
                'date' => Carbon::today(),
            ]);
        }
        customerBalance::where('lotid', $id)->update(['status' => 1]);


        $firebase = (new Factory)
            ->withServiceAccount(json_encode([
                "type" => "service_account",
                "project_id" => "steel24-a898f",
                "private_key_id" => "154e3c7d3ecb2b8fc1245ce9955d87ba8084ce77",
                "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCJOy/8yIPhlpv2\nwGNffZVU8vTSNYwEqUDy6aHF/TLcJsHnoLfAcMdXvts0Cq4vIOw3FRRQrVq70bIa\n7dIQcyXkHvb8z/lsvvb/cI4VMpW3EgWQ+bo8m4DYY4kWDqGxs5stD119G14/q9iX\nCDhSiRbetygKGH5zxXJllsa5MFKAXNj9QD7mksJllEZV4Q/d+2z4HkfxggTK/KZy\nlEU1scb4P/U1mWukY4C/LgugJicQhIMtGDt6PaFHm5ZssQ2vZ2lumuMhnRIzQ/e9\nWkPbTJESeUoprZHJxPfSPbtRika9NYp3/YntHh1Y3aszGpzLx/VOeBmKo5SWIjOs\nSUj2gY3fAgMBAAECggEABsJfjrfhpw7gB7taKa3p2RFOdbwldWVQyaYwTaw3ARj3\nnA0Sf+wOJYhFC78q7S9V8zCam46uVWnyt9jW6/CAAUh1KeakhnKxf8tvdCPVs/qz\nQ3zJa4rNQdtFOUznMfWCwylqlWrvrXstY+MHwyj1c2raEgU61UD4bYCLsTtsFN5r\naXTa9NLEaExb+5PIaubtE9uJHESn/XJhTBXEhT8dt6YFr6jPBil7Ak4wJqsr+82l\nMILMSbPGl9F3Hd/LP+WRxPvjrHSU6U46vocZhEHCohMx08srMsl4NO6vt57Ty19h\nCaakYiM4PciJTwVl24yqrP4oszexdggIRzkT+0wJAQKBgQDACgw3LLMID+AP8tHZ\nnQwmxJ1YiWbhUliORGzCsontc9hz9N57BBE2GwqGAEmD+RaNFllbt1aGg+v3dFMl\nycKIc83Q4c+J85X2tNwXh94niQLF5Wl2lHgdioTSh8WNSZVh/0cekN7wh8zJBRKY\nkT59J7HM92gDrABYuGUDaofD/wKBgQC28AKCrQxb4EUpYsLC42fSMY066+5js3Tt\neguLyj4jNhIQ0nSe3TBBdWlvCJl+Drw8qKt1xM0RmNsqSLYBTK1PzkITLG6S4nvg\n7vf713Q116wipE5WX8FakMWL+cjlDdy3JAoBWHi4og4ZuqoMZr+JyM9dR2KH5UFl\nARuIIla2IQKBgQCC8BbmI98qNxDSLwEwfGlFobebH4x7Q5dH4ZW6pttugRdr8OEl\nRV+q4YMqXNXDWzoqFrv00iv36ckhXzo2QLwYJ8WEkALfD6wHm8eZb7VkhYHThxmC\nlbbUhZcMqTBkpnBpchJ+385yeFWEFqZYSmguE7uigmp0XnmaBJgzXRaW5wKBgBdf\nQKLbYwnV9GAeOw3VKe2D4SxW+kUIp3azsgfxFdE/1j0J9lZZohGq44aJDbs6PLhv\nQECynRSTd+TGF2LBHh9lFbIHajUf9H2/ajVlyHYckOR4I34Li9N7TZHdntoM1Fcd\npp2XZQ0Jv01wOMuO0QfUfRHIzgDYvGsgIhlZccShAoGAdctj7iBKhoTC3KecFUxy\nNHS1D+x/1VuZgUN1mKRhWjhQV1CyR8ao7O7om1at6IRJ8a3O7u2CZMdIB21pgBE2\n4lsMScCSl5Fr5UEMNuEELK3tMfuEVSt709HrtVrnzKU+LABQMJYrACWA3n6kUTRx\nFGWSMAzCBURToznCQEl88yc=\n-----END PRIVATE KEY-----\n",
                "client_email" => "firebase-adminsdk-1jmgy@steel24-a898f.iam.gserviceaccount.com",
                "client_id" => "106107898058399972491",
                "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
                "token_uri" => "https://oauth2.googleapis.com/token",
                "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
                "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-1jmgy%40steel24-a898f.iam.gserviceaccount.com"

            ]))->withDatabaseUri('https://steel24-a898f-default-rtdb.firebaseio.com/');
        $database = $firebase->createDatabase();
        $database->getReference('TodaysLots/liveList/' . $id)->remove();
        // $this->pushonfirbase();
        return redirect('/admin/live_lots_bids/' . $id);
    }


    // Pause Lot Make status Pause
    public function poseLots($id,)
    {
        $lot = lots::where('id', $id)->update(['lot_status' => 'pause']);
        $this->pushonfirbase();
        return redirect('/admin/live_lots_bids/' . $id);
    }

    // Restart Expired Lots
    public function reStartExpirelot(Request $request)
    {
        // dd($request);

        $requestData = $request->validate([
            'lotid' => 'required',
            'ReStartDate' => 'required',
            'ReEndDate' => 'required',

        ]);
        lots::where('id', $requestData['lotid'])->update(
            [
                'StartDate' => $requestData['ReStartDate'],
                'EndDate' => $requestData['ReEndDate'],
                'lot_status' => 'Restart'
            ]
            // [
            //     'StartDate' => Carbon::now(),
            //     'EndDate' => Carbon::now()->addHour()->toDateTimeString(),
            //     'lot_status' => 'Restart'
            // ]

        );
        payments::where('lotId',  $requestData['lotid'])->delete();
        $this->pushonfirbase();
        return redirect('admin/live_lots_bids/' . $requestData['lotid']);
    }

    public function LiveLotSequenceChange(Request $request)
    {
        $livelots = DB::select("SELECT * FROM `lots` WHERE date(StartDate) = CURDATE() or date(EndDate) = CURDATE() OR date(ReStartDate) = CURDATE() OR date(ReEndDate) = CURDATE() ORDER BY LiveSequenceNumber;");

        foreach ($livelots as $lot) {
            lots::where('id', $lot->id)->update(['LiveSequenceNumber' => $request[$lot->id]]);
        }
        $this->pushonfirbase();

        return redirect('/admin/live_lots');
    }

    public static function pushonfirbase()
    {
        $liveLots = DB::select("SELECT lots.* ,categories.title as categoriesTitle FROM `lots` 
        LEFT JOIN categories on categories.id  = lots.categoryId
        WHERE (date( lots.StartDate) = CURDATE() or date(lots.EndDate) = CURDATE()) and lots.lot_status  IN ('live','ReStart');");

        $liveLotslist = [];
        foreach ($liveLots as $lot) {
            $templot = $lot;
            $templot->ParticipateUsers = customerBalance::where([['lotId', $lot->id], ['status', '!=', '1'], ['action', '!=', 'Participate Fees Back']])->groupBy('customerId')->pluck('customerId')->toArray();

            $lastBid =  DB::select('SELECT bids_of_lots.*, customers.id as customerId,customers.name as customerName       
        FROM bids_of_lots
        LEFT JOIN customers on customers.id = bids_of_lots.customerId
        WHERE lotId =' . $lot->id . ' and bids_of_lots.id =  (SELECT max(id) from bids_of_lots WHERE lotid = ' . $lot->id . ') ORDER BY bids_of_lots.amount DESC  LIMIT 1;');
            $templot->lastBid =  array_pop($lastBid);

            $liveLotslist[$lot->id] = $templot;
        }
        $upcoming = DB::select("SELECT lots.* ,categories.title as categoriesTitle FROM `lots` 
        LEFT JOIN categories on categories.id  = lots.categoryId
        WHERE  lot_status =  'upcoming';");
        $lotList = ['liveList' => $liveLotslist, 'upcoming' => $upcoming];

        $firebase = (new Factory)
            ->withServiceAccount(json_encode([
                "type" => "service_account",
                "project_id" => "steel24-a898f",
                "private_key_id" => "154e3c7d3ecb2b8fc1245ce9955d87ba8084ce77",
                "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCJOy/8yIPhlpv2\nwGNffZVU8vTSNYwEqUDy6aHF/TLcJsHnoLfAcMdXvts0Cq4vIOw3FRRQrVq70bIa\n7dIQcyXkHvb8z/lsvvb/cI4VMpW3EgWQ+bo8m4DYY4kWDqGxs5stD119G14/q9iX\nCDhSiRbetygKGH5zxXJllsa5MFKAXNj9QD7mksJllEZV4Q/d+2z4HkfxggTK/KZy\nlEU1scb4P/U1mWukY4C/LgugJicQhIMtGDt6PaFHm5ZssQ2vZ2lumuMhnRIzQ/e9\nWkPbTJESeUoprZHJxPfSPbtRika9NYp3/YntHh1Y3aszGpzLx/VOeBmKo5SWIjOs\nSUj2gY3fAgMBAAECggEABsJfjrfhpw7gB7taKa3p2RFOdbwldWVQyaYwTaw3ARj3\nnA0Sf+wOJYhFC78q7S9V8zCam46uVWnyt9jW6/CAAUh1KeakhnKxf8tvdCPVs/qz\nQ3zJa4rNQdtFOUznMfWCwylqlWrvrXstY+MHwyj1c2raEgU61UD4bYCLsTtsFN5r\naXTa9NLEaExb+5PIaubtE9uJHESn/XJhTBXEhT8dt6YFr6jPBil7Ak4wJqsr+82l\nMILMSbPGl9F3Hd/LP+WRxPvjrHSU6U46vocZhEHCohMx08srMsl4NO6vt57Ty19h\nCaakYiM4PciJTwVl24yqrP4oszexdggIRzkT+0wJAQKBgQDACgw3LLMID+AP8tHZ\nnQwmxJ1YiWbhUliORGzCsontc9hz9N57BBE2GwqGAEmD+RaNFllbt1aGg+v3dFMl\nycKIc83Q4c+J85X2tNwXh94niQLF5Wl2lHgdioTSh8WNSZVh/0cekN7wh8zJBRKY\nkT59J7HM92gDrABYuGUDaofD/wKBgQC28AKCrQxb4EUpYsLC42fSMY066+5js3Tt\neguLyj4jNhIQ0nSe3TBBdWlvCJl+Drw8qKt1xM0RmNsqSLYBTK1PzkITLG6S4nvg\n7vf713Q116wipE5WX8FakMWL+cjlDdy3JAoBWHi4og4ZuqoMZr+JyM9dR2KH5UFl\nARuIIla2IQKBgQCC8BbmI98qNxDSLwEwfGlFobebH4x7Q5dH4ZW6pttugRdr8OEl\nRV+q4YMqXNXDWzoqFrv00iv36ckhXzo2QLwYJ8WEkALfD6wHm8eZb7VkhYHThxmC\nlbbUhZcMqTBkpnBpchJ+385yeFWEFqZYSmguE7uigmp0XnmaBJgzXRaW5wKBgBdf\nQKLbYwnV9GAeOw3VKe2D4SxW+kUIp3azsgfxFdE/1j0J9lZZohGq44aJDbs6PLhv\nQECynRSTd+TGF2LBHh9lFbIHajUf9H2/ajVlyHYckOR4I34Li9N7TZHdntoM1Fcd\npp2XZQ0Jv01wOMuO0QfUfRHIzgDYvGsgIhlZccShAoGAdctj7iBKhoTC3KecFUxy\nNHS1D+x/1VuZgUN1mKRhWjhQV1CyR8ao7O7om1at6IRJ8a3O7u2CZMdIB21pgBE2\n4lsMScCSl5Fr5UEMNuEELK3tMfuEVSt709HrtVrnzKU+LABQMJYrACWA3n6kUTRx\nFGWSMAzCBURToznCQEl88yc=\n-----END PRIVATE KEY-----\n",
                "client_email" => "firebase-adminsdk-1jmgy@steel24-a898f.iam.gserviceaccount.com",
                "client_id" => "106107898058399972491",
                "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
                "token_uri" => "https://oauth2.googleapis.com/token",
                "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
                "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-1jmgy%40steel24-a898f.iam.gserviceaccount.com"

            ]))->withDatabaseUri('https://steel24-a898f-default-rtdb.firebaseio.com/');
        $database = $firebase->createDatabase();
        $database->getReference('TodaysLots/')->set($lotList);

        return back();
    }


    public function categorieLots($id)
    {
        $livelots = DB::select("SELECT * FROM `lots` WHERE (date(StartDate) = CURDATE() or date(EndDate) = CURDATE()) and lot_status IN ('live','upcoming','Restart') AND categoryId = $id ORDER BY LiveSequenceNumber;");
        $categories = DB::select("SELECT categories.id, categories.title FROM categories LEFT JOIN lots on lots.categoryId = categories.id WHERE (date(lots.StartDate) = CURDATE() OR date(lots.ReStartDate) = CURDATE()) AND lots.lot_status IN ('live','upcoming','Restart') GROUP by categories.id;");


        return view('admin.lots.liveIndex', compact('categories', 'livelots'));
    }
}
