<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use JsonException;

class MovieUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->guard('sanctum')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|unique:movies,title,' . $this->route('movie'),
            'description' => 'required|string',
            'release_date' => 'required|date',
            'rating' => 'required|numeric|min:1|max:10',
            'country' => 'required|string',
            'genre' => 'required|array',
            'photo' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Title is required',
            'title.unique' => 'Title must be unique',
            'description.required' => 'Description is required',
            'release_date.required' => 'Release date is required',
            'release_date.date' => 'Release date must be a date',
            'rating.required' => 'Rating is required',
            'rating.numeric' => 'Rating must be a number',
            'country.required' => 'Country is required',
            'genre.required' => 'Genre is required',
            'genre.array' => 'Genre must be an array',
            'photo.required' => 'Photo is required',
        ];
    }


    /**
     * @param $key
     * @param $default
     * @return mixed
     * @throws JsonException
     */
    public function validated($key = null, $default = null): mixed
    {
        $data = parent::validated($key, $default);
        $data['genre'] = json_encode($data['genre'], JSON_THROW_ON_ERROR);

        return $data;
    }
}
