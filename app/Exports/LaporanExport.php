<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\DataHistorisSheet;
use App\Exports\Sheets\PrediksiSheet;

class LaporanExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new DataHistorisSheet(),
            new PrediksiSheet(),
        ];
    }
}