<?php

namespace Tests\Feature\Domain\Student\Requests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Program\Models\Program;
use src\Domain\Student\Models\Student;
use src\Domain\Student\Requests\StudentUpdateRequest;
use Tests\TestCase;

class StudentUpdateRequestTest extends TestCase
{
    use RefreshDatabase;

    private function validate(array $data): \Illuminate\Validation\Validator
    {
        $request = new StudentUpdateRequest();

        return Validator::make($data, $request->rules());
    }

    public function test_valid_request_passes_validation(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $student = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $validator = $this->validate([
            'id' => $student->id,
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'name' => 'Jane',
            'lastName' => 'Doe',
            'phone' => '+37120000000',
            'email' => 'jane@example.com',
        ]);

        $this->assertFalse($validator->fails());
    }

    public function test_id_is_required(): void
    {
        $validator = $this->validate([
            'faculty_id' => 1,
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('id', $validator->errors()->toArray());
    }

    public function test_id_must_exist(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);

        $validator = $this->validate([
            'id' => 999,
            'faculty_id' => $faculty->id,
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('id', $validator->errors()->toArray());
    }

    public function test_faculty_id_is_required(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $student = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $validator = $this->validate([
            'id' => $student->id,
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('faculty_id', $validator->errors()->toArray());
    }

    public function test_faculty_id_must_exist(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $student = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $validator = $this->validate([
            'id' => $student->id,
            'faculty_id' => 999,
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('faculty_id', $validator->errors()->toArray());
    }

    public function test_program_id_is_required(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $student = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $validator = $this->validate([
            'id' => $student->id,
            'faculty_id' => $faculty->id,
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('program_id', $validator->errors()->toArray());
    }

    public function test_program_id_must_exist(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $student = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $validator = $this->validate([
            'id' => $student->id,
            'faculty_id' => $faculty->id,
            'program_id' => 999,
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('program_id', $validator->errors()->toArray());
    }

    public function test_mentor_id_is_nullable(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $student = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $validator = $this->validate([
            'id' => $student->id,
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'mentor_id' => null,
            'name' => 'Jane',
            'lastName' => 'Doe',
            'phone' => '+37120000000',
            'email' => 'jane@example.com',
        ]);

        $this->assertFalse($validator->fails());
    }

    public function test_mentor_id_must_exist_if_provided(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $student = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $validator = $this->validate([
            'id' => $student->id,
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'mentor_id' => 999,
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('mentor_id', $validator->errors()->toArray());
    }

    public function test_valid_mentor_id_passes(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);
        $student = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $validator = $this->validate([
            'id' => $student->id,
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'mentor_id' => $mentor->id,
            'name' => 'Jane',
            'lastName' => 'Doe',
            'phone' => '+37120000000',
            'email' => 'jane@example.com',
        ]);

        $this->assertFalse($validator->fails());
    }

    public function test_name_is_required(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $student = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $validator = $this->validate([
            'id' => $student->id,
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'name' => '',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
    }

    public function test_lastName_is_required(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $student = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $validator = $this->validate([
            'id' => $student->id,
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'name' => 'Jane',
            'lastName' => '',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('lastName', $validator->errors()->toArray());
    }

    public function test_phone_is_required(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $student = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $validator = $this->validate([
            'id' => $student->id,
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'name' => 'Jane',
            'lastName' => 'Doe',
            'phone' => '',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('phone', $validator->errors()->toArray());
    }

    public function test_email_is_required(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $student = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $validator = $this->validate([
            'id' => $student->id,
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'name' => 'Jane',
            'lastName' => 'Doe',
            'phone' => '+37120000000',
            'email' => '',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    public function test_email_must_be_valid_email(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $student = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $validator = $this->validate([
            'id' => $student->id,
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'name' => 'Jane',
            'lastName' => 'Doe',
            'phone' => '+37120000000',
            'email' => 'not-an-email',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    public function test_comment_is_nullable(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $student = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $validator = $this->validate([
            'id' => $student->id,
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'name' => 'Jane',
            'lastName' => 'Doe',
            'phone' => '+37120000000',
            'email' => 'jane@example.com',
            'comment' => null,
        ]);

        $this->assertFalse($validator->fails());
    }

    public function test_lang_is_nullable(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $student = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $validator = $this->validate([
            'id' => $student->id,
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'name' => 'Jane',
            'lastName' => 'Doe',
            'phone' => '+37120000000',
            'email' => 'jane@example.com',
            'lang' => null,
        ]);

        $this->assertFalse($validator->fails());
    }

    public function test_authorization_returns_true(): void
    {
        $request = new StudentUpdateRequest();

        $this->assertTrue($request->authorize());
    }
}
