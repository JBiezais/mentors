<?php

namespace Tests\Feature\Domain\Mentor\Requests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Mentor\Requests\MentorUpdateRequest;
use src\Domain\Program\Models\Program;
use Tests\TestCase;

class MentorUpdateRequestTest extends TestCase
{
    use RefreshDatabase;

    private function validate(array $data): \Illuminate\Validation\Validator
    {
        $request = new MentorUpdateRequest();

        return Validator::make($data, $request->rules());
    }

    public function test_valid_request_passes_validation(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $validator = $this->validate([
            'id' => $mentor->id,
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'phone' => '+37120000000',
            'email' => 'john@example.com',
            'year' => 2,
            'about' => 'About me',
            'why' => 'Why I want to mentor',
            'lv' => true,
            'ru' => false,
            'en' => true,
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
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $validator = $this->validate([
            'id' => $mentor->id,
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('faculty_id', $validator->errors()->toArray());
    }

    public function test_faculty_id_must_exist(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $validator = $this->validate([
            'id' => $mentor->id,
            'faculty_id' => 999,
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('faculty_id', $validator->errors()->toArray());
    }

    public function test_program_id_is_required(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $validator = $this->validate([
            'id' => $mentor->id,
            'faculty_id' => $faculty->id,
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('program_id', $validator->errors()->toArray());
    }

    public function test_program_id_must_exist(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $validator = $this->validate([
            'id' => $mentor->id,
            'faculty_id' => $faculty->id,
            'program_id' => 999,
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('program_id', $validator->errors()->toArray());
    }

    public function test_phone_is_required(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $validator = $this->validate([
            'id' => $mentor->id,
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'phone' => '',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('phone', $validator->errors()->toArray());
    }

    public function test_email_is_required(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $validator = $this->validate([
            'id' => $mentor->id,
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
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
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $validator = $this->validate([
            'id' => $mentor->id,
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'phone' => '+37120000000',
            'email' => 'not-an-email',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    public function test_year_is_required(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $validator = $this->validate([
            'id' => $mentor->id,
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'phone' => '+37120000000',
            'email' => 'john@example.com',
            'year' => null,
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('year', $validator->errors()->toArray());
    }

    public function test_about_is_required(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $validator = $this->validate([
            'id' => $mentor->id,
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'phone' => '+37120000000',
            'email' => 'john@example.com',
            'year' => 2,
            'about' => '',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('about', $validator->errors()->toArray());
    }

    public function test_why_is_required(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $validator = $this->validate([
            'id' => $mentor->id,
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'phone' => '+37120000000',
            'email' => 'john@example.com',
            'year' => 2,
            'about' => 'About me',
            'why' => '',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('why', $validator->errors()->toArray());
    }

    public function test_language_fields_are_nullable(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $validator = $this->validate([
            'id' => $mentor->id,
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'phone' => '+37120000000',
            'email' => 'john@example.com',
            'year' => 2,
            'about' => 'About me',
            'why' => 'Why me',
        ]);

        $this->assertFalse($validator->fails());
    }

    public function test_authorization_returns_true(): void
    {
        $request = new MentorUpdateRequest();

        $this->assertTrue($request->authorize());
    }
}
