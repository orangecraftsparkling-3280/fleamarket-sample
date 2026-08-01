<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'img_path'  => 'nullable|image|mimes:jpeg,png|max:2048',
            'name'      => 'required|string|max:20',
            'post_code' => 'required|string|size:8|regex:/^\d{3}-\d{4}$/',
            'address'   => 'required|string|max:255',
            'building'  => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'image.image'        => '指定されたファイルが画像ではありません。',
            'image.mimes'        => '画像の拡張子は .jpeg または .png を指定してください。',
            'img_path.max'       => '画像は2MB以内のファイルを指定してください。',
            'name.required'      => 'お名前を入力してください。',
            'name.max'           => 'お名前は20文字以内で入力してください。',
            'post_code.required' => '郵便番号を入力してください。',
            'post_code.size'     => '郵便番号はハイフンを含めて8文字で入力してください。',
            'post_code.regex'    => '郵便番号は「000-0000」の形式で入力してください。',
            'address.required'   => '住所を入力してください。',
        ];
    }
}
