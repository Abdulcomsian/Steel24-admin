<?php

namespace App\Imports;

use DateTime;
use Carbon\Carbon;
use App\Models\lots;
use App\Models\materials;
use App\Models\categories;
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
            dd($rows);
            if (is_numeric($row['lot_no']))
            {
                $lot = new lots();
                $lot->title = $row['lot_no'] ?? '';
                $lot->description = $row['description'] ?? '';
                $lot->materialLocation = $row['material_location'] ?? '';
                $lot->Price = $row['start_price'] ?? '';
                $lot->Seller = $row['seller'] ?? '';

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
                $lot->Plant = $row['plant'];
                $lot->Quantity = $row['quantity'];
                $lot->StartDate = (date('Y-m-d H:i:s', strtotime($row['start_date'])));
                $lot->EndDate = (date('Y-m-d H:i:s', strtotime($row['end_date'])));

                // If the category doesn't exist, set the categoryId field
                if (!$category) {
                    $lot->save();
                } else {
                    // Category exists, don't set the categoryId field
                    $lot->saveQuietly();
                }

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
                
                $material = new materials();
                $material->width = $row['4'] ?? '';
                $material->coilLength = $row['5'] ?? '';
                $material->JSWgrade = $row['6'] ?? '';
                $material->grade = $row['7'] ?? '';
                $material->qty = $row['8'] ?? '';
                $material->tinTemper = $row['11'] ?? '';
                $material->passivation = $row['14'] ?? '';
                $material->coldTreatment = $row['15'] ?? '';
                $material->plantNo = $row['16'] ?? '';
                $material->storageLocation = $row['17'] ?? '';
                $material->plantLotNo = $row['18'] ?? '';
                $material->lot_id = $lot->id;
                $material->save();   

            }
            
            // Handle other conditions and continue the loop...
        }
    }

    public function onFailure(Failure ...$failures)
    {
        // Handle the failures how you'd like.
    }
}
