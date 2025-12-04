<?php

namespace Tests\Feature\Domain\Student\Requests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Program\Models\Program;
use src\Domain\Student\Requests\StudentCreateRequest;
use Tests\TestCase;

class StudentCreateRequestTest extends TestCase
{
    use RefreshDatabase;

    private function validate(array $data): \Illuminate\Validation\Validator
    {
        $request = new StudentCreateRequest();

        return Validator::make($data, $request->rules());
    }

    public function test_valid_request_passes_validation(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        $validator = $this->validate([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'name' => 'Jane',
            'lastName' => 'Doe',
            'phone' => '+37120000000',
            'email' => 'jane@example.com',
            'privacy' => true,
        ]);

        $this->assertFalse($validator->fails());
    }

    public function test_faculty_id_is_required(): void
    {
        $validator = $this->validate([
            'name' => 'Jane',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('faculty_id', $validator->errors()->toArray());
    }

    public function test_faculty_id_must_exist(): void
    {
        $validator = $this->validate([
            'faculty_id' => 999,
            'program_id' => 1,
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('faculty_id', $validator->errors()->toArray());
    }

    public function test_program_id_is_required(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);

        $validator = $this->validate([
            'faculty_id' => $faculty->id,
            'name' => 'Jane',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('program_id', $validator->errors()->toArray());
    }

    public function test_program_id_must_exist(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);

        $validator = $this->validate([
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

        $validator = $this->validate([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'mentor_id' => null,
            'name' => 'Jane',
            'lastName' => 'Doe',
            'phone' => '+37120000000',
            'email' => 'jane@example.com',
            'privacy' => true,
        ]);

        $this->assertFalse($validator->fails());
    }

    public function test_mentor_id_must_exist_if_provided(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        $validator = $this->validate([
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

        $validator = $this->validate([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'mentor_id' => $mentor->id,
            'name' => 'Jane',
            'lastName' => 'Doe',
            'phone' => '+37120000000',
            'email' => 'jane@example.com',
            'privacy' => true,
        ]);

        $this->assertFalse($validator->fails());
    }

    public function test_name_is_required(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        $validator = $this->validate([
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

        $validator = $this->validate([
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
        $validator = $this->validate([
            'phone' => '',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('phone', $validator->errors()->toArray());
    }

    public function test_email_is_required(): void
    {
        $validator = $this->validate([
            'email' => '',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    public function test_email_must_be_valid_email(): void
    {
        $validator = $this->validate([
            'email' => 'not-an-email',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    public function test_comment_is_nullable(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        $validator = $this->validate([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'name' => 'Jane',
            'lastName' => 'Doe',
            'phone' => '+37120000000',
            'email' => 'jane@example.com',
            'comment' => null,
            'privacy' => true,
        ]);

        $this->assertFalse($validator->fails());
    }

    public function test_lang_is_nullable(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        $validator = $this->validate([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'name' => 'Jane',
            'lastName' => 'Doe',
            'phone' => '+37120000000',
            'email' => 'jane@example.com',
            'lang' => null,
            'privacy' => true,
        ]);

        $this->assertFalse($validator->fails());
    }

    public function test_privacy_is_required(): void
    {
        $validator = $this->validate([
            'privacy' => null,
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('privacy', $validator->errors()->toArray());
    }

    public function test_authorization_returns_true(): void
    {
        $request = new StudentCreateRequest();

        $this->assertTrue($request->authorize());
    }
}
