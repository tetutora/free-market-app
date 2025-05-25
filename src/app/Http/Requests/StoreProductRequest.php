<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|integer|min:1',
            'images' => 'required|array|max:10', // 配列として10枚まで
            'images.*' => 'image|max:2048',      // 各画像のルール
            'category_ids' => ['required', 'array'],
            'category_ids.*' => ['exists:categories,id'],
            'brand_ids' => ['required', 'array'],
            'brand_ids.*' => ['exists:brands,id'],
            'condition' => 'required|string|in:新品・未使用,未使用に近い,目立った傷や汚れなし,やや傷や汚れあり,傷や汚れあり,全体的に状態が悪い',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '商品名は必須です。',
            'name.max' => '商品名は255文字以内で入力してください。',
            'description.required' => '商品説明は必須です。',
            'price.required' => '価格は必須です。',
            'price.integer' => '価格は整数で入力してください。',
            'price.min' => '価格は1円以上にしてください。',
            'images.required' => '商品画像は1枚以上アップロードしてください。',
            'images.array' => '画像を複数選択する場合はファイルを一括選択してください。',
            'images.max' => '画像は最大10枚までアップロードできます。',
            'images.*.image' => '画像ファイルを選択してください。',
            'images.*.max' => '各画像サイズは2MB以下にしてください。',
            'category_ids.required' => 'カテゴリーは必須です。',
            'category_ids.array' => 'カテゴリーは配列で送信してください。',
            'category_ids.*.exists' => '選択したカテゴリーが存在しません。',
            'brand_ids.required' => 'ブランドは必須です。',
            'brand_ids.array' => 'ブランドは配列で送信してください。',
            'brand_ids.*.exists' => '選択したブランドが存在しません。',
            'condition.required' => '商品の状態を選択してください。',
            'condition.in' => '商品の状態に無効な値が含まれています。',
        ];
    }
}
