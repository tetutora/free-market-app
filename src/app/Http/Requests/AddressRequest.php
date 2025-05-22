<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
            'postal_code' => 'required|string|max:20',
            'prefecture' => 'required|string|max:20',
            'city' => 'required|string|max:20',
            'street' => 'required|string|max:20',
            'building' => 'nullable|string|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'postal_code.required' => '郵便番号は必須です。',
            'postal_code.string' => '郵便番号は文字列で入力してください。',
            'postal_code.max' => '郵便番号は:max文字以内で入力してください。',

            'prefecture.required' => '都道府県は必須です。',
            'prefecture.string' => '都道府県は文字列で入力してください。',
            'prefecture.max' => '都道府県は:max文字以内で入力してください。',

            'city.required' => '市区町村は必須です。',
            'city.string' => '市区町村は文字列で入力してください。',
            'city.max' => '市区町村は:max文字以内で入力してください。',

            'street.required' => '番地は必須です。',
            'street.string' => '番地は文字列で入力してください。',
            'street.max' => '番地は:max文字以内で入力してください。',

            'building.string' => '建物名は文字列で入力してください。',
            'building.max' => '建物名は:max文字以内で入力してください。',
        ];
    }
}
