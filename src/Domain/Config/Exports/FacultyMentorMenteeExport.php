<?php

namespace src\Domain\Config\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FacultyMentorMenteeExport implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {
        $sheets = [];

        $years = range(2023, Carbon::now()->year);

        foreach ($years as $year) {
            $sheets[] = new FacultyMentorMenteeSheet($year);
        }

        return $sheets;
    }
}
