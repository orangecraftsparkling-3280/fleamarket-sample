<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class Verify_emailFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_認証誘導画面が表示される()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);
        /** @var User $user */
        $response = $this->actingAs($user)->get(route('verification.notice'));
        $response->assertStatus(200);
        $response->assertViewIs('auth.verify_email');
    }

    public function test_認証リンククリックで認証が完了しプロフィール編集画面へ遷移する()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::signedRoute('verification.verify', [
            'id' => $user->id,
            'hash' => sha1($user->email),
        ]);

        /** @var User $user */
        $response = $this->actingAs($user)->get($verificationUrl);
        $response->assertRedirect(route('profile.edit'));

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }
}
