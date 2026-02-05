<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Validator;

class LoginRequestTest extends TestCase
{
    /**
     * @dataProvider loginValidationProvider
     */
    public function test_login_validation_rules($data, $errorKey, $expectedMessage)
    {
        $request = new LoginRequest();
        $validator = Validator::make($data, $request->rules(), $request->messages());

        $this->assertTrue($validator->fails(), "{$errorKey} のバリデーションエラーが発生しませんでした。");
        $this->assertEquals($expectedMessage, $validator->errors()->first($errorKey));
    }

    public function test_login_validation_success()
    {
        $data = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $request = new LoginRequest();
        $validator = Validator::make($data, $request->rules(), $request->messages());

        $this->assertFalse($validator->fails(), '正しいデータでバリデーションに失敗しました: ' . json_encode($validator->errors()->all(), JSON_UNESCAPED_UNICODE));
    }

    public function loginValidationProvider()
    {
        return [
            'メールアドレスが入力されていない場合' => [
                ['email' => '', 'password' => 'password123'],
                'email',
                'メールアドレスを入力してください。'
            ],
            'パスワードが入力されていない場合' => [
                ['email' => 'test@example.com', 'password' => ''],
                'password',
                'パスワードを入力してください。'
            ],
        ];
    }
}
