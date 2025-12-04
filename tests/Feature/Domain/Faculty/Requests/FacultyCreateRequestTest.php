<?php

namespace Tests\Feature\Domain\Faculty\Requests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use src\Domain\Faculty\Requests\FacultyCreateRequest;
use Tests\TestCase;

class FacultyCreateRequestTest extends TestCase
{
    use RefreshDatabase;

    private function validate(array $data): \Illuminate\Validation\Validator
    {
        $request = new FacultyCreateRequest();

        return Validator::make($data, $request->rules());
    }

    public function test_valid_request_passes_validation(): void
    {
        $validator = $this->validate([
            'title' => 'Faculty of Engineering',
            'code' => 'FE',
        ]);

        $this->assertFalse($validator->fails());
    }

    public function test_title_is_required(): void
    {
        $validator = $this->validate([
            'title' => '',
            'code' => 'FE',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('title', $validator->errors()->toArray());
    }

    public function test_title_must_be_string(): void
    {
        $validator = $this->validate([
            'title' => 12345,
            'code' => 'FE',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('title', $validator->errors()->toArray());
    }

    public function test_code_is_required(): void
    {
        $validator = $this->validate([
            'title' => 'Faculty of Engineering',
            'code' => '',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('code', $validator->errors()->toArray());
    }

    public function test_code_must_be_string(): void
    {
        $validator = $this->validate([
            'title' => 'Faculty of Engineering',
            'code' => 12345,
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('code', $validator->errors()->toArray());
    }

    public function test_authorization_returns_true(): void
    {
        $request = new FacultyCreateRequest();

        $this->assertTrue($request->authorize());
    }

    public function test_missing_title_and_code_fails(): void
    {
        $validator = $this->validate([]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('title', $validator->errors()->toArray());
        $this->assertArrayHasKey('code', $validator->errors()->toArray());
    }
}
