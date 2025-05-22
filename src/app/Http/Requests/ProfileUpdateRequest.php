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

            // 住所1は必須
            'addresses.0.postal_code' => ['required', 'string', 'max:20'],
            'addresses.0.prefecture' => ['required', 'string', 'max:255'],
            'addresses.0.city' => ['required', 'string', 'max:255'],
            'addresses.0.street' => ['required', 'string', 'max:255'],
            'addresses.0.building' => ['nullable', 'string', 'max:255'],
            'addresses.0.phone' => ['nullable', 'string', 'max:20'], // 住所1の電話番号（任意）

            // 住所2以降は一旦nullableで受け取る（基本的な型チェック）
            'addresses.*.postal_code' => ['nullable', 'string', 'max:20'],
            'addresses.*.prefecture' => ['nullable', 'string', 'max:255'],
            'addresses.*.city' => ['nullable', 'string', 'max:255'],
            'addresses.*.street' => ['nullable', 'string', 'max:255'],
            'addresses.*.building' => ['nullable', 'string', 'max:255'],
            'addresses.*.phone' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $addresses = $this->input('addresses', []);

            foreach ($addresses as $index => $address) {
                if ($index === 0) continue; // 住所1は別途必須バリデーション済み

                // 住所2以降で「どれか1つでも入力があれば必須項目チェック」
                $fieldsToCheck = ['postal_code', 'prefecture', 'city', 'street', 'phone'];
                $hasAnyValue = false;
                foreach ($fieldsToCheck as $field) {
                    if (!empty($address[$field])) {
                        $hasAnyValue = true;
                        break;
                    }
                }

                if ($hasAnyValue) {
                    foreach ($fieldsToCheck as $field) {
                        if (empty($address[$field])) {
                            $validator->errors()->add(
                                "addresses.$index.$field",
                                "住所" . ($index + 1) . "の{$field}は必須です。"
                            );
                        }
                    }
                }
            }
        });
    }
}