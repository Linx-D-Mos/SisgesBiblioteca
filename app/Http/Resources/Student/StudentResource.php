<?php

namespace App\Http\Resources\Student;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'student_code' => $this->student_code,
            'name' => $this->name,
            'last_name' => $this->last_name,
            'email' => $this->email,
        ];
    }
}
