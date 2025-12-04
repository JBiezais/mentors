<?php

namespace Tests\Feature\Domain\User\Actions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use src\Domain\User\Actions\UserCreateAction;
use src\Domain\User\DTO\UserCreateData;
use src\Domain\User\Models\User;
use Tests\TestCase;

class UserCreateActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_user_with_valid_data(): void
    {
        $data = UserCreateData::from([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '12345678',
            'password' => 'password123',
        ]);

        UserCreateAction::execute($data);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '12345678',
        ]);
    }

    public function test_it_encrypts_password_on_creation(): void
    {
        $plainPassword = 'secret_password';

        $data = UserCreateData::from([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '87654321',
            'password' => $plainPassword,
        ]);

        UserCreateAction::execute($data);

        $user = User::where('email', 'jane@example.com')->first();

        $this->assertNotEquals($plainPassword, $user->password);
        $this->assertTrue(Hash::check($plainPassword, $user->password));
    }

    public function test_it_creates_user_with_all_required_fields(): void
    {
        $data = UserCreateData::from([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '99999999',
            'password' => 'testpassword',
        ]);

        UserCreateAction::execute($data);

        $user = User::where('email', 'test@example.com')->first();

        $this->assertNotNull($user);
        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertEquals('99999999', $user->phone);
    }

    public function test_it_creates_multiple_users_with_different_emails(): void
    {
        $initialCount = User::count();

        $data1 = UserCreateData::from([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'phone' => '11111111',
            'password' => 'password1',
        ]);

        $data2 = UserCreateData::from([
            'name' => 'User Two',
            'email' => 'user2@example.com',
            'phone' => '22222222',
            'password' => 'password2',
        ]);

        UserCreateAction::execute($data1);
        UserCreateAction::execute($data2);

        $this->assertDatabaseCount('users', $initialCount + 2);
        $this->assertDatabaseHas('users', ['email' => 'user1@example.com']);
        $this->assertDatabaseHas('users', ['email' => 'user2@example.com']);
    }
}
