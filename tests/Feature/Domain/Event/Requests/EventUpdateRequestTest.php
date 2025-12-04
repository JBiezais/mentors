<?php

namespace Tests\Feature\Domain\Event\Requests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use src\Domain\Event\Requests\EventUpdateRequest;
use Tests\TestCase;

class EventUpdateRequestTest extends TestCase
{
    use RefreshDatabase;

    private function validate(array $data): \Illuminate\Validation\Validator
    {
        $request = new EventUpdateRequest();

        return Validator::make($data, $request->rules());
    }

    public function test_valid_request_passes_validation(): void
    {
        $validator = $this->validate([
            'id' => 1,
            'title' => 'Updated Event',
            'date' => '2024-01-15',
        ]);

        $this->assertFalse($validator->fails());
    }

    public function test_valid_request_with_all_fields_passes_validation(): void
    {
        $validator = $this->validate([
            'id' => 1,
            'title' => 'Updated Event',
            'date' => '2024-01-15',
            'location' => 'Main Hall',
            'mentors_training' => true,
            'mentees_applying' => true,
            'description' => 'Event description',
            'link' => 'https://example.com',
        ]);

        $this->assertFalse($validator->fails());
    }

    public function test_id_is_required(): void
    {
        $validator = $this->validate([
            'title' => 'Updated Event',
            'date' => '2024-01-15',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('id', $validator->errors()->toArray());
    }

    public function test_id_must_be_integer(): void
    {
        $validator = $this->validate([
            'id' => 'not-an-integer',
            'title' => 'Updated Event',
            'date' => '2024-01-15',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('id', $validator->errors()->toArray());
    }

    public function test_title_is_required(): void
    {
        $validator = $this->validate([
            'id' => 1,
            'title' => '',
            'date' => '2024-01-15',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('title', $validator->errors()->toArray());
    }

    public function test_title_must_be_string(): void
    {
        $validator = $this->validate([
            'id' => 1,
            'title' => 12345,
            'date' => '2024-01-15',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('title', $validator->errors()->toArray());
    }

    public function test_date_is_required(): void
    {
        $validator = $this->validate([
            'id' => 1,
            'title' => 'Updated Event',
            'date' => '',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('date', $validator->errors()->toArray());
    }

    public function test_location_is_nullable(): void
    {
        $validator = $this->validate([
            'id' => 1,
            'title' => 'Updated Event',
            'date' => '2024-01-15',
            'location' => null,
        ]);

        $this->assertFalse($validator->fails());
    }

    public function test_mentors_training_is_nullable(): void
    {
        $validator = $this->validate([
            'id' => 1,
            'title' => 'Updated Event',
            'date' => '2024-01-15',
            'mentors_training' => null,
        ]);

        $this->assertFalse($validator->fails());
    }

    public function test_mentors_training_must_be_boolean(): void
    {
        $validator = $this->validate([
            'id' => 1,
            'title' => 'Updated Event',
            'date' => '2024-01-15',
            'mentors_training' => 'not-boolean',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('mentors_training', $validator->errors()->toArray());
    }

    public function test_mentees_applying_is_nullable(): void
    {
        $validator = $this->validate([
            'id' => 1,
            'title' => 'Updated Event',
            'date' => '2024-01-15',
            'mentees_applying' => null,
        ]);

        $this->assertFalse($validator->fails());
    }

    public function test_mentees_applying_must_be_boolean(): void
    {
        $validator = $this->validate([
            'id' => 1,
            'title' => 'Updated Event',
            'date' => '2024-01-15',
            'mentees_applying' => 'not-boolean',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('mentees_applying', $validator->errors()->toArray());
    }

    public function test_description_is_nullable(): void
    {
        $validator = $this->validate([
            'id' => 1,
            'title' => 'Updated Event',
            'date' => '2024-01-15',
            'description' => null,
        ]);

        $this->assertFalse($validator->fails());
    }

    public function test_link_is_nullable(): void
    {
        $validator = $this->validate([
            'id' => 1,
            'title' => 'Updated Event',
            'date' => '2024-01-15',
            'link' => null,
        ]);

        $this->assertFalse($validator->fails());
    }

    public function test_authorization_returns_true(): void
    {
        $request = new EventUpdateRequest();

        $this->assertTrue($request->authorize());
    }
}
