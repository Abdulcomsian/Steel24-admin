<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\lots;
use App\Models\new_maerials_2;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;



// class LotsExport implements FromCollection, WithHeadings
// {
//     use Exportable;

//     protected $lots;

//     public function __construct($lots)
//     {
//         $this->lots = $lots;
//     }

//         public function headings(): array
//     {
//         return [
//             // Live lot columns
//             'title',
//             'description',
//             'categoryId',
//             'uid',
//             'Seller',
//             'Plant',
//             'materialLocation',
//             'Quantity',
//             'Payment_terms',
//             'StartDate',
//             'EndDate',
//             'Price',
//             'auction_status',
//             'lot_status',
//             'customFields',
//             'participate_fee',
//             'ReStartDate',
//             'ReEndDate',
//             'LiveSequenceNumber',
//             'status',
//             // Materials columns
//             'lotid',
//             'Product',
//             'Thickness',
//             'Width',
//             'Length',
//             'Weight',
//             'Grade',
//             'Remark',
//             'images',
//         ];
//     }

//     public function collection()
//     {
//         $data = [];

//         foreach ($this->lots as $lot) {
//             foreach ($lot->new_maerials_2 as $material) {
//                 $data[] = [
//                     // Live lot data
//                     $lot->title,
//                     $lot->description,
//                     $lot->categoryId,
//                     $lot->uid,
//                     $lot->Seller,
//                     $lot->Plant,
//                     $lot->materialLocation,
//                     $lot->Quantity,
//                     $lot->Payment_terms,
//                     $lot->StartDate,
//                     $lot->EndDate,
//                     $lot->Price,
//                     $lot->auction_status,
//                     $lot->lot_status,
//                     $lot->customFields,
//                     $lot->participate_fee,
//                     $lot->ReStartDate,
//                     $lot->ReEndDate,
//                     $lot->LiveSequenceNumber,
//                     $lot->status,
//                     // Material data
//                     $material->lotid,
//                     $material->Product,
//                     $material->Thickness,
//                     $material->Width,
//                     $material->Length,
//                     $material->Weight,
//                     $material->Grade,
//                     $material->Remark,
//                     $material->images,
//                 ];
//             }
//         }

//         return collect($data);
//     }

// }





// class LotsExport implements FromCollection, WithHeadings, WithCustomStartCell
// {
//     use Exportable;

//     protected $lots;

//     public function __construct($lots)
//     {
//         $this->lots = $lots;
//     }

//     public function collection()
//     {
//         $data = new Collection();

//         // Add header rows
//         $data->push(['Steelemart Auction List - Coated by JSW-COATED - 37 Lots']);
//         $data->push(['Report Date: ' . now()->format('D d M Y h:i a')]);
//         $data->push([]);

//         // Add column headers
//         $data->push([
//             'Lot No', 'Seller', 'Plant', 'Material Location', 'Product', 'Category', 'Description',
//             'Quantity', 'Start Date', 'End Date', 'Start Price', 'Material', 'Auction'
//         ]);

//         foreach ($this->lots as $lot) {
//             $lotRow = [
//                 $lot->id,
//                 $lot->Seller,
//                 $lot->Plant,
//                 $lot->materialLocation,
//                 $lot->Product,
//                 $lot->Category,
//                 $lot->Description,
//                 $lot->Quantity,
//                 Carbon::parse($lot->StartDate)->format('d-M-y h:ia'), // Convert and format StartDate
//                 Carbon::parse($lot->EndDate)->format('d-M-y h:ia'),   // Convert and format EndDate
//                 $lot->Price,
//                 'Ex-Stock',
//                 'Bid n Win'
//             ];
//             $data->push($lotRow);

//             foreach ($lot->new_maerials_2 as $material) {
//                 $materialRow = [
//                     'Batch No',
//                     $material->Product,
//                     $material->ProductCode,
//                     $material->Thk,
//                     $material->Width,
//                     $material->CoilLength,
//                     $material->JSWGrade,
//                     $material->Grade,
//                     $material->Qty,
//                     $material->MajorDefect,
//                     $material->Coating,
//                     $material->TinTemper,
//                     $material->EqSpeci,
//                     $material->SpangleType,
//                     $material->Passivation,
//                     $material->ColdTreatment,
//                     $material->PlantNo,
//                     $material->Remark,
//                     $material->StorageLocation,
//                     $material->PlantLotNo,
//                 ];
//                 $data->push($materialRow);
//             }
//         }

//         return $data;
//     }

//     public function headings(): array
//     {
//         return [];
//     }

//     public function startCell(): string
//     {
//         return 'A1';
//     }
// }




// class LotsExport implements FromCollection, WithHeadings, WithCustomStartCell
// {
//     use Exportable;

//     protected $lots;

//     public function __construct($lots)
//     {
//         $this->lots = $lots;
//     }

//     public function collection()
//     {
//         $data = new Collection();

//         foreach ($this->lots as $index => $lot) {
//             if ($index > 0) {
//                 $data->push([]); // Add a blank row between lots
//             }

//             // Add header rows
//             $data->push(['Steelemart Auction List - Coated by JSW-COATED - 37 Lots']);
//             $data->push(['Report Date: ' . now()->format('D d M Y h:i a')]);
//             $data->push([]);

//             // Add column headers
//             $data->push([
//                 'Lot No', 'Seller', 'Plant', 'Material Location', 'Product', 'Category', 'Description',
//                 'Quantity', 'Start Date', 'End Date', 'Start Price', 'Material', 'Auction'
//             ]);

//             $lotRow = [
//                 $lot->id,
//                 $lot->Seller,
//                 $lot->Plant,
//                 $lot->materialLocation,
//                 $lot->Product,
//                 $lot->Category,
//                 $lot->Description,
//                 $lot->Quantity,
//                 Carbon::parse($lot->StartDate)->format('d-M-y h:ia'), // Convert and format StartDate
//                 Carbon::parse($lot->EndDate)->format('d-M-y h:ia'),   // Convert and format EndDate
//                 $lot->Price,
//                 'Ex-Stock',
//                 'Bid n Win'
//             ];
//             $data->push($lotRow);

//             foreach ($lot->new_maerials_2 as $material) {
//                 $materialRow = [
//                     'Batch No',
//                     $material->Product,
//                     $material->ProductCode,
//                     $material->Thk,
//                     $material->Width,
//                     $material->CoilLength,
//                     $material->JSWGrade,
//                     $material->Grade,
//                     $material->Qty,
//                     $material->MajorDefect,
//                     $material->Coating,
//                     $material->TinTemper,
//                     $material->EqSpeci,
//                     $material->SpangleType,
//                     $material->Passivation,
//                     $material->ColdTreatment,
//                     $material->PlantNo,
//                     $material->Remark,
//                     $material->StorageLocation,
//                     $material->PlantLotNo,
//                 ];
//                 $data->push($materialRow);
//             }
//         }

//         return $data;
//     }

//     public function headings(): array
//     {
//         return [];
//     }

//     public function startCell(): string
//     {
//         return 'A1';
//     }
// }


class LotsExport implements FromCollection, WithHeadings, WithCustomStartCell
{
    use Exportable;

    protected $lots;

    public function __construct($lots)
    {
        $this->lots = $lots;
    }

    public function collection()
    {
        $data = new Collection();

        foreach ($this->lots as $index => $lot) {
            if ($index > 0) {
                $data->push([]); 
            }

            // Add header rows
            $data->push(['Steelemart Auction List - Coated by JSW-COATED - 37 Lots']);
            $data->push(['Report Date: ' . now()->format('D d M Y h:i a')]);
            $data->push([]);

            // Add column headers
            $data->push([
                'Lot No', 'Seller', 'Plant', 'Material Location', 'Product', 'Category', 'Description',
                'Quantity', 'Start Date', 'End Date', 'Start Price', 'Material', 'Auction'
            ]);

            $lotRow = [
                $lot->id,
                $lot->Seller,
                $lot->Plant,
                $lot->materialLocation,
                $lot->Product,
                $lot->Category,
                $lot->Description,
                $lot->Quantity,
                Carbon::parse($lot->StartDate)->format('d-M-y h:ia'), 
                Carbon::parse($lot->EndDate)->format('d-M-y h:ia'), 
                $lot->Price,
                'Ex-Stock',
                'Bid n Win'
            ];
            $data->push($lotRow);

            $data->push([]);

            foreach ($lot->new_maerials_2 as $material) 
            {

                $materialRow = [
                    'Batch No',
                    $material->Product,
                    $material->ProductCode,
                    $material->Thk,
                    $material->Width,
                    $material->CoilLength,
                    $material->JSWGrade,
                    $material->Grade,
                    $material->Quantity,
                    $material->MajorDefect,
                    $material->Coating,
                    $material->TinTemper,
                    $material->EqSpeci,
                    $material->SpangleType,
                    $material->Passivation,
                    $material->ColdTreatment,
                    $material->PlantNo,
                    $material->Remark,
                    $material->StorageLocation,
                    $material->PlantLotNo,
                ];
                $data->push($materialRow);

                // dd($materialRow);
            }
        }

        return $data;
    }

    public function headings(): array
    {
        return [];
    }

    public function startCell(): string
    {
        return 'A1';
    }
}

