<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Author;
use App\Models\Book;

class BookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'isbn' => $this->faker->word(),
            'published_date' => $this->faker->dateTime(),
            'author_id' => Author::factory(),
            'status' => $this->faker->randomElement(["AVAILABLE","BORROWED"]),
        ];
    }
}
