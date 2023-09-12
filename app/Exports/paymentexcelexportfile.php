<?php

namespace App\Exports;

use App\Models\Excel_export_payment;
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


class paymentexcelexportfile implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($item) 
        {
            return [
                'Date' => $item['Date'],
                'Action' => $item['Action'],
                'Title' => $item['lottitle'],
                'Credit' => $item['Credit'],
                'Debit' => $item['Debit'],
                'Amount' => $item['finalAmount'],
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Date',
            'Action',
            'Title',
            'Credit',
            'Debit',
            'Amount',
        ];
    }
}