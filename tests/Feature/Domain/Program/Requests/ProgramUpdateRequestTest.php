<?php

namespace Tests\Feature\Domain\Program\Requests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Program\Models\Program;
use src\Domain\Program\Requests\ProgramUpdateRequest;
use Tests\TestCase;

class ProgramUpdateRequestTest extends TestCase
{
    use RefreshDatabase;

    private function validate(array $data): \Illuminate\Validation\Validator
    {
        $request = new ProgramUpdateRequest();

        return Validator::make($data, $request->rules());
    }

    public function test_valid_request_passes_validation(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create([
            'faculty_id' => $faculty->id,
            'code' => 'CS',
        ]);

        $validator = $this->validate([
            'id' => $program->id,
            'faculty_id' => $faculty->id,
            'title' => 'Updated Computer Science',
            'code' => 'CS',
            'level' => 'pamatstudijas',
        ]);

        $this->assertFalse($validator->fails());
    }

    public function test_id_is_required(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);

        $validator = $this->validate([
            'faculty_id' => $faculty->id,
            'title' => 'Computer Science',
            'code' => 'CS',
            'level' => 'pamatstudijas',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('id', $validator->errors()->toArray());
    }

    public function test_id_must_be_integer(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);

        $validator = $this->validate([
            'id' => 'not-an-integer',
            'faculty_id' => $faculty->id,
            'title' => 'Computer Science',
            'code' => 'CS',
            'level' => 'pamatstudijas',
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
            'title' => 'Computer Science',
            'code' => 'CS',
            'level' => 'pamatstudijas',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('id', $validator->errors()->toArray());
    }

    public function test_faculty_id_is_required(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create([
            'faculty_id' => $faculty->id,
            'code' => 'CS',
        ]);

        $validator = $this->validate([
            'id' => $program->id,
            'title' => 'Computer Science',
            'code' => 'CS',
            'level' => 'pamatstudijas',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('faculty_id', $validator->errors()->toArray());
    }

    public function test_faculty_id_must_exist(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create([
            'faculty_id' => $faculty->id,
            'code' => 'CS',
        ]);

        $validator = $this->validate([
            'id' => $program->id,
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
        $program = Program::factory()->create([
            'faculty_id' => $faculty->id,
            'code' => 'CS',
        ]);

        $validator = $this->validate([
            'id' => $program->id,
            'faculty_id' => $faculty->id,
            'title' => '',
            'code' => 'CS',
            'level' => 'pamatstudijas',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('title', $validator->errors()->toArray());
    }

    public function test_code_is_required(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create([
            'faculty_id' => $faculty->id,
            'code' => 'CS',
        ]);

        $validator = $this->validate([
            'id' => $program->id,
            'faculty_id' => $faculty->id,
            'title' => 'Computer Science',
            'code' => '',
            'level' => 'pamatstudijas',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('code', $validator->errors()->toArray());
    }

    public function test_level_is_required(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create([
            'faculty_id' => $faculty->id,
            'code' => 'CS',
        ]);

        $validator = $this->validate([
            'id' => $program->id,
            'faculty_id' => $faculty->id,
            'title' => 'Computer Science',
            'code' => 'CS',
            'level' => '',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('level', $validator->errors()->toArray());
    }

    public function test_authorization_returns_true(): void
    {
        $request = new ProgramUpdateRequest();

        $this->assertTrue($request->authorize());
    }
}
