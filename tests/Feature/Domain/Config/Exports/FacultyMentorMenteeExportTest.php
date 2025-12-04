<?php

namespace Tests\Feature\Domain\Config\Exports;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use src\Domain\Config\Exports\FacultyMentorMenteeExport;
use src\Domain\Config\Sheets\FacultyMentorMenteeSheet;
use Tests\TestCase;

class FacultyMentorMenteeExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_export_instance(): void
    {
        $export = new FacultyMentorMenteeExport();

        $this->assertInstanceOf(FacultyMentorMenteeExport::class, $export);
    }

    public function test_sheets_returns_array(): void
    {
        $export = new FacultyMentorMenteeExport();
        $sheets = $export->sheets();

        $this->assertIsArray($sheets);
    }

    public function test_sheets_contains_faculty_mentor_mentee_sheets(): void
    {
        $export = new FacultyMentorMenteeExport();
        $sheets = $export->sheets();

        foreach ($sheets as $sheet) {
            $this->assertInstanceOf(FacultyMentorMenteeSheet::class, $sheet);
        }
    }

    public function test_sheets_starts_from_2024(): void
    {
        $export = new FacultyMentorMenteeExport();
        $sheets = $export->sheets();

        $this->assertNotEmpty($sheets);
        $firstSheet = $sheets[0];
        $this->assertEquals(2024, $firstSheet->year);
    }

    public function test_sheets_ends_at_current_year(): void
    {
        $export = new FacultyMentorMenteeExport();
        $sheets = $export->sheets();

        $this->assertNotEmpty($sheets);
        $lastSheet = end($sheets);
        $this->assertEquals(Carbon::now()->year, $lastSheet->year);
    }

    public function test_sheets_count_matches_year_range(): void
    {
        $export = new FacultyMentorMenteeExport();
        $sheets = $export->sheets();

        $expectedCount = Carbon::now()->year - 2024 + 1;
        $this->assertCount($expectedCount, $sheets);
    }

    public function test_sheets_are_in_chronological_order(): void
    {
        $export = new FacultyMentorMenteeExport();
        $sheets = $export->sheets();

        $years = array_map(fn($sheet) => $sheet->year, $sheets);

        for ($i = 0; $i < count($years) - 1; $i++) {
            $this->assertLessThan($years[$i + 1], $years[$i]);
        }
    }

    public function test_each_sheet_has_unique_year(): void
    {
        $export = new FacultyMentorMenteeExport();
        $sheets = $export->sheets();

        $years = array_map(fn($sheet) => $sheet->year, $sheets);

        $this->assertEquals(count($years), count(array_unique($years)));
    }
}
