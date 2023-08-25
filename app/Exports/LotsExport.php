<?php

namespace App\Exports;

use App\Models\lots;
use App\Models\new_maerials_2;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;


// class LotsExport implements FromCollection, WithHeadings, ShouldAutoSize
// {
//     use Exportable;

//     public function collection()
//     {
//         return lots::where('lot_status', 'live')->get();
//     }

//     public function headings(): array
//     {
//         return [
//             'id',
//             'title', 
//             'description', 
//             // 'categoryId',
//             // 'uid',
//             'materialLocation', 
//             'Seller',
//             'Plant',
//             'Quantity',
//             'Payment_terms',
//             'StartDate',
//             'EndDate',
//             'Price',
//             // 'auction_status',
//             'lot_status',
//             'customFields',
//             'participate_fee',
//             'ReStartDate',
//             'ReEndDate',
//             'LiveSequenceNumber',
//             'status'
//         ];
//     }
// }

// class LotsExport implements FromCollection, WithHeadings, ShouldAutoSize
// {
//     use Exportable;

//     public function collection()
//     {
//         return lots::with('new_maerials_2')->where('lot_status', 'live')->get();
//     }

//     public function headings(): array
//     {
//         return [
//             'title', 
//             'description', 
//             // 'categoryId',
//             // 'uid',
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
//             'lotid',
//             'Product',
//             'Thickness',
//             'Width',
//             'Length',
//             'Weight',
//             'Grade',
//             'Remark',
//             'Material Images',
//         ];
//     }

//     public function map($lot): array
//     {
//         // Create a row for each material associated with the lot
//         $rows = [];
//         foreach ($lot->new_maerials_2 as $material) {
//             $rows[] = array_merge(
//                 $lot->toArray(), // Lots data
//                 [
//                     $material->lotid,
//                     $material->Product,
//                     $material->Thickness,
//                     $material->Width,
//                     $material->Length,
//                     $material->Weight,
//                     $material->Grade,
//                     $material->Remark,
//                     $material->images,
//                 ]
//             );
//         }

//         return $rows;
//     }
// }


class LotsExport implements FromCollection, WithHeadings
{
    use Exportable;

    protected $lots;

    public function __construct($lots)
    {
        $this->lots = $lots;
    }

    // public function collection()
    // {
    //     $data = [];

    //     foreach ($this->lots as $lot) {
    //         // Add live lot data
    //         $lotData = [
    //             $lot->title,
    //             $lot->description,
    //             $lot->categoryId,
    //             $lot->uid,
    //             $lot->Seller,
    //             $lot->Plant,
    //             $lot->materialLocation,
    //             $lot->Quantity,
    //             $lot->Payment_terms,
    //             $lot->StartDate,
    //             $lot->EndDate,
    //             $lot->Price,
    //             $lot->auction_status,
    //             $lot->lot_status,
    //             $lot->customFields,
    //             $lot->participate_fee,
    //             $lot->ReStartDate,
    //             $lot->ReEndDate,
    //             $lot->LiveSequenceNumber,
    //             $lot->status,
    //             // Leave empty columns for related materials
    //             '', '', '', '', '', '', '', '', '',
    //         ];

    //         $data[] = $lotData;

    //         // Add related materials data
    //         foreach ($lot->new_maerials_2 as $material) {
    //             $materialData = [
    //                 '', '', '', '', '', '', '', '', '', // Leave empty columns for live lot data
    //                 $material->lotid,
    //                 $material->Product,
    //                 $material->Thickness,
    //                 $material->Width,
    //                 $material->Length,
    //                 $material->Weight,
    //                 $material->Grade,
    //                 $material->Remark,
    //                 $material->images,
    //             ];

    //             $data[] = $materialData;
    //         }
    //     }

    //     return collect($data);
    // }



    public function headings(): array
{
    return [
        // Live lot columns
        'title',
        'description',
        'categoryId',
        'uid',
        'Seller',
        'Plant',
        'materialLocation',
        'Quantity',
        'Payment_terms',
        'StartDate',
        'EndDate',
        'Price',
        'auction_status',
        'lot_status',
        'customFields',
        'participate_fee',
        'ReStartDate',
        'ReEndDate',
        'LiveSequenceNumber',
        'status',
        // Materials columns
        'lotid',
        'Product',
        'Thickness',
        'Width',
        'Length',
        'Weight',
        'Grade',
        'Remark',
        'images',
    ];
}

public function collection()
{
    $data = [];

    foreach ($this->lots as $lot) {
        foreach ($lot->new_maerials_2 as $material) {
            $data[] = [
                // Live lot data
                $lot->title,
                $lot->description,
                $lot->categoryId,
                $lot->uid,
                $lot->Seller,
                $lot->Plant,
                $lot->materialLocation,
                $lot->Quantity,
                $lot->Payment_terms,
                $lot->StartDate,
                $lot->EndDate,
                $lot->Price,
                $lot->auction_status,
                $lot->lot_status,
                $lot->customFields,
                $lot->participate_fee,
                $lot->ReStartDate,
                $lot->ReEndDate,
                $lot->LiveSequenceNumber,
                $lot->status,
                // Material data
                $material->lotid,
                $material->Product,
                $material->Thickness,
                $material->Width,
                $material->Length,
                $material->Weight,
                $material->Grade,
                $material->Remark,
                $material->images,
            ];
        }
    }

    return collect($data);
}

}

