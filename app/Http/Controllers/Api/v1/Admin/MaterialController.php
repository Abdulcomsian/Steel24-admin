<?php

namespace App\Http\Controllers\Api\V1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\materials;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use \Illuminate\Support\Carbon;
use \Illuminate\Support\Facades\DB;
class MaterialContoller extends Controller
{
    private $user, $defaultNumber;
    public function getMaterials(Request $request)
    {
       $materials = materials::get();
    //    $orderList = DB::table('lots')
    // ->join('lot_materials', 'lots.id', '=', 'lot_materials.lots_id')
    // ->join('materials', 'materials.id', '=', 'lot_materials.materials_id')
    // ->where('lots.id', '=', 5)
    // ->get();

    return response()->json(["msg"=>"get Lots successfully","Materials"=>$materials]);

    //    $start = Carbon::parse($request->auction_date);

    //    $get_all_user = Auction::whereDate('date','<=',$end->format('m-d-y'))
    //    ->whereDate('date','>=',$start->format('m-d-y'));


    }
}
