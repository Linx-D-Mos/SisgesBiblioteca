<?php

namespace App\Http\Controllers;

use App\Http\Requests\Book\UpdateBookRequest;
use App\Http\Requests\StoreBookRequest;
use App\Http\Resources\Book\BookResource;
use App\Http\Resources\StoreBookResource;
use App\Models\Book;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with('authors')
            ->paginate(10);
        return BookResource::collection($books);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        try {
            $book =  DB::transaction(
                function () use ($request) {
                    $book = Book::create($request->validated());
                    $book->authors()->sync($request->validated()['authors']);

                    return $book->load('authors');
                }
            );
            return new StoreBookResource($book);
        } catch (\Exception $e) {
            Log::error('Error creando libro: ' . $e->getMessage());
            return response()->json([
                'message' => 'Ocurrio un error interno al procesar su solicitud ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book->load('authors');
        return new BookResource($book);
    }

    /**
     * Update the specified resource in storage.
     */
    
    public function update(UpdateBookRequest $request, Book $book)
    {
        try {
            $book = DB::transaction(
                function () use ($request, $book) {
                    $book->update($request->validated());
                    $book->authors()->sync($request->validated()['authors']);

                    return $book->load('authors');
                }
            );
            return new BookResource($book);
        } catch (Exception $e) {
            Log::error('Error actualizando el libro: ' . $e->getMessage());
            return response()->json([
                'message' => 'Ocurrio un error interno al procesar su solicitud' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        try {
            DB::transaction(
                function () use ($book) {
                    $book->authors()->detach();
                    $book->delete();
                }
            );
            return response()->noContent();
        } catch (Exception $e) {
            Log::error('Error actualizando el libro: ' . $e->getMessage());
            return response()->json([
                'message' => 'Ocurrio un error interno al procesar su solicitud' . $e->getMessage()
            ], 500);
        }
    }
}
