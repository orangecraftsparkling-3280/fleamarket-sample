<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;

class ProfileFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_see_profile_info()
    {
        /** @var User $user */
        $user = User::factory()->create(['name' => 'テストユーザー']);
        Profile::factory()->create([
            'user_id' => $user->id,
            'img_path' => 'profiles/test_avatar.jpg'
        ]);

        Item::factory()->create(['user_id' => $user->id, 'name' => '出品アイテムA']);
        $boughtItem = Item::factory()->create([
            'name' => '購入アイテムB',
            'is_sold' => true
        ]);

        \App\Models\Purchase::create([
            'item_id' => $boughtItem->id,
            'user_id' => $user->id,
            'post_code' => '123-4567',
            'address'   => '秋田県秋田市',
        ]);

        $response = $this->actingAs($user)->get('/mypage');

        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
        $response->assertSee('profiles/test_avatar.jpg');
        $response->assertSee('出品アイテムA');

        $responseBuy = $this->actingAs($user)->get('/mypage?tab=buy');
        $responseBuy->assertSee('購入アイテムB');
    }

    public function test_user_can_update_profile_data_and_image()
    {
        Storage::fake('public');

        /** @var User $user */
        $user = User::factory()->create(['email_verified_at' => now()]);
        Profile::factory()->create(['user_id' => $user->id]);

        $file = UploadedFile::fake()->create('new_avatar.jpg', 100, 'image/jpeg');

        $updateData = [
            'name' => '更新後の名前',
            'img_path' => $file,
            'post_code' => '123-4567',
            'address' => '更新後の住所',
            'building' => '更新後のビル名',
        ];

        $response = $this->actingAs($user)->post(route('profile.update'), $updateData);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => '更新後の名前']);
        $this->assertDatabaseHas('profiles', [
            'user_id' => $user->id,
            'post_code' => '123-4567',
            'address' => '更新後の住所',
        ]);

        $profile = Profile::where('user_id', $user->id)->first();
        $this->assertTrue(Storage::disk('public')->exists($profile->img_path));
    }

    public function test_oversized_profile_image_is_rejected()
    {
        Storage::fake('public');

        /** @var User $user */
        $user = User::factory()->create(['email_verified_at' => now()]);
        Profile::factory()->create(['user_id' => $user->id]);

        $file = UploadedFile::fake()->create('too_big.jpg', 3000, 'image/jpeg');

        $response = $this->actingAs($user)->post(route('profile.update'), [
            'name' => '更新後の名前',
            'img_path' => $file,
            'post_code' => '123-4567',
            'address' => '更新後の住所',
        ]);

        $response->assertSessionHasErrors(['img_path']);
        $this->assertDatabaseMissing('users', ['id' => $user->id, 'name' => '更新後の名前']);
    }
}
