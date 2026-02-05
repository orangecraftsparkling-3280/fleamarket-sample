<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class RegistrationFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->post('/register', [
            'name' => '田中太郎',
            'email' => 'tanaka@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => '田中太郎',
            'email' => 'tanaka@example.com',
        ]);

        $response->assertRedirect('/email/verify');
    }

    public function test_user_is_redirected_to_profile_after_email_verification()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $this->withoutMiddleware([\Illuminate\Routing\Middleware\ValidateSignature::class]);

        /** @var \App\Models\User $user */
        $response = $this->actingAs($user)
            ->get("/email/verify/{$user->id}/" . sha1($user->email));

        $response->assertRedirect(route('profile.edit'));
    }
}
