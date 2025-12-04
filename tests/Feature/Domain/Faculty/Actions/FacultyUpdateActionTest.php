<?php

namespace Tests\Feature\Domain\Faculty\Actions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use src\Domain\Faculty\Actions\FacultyUpdateAction;
use src\Domain\Faculty\DTO\FacultyData;
use src\Domain\Faculty\Models\Faculty;
use Tests\TestCase;

class FacultyUpdateActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_faculty_title(): void
    {
        $faculty = Faculty::factory()->create([
            'title' => 'Old Title',
            'code' => 'OT',
        ]);

        $data = FacultyData::from([
            'title' => 'New Title',
            'code' => 'OT',
        ]);

        FacultyUpdateAction::execute($faculty, $data);

        $this->assertDatabaseHas('faculties', [
            'id' => $faculty->id,
            'title' => 'New Title',
            'code' => 'OT',
        ]);
    }

    public function test_it_updates_faculty_code(): void
    {
        $faculty = Faculty::factory()->create([
            'title' => 'Faculty Title',
            'code' => 'OLD',
        ]);

        $data = FacultyData::from([
            'title' => 'Faculty Title',
            'code' => 'NEW',
        ]);

        FacultyUpdateAction::execute($faculty, $data);

        $this->assertDatabaseHas('faculties', [
            'id' => $faculty->id,
            'title' => 'Faculty Title',
            'code' => 'NEW',
        ]);
    }

    public function test_it_updates_all_faculty_fields(): void
    {
        $faculty = Faculty::factory()->create([
            'title' => 'Original Title',
            'code' => 'ORIG',
        ]);

        $data = FacultyData::from([
            'title' => 'Updated Title',
            'code' => 'UPD',
        ]);

        FacultyUpdateAction::execute($faculty, $data);

        $faculty->refresh();

        $this->assertEquals('Updated Title', $faculty->title);
        $this->assertEquals('UPD', $faculty->code);
    }

    public function test_it_only_updates_specified_faculty(): void
    {
        $faculty1 = Faculty::factory()->create([
            'title' => 'Faculty One',
            'code' => 'F1',
        ]);

        $faculty2 = Faculty::factory()->create([
            'title' => 'Faculty Two',
            'code' => 'F2',
        ]);

        $data = FacultyData::from([
            'title' => 'Updated Faculty One',
            'code' => 'UF1',
        ]);

        FacultyUpdateAction::execute($faculty1, $data);

        $this->assertDatabaseHas('faculties', [
            'id' => $faculty1->id,
            'title' => 'Updated Faculty One',
        ]);

        $this->assertDatabaseHas('faculties', [
            'id' => $faculty2->id,
            'title' => 'Faculty Two',
        ]);
    }
}
