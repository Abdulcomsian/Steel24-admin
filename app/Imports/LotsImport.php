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
        foreach ($rows as $row) 
        {
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
                $lot->save();
            }
            if($row['lot_no']=='Batch No')
            {
                continue;
            }
            if($row['lot_no'] == "Lot No")
            {
                continue;
            }
            if(is_numeric($row['lot_no']))
            {
                continue;
                
            }
            if(is_null($row['lot_no']))
            {
                continue;
            }
            
            $material = new materials();
            $material->thick = $row['material_location'] ?? '';
            $material->width = $row['product'] ?? '';
            $material->plantNo = $row['16'] ?? '';
            $material->qty = $row['end_date'] ?? '';
            $material->grade = $row['quantity'] ?? '';
            $material->JSWgrade = $row['description'] ?? '';
            $material->coilLength = $row['category'] ?? '';
            $material->tinTemper = $row['material'] ?? '';
            $material->passivation = $row['14'] ?? '';
            $material->coldTreatment = $row['15'] ?? '';
            $material->storageLocation = $row['17'] ?? '';
            $material->plantLotNo = $row['18'] ?? '';
            $material->lot_id = $lot->id;
            $material->save();
        }        
    }


    public function onFailure(Failure ...$failures)
    {
        // Handle the failures how you'd like.
    }
}
