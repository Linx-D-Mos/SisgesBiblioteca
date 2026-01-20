<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Loan>
 */
class LoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        //Generate a random date/time within a spacific range
        $date = $this->faker->dateTimeBetween('-30 days', '-2 days');
        return [
            'book_id' => Book::factory()->create()->id,
            'student_id' => Student::factory()->create()->id,
            'loaned_at' => $date,
            'returned_at' => (rand(1, 2) == 1) ? now() : null,
        ];
    }
}
