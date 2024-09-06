<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Book;
use App\Models\BorrowRecord;
use App\Models\User;

class BorrowRecordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BorrowRecord::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'book_id' => Book::factory(),
            'borrowed_at' => $this->faker->dateTime(),
            'due_at' => $this->faker->dateTime(),
            'returned_at' => $this->faker->dateTime(),
        ];
    }
}
