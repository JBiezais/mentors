<?php

namespace Tests\Feature\Domain\Program\Actions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Program\Actions\ProgramCreateAction;
use src\Domain\Program\DTO\ProgramCreateData;
use src\Domain\Program\Models\Program;
use Tests\TestCase;

class ProgramCreateActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_program_with_valid_data(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);

        $data = ProgramCreateData::from([
            'faculty_id' => $faculty->id,
            'title' => 'Computer Science',
            'code' => 'CS',
            'level' => 'pamatstudijas',
        ]);

        ProgramCreateAction::execute($data);

        $this->assertDatabaseHas('study_programs', [
            'faculty_id' => $faculty->id,
            'title' => 'Computer Science',
            'code' => 'CS',
            'level' => 'pamatstudijas',
        ]);
    }

    public function test_it_creates_program_with_all_required_fields(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FS']);

        $data = ProgramCreateData::from([
            'faculty_id' => $faculty->id,
            'title' => 'Mathematics',
            'code' => 'MATH',
            'level' => 'augstākā līmeņa studijas',
        ]);

        ProgramCreateAction::execute($data);

        $program = Program::where('code', 'MATH')->first();

        $this->assertNotNull($program);
        $this->assertEquals($faculty->id, $program->faculty_id);
        $this->assertEquals('Mathematics', $program->title);
        $this->assertEquals('MATH', $program->code);
        $this->assertEquals('augstākā līmeņa studijas', $program->level);
    }

    public function test_it_creates_multiple_programs_for_same_faculty(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);

        $data1 = ProgramCreateData::from([
            'faculty_id' => $faculty->id,
            'title' => 'Program One',
            'code' => 'P1',
            'level' => 'pamatstudijas',
        ]);

        $data2 = ProgramCreateData::from([
            'faculty_id' => $faculty->id,
            'title' => 'Program Two',
            'code' => 'P2',
            'level' => 'pamatstudijas',
        ]);

        ProgramCreateAction::execute($data1);
        ProgramCreateAction::execute($data2);

        $this->assertDatabaseCount('study_programs', 2);
        $this->assertEquals(2, $faculty->refresh()->programs()->count());
    }

    public function test_it_creates_programs_for_different_faculties(): void
    {
        $faculty1 = Faculty::factory()->create(['code' => 'F1']);
        $faculty2 = Faculty::factory()->create(['code' => 'F2']);

        $data1 = ProgramCreateData::from([
            'faculty_id' => $faculty1->id,
            'title' => 'Program One',
            'code' => 'P1',
            'level' => 'pamatstudijas',
        ]);

        $data2 = ProgramCreateData::from([
            'faculty_id' => $faculty2->id,
            'title' => 'Program Two',
            'code' => 'P2',
            'level' => 'pamatstudijas',
        ]);

        ProgramCreateAction::execute($data1);
        ProgramCreateAction::execute($data2);

        $this->assertEquals(1, $faculty1->refresh()->programs()->count());
        $this->assertEquals(1, $faculty2->refresh()->programs()->count());
    }
}
