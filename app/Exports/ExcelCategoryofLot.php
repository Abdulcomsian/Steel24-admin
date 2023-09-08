<?php

namespace App\Exports;

use App\Models\ExcelCategoryOfLots;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Excel_specific_win_lots;
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


// class ExcelCategoryofLot implements FromCollection
// {
//     /**
//     * @return \Illuminate\Support\Collection
//     */
//     public function collection()
//     {
//         return ExcelCategoryOfLots::all();
//     }
// }


// class ExcelCategoryofLot implements FromCollection, WithHeadings, WithCustomStartCell
// {
//     use Exportable;

//     protected $allLots;

//     public function __construct($allLots)
//     {
//         $this->allLots = $allLots;

//         // dd($lots);
//     }


//     public function collection()
//     {

//         $data = new Collection();

//         foreach ($this->allLots as $lot)  
//         {
//             $data->push(['']);

//             $data->push([
//                 'Lot No', 'Title', 'Description', 'Category Id', 'U_Id', 'Seller', 'Plant', 'Material Location', 'Quantity', 'StartDate', 'EndDate', 'Start Price', 'Lot Status', 'Participate Fee'
//             ]);
//             // dd($lot);
//             $lotRow = [
//                 $lot->lotDetail->id,
//                 $lot->lotDetail->title,
//                 $lot->lotDetail->description,
//                 $lot->lotDetail->categoryId,
//                 $lot->lotDetail->uid,
//                 $lot->lotDetail->Seller,
//                 $lot->lotDetail->Plant,
//                 $lot->lotDetail->materialLocation,
//                 $lot->lotDetail->Quantity,
//                 $lot->lotDetail->StartDate,
//                 $lot->lotDetail->EndDate,
//                 // Carbon::parse($lot->StartDate)->format('d-M-y h:ia'), 
//                 // Carbon::parse($lot->EndDate)->format('d-M-y h:ia'), 
//                 $lot->lotDetail->Price,
//                 $lot->lotDetail->lot_status,
//                 $lot->lotDetail->participate_fee,
//                 // 'Ex-Stock',
//                 // 'Bid n Win'
//             ];
//             $data->push($lotRow);

//             $data->push(['']); 
        
//             $data->push([
//                 'Category Id', 'Title', 'Description', 'Parentcategory', 'created_at', 'updated_at'
//             ]);

//             foreach ($lot->categories as $category) 
//             {
//                 $materialRow = [
//                     $category->id,
//                     $category->title,
//                     $category->description,
//                     $category->parentcategory,
//                     $category->created_at,
//                     $category->updated_at,
//                 ];
//                 $data->push($materialRow);
//             }

//             $data->push(['']); // Empty row
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

class ExcelCategoryofLot implements FromCollection, WithHeadings
{
    protected $allLots;

    public function __construct($allLots)
    {
        $this->allLots = $allLots;
    }

    public function collection()
    {
        $data = new Collection();

        // Add column headings
        // $data->push([
        //     'Lot No', 'Title', 'Description', 'Category Name', 'Customer Name', 'Last Bid Amount', 'Lot Status',
        //     'Start Date', 'End Date', 'Participate Fee',
        // ]);

        foreach ($this->allLots as $lot) 
        {
            $lotRow = [
                $lot['id'],
                $lot['title'],
                $lot['description'],
                $lot['categoryId'],
                $lot['uid'],
                $lot['Seller'],
                $lot['Plant'],
                $lot['materialLocation'],
                $lot['Quantity'],
                $lot['StartDate'],
                $lot['EndDate'],
                $lot['Price'],
                $lot['lot_status'],
                $lot['customFields'],
                $lot['created_at'],
                $lot['updated_at'],
                $lot['participate_fee'],
                $lot['categories']['id'] ?? null, 
                $lot['categories']['title'] ?? null, 
                $lot['categories']['description'] ?? null,
                $lot['categories']['parentcategory'] ?? null,
                $lot['categories']['created_at'] ?? null,
                $lot['categories']['updated_at'] ?? null,
                $lot['bids'][0]['id'] ?? null,
                $lot['bids'][0]['customerId'] ?? null,
                $lot['bids'][0]['amount'] ?? null,
                $lot['bids'][0]['lotId'] ?? null,
                $lot['bids'][0]['autoBid'] ?? null,
                $lot['bids'][0]['created_at'] ?? null,
                $lot['bids'][0]['updated_at'] ?? null,
            ];

            $data->push($lotRow);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Lot No', 
            'Title', 
            'Description', 
            'category Id',
            'uid',
            'Seller',
            'Plant',
            'Material Location',
            'Quantity',
            'Start Date', 
            'End Date', 
            'Price',
            'Lot_status',
            'Custom Fields',
            'created_at',
            'updated_at',
            'Participate Fee',
            'Category Id',
            'Title',
            'Description',
            'Parentcategory',
            'created_at',
            'updated_at',
            'Bid Id',
            'Customer Id',
            'Amount',
            'Lot Id ',
            'Auto Bid',
            'created_at',
            'updated_at',
        ];
    }
}
