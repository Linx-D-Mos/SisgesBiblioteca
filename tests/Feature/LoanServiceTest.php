<?php

use App\Models\Book;
use App\Models\Loan;
use App\Models\Student;
use App\Services\LoanService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);
it('failed when load a book with 0 stock', function () {
    $loanService = app(LoanService::class);
    $book = Book::factory()->create([
        "stock" => 0,
    ]);
    $student = Student::factory()->create();
    expect(fn() => $loanService->createLoan($student, $book))
        ->toThrow(Exception::class, 'El libro '. $book->title .'no cuenta con suficiente stock.');
})->group('CreateLoan');

it('fail when a student with 3 o more books try to loan', function () {
    $loanService = app(LoanService::class);
    $book = Book::factory()->create(['stock' => 3]);
    $student = Student::factory()->create();
    for ($i = 0; $i < 3; $i++) {
        $date = fake()->dateTimeBetween('-30 days', '-2 days');
        Loan::create([
            'book_id' => Book::factory()->create()->id,
            'student_id' => $student->id,
            'loaned_at' => $date,
            'returned_at' => null,
        ]);
    }
    expect(fn() => $loanService->createLoan($student,$book))
    ->toThrow(Exception::class, 'El estudiante ya tiene 3 libros en prestamo.');
})->group('CreateLoan');

it('fail when tries to returned a book that was returned', function(){
    $loanService = app(LoanService::class);
    $loan = Loan::factory()->create([
        'returned_at' => now(),
    ]);
    expect(fn() => $loanService->returnLoan($loan))
        ->toThrow(Exception::class, 'Este libro ya fue devuelto');
})->group('return Loan');