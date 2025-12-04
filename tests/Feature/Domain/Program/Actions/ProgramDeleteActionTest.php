<?php

namespace Tests\Feature\Domain\Program\Actions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Program\Actions\ProgramDeleteAction;
use src\Domain\Program\Models\Program;
use Tests\TestCase;

class ProgramDeleteActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_deletes_program(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create([
            'faculty_id' => $faculty->id,
            'title' => 'Program to Delete',
            'code' => 'PTD',
            'level' => 'pamatstudijas',
        ]);

        ProgramDeleteAction::execute($program);

        $this->assertSoftDeleted('study_programs', [
            'id' => $program->id,
        ]);
    }

    public function test_it_does_not_affect_other_programs(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);

        $program1 = Program::factory()->create([
            'faculty_id' => $faculty->id,
            'title' => 'Program One',
            'code' => 'P1',
            'level' => 'pamatstudijas',
        ]);

        $program2 = Program::factory()->create([
            'faculty_id' => $faculty->id,
            'title' => 'Program Two',
            'code' => 'P2',
            'level' => 'pamatstudijas',
        ]);

        ProgramDeleteAction::execute($program1);

        $this->assertSoftDeleted('study_programs', [
            'id' => $program1->id,
        ]);

        $this->assertDatabaseHas('study_programs', [
            'id' => $program2->id,
            'deleted_at' => null,
        ]);
    }

    public function test_it_does_not_affect_parent_faculty(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);

        $program = Program::factory()->create([
            'faculty_id' => $faculty->id,
            'title' => 'Program to Delete',
            'code' => 'PTD',
            'level' => 'pamatstudijas',
        ]);

        ProgramDeleteAction::execute($program);

        $this->assertDatabaseHas('faculties', [
            'id' => $faculty->id,
            'deleted_at' => null,
        ]);
    }

    public function test_deleted_program_is_not_in_regular_queries(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);

        $program = Program::factory()->create([
            'faculty_id' => $faculty->id,
            'title' => 'Program to Delete',
            'code' => 'PTD',
            'level' => 'pamatstudijas',
        ]);

        $programId = $program->id;

        ProgramDeleteAction::execute($program);

        $this->assertNull(Program::find($programId));
        $this->assertNotNull(Program::withTrashed()->find($programId));
    }
}
