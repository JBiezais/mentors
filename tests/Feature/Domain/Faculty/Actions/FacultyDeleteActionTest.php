<?php

namespace Tests\Feature\Domain\Faculty\Actions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use src\Domain\Faculty\Actions\FacultyDeleteAction;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Program\Models\Program;
use Tests\TestCase;

class FacultyDeleteActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_deletes_faculty(): void
    {
        $faculty = Faculty::factory()->create([
            'title' => 'Faculty to Delete',
            'code' => 'FTD',
        ]);

        FacultyDeleteAction::execute($faculty);

        $this->assertSoftDeleted('faculties', [
            'id' => $faculty->id,
        ]);
    }

    public function test_it_deletes_associated_programs_when_deleting_faculty(): void
    {
        $faculty = Faculty::factory()->create([
            'title' => 'Faculty with Programs',
            'code' => 'FWP',
        ]);

        $program1 = Program::factory()->create([
            'faculty_id' => $faculty->id,
            'title' => 'Program One',
            'code' => 'P1',
        ]);

        $program2 = Program::factory()->create([
            'faculty_id' => $faculty->id,
            'title' => 'Program Two',
            'code' => 'P2',
        ]);

        FacultyDeleteAction::execute($faculty);

        $this->assertSoftDeleted('faculties', [
            'id' => $faculty->id,
        ]);

        $this->assertSoftDeleted('study_programs', [
            'id' => $program1->id,
        ]);

        $this->assertSoftDeleted('study_programs', [
            'id' => $program2->id,
        ]);
    }

    public function test_it_does_not_affect_other_faculties(): void
    {
        $faculty1 = Faculty::factory()->create([
            'title' => 'Faculty One',
            'code' => 'F1',
        ]);

        $faculty2 = Faculty::factory()->create([
            'title' => 'Faculty Two',
            'code' => 'F2',
        ]);

        FacultyDeleteAction::execute($faculty1);

        $this->assertSoftDeleted('faculties', [
            'id' => $faculty1->id,
        ]);

        $this->assertDatabaseHas('faculties', [
            'id' => $faculty2->id,
            'deleted_at' => null,
        ]);
    }

    public function test_it_deletes_faculty_without_programs(): void
    {
        $faculty = Faculty::factory()->create([
            'title' => 'Faculty Without Programs',
            'code' => 'FWP',
        ]);

        FacultyDeleteAction::execute($faculty);

        $this->assertSoftDeleted('faculties', [
            'id' => $faculty->id,
        ]);
    }
}
