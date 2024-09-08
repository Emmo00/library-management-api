<?php

namespace Tests\Unit;

use App\Enums\BookStatus;
use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;
use App\Models\BorrowRecord;
use App\Models\User;
use Illuminate\Support\Carbon;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_books()
    {
        $response = $this->get('/api/books');
        $response->assertStatus(200);
    }

    public function test_get_book_by_id()
    {
        $book = Book::factory()->create();

        $response = $this->get("/api/books/{$book->id}");
        $response->assertStatus(200);
        $response->assertJson(['data' => ['id' => $book->id]]);
    }

    public function test_create_book_as_admin()
    {
        $admin = User::factory()->create(['role' => 'ADMIN']);
        $author = Author::factory()->create();

        $response = $this->actingAs($admin)
            ->withHeader("Accept", "application/json")
            ->post('/api/books', [
                'title' => 'New Book',
                'isbn' => '123456789',
                'author_id' => $author->id,
                'published_date' => '2023-01-01',
            ]);

        $response->assertStatus(201);
    }

    public function test_update_book_as_admin()
    {
        $admin = User::factory()->create(['role' => 'ADMIN']);
        $book = Book::factory()->create();
        $this->actingAs($admin);

        $response = $this->put("/api/books/{$book->id}", [
            'title' => 'Updated Book Title',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('books', ['id' => $book->id, 'title' => 'Updated Book Title']);
    }

    public function test_delete_book_as_admin()
    {
        $admin = User::factory()->create(['role' => 'ADMIN']);
        $book = Book::factory()->create();
        $this->actingAs($admin);

        $response = $this->delete("/api/books/{$book->id}");

        $response->assertStatus(200);
    }

    public function test_borrow_book_as_member()
    {
        $member = User::factory()->create(['role' => 'MEMBER']);
        $book = Book::factory()->create();
        $this->actingAs($member)->withHeader("Accept", "application/json");

        $response = $this->post("/api/books/{$book->id}/borrow", [
            'due_date' => Carbon::parse("next week monday")->toDateString(),
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('books', ['id' => $book->id, 'status' => BookStatus::BORROWED]);
    }

    public function test_return_borrowed_book_as_member()
    {
        $member = User::factory()->create(['role' => 'MEMBER']);
        $book = Book::factory()->create(['status' => BookStatus::BORROWED]);
        $borrowRecord = BorrowRecord::factory()->create([
            'user_id' => $member->id,
            'book_id' => $book->id,
        ]);
        $this->actingAs($member);

        $response = $this->post("/api/books/{$book->id}/return");

        $response->assertStatus(200);
        $this->assertDatabaseHas('books', ['id' => $book->id, 'status' => BookStatus::AVAILABLE]);
    }
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }
}
