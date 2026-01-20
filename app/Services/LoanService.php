<?php

namespace App\Services;

use App\Http\Resources\Loan\LoanResource;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Student;
use Exception;
use Illuminate\Support\Facades\DB;

use function Symfony\Component\Clock\now;

class LoanService
{
    //Recibimos 1 estudiante, 1 libro y devolvemos un prestamo, este prestamo lo usaremos luego en el LoanResource
    public function createLoan(Student $student, Book $book): Loan
    {
        //llamamos la funcion auxiliar de verificar el stock de un libro para comprobar que al menos tenga 1 libro 
        if (!$this->verifyngBookStock($book)) {

            throw new Exception('El libro no cuenta con suficiente stock.');
        }
        //Luego usamos la funcion de ver si el estudiante tiene credito disponible, osea que tiene 2 o menos libros en
        //sin devolver.
        if (!$this->verifyngStudentCredit($student)) {

            throw new Exception('El estudiante ya tiene 3 libros en prestamo.');
        }
        //deduje yo que era necesario crear una variable del tipo Loan para poder usarla en la DB transaction,
        //    ya que crearla adentro no tenía sentido para mi

        $loan = DB::transaction(function () use ($book, $student) {
                //llame a nuestro book y accedi a su funcion de update y cambie el valor de stock en 1... 
                $book->decrement('stock');
                //ahora creamos nuestra deuda de verdad
                $loan = Loan::create([
                    //hacemos sync a las relaciones presentes en la deuda.
                    'book_id' => $book->id,
                    'student_id' => $student->id,
                    'loaned_at' => now(),
                    'returned_at' => null,
                ]);
                //retornamos el objeto de deuda en conjunto con sus relaciones.
                return $loan->load('student', 'book');
            }
        );
        return $loan;
    }
    public function verifyngBookStock(Book $book)
    {
        return $book->stock > 0;
    }
    public function verifyngStudentCredit(Student $student)
    {
        
        return $student->loans()->where('returned_at', NULL)->count() < 3;
    }
}
