<?php

namespace Tests\Feature\Domain\User\Requests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use src\Domain\User\Models\User;
use src\Domain\User\Requests\UserCreateRequest;
use Tests\TestCase;

class UserCreateRequestTest extends TestCase
{
    use RefreshDatabase;

    private function validate(array $data): \Illuminate\Validation\Validator
    {
        $request = new UserCreateRequest();

        return Validator::make($data, $request->rules());
    }

    public function test_valid_request_passes_validation(): void
    {
        $validator = $this->validate([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '12345678',
            'password' => 'password123',
        ]);

        $this->assertFalse($validator->fails());
    }

    public function test_name_is_required(): void
    {
        $validator = $this->validate([
            'name' => '',
            'email' => 'john@example.com',
            'phone' => '12345678',
            'password' => 'password123',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
    }

    public function test_name_must_be_string(): void
    {
        $validator = $this->validate([
            'name' => 12345,
            'email' => 'john@example.com',
            'phone' => '12345678',
            'password' => 'password123',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
    }

    public function test_email_is_required(): void
    {
        $validator = $this->validate([
            'name' => 'John Doe',
            'email' => '',
            'phone' => '12345678',
            'password' => 'password123',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    public function test_email_must_be_valid_email(): void
    {
        $validator = $this->validate([
            'name' => 'John Doe',
            'email' => 'not-an-email',
            'phone' => '12345678',
            'password' => 'password123',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    public function test_email_must_be_unique(): void
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $validator = $this->validate([
            'name' => 'John Doe',
            'email' => 'existing@example.com',
            'phone' => '12345678',
            'password' => 'password123',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    public function test_phone_is_required(): void
    {
        $validator = $this->validate([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '',
            'password' => 'password123',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('phone', $validator->errors()->toArray());
    }

    public function test_phone_minimum_length_is_8(): void
    {
        $validator = $this->validate([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567',
            'password' => 'password123',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('phone', $validator->errors()->toArray());
    }

    public function test_phone_with_8_characters_passes(): void
    {
        $validator = $this->validate([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '12345678',
            'password' => 'password123',
        ]);

        $this->assertFalse($validator->fails());
    }

    public function test_password_is_required(): void
    {
        $validator = $this->validate([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '12345678',
            'password' => '',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }

    public function test_password_minimum_length_is_8(): void
    {
        $validator = $this->validate([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '12345678',
            'password' => '1234567',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }

    public function test_password_with_8_characters_passes(): void
    {
        $validator = $this->validate([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '12345678',
            'password' => '12345678',
        ]);

        $this->assertFalse($validator->fails());
    }

    public function test_authorization_returns_true(): void
    {
        $request = new UserCreateRequest();

        $this->assertTrue($request->authorize());
    }
}
