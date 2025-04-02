<?php

namespace src\Domain\Config\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use src\Domain\Config\Sheets\FacultyMentorMenteeSheet;

class FacultyMentorMenteeExport implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {
        $sheets = [];

        $years = range(2024, Carbon::now()->year);

        foreach ($years as $year) {
            $sheets[] = new FacultyMentorMenteeSheet($year);
        }

        return $sheets;
    }
}
