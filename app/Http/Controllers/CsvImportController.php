<?php

namespace App\Http\Controllers;

use App\Models\Lots;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CsvImportController extends Controller
{
    public function showForm()
    {
        return view('admin.lots.import-csv');
    }

    public function import(Request $request)
    {
        if ($request->hasFile('csv_file')) {
            $path = $request->file('csv_file')->getRealPath();

            $data = Excel::toArray([], $path);

            if (!empty($data)) {
                foreach ($data[0] as $index=>$row) {
                    if($row[0]=="Lot No")
                    {
                       continue;
                    }
                    if($index==1)
                    {
                        // dd( $row[8]);
                        $start_date = date('Y-m-d H:m:i', strtotime($row[8]));
                        $end_date = date('Y-m-d H:m:i', strtotime($row[9]));
                    }
                    lots::create([
                        'title' => $row[0],
                        'Seller' => $row[1],
                        'Plant' => $row[2],
                        'materialLocation' => $row[3],
                        'description' => $row[6],
                        'Quantity' => $row[7],
                        'StartDate' => $start_date,
                        'EndDate' => $end_date,
                        'Price' => $row[10],
                    ]);
                    if($index==1){
                     exit;
                    }
                }
            }

            $lotsData = lots::all();

            return view('admin.lots.import-csv', compact('lotsData'));
        }

        return "No CSV file found.";
    }
}
