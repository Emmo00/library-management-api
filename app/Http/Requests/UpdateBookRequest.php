<?php

namespace App\Http\Requests;

use App\Enums\BookStatus;
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
            "title" => ["sometimes", "required", "string"],
            "isbn" => ["sometimes", "required", "string", "unique:books"],
            "published_date" => ["sometimes", "required", "date"],
            "author_id" => ["sometimes", "required", "string", "exists:authors.id"],
            "status" => ["sometimes", "required", Rule::in(BookStatus::all())]
        ];
    }
}
