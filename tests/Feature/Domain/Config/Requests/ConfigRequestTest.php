<?php

namespace Tests\Feature\Domain\Config\Requests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use src\Domain\Config\Requests\ConfigRequest;
use Tests\TestCase;

class ConfigRequestTest extends TestCase
{
    use RefreshDatabase;

    private function validate(array $data): \Illuminate\Validation\Validator
    {
        $request = new ConfigRequest();

        return Validator::make($data, $request->rules());
    }

    public function test_valid_request_passes_validation(): void
    {
        $validator = $this->validate([
            'color' => '#f43f5e',
        ]);

        $this->assertFalse($validator->fails());
    }

    public function test_color_rejects_invalid_hex(): void
    {
        $validator = $this->validate([
            'color' => '#ff',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('color', $validator->errors()->toArray());
    }

    public function test_color_rejects_non_hex_string(): void
    {
        $validator = $this->validate([
            'color' => 'invalid_scheme',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('color', $validator->errors()->toArray());
    }

    public function test_color_is_required(): void
    {
        $validator = $this->validate([
            'color' => '',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('color', $validator->errors()->toArray());
    }

    public function test_color_must_be_string(): void
    {
        $validator = $this->validate([
            'color' => 12345,
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('color', $validator->errors()->toArray());
    }

    public function test_authorization_returns_true(): void
    {
        $request = new ConfigRequest();

        $this->assertTrue($request->authorize());
    }

    public function test_missing_all_fields_fails(): void
    {
        $validator = $this->validate([]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('color', $validator->errors()->toArray());
    }
}
