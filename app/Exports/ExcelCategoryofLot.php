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


class ExcelCategoryofLot implements FromCollection, WithHeadings, WithCustomStartCell
{
    use Exportable;

    protected $lots;

    public function __construct($lots)
    {
        $this->lots = $lots;

        // dd($lots);
    }


    public function collection()
    {

        $data = new Collection();

        foreach ($this->lots as $lot)  
        {
            $data->push(['']);

            $data->push([
                'Lot No', 'Title', 'Description', 'Category Id', 'U_Id', 'Seller', 'Plant', 'Material Location', 'Quantity', 'StartDate', 'EndDate', 'Start Price', 'Lot Status', 'Participate Fee'
            ]);
            // dd($lot);
            $lotRow = [
                $lot->lotDetail->id,
                $lot->lotDetail->title,
                $lot->lotDetail->description,
                $lot->lotDetail->categoryId,
                $lot->lotDetail->uid,
                $lot->lotDetail->Seller,
                $lot->lotDetail->Plant,
                $lot->lotDetail->materialLocation,
                $lot->lotDetail->Quantity,
                $lot->lotDetail->StartDate,
                $lot->lotDetail->EndDate,
                // Carbon::parse($lot->StartDate)->format('d-M-y h:ia'), 
                // Carbon::parse($lot->EndDate)->format('d-M-y h:ia'), 
                $lot->lotDetail->Price,
                $lot->lotDetail->lot_status,
                $lot->lotDetail->participate_fee,
                // 'Ex-Stock',
                // 'Bid n Win'
            ];
            $data->push($lotRow);

            $data->push(['']); 
        
            $data->push([
                'Category Id', 'Title', 'Description', 'Parentcategory', 'created_at', 'updated_at'
            ]);

            foreach ($lot->categories as $category) 
            {
                $materialRow = [
                    $category->id,
                    $category->title,
                    $category->description,
                    $category->parentcategory,
                    $category->created_at,
                    $category->updated_at,
                ];
                $data->push($materialRow);
            }

            $data->push(['']); // Empty row
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
