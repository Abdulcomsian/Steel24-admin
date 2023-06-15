<?php

namespace App\Imports;

use App\Models\lots;
use App\Models\materials;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

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
                $lot->description   = $row['description'] ?? 'NULL';
                $lot->uid           = $row['lot_no'] ?? 'NULL';
                $lot->Seller        = $row['seller'] ?? 'NULL';
                $lot->Category      = $row['category'] ?? 'NULL';
                $lot->Plant         = $row['plant'] ?? 'NULL';
                $lot->Quantity      = $row['quantity'] ?? 'NULL';
                $lot->lot_status    = "Pending";
                $lot->StartDate     = (date('Y-m-d H:m:i', strtotime($row['start_date']))) ?? 'NULL';
                $lot->EndDate       = (date('Y-m-d H:m:i', strtotime($row['end_date']))) ?? 'NULL';
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
            $material->title            = $row['seller'] ?? 'NULL';
            $material->uid              = $row['lot_no'] ?? 'NULL';
            $material->thick            = $row['material_location'] ?? 'NULL';
            $material->width            = $row['product'] ?? 'NULL';
            $material->plantNo          = $row['16'] ?? 'NULL';
            $material->qty              = $row['end_date'] ?? 'NULL';
            $material->grade            = $row['quantity'] ?? 'NULL';
            $material->JSWgrade         = $row['description'] ?? 'NULL';
            $material->coilLength       = $row['category'] ?? 'NULL';
            $material->tinTemper        = $row['material'] ?? 'NULL';
            $material->passivation      = $row['14'] ?? 'NULL';
            $material->coldTreatment    = $row['15'] ?? 'NULL';
            $material->storageLocation  = $row['17'] ?? 'NULL';
            $material->plantLotNo       = $row['18'] ?? 'NULL';
            $material->qualityRemark    = $row['24'] ?? 'NULL';
            $material->heatNo           = $row['22'] ?? 'NULL';
            $material->majorDefect      = $row['21'] ?? 'NULL';
            $material->coating          = $row['start_price'] ?? 'NULL';
            $material->eqSpeci          = $row['auction'] ?? 'NULL';
            $material->lot_id           = $lot->id;
            $material->save();
        }        
    }


    public function onFailure(Failure ...$failures)
    {
        // Handle the failures how you'd like.
    }
}
