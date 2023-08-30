<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Collection;
use App\Models\CustomerLot;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\ExportWinLots;
use Illuminate\Http\Request;


class WinLotsExport implements FromCollection, WithHeadings, ShouldAutoSize, ShouldQueue
{
    use SerializesModels;

    protected $winningLots;

    public function __construct($winningLots)
    {
        $this->winningLots = $winningLots;
    }

    public function collection()
    {
        return new Collection($this->winningLots);
    }

    public function headings(): array
    {
        // Define the headings for the Excel file
        return [
            'ID',
            'Customer ID',
            'Lot ID',
            'Created At',
            'Updated At',
        ];
    }

        public function storeExcelFile()
    {
        // Generate a unique file name
        $fileName = 'winlots_' . Carbon::now()->format('YmdHis') . '.xlsx';

        // Create an instance of the Excel export class
        $excelExport = new WinLotsExport($this->winningLots);

        // Generate the Excel file
        Excel::store($excelExport, $fileName, 'public/ExcelWinLots');

        // Save the URL in the database
        ExportWinLots::create([
            'url' => $fileName,
        ]);
    }

    public function getFileUrl()
    {
        return url('ExcelWinLots/' . $this->winningLots[0]['url']);
    }


}


// class WinLotsExport implements FromCollection, WithHeadings, ShouldAutoSize
// {
//     protected $winningLots;

//     public function __construct($winningLots)
//     {
//         $this->winningLots = $winningLots;
//     }

//     public function collection()
//     {
//         return new Collection($this->winningLots);
//     }

//     public function headings(): array
//     {
//         // Define the headings for the Excel file
//         return [
//             'ID',
//             'Customer ID',
//             'Lot ID',
//             'Created At',
//             'Updated At',
//         ];
//     }
// }