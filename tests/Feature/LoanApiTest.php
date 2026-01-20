<?php

use App\Models\Book;
use App\Models\Loan;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\patch;
use function Pest\Laravel\patchJson;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);
it('returns 409 whe tryng to loan a book with no stock', function () {
    $student = Student::factory()->create();
    $book = Book::factory()->create([
        'stock' => 0
    ]);
    $response = postJson('/api/loans', [
        'student_id' => $student->id,
        'book_id' => $book->id,
    ]);
    $response->assertStatus(409)
        ->assertJsonFragment([
            'message' => 'El libro ' . $book->title . 'no cuenta con suficiente stock.'
        ]);
});
it('return a loaned book', function () {
    $book = Book::factory()->create(['stock' => 5]);
    $loan = Loan::factory()->create(
        [
            'book_id' => $book->id,
            'returned_at' => null
        ]
    );
    $response = patchJson("/api/loans/{$loan->id}/return");
    $response->dump();
    $response->assertStatus(200)
        ->assertJsonFragment([
            'book_id' => $loan->book->id,
        ]);
    //testea que el prestamo ya no aparezca como null
    $this->assertDatabaseMissing('loans',[
        'id' => $loan->id,
        'returned_at' => null
    ]);
    //Aqui se valida que realmente se haga la adición de 1 en el stock del libro devuelto.
    expect($book->fresh()->stock)->toBe(6);
});
