<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
            'body' => 'required|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'body.required' => 'コメントを入力してください。',
            'body.string' => 'コメントは文字列で入力してください。',
            'body.max' => 'コメントは:max以内で入力してください。',
        ];
    }
}
