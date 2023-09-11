<?php

namespace App\Exports;

use App\Models\favlotsexcel_export;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\CustomerLot;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;


// class favLotsExcelExport implements FromCollection, WithHeadings
// {
//     protected $lots;

//     public function __construct(Collection $lots)
//     {
//         $this->lots = $lots;
//     }

//     public function collection()
//     {
//         return $this->lots->map(function ($lots) 
//         {
//             return [
//                 'ID' => $lots->id,
//                 'customer ID' => $lots->customer_id,
//                 'Lot ID' => $lots->lot->id,
//                 'Created_at' => $lots->created_at,
//                 'Updated_at' => $lots->updated_at,
//                 'Lot Title' => $lots->lot->title,
//                 'Description' => $lots->lot->description,
//                 'CategoryId' => $lots->lot->categoryId,
//                 'U_ID' => $lots->lot->uid,
//                 'Seller' => $lots->lot->Seller,
//                 'Plant' => $lots->lot->Plant,
//                 'Material Location' => $lots->lot->materialLocation,
//                 'Quantity' => $lots->lot->Quantity,
//                 'StartDate' => $lots->lot->StartDate,
//                 'EndDate' => $lots->lot->EndDate,
//                 'Price' => $lots->lot->Price,
//                 'Lot Status' => $lots->lot->lot_status,
//                 'Participate fee' => $lots->lot->participate_fee,
//                 'Created at' => $lots->lot->created_at,
//                 'Updated at' => $lots->lot->created_at,
//                 // 'Customer Name' => $lots->customer->name, // Access customer details
//             ];
//         });
//     }

//     public function headings(): array
//     {
//         return [
//             'ID',
//             'customer ID',
//             'Lot ID',
//             'Created_at',
//             'Updated_at',
//             'Lot Title',
//             'Description',
//             'CategoryId',
//             'U_ID',
//             'Seller',
//             'Plant',
//             'Material Location',
//             'Quantity',
//             'Start Date',
//             'EndDate',
//             'Price',
//             'Lot Status',
//             'Participate fee',
//             'Created at',
//             'Updated at',
//         ];
//     }

// }


class favLotsExcelExport implements FromCollection, WithHeadings
{
    protected $lots;

    public function __construct(Collection $lots)
    {
        $this->lots = $lots;
    }

    public function collection()
    {
        return $this->lots->map(function ($favLot) 
        {
            return [
                'ID' => $favLot->id,
                'customer ID' => $favLot->customer_id,
                'Lot ID' => $favLot->lot->id,
                'Lot Title' => $favLot->lot->title,
                'Created_at' => $favLot->created_at,
                'Updated_at' => $favLot->updated_at,
                'Description' => $favLot->lot->description,
                'CategoryId' => $favLot->lot->categoryId,
                'U_ID' => $favLot->lot->uid,
                'Seller' => $favLot->lot->Seller,
                'Plant' => $favLot->lot->Plant,
                'Material Location' => $favLot->lot->materialLocation,
                'Quantity' => $favLot->lot->Quantity,
                'Start Date' => $favLot->lot->StartDate,
                'EndDate' => $favLot->lot->EndDate,
                'Price' => $favLot->lot->Price,
                'Lot Status' => $favLot->lot->lot_status,
                'Participate fee' => $favLot->lot->participate_fee,
                'Created at' => $favLot->lot->created_at,
                'Updated at' => $favLot->lot->created_at,
                // 'Materials' => $favLot->lot->materials->pluck('Product')->implode(', '),
                'Material ID' => $favLot->lot->materials->pluck('id')->implode(', '),
                'Product' => $favLot->lot->materials->pluck('Product')->implode(', '),
                'Thickness' => $favLot->lot->materials->pluck('Thickness')->implode(', '),
                'Width' => $favLot->lot->materials->pluck('Width')->implode(', '),
                'Length' => $favLot->lot->materials->pluck('Length')->implode(', '),
                'Weight' => $favLot->lot->materials->pluck('Weight')->implode(', '),
                'Grade' => $favLot->lot->materials->pluck('Grade')->implode(', '), 
                'Remark' => $favLot->lot->materials->pluck('Remark')->implode(', '),
                'Images' => $favLot->lot->materials->pluck('images')->implode(', '),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'customer ID',
            'Lot ID',
            'Created_at',
            'Updated_at',
            'Lot Title',
            'Description',
            'CategoryId',
            'U_ID',
            'Seller',
            'Plant',
            'Material Location',
            'Quantity',
            'Start Date',
            'EndDate',
            'Price',
            'Lot Status',
            'Participate fee',
            'Created at',
            'Updated at',
            // 'Materials',
            'Material ID',
            'Product',
            'Thickness',
            'Width',
            'Length',
            'Weight',
            'Grade',
            'Remark',
            'Images',
        ];
    }
}