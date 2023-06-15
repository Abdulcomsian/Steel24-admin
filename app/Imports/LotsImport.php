<?php

namespace App\Imports;

use App\Models\lots;
use App\Models\materials;
use App\Models\categories;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LotsImport implements WithHeadingRow, SkipsOnFailure, ToCollection
{
    use Importable, SkipsFailures;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            if($row->filter()->isNotEmpty())
            {
                if (is_numeric($row['lot_no']))
                {
                    $lot = new lots();

                    $lot->description = $row['description'] ?? 'NULL';
                    $lot->title = $row['lot_no'] ?? 'NULL';
                    $lot->Seller = $row['seller'] ?? 'NULL';
                    // $lot->Category      = $row['category'] ?? 'NULL';
                    $lot->Plant = $row['plant'] ?? 'NULL';
                    $lot->Quantity = $row['quantity'] ?? 'NULL';
                    $lot->lot_status = "Pending";
                    $lot->StartDate = (date('Y-m-d H:m:i', strtotime($row['start_date']))) ?? 'NULL';
                    $lot->EndDate = (date('Y-m-d H:m:i', strtotime($row['end_date']))) ?? 'NULL';
                    $lot->materialLocation = $row['material_location'] ?? '';
                    $lot->Price = $row['start_price'] ?? '';
                    // Check if the category exists
                    $category = categories::where('title', $row['category'])->first();

                    if (!$category) {
                        // Category does not exist, create a new one
                        $category = new categories();
                        $category->title = $row['category'];
                        // Set other category fields here
                        $category->save();
                    
                    }
                    $lot->categoryId = $category->id;

                    // If the category doesn't exist, set the categoryId field
                    if (!$category) {
                        $lot->save();
                    } else {
                        // Category exists, don't set the categoryId field
                        $lot->saveQuietly();
                    }
                    // $lot->save();
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

                    // $lot->title = $row['lot_no'] ?? '';
                    // $lot->description = $row['description'] ?? '';
                    // $lot->materialLocation = $row['material_location'] ?? '';
                    // $lot->Price = $row['start_price'] ?? '';
                    // $lot->Seller = $row['seller'] ?? '';

                

                    // previous code ither dev
                    // if($row['lot_no']=='Batch No')
                    // {
                    //     continue;
                    // }
                    // if($row['lot_no'] == "Lot No")
                    // {
                    //     continue;
                    // }
                    // if(is_numeric($row['lot_no']))
                    // {
                    //     continue;
                        
                    // }
                    // if(is_null($row['lot_no']))
                    // {
                    //     continue;
                    // }


                    // $material = new materials();
                    // $material->width = $row['product'] ?? '';
                    // $material->plantNo = $row['16'] ?? '';
                    // $material->qty = $row['end_date'] ?? '';
                    // $material->grade = $row['quantity'] ?? '';
                    // $material->JSWgrade = $row['description'] ?? '';
                    // $material->coilLength = $row['category'] ?? '';
                    // $material->tinTemper = $row['material'] ?? '';
                    // $material->passivation = $row['14'] ?? '';
                    // $material->coldTreatment = $row['15'] ?? '';
                    // $material->storageLocation = $row['17'] ?? '';
                    // $material->plantLotNo = $row['18'] ?? '';
                    // $material->lot_id = $lot->id;
                    // $material->save();

                    // Updated code with indexes 
                    
                    // $material = new materials();
                    // $material->width = $row['4'] ?? '';
                    // $material->coilLength = $row['5'] ?? '';
                    // $material->JSWgrade = $row['6'] ?? '';
                    // $material->grade = $row['7'] ?? '';
                    // $material->qty = $row['8'] ?? '';
                    // $material->tinTemper = $row['11'] ?? '';
                    // $material->passivation = $row['14'] ?? '';
                    // $material->coldTreatment = $row['15'] ?? '';
                    // $material->plantNo = $row['16'] ?? '';
                    // $material->storageLocation = $row['17'] ?? '';
                    // $material->plantLotNo = $row['18'] ?? '';
                    // $material->lot_id = $lot->id;
                    // $material->save();   

                // }

                $material = new materials();
                $material->title = $row['seller'] ?? 'NULL';
                $material->uid = $row['lot_no'] ?? 'NULL';
                $material->thick = $row['material_location'] ?? 'NULL';
                $material->width = $row['product'] ?? 'NULL';
                $material->plantNo = $row['16'] ?? 'NULL';
                $material->qty = $row['start_date'] ?? 'NULL';
                $material->grade = $row['quantity'] ?? 'NULL';
                $material->JSWgrade = $row['description'] ?? 'NULL';
                $material->coilLength = $row['category'] ?? 'NULL';
                $material->tinTemper = $row['material'] ?? 'NULL';
                $material->passivation = $row['14'] ?? 'NULL';
                $material->coldTreatment = $row['15'] ?? 'NULL';
                $material->storageLocation = $row['17'] ?? 'NULL';
                $material->plantLotNo = $row['18'] ?? 'NULL';
                $material->qualityRemark = $row['24'] ?? 'NULL';
                $material->heatNo = $row['22'] ?? 'NULL';
                $material->majorDefect = $row['21'] ?? 'NULL';
                $material->coating = $row['start_price'] ?? 'NULL';
                $material->eqSpeci = $row['auction'] ?? 'NULL';
                $material->lot_id = $lot->id;
                $material->save();
                    }
        }
            
            // Handle other conditions and continue the loop...
        // }
    }

    public function onFailure(Failure ...$failures)
    {
        // Handle the failures how you'd like.
    }
}
