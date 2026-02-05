<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterRequestTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @dataProvider validationProvider
     */
    public function test_registration_validation_rules($data, $errorKey, $expectedMessage)
    {
        $request = new RegisterRequest();

        $validator = Validator::make($data, $request->rules(), $request->messages());

        $this->assertTrue($validator->fails(), "期待したバリデーションエラー ({$errorKey}) が発生しませんでした。");

        $this->assertEquals($expectedMessage, $validator->errors()->first($errorKey));
    }

    public function test_registration_validation_success()
    {
        $data = [
            'name' => '田中太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $request = new RegisterRequest();
        $validator = Validator::make($data, $request->rules(), $request->messages());

        $this->assertFalse($validator->fails(), 'バリデーションに失敗しました: ' . json_encode($validator->errors()->all(), JSON_UNESCAPED_UNICODE));
    }

    public function validationProvider()
    {
        return [
            '名前が入力されていない場合' => [
                ['name' => '', 'email' => 'test@example.com', 'password' => 'password123', 'password_confirmation' => 'password123'],
                'name',
                'お名前を入力してください。'
            ],
            'メールアドレスが入力されていない場合' => [
                ['name' => '田中太郎', 'email' => '', 'password' => 'password123', 'password_confirmation' => 'password123'],
                'email',
                'メールアドレスを入力してください。'
            ],
            'パスワードが入力されていない場合' => [
                ['name' => '田中太郎', 'email' => 'test@example.com', 'password' => '', 'password_confirmation' => ''],
                'password',
                'パスワードを入力してください。'
            ],
            'パスワードが7文字以下の場合' => [
                ['name' => '田中太郎', 'email' => 'test@example.com', 'password' => '1234567', 'password_confirmation' => '1234567'],
                'password',
                'パスワードは8文字以上で入力してください。'
            ],
            'パスワードが確認用パスワードと一致しない場合' => [
                ['name' => '田中太郎', 'email' => 'test@example.com', 'password' => 'password123', 'password_confirmation' => 'diff_password'],
                'password',
                'パスワードと一致しません。'
            ],
        ];
    }
}
