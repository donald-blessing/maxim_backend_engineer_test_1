<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class MovieCharacterCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('sanctum')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'movie_id' => 'required|integer|exists:movies,id',
            'name' => 'required|string|unique:movie_characters,name',
            'gender' => 'required|string',
            'height' => 'required|integer',
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'movie_id.required' => 'Movie ID is required!',
            'movie_id.integer' => 'Movie ID must be an integer!',
            'movie_id.exists' => 'Movie ID must exist in the database!',
            'name.required' => 'Name is required!',
            'name.string' => 'Name must be a string!',
            'name.unique' => 'Name must be unique!',
            'gender.required' => 'Gender is required!',
            'height.required' => 'Height is required!',
            'height.integer' => 'Height must be an integer!',
        ];
    }
}
