<?php

namespace App\Http\Requests;

use App\Traits\AuthUserTrait;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CommentUpdateRequest extends FormRequest
{
    use AuthUserTrait;

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
            'comment' => 'required|string|max:500',
            'comment_id' => 'nullable|integer|exists:comments,id',
            'movie_id' => 'required|integer|exists:movies,id',
        ];
    }

    public function messages(): array
    {
        return [
            'comment.required' => 'Comment is required!',
            'comment.string' => 'Comment must be a string!',
            'comment.max' => 'Comment must be less than 500 characters!',
            'comment_id.integer' => 'Comment ID must be an integer!',
            'comment_id.exists' => 'Comment ID must exist in the database!',
            'movie_id.required' => 'Movie ID is required!',
            'movie_id.integer' => 'Movie ID must be an integer!',
            'movie_id.exists' => 'Movie ID must exist in the database!',
        ];
    }

    /**
     * @param $key
     * @param $default
     * @return mixed
     */
    public function validated($key = null, $default = null): mixed
    {
        $data = parent::validated($key, $default);
        $data['ip_address'] = $this->ip();
        return $data;
    }
}
