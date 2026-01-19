<?php

use App\Models\Student;

it('Cant exists 2 students with the same email', function () {
    $student = Student::factory()->create([
        'email' => 'example@gmail.com'
    ]);
    expect( fn() => Student::factory()->create([
        'email' => 'example@gmail.com'
    ]))->toThrow(Illuminate\Database\QueryException::class);
});
