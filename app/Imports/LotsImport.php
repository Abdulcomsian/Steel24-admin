<?php

namespace App\Imports;

use DateTime;
use Carbon\Carbon;
use App\Models\lots;
use App\Models\materials;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class LotsImport implements WithHeadingRow, SkipsOnFailure, ToCollection
{
    use Importable;

    public function collection(Collection $rows)
    {
        foreach ($rows as $key=>$row) 
        {
            // dump($row);
            if(is_numeric($row['lot_no']))
            {
                $lot = new lots();
                $lot->description = $row['description'] ?? '';
                $lot->Seller = $row['seller'] ?? '';
                $lot->Category = $row['category'] ?? '';
                $lot->Plant    = $row['plant'];
                $lot->Quantity    = $row['quantity'];
                $lot->StartDate    = (date('Y-m-d H:m:i', strtotime($row['start_date'])));
                $lot->EndDate    = (date('Y-m-d H:m:i', strtotime($row['end_date'])));
                // $lot->EndDate    = $row['end_date'];
                $lot->save();
            }
        }
        
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    // public function model(array $row)
    // {
    //     if(!array_filter($row)) {
    //         return null;
    //     }  
    //     dump($row);
        // dump($row['lot_no'],is_numeric($row['lot_no']));  
        // dd($row['start_date']);
        // $dtime = strtotime("d-m-y m:i:h", $row['start_date']);
        // $timestamp = $dtime->getTimestamp();
        // dd($dtime);
        // $date = date($row['start_date']);
        // $newDate = Carbon::createFromFormat('Y-m-d H:i:s', $date)
        //                             ->format('m/d/Y');
        // dd($row['start_date']->format('d-m-Y'));
        // if(is_numeric($row['lot_no']))
        // {
        //     $lot = new lots();
        //     $lot->description = $row['description'] ?? '';
        //     $lot->Seller = $row['seller'] ?? '';
        //     $lot->Category = $row['category'] ?? '';
        //     $lot->Plant    = $row['plant'];
        //     $lot->Quantity    = $row['quantity'];
        //     $lot->StartDate    = (date('Y-m-d H:m:i', strtotime($row['start_date'])));
        //     $lot->EndDate    = (date('Y-m-d H:m:i', strtotime($row['end_date'])));
        //     // $lot->EndDate    = $row['end_date'];
        //     $lot->save();
        // }
        // $lots = new lots([
        //     'seller'    => $row['seller'],
        //     'plant'    => $row['plant'],
        //     'material_location'    => $row['material_location'],
        //     'quantity'    => $row['quantity'],
        //     'start_date'    => $row['start_date'],
        //     'end_date'    => $row['end_date'],
        // ]);
        // return $lots;
    // }

    public function onFailure(Failure ...$failures)
    {
        // Handle the failures how you'd like.
    }
}
