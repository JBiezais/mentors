<?php

namespace Tests\Feature\Domain\Program\Actions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Program\Actions\ProgramUpdateAction;
use src\Domain\Program\DTO\ProgramUpdateData;
use src\Domain\Program\Models\Program;
use Tests\TestCase;

class ProgramUpdateActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_program_title(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create([
            'faculty_id' => $faculty->id,
            'title' => 'Old Title',
            'code' => 'OT',
            'level' => 'pamatstudijas',
        ]);

        $data = ProgramUpdateData::from([
            'id' => $program->id,
            'faculty_id' => $faculty->id,
            'title' => 'New Title',
            'code' => 'OT',
            'level' => 'pamatstudijas',
        ]);

        ProgramUpdateAction::execute($program, $data);

        $this->assertDatabaseHas('study_programs', [
            'id' => $program->id,
            'title' => 'New Title',
        ]);
    }

    public function test_it_updates_program_code(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create([
            'faculty_id' => $faculty->id,
            'title' => 'Program Title',
            'code' => 'OLD',
            'level' => 'pamatstudijas',
        ]);

        $data = ProgramUpdateData::from([
            'id' => $program->id,
            'faculty_id' => $faculty->id,
            'title' => 'Program Title',
            'code' => 'NEW',
            'level' => 'pamatstudijas',
        ]);

        ProgramUpdateAction::execute($program, $data);

        $this->assertDatabaseHas('study_programs', [
            'id' => $program->id,
            'code' => 'NEW',
        ]);
    }

    public function test_it_updates_program_level(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create([
            'faculty_id' => $faculty->id,
            'title' => 'Program Title',
            'code' => 'PT',
            'level' => 'pamatstudijas',
        ]);

        $data = ProgramUpdateData::from([
            'id' => $program->id,
            'faculty_id' => $faculty->id,
            'title' => 'Program Title',
            'code' => 'PT',
            'level' => 'augstākā līmeņa studijas',
        ]);

        ProgramUpdateAction::execute($program, $data);

        $this->assertDatabaseHas('study_programs', [
            'id' => $program->id,
            'level' => 'augstākā līmeņa studijas',
        ]);
    }

    public function test_it_updates_all_program_fields(): void
    {
        $faculty1 = Faculty::factory()->create(['code' => 'F1']);
        $faculty2 = Faculty::factory()->create(['code' => 'F2']);

        $program = Program::factory()->create([
            'faculty_id' => $faculty1->id,
            'title' => 'Original Title',
            'code' => 'ORIG',
            'level' => 'pamatstudijas',
        ]);

        $data = ProgramUpdateData::from([
            'id' => $program->id,
            'faculty_id' => $faculty2->id,
            'title' => 'Updated Title',
            'code' => 'UPD',
            'level' => 'augstākā līmeņa studijas',
        ]);

        ProgramUpdateAction::execute($program, $data);

        $program->refresh();

        $this->assertEquals($faculty2->id, $program->faculty_id);
        $this->assertEquals('Updated Title', $program->title);
        $this->assertEquals('UPD', $program->code);
        $this->assertEquals('augstākā līmeņa studijas', $program->level);
    }

    public function test_it_only_updates_specified_program(): void
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

        $data = ProgramUpdateData::from([
            'id' => $program1->id,
            'faculty_id' => $faculty->id,
            'title' => 'Updated Program One',
            'code' => 'UP1',
            'level' => 'pamatstudijas',
        ]);

        ProgramUpdateAction::execute($program1, $data);

        $this->assertDatabaseHas('study_programs', [
            'id' => $program1->id,
            'title' => 'Updated Program One',
        ]);

        $this->assertDatabaseHas('study_programs', [
            'id' => $program2->id,
            'title' => 'Program Two',
        ]);
    }
}
