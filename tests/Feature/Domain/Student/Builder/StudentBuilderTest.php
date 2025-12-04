<?php

namespace Tests\Feature\Domain\Student\Builder;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Program\Models\Program;
use src\Domain\Student\Models\Student;
use Tests\TestCase;

class StudentBuilderTest extends TestCase
{
    use RefreshDatabase;

    public function test_requested_returns_students_with_null_mentor_id(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'mentor_id' => null,
        ]);

        Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'mentor_id' => $mentor->id,
        ]);

        $results = Student::query()->requested()->get();

        $this->assertCount(1, $results);
        $this->assertNull($results->first()->mentor_id);
    }

    public function test_confirmed_returns_students_with_mentor_id(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'mentor_id' => null,
        ]);

        Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'mentor_id' => $mentor->id,
        ]);

        $results = Student::query()->confirmed()->get();

        $this->assertCount(1, $results);
        $this->assertNotNull($results->first()->mentor_id);
    }

    public function test_filter_from_request_filters_by_keyword(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'name' => 'John',
            'lastName' => 'Doe',
        ]);

        Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'name' => 'Jane',
            'lastName' => 'Smith',
        ]);

        $request = new Request(['keyword' => 'John']);
        $results = Student::query()->filterFromRequest($request)->get();

        $this->assertCount(1, $results);
        $this->assertEquals('John', $results->first()->name);
    }

    public function test_filter_from_request_filters_by_type_requested(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'mentor_id' => null,
        ]);

        Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'mentor_id' => $mentor->id,
        ]);

        $request = new Request(['type' => 'requested']);
        $results = Student::query()->filterFromRequest($request)->get();

        $this->assertCount(1, $results);
        $this->assertNull($results->first()->mentor_id);
    }

    public function test_filter_from_request_filters_by_type_confirmed(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'mentor_id' => null,
        ]);

        Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'mentor_id' => $mentor->id,
        ]);

        $request = new Request(['type' => 'confirmed']);
        $results = Student::query()->filterFromRequest($request)->get();

        $this->assertCount(1, $results);
        $this->assertNotNull($results->first()->mentor_id);
    }

    public function test_filter_from_request_filters_by_faculty(): void
    {
        $faculty1 = Faculty::factory()->create(['code' => 'F1']);
        $faculty2 = Faculty::factory()->create(['code' => 'F2']);
        $program1 = Program::factory()->create(['faculty_id' => $faculty1->id]);
        $program2 = Program::factory()->create(['faculty_id' => $faculty2->id]);

        Student::factory()->create([
            'faculty_id' => $faculty1->id,
            'program_id' => $program1->id,
        ]);

        Student::factory()->create([
            'faculty_id' => $faculty2->id,
            'program_id' => $program2->id,
        ]);

        $request = new Request(['faculty' => $faculty1->id]);
        $results = Student::query()->filterFromRequest($request)->get();

        $this->assertCount(1, $results);
        $this->assertEquals($faculty1->id, $results->first()->faculty_id);
    }

    public function test_filter_from_request_filters_by_program(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program1 = Program::factory()->create(['faculty_id' => $faculty->id]);
        $program2 = Program::factory()->create(['faculty_id' => $faculty->id]);

        Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program1->id,
        ]);

        Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program2->id,
        ]);

        $request = new Request(['program' => $program1->id]);
        $results = Student::query()->filterFromRequest($request)->get();

        $this->assertCount(1, $results);
        $this->assertEquals($program1->id, $results->first()->program_id);
    }

    public function test_filter_from_request_can_combine_filters(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'name' => 'John',
            'mentor_id' => $mentor->id,
        ]);

        Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'name' => 'John',
            'mentor_id' => null,
        ]);

        Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'name' => 'Jane',
            'mentor_id' => $mentor->id,
        ]);

        $request = new Request([
            'keyword' => 'John',
            'type' => 'confirmed',
        ]);
        $results = Student::query()->filterFromRequest($request)->get();

        $this->assertCount(1, $results);
        $this->assertEquals('John', $results->first()->name);
        $this->assertNotNull($results->first()->mentor_id);
    }

    public function test_member_of_program_filters_correctly(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program1 = Program::factory()->create(['faculty_id' => $faculty->id]);
        $program2 = Program::factory()->create(['faculty_id' => $faculty->id]);

        Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program1->id,
        ]);

        Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program2->id,
        ]);

        $results = Student::query()->memberOfProgram($program1->id)->get();

        $this->assertCount(1, $results);
        $this->assertEquals($program1->id, $results->first()->program_id);
    }
}
