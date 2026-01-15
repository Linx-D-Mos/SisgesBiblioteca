<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $authors = Author::factory(10)->create();
        $books = Book::factory(15)->create()->each(function ($book) use ($authors) {
            $book->authors()->attach($authors->random(rand(1, 3)));
        });
        $students = Student::factory(20)->create();
        foreach ($students as $student) {
            if (rand(1, 2) == 1) {
                $loansCount = rand(1, 3);
                for ($i = 1; $i <= $loansCount; $i++) {
                    Loan::factory()
                        ->create([
                            'book_id' => $books->random()->id,
                            'student_id' => $student->id,
                            'returned_at' => (rand(1, 2) == 1) ? now() : null,
                        ]);
                }
            }
        }
    }
}
