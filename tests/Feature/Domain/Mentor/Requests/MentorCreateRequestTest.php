<?php

namespace Tests\Feature\Domain\Mentor\Requests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Mentor\Requests\MentorCreateRequest;
use src\Domain\Program\Models\Program;
use Tests\TestCase;

class MentorCreateRequestTest extends TestCase
{
    use RefreshDatabase;

    private function validate(array $data): \Illuminate\Validation\Validator
    {
        $request = new MentorCreateRequest();

        return Validator::make($data, $request->rules());
    }

    public function test_valid_request_passes_validation(): void
    {
        Storage::fake('public');

        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        $validator = $this->validate([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'name' => 'John',
            'lastName' => 'Doe',
            'phone' => '+37120000000',
            'email' => 'john@example.com',
            'mentees' => 3,
            'year' => 2,
            'about' => 'About me',
            'why' => 'Why I want to mentor',
            'lv' => true,
            'ru' => false,
            'en' => true,
            'privacy' => true,
            'img' => UploadedFile::fake()->image('avatar.jpg'),
        ]);

        $this->assertFalse($validator->fails());
    }

    public function test_faculty_id_is_required(): void
    {
        $validator = $this->validate([
            'name' => 'John',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('faculty_id', $validator->errors()->toArray());
    }

    public function test_faculty_id_must_exist(): void
    {
        $validator = $this->validate([
            'faculty_id' => 999,
            'program_id' => 1,
            'name' => 'John',
            'lastName' => 'Doe',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('faculty_id', $validator->errors()->toArray());
    }

    public function test_program_id_is_required(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);

        $validator = $this->validate([
            'faculty_id' => $faculty->id,
            'name' => 'John',
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
            'name' => 'John',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('program_id', $validator->errors()->toArray());
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
            'name' => 'John',
            'lastName' => '',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('lastName', $validator->errors()->toArray());
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

    public function test_mentees_is_required(): void
    {
        $validator = $this->validate([
            'mentees' => null,
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('mentees', $validator->errors()->toArray());
    }

    public function test_mentees_minimum_is_one(): void
    {
        $validator = $this->validate([
            'mentees' => 0,
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('mentees', $validator->errors()->toArray());
    }

    public function test_mentees_maximum_is_five(): void
    {
        $validator = $this->validate([
            'mentees' => 6,
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('mentees', $validator->errors()->toArray());
    }

    public function test_year_is_required(): void
    {
        $validator = $this->validate([
            'year' => null,
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('year', $validator->errors()->toArray());
    }

    public function test_about_is_required(): void
    {
        $validator = $this->validate([
            'about' => '',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('about', $validator->errors()->toArray());
    }

    public function test_why_is_required(): void
    {
        $validator = $this->validate([
            'why' => '',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('why', $validator->errors()->toArray());
    }

    public function test_privacy_is_required(): void
    {
        $validator = $this->validate([
            'privacy' => null,
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('privacy', $validator->errors()->toArray());
    }

    public function test_img_is_required(): void
    {
        $validator = $this->validate([
            'img' => null,
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('img', $validator->errors()->toArray());
    }

    public function test_img_must_be_valid_image_type(): void
    {
        Storage::fake('public');

        $validator = $this->validate([
            'img' => UploadedFile::fake()->create('document.pdf', 100),
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('img', $validator->errors()->toArray());
    }

    public function test_authorization_returns_true(): void
    {
        $request = new MentorCreateRequest();

        $this->assertTrue($request->authorize());
    }
}
