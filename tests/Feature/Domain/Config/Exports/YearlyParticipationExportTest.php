<?php

namespace Tests\Feature\Domain\Config\Exports;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use src\Domain\Config\Exports\YearlyParticipationExport;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Program\Models\Program;
use src\Domain\Student\Models\Student;
use Tests\TestCase;

class YearlyParticipationExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_export_instance(): void
    {
        $export = new YearlyParticipationExport();

        $this->assertInstanceOf(YearlyParticipationExport::class, $export);
    }

    public function test_title_returns_statistics(): void
    {
        $export = new YearlyParticipationExport();

        $this->assertEquals('Statistics', $export->title());
    }

    public function test_headings_returns_correct_columns(): void
    {
        $export = new YearlyParticipationExport();
        $headings = $export->headings();

        $this->assertIsArray($headings);
        $this->assertCount(3, $headings);
        $this->assertEquals('Gads', $headings[0]);
        $this->assertEquals('Mentoru skaits', $headings[1]);
        $this->assertEquals('Studentu skaits', $headings[2]);
    }

    public function test_collection_returns_collection_instance(): void
    {
        $export = new YearlyParticipationExport();
        $collection = $export->collection();

        $this->assertInstanceOf(Collection::class, $collection);
    }

    public function test_collection_returns_empty_when_no_data(): void
    {
        $export = new YearlyParticipationExport();
        $collection = $export->collection();

        $this->assertTrue($collection->isEmpty());
    }

    public function test_collection_returns_data_when_mentors_exist(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $export = new YearlyParticipationExport();
        $collection = $export->collection();

        $this->assertFalse($collection->isEmpty());
    }

    public function test_collection_returns_data_when_students_exist(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $export = new YearlyParticipationExport();
        $collection = $export->collection();

        $this->assertFalse($collection->isEmpty());
    }

    public function test_map_returns_correct_structure(): void
    {
        $row = (object) [
            'year' => 2024,
            'mentor_count' => 5,
            'student_count' => 10,
        ];

        $export = new YearlyParticipationExport();
        $mapped = $export->map($row);

        $this->assertIsArray($mapped);
        $this->assertCount(3, $mapped);
        $this->assertEquals(2024, $mapped[0]);
        $this->assertEquals(5, $mapped[1]);
        $this->assertEquals(10, $mapped[2]);
    }

    public function test_map_handles_null_counts(): void
    {
        $row = (object) [
            'year' => 2024,
            'mentor_count' => null,
            'student_count' => null,
        ];

        $export = new YearlyParticipationExport();
        $mapped = $export->map($row);

        $this->assertEquals(0, $mapped[1]);
        $this->assertEquals(0, $mapped[2]);
    }

    public function test_charts_returns_null_when_no_data(): void
    {
        $export = new YearlyParticipationExport();
        $export->collection();
        $chart = $export->charts();

        $this->assertNull($chart);
    }
}
