<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'phone' => ['nullable', 'string', 'max:20'],

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
            'name.required' => 'ユーザー名は必須です。',
            'name.string' => 'ユーザー名は文字列で入力してください。',
            'name.max' => 'ユーザー名は:max文字以内で入力してください。',

            'email.required' => 'メールアドレスは必須です。',
            'email.string' => 'メールアドレスは文字列で入力してください。',
            'email.email' => '有効なメールアドレスを入力してください。',
            'email.max' => 'メールアドレスは:max文字以内で入力してください。',
            'email.unique' => 'このメールアドレスは既に使用されています。',

            'phone.string' => '電話番号は文字列で入力してください。',
            'phone.max' => '電話番号は:max文字以内で入力してください。',

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