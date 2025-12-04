<?php

namespace Tests\Feature\Domain\Program\Requests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Program\Requests\ProgramCreateRequest;
use Tests\TestCase;

class ProgramCreateRequestTest extends TestCase
{
    use RefreshDatabase;

    private function validate(array $data): \Illuminate\Validation\Validator
    {
        $request = new ProgramCreateRequest();

        return Validator::make($data, $request->rules());
    }

    public function test_valid_request_passes_validation(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);

        $validator = $this->validate([
            'faculty_id' => $faculty->id,
            'title' => 'Computer Science',
            'code' => 'CS',
            'level' => 'pamatstudijas',
        ]);

        $this->assertFalse($validator->fails());
    }

    public function test_faculty_id_is_required(): void
    {
        $validator = $this->validate([
            'title' => 'Computer Science',
            'code' => 'CS',
            'level' => 'pamatstudijas',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('faculty_id', $validator->errors()->toArray());
    }

    public function test_faculty_id_must_be_integer(): void
    {
        $validator = $this->validate([
            'faculty_id' => 'not-an-integer',
            'title' => 'Computer Science',
            'code' => 'CS',
            'level' => 'pamatstudijas',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('faculty_id', $validator->errors()->toArray());
    }

    public function test_faculty_id_must_exist(): void
    {
        $validator = $this->validate([
            'faculty_id' => 999,
            'title' => 'Computer Science',
            'code' => 'CS',
            'level' => 'pamatstudijas',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('faculty_id', $validator->errors()->toArray());
    }

    public function test_title_is_required(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);

        $validator = $this->validate([
            'faculty_id' => $faculty->id,
            'title' => '',
            'code' => 'CS',
            'level' => 'pamatstudijas',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('title', $validator->errors()->toArray());
    }

    public function test_title_must_be_string(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);

        $validator = $this->validate([
            'faculty_id' => $faculty->id,
            'title' => 12345,
            'code' => 'CS',
            'level' => 'pamatstudijas',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('title', $validator->errors()->toArray());
    }

    public function test_code_is_required(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);

        $validator = $this->validate([
            'faculty_id' => $faculty->id,
            'title' => 'Computer Science',
            'code' => '',
            'level' => 'pamatstudijas',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('code', $validator->errors()->toArray());
    }

    public function test_code_must_be_string(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);

        $validator = $this->validate([
            'faculty_id' => $faculty->id,
            'title' => 'Computer Science',
            'code' => 12345,
            'level' => 'pamatstudijas',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('code', $validator->errors()->toArray());
    }

    public function test_level_is_required(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);

        $validator = $this->validate([
            'faculty_id' => $faculty->id,
            'title' => 'Computer Science',
            'code' => 'CS',
            'level' => '',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('level', $validator->errors()->toArray());
    }

    public function test_level_must_be_string(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);

        $validator = $this->validate([
            'faculty_id' => $faculty->id,
            'title' => 'Computer Science',
            'code' => 'CS',
            'level' => 12345,
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('level', $validator->errors()->toArray());
    }

    public function test_authorization_returns_true(): void
    {
        $request = new ProgramCreateRequest();

        $this->assertTrue($request->authorize());
    }
}
