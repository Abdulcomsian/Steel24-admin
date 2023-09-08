<?php

namespace App\Exports;

use App\Models\ExportWinLots;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Collection;
use App\Models\CustomerLot;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
// use Illuminate\Support\Carbon;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;


class winlotexportapi implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle, WithCustomStartCell
{
    protected $winningLotsData;

    public function __construct(Collection $winningLotsData)
    {
        $this->winningLotsData = $winningLotsData;
    }

    public function collection()
    {
        $data = $this->transformData($this->winningLotsData);

        return new Collection($data);
    }

    public function headings(): array
    {

        return [
            'Lot ID',
            'Lot Title',
            'Lot Description',
            'Category Id',
            'User Id',
            'Seller',
            'Plant',
            'Material Location',
            'Quantity',
            'StartDate',
            'EndDate',
            'Price',
            'Lot Status',
            'Auction Status',
            'Custom Fields',
            'Created_at',
            'Updated_at',
            'Participate Fee',
            'ReStart Date',
            'ReEnd Date',
            'Live Sequence Number',
            'Payment Tterms',
            'Status',
            'Material ID',
            'Material Product',
            'Material Thickness',
            'Material Width',
            'Material Length',
            'Material Weight',
            'Material Grade',
        ];
    }

    public function title(): string
    {
        return 'WinLots';
    }


    public function startCell(): string
    {
        return 'A2';
    }

    protected function transformData($data)
    {
        $transformedData = [];

        foreach ($data as $lot) 
        {
            if ($lot->lotDetail->materials->count())
            { 
                foreach ($lot->lotDetail->materials as $material)
                {
                    $transformedData[] = [
                        'Lot ID' => $lot->lot_id,
                        'Lot Title' => $lot->lotDetail->title,
                        'Lot Description' => $lot->lotDetail->description,
                        'Category Id' => $lot->lotDetail->categoryId,
                        'User Id' => $lot->lotDetail->uid,
                        'Seller' => $lot->lotDetail->Seller,
                        'Plant' => $lot->lotDetail->Plant,
                        'Material Location' => $lot->lotDetail->materialLocation,
                        'Quantity' => $lot->lotDetail->Quantity,
                        'StartDate' => $lot->lotDetail->StartDate,
                        'EndDate' => $lot->lotDetail->EndDate,
                        'Price' => $lot->lotDetail->Price,
                        'Lot Status' => $lot->lotDetail->lot_status,
                        'Auction Status' => $lot->lotDetail->auction_status,
                        'Custom Fields' => $lot->lotDetail->customFields,
                        'Created_at' => $lot->lotDetail->created_at,
                        'Updated_at' => $lot->lotDetail->updated_at,
                        'Participate Fee' => $lot->lotDetail->participate_fee,
                        'ReStart Date' => $lot->lotDetail->ReStartDate,
                        'ReEnd Date' => $lot->lotDetail->ReEndDate,
                        'Live Sequence Number' => $lot->lotDetail->LiveSequenceNumber,
                        'Payment Terms' => $lot->lotDetail->Payment_terms,
                        'Status' => $lot->lotDetail->status,
                        'Material ID' => $material->id,
                        'Material Product' => $material->Product,
                        'Material Thickness' => $material->Thickness,
                        'Material Width' => $material->Width,
                        'Material Length' => $material->Length,
                        'Material Weight' => $material->Weight,
                        'Material Grade' => $material->Grade,
                    ];
                }
            }
        }
        return $transformedData;
    }

}




// also working
// class winlotexportapi implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle, WithCustomStartCell
// {
//     protected $winningLotsData;

//     public function __construct(Collection $winningLotsData)
//     {
//         $this->winningLotsData = $winningLotsData;
//     }

//     public function collection()
//     {
//         $data = $this->transformData($this->winningLotsData);
//         return new Collection($data);
//     }

//     public function headings(): array
//     {
//         return [
//             'Lot ID',
//             'Lot Title',
//             'Lot Description',
//             'Category Id',
//             'User Id',
//             'Seller',
//             'Plant',
//             'Material Location',
//             'Quantity',
//             'StartDate',
//             'EndDate',
//             'Price',
//             'Lot Status',
//             'Auction Status',
//             'Custom Fields',
//             'Created_at',
//             'Updated_at',
//             'Participate Fee',
//             'ReStart Date',
//             'ReEnd Date',
//             'Live Sequence Number',
//             'Payment Terms',
//             'Status',

//             'Material ID',
//             'Material Product',
//             'Material Thickness',
//             'Material Width',
//             'Material Length',
//             'Material Weight',
//             'Material Grade',
//         ];
//     }

//     public function title(): string
//     {
//         return 'WinLots';
//     }

//     public function startCell(): string
//     {
//         return 'A2';
//     }

//     protected function transformData($data)
//     {
//         $transformedData = [];

//         foreach ($data as $lot) {
//             // Add lot details to the transformed data
//             $lotDetails = [
//                 'Lot ID' => $lot['lot_id'],
//                 'Lot Title' => $lot['lot']['title'],
//                 'Lot Description' => $lot['lot']['description'],
//                 'Category Id' => $lot['lot']['categoryId'],
//                 'User Id' => $lot['lot']['uid'],
//                 'Seller' => $lot['lot']['Seller'],
//                 'Plant' => $lot['lot']['Plant'],
//                 'Material Location' => $lot['lot']['materialLocation'],
//                 'Quantity' => $lot['lot']['Quantity'],
//                 'StartDate' => $lot['lot']['StartDate'],
//                 'EndDate' => $lot['lot']['EndDate'],
//                 'Price' => $lot['lot']['Price'],
//                 'Lot Status' => $lot['lot']['lot_status'],
//                 'Auction Status' => $lot['lot']['auction_status'],
//                 'Custom Fields' => $lot['lot']['customFields'],
//                 'Created_at' => $lot['lot']['created_at'],
//                 'Updated_at' => $lot['lot']['updated_at'],
//                 'Participate Fee' => $lot['lot']['participate_fee'],
//                 'ReStart Date' => $lot['lot']['ReStartDate'],
//                 'ReEnd Date' => $lot['lot']['ReEndDate'],
//                 'Live Sequence Number' => $lot['lot']['LiveSequenceNumber'],
//                 'Payment Terms' => $lot['lot']['Payment_terms'],
//                 'Status' => $lot['lot']['status'],
//             ];

//             $transformedData[] = $lotDetails;

//             // Add material details to the transformed data
//             foreach ($lot['lot']['materials'] as $material) {
//                 $materialDetails = [
//                     'Lot ID' => '',
//                     'Lot Title' => '',
//                     'Lot Description' => '',
//                     'Category Id' => '',
//                     'User Id' => '',
//                     'Seller' => '',
//                     'Plant' => '',
//                     'Material Location' => '',
//                     'Quantity' => '',
//                     'StartDate' => '',
//                     'EndDate' => '',
//                     'Price' => '',
//                     'Lot Status' => '',
//                     'Auction Status' => '',
//                     'Custom Fields' => '',
//                     'Created_at' => '',
//                     'Updated_at' => '',
//                     'Participate Fee' => '',
//                     'ReStart Date' => '',
//                     'ReEnd Date' => '',
//                     'Live Sequence Number' => '',
//                     'Payment Terms' => '',
//                     'Status' => '',
//                     // Add material details
//                     'Material ID' => $material['id'],
//                     'Material Product' => $material['Product'],
//                     'Material Thickness' => $material['Thickness'],
//                     'Material Width' => $material['Width'],
//                     'Material Length' => $material['Length'],
//                     'Material Weight' => $material['Weight'],
//                     'Material Grade' => $material['Grade'],
//                 ];

//                 $transformedData[] = $materialDetails;
//             }
//         }

//         return $transformedData;
//     }
// }

