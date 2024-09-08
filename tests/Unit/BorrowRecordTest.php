<?php

namespace Tests\Unit;

use App\Enums\UserRole;
use App\Models\BorrowRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BorrowRecordTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_borrow_records_as_admin()
    {
        $admin = User::factory()->create(['role' => 'ADMIN']);
        $this->actingAs($admin);

        $response = $this->get('/api/borrow-records');
        $response->assertStatus(200);
    }

    public function test_get_borrow_record_by_id_as_librarian()
    {
        $librarian = User::factory()->create(['role' => UserRole::LIBRARIAN]);
        $this->actingAs($librarian);

        $borrowRecord = BorrowRecord::factory()->create(); // Assuming you have a BorrowRecord model

        $response = $this->get("/api/borrow-records/{$borrowRecord->id}");
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
