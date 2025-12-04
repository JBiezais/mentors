<?php

namespace Tests\Feature\Domain\Mentor\Builders;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Program\Models\Program;
use Tests\TestCase;

class MentorBuilderTest extends TestCase
{
    use RefreshDatabase;

    public function test_requested_returns_mentors_with_status_zero(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'status' => 0,
        ]);

        Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'status' => 1,
        ]);

        $results = Mentor::query()->requested()->get();

        $this->assertCount(1, $results);
        $this->assertEquals(0, $results->first()->status);
    }

    public function test_confirmed_returns_mentors_with_status_one(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'status' => 0,
        ]);

        Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'status' => 1,
        ]);

        $results = Mentor::query()->confirmed()->get();

        $this->assertCount(1, $results);
        $this->assertEquals(1, $results->first()->status);
    }

    public function test_filter_from_request_filters_by_keyword(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'name' => 'John',
            'lastName' => 'Doe',
        ]);

        Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'name' => 'Jane',
            'lastName' => 'Smith',
        ]);

        $request = new Request(['keyword' => 'John']);
        $results = Mentor::query()->filterFromRequest($request)->get();

        $this->assertCount(1, $results);
        $this->assertEquals('John', $results->first()->name);
    }

    public function test_filter_from_request_filters_by_type_requested(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'status' => 0,
        ]);

        Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'status' => 1,
        ]);

        $request = new Request(['type' => 'requested']);
        $results = Mentor::query()->filterFromRequest($request)->get();

        $this->assertCount(1, $results);
        $this->assertEquals(0, $results->first()->status);
    }

    public function test_filter_from_request_filters_by_type_confirmed(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'status' => 0,
        ]);

        Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'status' => 1,
        ]);

        $request = new Request(['type' => 'confirmed']);
        $results = Mentor::query()->filterFromRequest($request)->get();

        $this->assertCount(1, $results);
        $this->assertEquals(1, $results->first()->status);
    }

    public function test_filter_from_request_filters_by_faculty(): void
    {
        $faculty1 = Faculty::factory()->create(['code' => 'F1']);
        $faculty2 = Faculty::factory()->create(['code' => 'F2']);
        $program1 = Program::factory()->create(['faculty_id' => $faculty1->id]);
        $program2 = Program::factory()->create(['faculty_id' => $faculty2->id]);

        Mentor::factory()->create([
            'faculty_id' => $faculty1->id,
            'program_id' => $program1->id,
        ]);

        Mentor::factory()->create([
            'faculty_id' => $faculty2->id,
            'program_id' => $program2->id,
        ]);

        $request = new Request(['faculty' => $faculty1->id]);
        $results = Mentor::query()->filterFromRequest($request)->get();

        $this->assertCount(1, $results);
        $this->assertEquals($faculty1->id, $results->first()->faculty_id);
    }

    public function test_filter_from_request_filters_by_program(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program1 = Program::factory()->create(['faculty_id' => $faculty->id]);
        $program2 = Program::factory()->create(['faculty_id' => $faculty->id]);

        Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program1->id,
        ]);

        Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program2->id,
        ]);

        $request = new Request(['program' => $program1->id]);
        $results = Mentor::query()->filterFromRequest($request)->get();

        $this->assertCount(1, $results);
        $this->assertEquals($program1->id, $results->first()->program_id);
    }

    public function test_filter_from_request_can_combine_filters(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'name' => 'John',
            'status' => 1,
        ]);

        Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'name' => 'John',
            'status' => 0,
        ]);

        Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'name' => 'Jane',
            'status' => 1,
        ]);

        $request = new Request([
            'keyword' => 'John',
            'type' => 'confirmed',
        ]);
        $results = Mentor::query()->filterFromRequest($request)->get();

        $this->assertCount(1, $results);
        $this->assertEquals('John', $results->first()->name);
        $this->assertEquals(1, $results->first()->status);
    }

    public function test_member_of_program_filters_correctly(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program1 = Program::factory()->create(['faculty_id' => $faculty->id]);
        $program2 = Program::factory()->create(['faculty_id' => $faculty->id]);

        Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program1->id,
        ]);

        Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program2->id,
        ]);

        $results = Mentor::query()->memberOfProgram($program1->id)->get();

        $this->assertCount(1, $results);
        $this->assertEquals($program1->id, $results->first()->program_id);
    }
}
