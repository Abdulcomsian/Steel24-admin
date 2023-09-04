<?php

namespace App\Exports;

use App\Models\Excel_specific_win_lots;
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


class ExportSpecificwin_lots implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle, WithCustomStartCell
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
            foreach ($lot['lot']['materials'] as $material) 
            {

                $transformedData[] = [
                    'Lot ID' => $lot['lot_id'],
                    'Lot Title' => $lot['lot']['title'],
                    'Lot Description' => $lot['lot']['description'],
                    'Category Id' => $lot['lot']['categoryId'],
                    'User Id' => $lot['lot']['uid'],
                    'Seller' => $lot['lot']['Seller'],
                    'Plant' => $lot['lot']['Plant'],
                    'Material Location' => $lot['lot']['materialLocation'],
                    'Quantity' => $lot['lot']['Quantity'],
                    'StartDate' => $lot['lot']['StartDate'],
                    'EndDate' => $lot['lot']['EndDate'],
                    'Price' => $lot['lot']['Price'],
                    'Lot Status' => $lot['lot']['lot_status'],
                    'Auction Status' => $lot['lot']['auction_status'],
                    'Custom Fields' => $lot['lot']['customFields'],
                    'Created_at' => $lot['lot']['created_at'],
                    'Updated_at' => $lot['lot']['updated_at'],
                    'Participate Fee' => $lot['lot']['participate_fee'],
                    'ReStart Date' => $lot['lot']['ReStartDate'],
                    'ReEnd Date' => $lot['lot']['ReEndDate'],
                    'Live Sequence Number' => $lot['lot']['LiveSequenceNumber'],
                    'Payment Terms' => $lot['lot']['Payment_terms'],
                    'Status' => $lot['lot']['status'],
                    'Material ID' => $material['id'],
                    'Material Product' => $material['Product'],
                    'Material Thickness' => $material['Thickness'],
                    'Material Width' => $material['Width'],
                    'Material Length' => $material['Length'],
                    'Material Weight' => $material['Weight'],
                    'Material Grade' => $material['Grade'],
                ];
            }
        }

        return $transformedData;
   }
}
