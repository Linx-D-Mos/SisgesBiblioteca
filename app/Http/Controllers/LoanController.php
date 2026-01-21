<?php

namespace App\Http\Controllers;

use App\Http\Requests\Loan\StoreLoanRequest;
use App\Http\Resources\Loan\LoanResource;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Student;
use App\Services\LoanService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLoanRequest $request, LoanService $loanService)
    {
        $validated = $request->validated();
        $student = Student::findOrFail($validated['student_id']);
        $book = Book::findOrFail($validated['book_id']);
        try {
            $loan = $loanService->createLoan($student, $book);
            return (new LoanResource($loan))
            ->additional([
                    'message' => '¡Libro prestado con exito!'
                ])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
        } catch (Exception $e) {
            Log::error('Ocurrio un error al realizar el prestamo' . $e->getmessage());
            return response()->json(
                [
                    'error' => 'Business Logic Error',
                    'message' => $e->getMessage(),
                ],
                409
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function returnBook(Loan $loan, LoanService $loanService)
    {
        try {
            $loan = $loanService->returnLoan($loan);
            return (new LoanResource($loan))
                ->additional([
                    'message' => '¡Libro devuelto con exito!'
                ])
                ->response()
                ->setStatusCode(Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error('Error al intentar devolver el libro' . $e->getMessage());
            return response()->json([
                'message' => $e->getMessage(),
            ], 409);
        }
    }
}
