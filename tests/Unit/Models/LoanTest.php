<?php

use App\Models\Loan;
use Illuminate\Support\Carbon;

it('Has cast in the model and is attacht to students and book', function(){
    $loan = Loan::factory()->create();
    expect($loan->loaned_at)
    ->toBeInstanceOf(Illuminate\Support\Carbon::class);
    expect($loan->student)
    ->not()
    ->toBeNull();
    expect($loan->student_id)
    ->toBe($loan->student->id);
});
