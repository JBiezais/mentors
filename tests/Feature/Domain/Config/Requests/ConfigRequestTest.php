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
            'color' => '#ffffff',
            'banner' => ['banner_image.jpg'],
            'background' => ['background_image.jpg'],
        ]);

        $this->assertFalse($validator->fails());
    }

    public function test_color_is_required(): void
    {
        $validator = $this->validate([
            'color' => '',
            'banner' => ['banner.jpg'],
            'background' => ['background.jpg'],
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('color', $validator->errors()->toArray());
    }

    public function test_color_must_be_string(): void
    {
        $validator = $this->validate([
            'color' => 12345,
            'banner' => ['banner.jpg'],
            'background' => ['background.jpg'],
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('color', $validator->errors()->toArray());
    }

    public function test_banner_is_required(): void
    {
        $validator = $this->validate([
            'color' => '#ffffff',
            'banner' => null,
            'background' => ['background.jpg'],
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('banner', $validator->errors()->toArray());
    }

    public function test_banner_must_be_array(): void
    {
        $validator = $this->validate([
            'color' => '#ffffff',
            'banner' => 'not-an-array',
            'background' => ['background.jpg'],
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('banner', $validator->errors()->toArray());
    }

    public function test_banner_must_have_at_least_one_item(): void
    {
        $validator = $this->validate([
            'color' => '#ffffff',
            'banner' => [],
            'background' => ['background.jpg'],
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('banner', $validator->errors()->toArray());
    }

    public function test_banner_must_have_at_most_one_item(): void
    {
        $validator = $this->validate([
            'color' => '#ffffff',
            'banner' => ['banner1.jpg', 'banner2.jpg'],
            'background' => ['background.jpg'],
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('banner', $validator->errors()->toArray());
    }

    public function test_background_is_required(): void
    {
        $validator = $this->validate([
            'color' => '#ffffff',
            'banner' => ['banner.jpg'],
            'background' => null,
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('background', $validator->errors()->toArray());
    }

    public function test_background_must_be_array(): void
    {
        $validator = $this->validate([
            'color' => '#ffffff',
            'banner' => ['banner.jpg'],
            'background' => 'not-an-array',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('background', $validator->errors()->toArray());
    }

    public function test_background_must_have_at_least_one_item(): void
    {
        $validator = $this->validate([
            'color' => '#ffffff',
            'banner' => ['banner.jpg'],
            'background' => [],
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('background', $validator->errors()->toArray());
    }

    public function test_background_must_have_at_most_one_item(): void
    {
        $validator = $this->validate([
            'color' => '#ffffff',
            'banner' => ['banner.jpg'],
            'background' => ['bg1.jpg', 'bg2.jpg'],
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('background', $validator->errors()->toArray());
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
        $this->assertArrayHasKey('banner', $validator->errors()->toArray());
        $this->assertArrayHasKey('background', $validator->errors()->toArray());
    }
}
