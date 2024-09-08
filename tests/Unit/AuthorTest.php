<?php

namespace Tests\Unit;

use App\Models\Author;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_authors()
    {
        $response = $this->get('/api/authors');
        $response->assertStatus(200);
    }

    public function test_get_author_by_id()
    {
        $author = Author::factory()->create();

        $response = $this->get("/api/authors/{$author->id}");
        $response->assertStatus(200);
        $response->assertJson(['data' => ['id' => $author->id]]);
    }

    public function test_create_author_as_admin()
    {
        $admin = User::factory()->create(['role' => 'ADMIN']);
        $this->actingAs($admin);

        $response = $this->post('/api/authors', [
            'name' => 'New Author',
            'bio' => "hello",
            "birthdate" => Carbon::now()
        ]);

        $response->assertStatus(201);
    }

    public function test_update_author_as_admin()
    {
        $admin = User::factory()->create(['role' => 'ADMIN']);
        $author = Author::factory()->create();
        $this->actingAs($admin);

        $response = $this->put("/api/authors/{$author->id}", [
            'name' => 'Updated Author Name',
            'bio' => "hello"
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('authors', ['id' => $author->id, 'name' => 'Updated Author Name']);
    }

    public function test_delete_author_as_admin()
    {
        $admin = User::factory()->create(['role' => 'ADMIN']);
        $author = Author::factory()->create();
        $this->actingAs($admin);

        $response = $this->delete("/api/authors/{$author->id}");

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
