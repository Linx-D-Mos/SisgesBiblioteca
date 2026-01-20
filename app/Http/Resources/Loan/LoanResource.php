<?php

namespace App\Http\Resources\Loan;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'book_id' => $this->book_id,
            'title' => $this->book->title,
            'student_id' => $this->student_id,
            'student_code' => $this->student->student_code,
            'loaned_at' => $this->loaned_at,
            'returned_at' => $this->returned_at,
        ];
    }
}
