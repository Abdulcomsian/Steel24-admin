<?php

namespace App\Http\Controllers;

use App\Imports\LotsImport;
use App\Models\lots;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CsvImportController extends Controller
{
    public function showForm()
    {
        return view('admin.lots.import-csv');
    }

    public function import(Request $request)
    {
        try {
            $importResult = Excel::import(new LotsImport(), $request->file('csv_file'));
            
            if ($importResult) {
                return "CSV file is found successfully.";
            } else {
                return "CSV file is not found.";
            }
        } catch (Exception $ex) {
            return "An error occurred while importing the CSV file: " . $ex->getMessage();
            
        }
    }
}
