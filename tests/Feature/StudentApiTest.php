<?php

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson as postJsonRequest;

uses(RefreshDatabase::class);

it('creates a student in the DB', function () {
    $student_data = [
        'student_code' => fake()->unique()->bothify('???-######'),
        'name' => fake()->firstName(),
        'last_name' => fake()->lastName(),
        'email' => fake()->unique()->safeEmail(),
    ];
    $response = $this->postJson('/api/students', $student_data);
    assertDatabaseCount('students', 1);
    assertDatabaseHas('students', $student_data);
    $response->assertStatus(201);
})->group('student');

it('show all the students', function(){
    $students = Student::factory(15)->create();
    $response = $this->getJson('/api/students');
    $response->assertJsonStructure([
        'data' => [],
        'links' => [],
        'meta' => [],
    ])->assertJsonCount(10,'data');
    $response->assertStatus(200);

})->group('student');