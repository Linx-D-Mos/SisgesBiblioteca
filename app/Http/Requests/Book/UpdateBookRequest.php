<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'isbn' => ['required','string', Rule::unique('books','isbn')->ignore($this->book)],
            'published_year' => 'required|integer|digits:4|max:' . date('Y'),
            'stock' => 'required|integer|min:0',
            'authors' => 'required|array',
            'authors.*' => 'exists:authors,id'
        ];
    }
}
