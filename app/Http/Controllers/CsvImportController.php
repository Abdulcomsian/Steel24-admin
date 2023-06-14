<?php

namespace App\Http\Controllers;

use App\Imports\LotsImport;
use App\Models\lots;
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
        try {
            Excel::import(new LotsImport(), $request->file('csv_file'));
    
            // dd("done");
            // dd($excel_import);
            // if ($request->hasFile('csv_file')) {
            //     $path = $request->file('csv_file')->getRealPath();
            //     // dd($path);
            //     $data = Excel::toArray([], $path);
            //     // dd($data);
            //     if (!empty($data)) {
            //         foreach ($data[0] as $index=>$row) {
            //             if($row[0]=="Lot No")
            //             {
            //                continue;
            //             }
            //             if($index==1)
            //             {
            //                 $start_date = date('Y-m-d H:m:i', strtotime($row[8]));
            //                 $end_date = date('Y-m-d H:m:i', strtotime($row[9]));
            //             }
            //             lots::create([
            //                 'title' => $row[0],
            //                 'Seller' => $row[1],
            //                 'Plant' => $row[2],
            //                 'materialLocation' => $row[3],
            //                 'description' => $row[6],
            //                 'Quantity' => $row[7],
            //                 'StartDate' => $start_date,
            //                 'EndDate' => $end_date,
            //                 'Price' => $row[10],
            //             ]);
            //             // if($index==1){
            //             //  exit;
            //             // }
            //         }
            //     }
                // dd()
            //     $lotsData = lots::all();
    
            //     return view('admin.lots.import-csv', compact('lotsData'));
            // }
    
            return "CSV file is found.";
        } 
        catch (\Throwable $e) 
        {
            return "CSV file is not found.";
        }
    }
}


