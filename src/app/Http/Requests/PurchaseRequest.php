<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            'address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:card,konbini',
        ];
    }

    public function messages()
    {
        return [
            'address_id.required' => '配送先は必須です。',
            'address_id.exists' => '選択された配送先は無効です。',
            'payment_method.required' => '支払い方法を選択してください。',
            'payment_method.in' => '支払い方法が正しくありません。',
        ];
    }
}
