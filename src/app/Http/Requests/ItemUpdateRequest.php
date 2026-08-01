<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'            => 'required|string|max:255',
            'description'     => 'required|string|max:255',
            'item_image'      => 'nullable|image|mimes:jpeg,png',
            'category_ids'    => 'required|array|min:1|max:2',
            'condition_id'    => 'required|integer|exists:conditions,id',
            'price'           => 'required|integer|min:0',
            'brand'           => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'         => '商品名を入力してください。',
            'description.required'  => '商品説明を入力してください。',
            'item_image.mimes'      => '商品の画像は .jpeg または .png を指定してください。',
            'category_ids.required' => 'カテゴリーを選択してください。',
            'category_ids.max'      => 'カテゴリーは最大2つまでしか選択できません。',
            'condition_id.required' => '商品の状態を選択してください。',
            'price.required'        => '商品価格を入力してください。',
        ];
    }
}
