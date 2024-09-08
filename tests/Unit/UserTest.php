<?php

namespace Tests\Unit;

use App\Enums\BookStatus;
use App\Enums\UserRole;
use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;
use App\Models\BorrowRecord;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_user()
    {
        $response = $this->post('/api/users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

    public function test_login_user()
    {
        $user = User::factory()->create(['password' => Hash::make('password123'), 'role' => UserRole::MEMBER]);

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['token']]);
    }

    public function test_get_users_as_admin()
    {
        $admin = User::factory()->create(['role' => 'ADMIN']);
        $this->actingAs($admin);

        $response = $this->get('/api/users');
        $response->assertStatus(200);
    }

    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }
}
