<?php

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can realeated authors to a book', function () {
    $author = Author::factory()->create();
    $book = Book::factory()->hasAttached($author)->create();
    expect($book->authors)->toHaveCount(1);
    expect($book->authors->first()->id)->toBe($author->id);
});
