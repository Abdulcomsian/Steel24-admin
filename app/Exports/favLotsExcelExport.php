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

// class favLotsExcelExport implements FromCollection
// {
//     /**
//     * @return \Illuminate\Support\Collection
//     */
//     public function collection()
//     {
//         return favlotsexcel_export::all();
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
        return $this->lots->map(function ($lot) 
        {
            return [
                'ID' => $lot->id,
                'customer ID' => $lot->customer_id,
                'Lot ID' => $lot->lot_id,
                'Created_at' => $lot->created_at,
                'Updated_at' => $lot->updated_at,
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
        ];
    }
}