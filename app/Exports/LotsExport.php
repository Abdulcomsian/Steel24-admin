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

        $data->push(['Steel24 Auction List']);
        $data->push(['Report Date: ' . now()->format('D d M Y h:i a')]);

        foreach ($this->lots as $lot) 
        {
            $data->push(['']);

            $data->push([
                'Lot No', 'Title', 'Description', 'Category Id', 'U_Id', 'Seller', 'Plant', 'Material Location', 'Quantity', 'StartDate', 'EndDate', 'Start Price', 'Lot Status', 'Participate Fee'
            ]);

            $lotRow = [
                $lot->id,
                $lot->title,
                $lot->description,
                $lot->categoryId,
                $lot->uid,
                $lot->Seller,
                $lot->Plant,
                $lot->materialLocation,
                $lot->Quantity,
                $lot->StartDate,
                $lot->EndDate,
                // Carbon::parse($lot->StartDate)->format('d-M-y h:ia'), 
                // Carbon::parse($lot->EndDate)->format('d-M-y h:ia'), 
                $lot->Price,
                $lot->lot_status,
                $lot->participate_fee,
                // 'Ex-Stock',
                // 'Bid n Win'
            ];
            $data->push($lotRow);

            $data->push(['']); 
        
            $data->push([
                'Material Id', 'Lot Id', 'Product', 'Thickness', 'Width', 'Length', 'Weight', 'Grade',
                'Remark', 'images', 'Created_at', 'Updated_at'
            ]);

            foreach ($lot->new_maerials_2 as $material) 
            {
                $materialRow = [
                    $material->id,
                    $material->lotid,
                    $material->Product,
                    $material->Thickness,
                    $material->Width,
                    $material->Length,
                    $material->Weight,
                    $material->Grade,
                    $material->Remark,
                    $material->images,
                    $material->created_at,
                    $material->updated_at,
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