<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Item;

class ItemEditDeleteFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_update_item()
    {
        /** @var User $user */
        $user = User::factory()->create(['email_verified_at' => now()]);
        $category = Category::create(['name' => 'ファッション']);
        Condition::firstOrCreate(['id' => 1], ['condition' => '良好']);

        $item = Item::factory()->create(['user_id' => $user->id]);
        $item->categories()->attach($category->id);

        $response = $this->actingAs($user)->put(route('item.update', $item->id), [
            'name'         => '更新後の商品名',
            'description'  => '更新後の説明文です。',
            'price'        => 3000,
            'condition_id' => 1,
            'category_ids' => [$category->id],
        ]);

        $response->assertRedirect(route('item.show', $item->id));

        $this->assertDatabaseHas('items', [
            'id'   => $item->id,
            'name' => '更新後の商品名',
            'price' => 3000,
        ]);
    }

    public function test_oversized_item_image_is_rejected_on_update()
    {
        Storage::fake('public');

        /** @var User $user */
        $user = User::factory()->create(['email_verified_at' => now()]);
        $category = Category::create(['name' => 'ファッション']);
        Condition::firstOrCreate(['id' => 1], ['condition' => '良好']);

        $item = Item::factory()->create(['user_id' => $user->id]);
        $item->categories()->attach($category->id);

        $file = UploadedFile::fake()->create('too_big.jpg', 3000, 'image/jpeg');

        $response = $this->actingAs($user)->put(route('item.update', $item->id), [
            'name'         => '更新後の商品名',
            'description'  => '更新後の説明文です。',
            'price'        => 3000,
            'condition_id' => 1,
            'category_ids' => [$category->id],
            'item_image'   => $file,
        ]);

        $response->assertSessionHasErrors(['item_image']);
        $this->assertDatabaseMissing('items', ['id' => $item->id, 'name' => '更新後の商品名']);
    }

    public function test_non_owner_cannot_update_item()
    {
        $owner = User::factory()->create(['email_verified_at' => now()]);
        /** @var User $otherUser */
        $otherUser = User::factory()->create(['email_verified_at' => now()]);
        Condition::firstOrCreate(['id' => 1], ['condition' => '良好']);
        $category = Category::create(['name' => 'ファッション']);

        $item = Item::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($otherUser)->put(route('item.update', $item->id), [
            'name'         => '不正な更新',
            'description'  => '不正な更新です。',
            'price'        => 1,
            'condition_id' => 1,
            'category_ids' => [$category->id],
        ])->assertStatus(403);
    }

    public function test_owner_can_delete_unsold_item()
    {
        /** @var User $user */
        $user = User::factory()->create(['email_verified_at' => now()]);
        $item = Item::factory()->create(['user_id' => $user->id, 'is_sold' => false]);

        $this->actingAs($user)->delete(route('item.destroy', $item->id))
            ->assertRedirect(route('mypage'));

        $this->assertDatabaseMissing('items', ['id' => $item->id]);
    }

    public function test_non_owner_cannot_delete_item()
    {
        $owner = User::factory()->create(['email_verified_at' => now()]);
        /** @var User $otherUser */
        $otherUser = User::factory()->create(['email_verified_at' => now()]);
        $item = Item::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($otherUser)->delete(route('item.destroy', $item->id))
            ->assertStatus(403);

        $this->assertDatabaseHas('items', ['id' => $item->id]);
    }

    public function test_sold_item_cannot_be_deleted()
    {
        /** @var User $user */
        $user = User::factory()->create(['email_verified_at' => now()]);
        $item = Item::factory()->create(['user_id' => $user->id, 'is_sold' => true]);

        $this->actingAs($user)->delete(route('item.destroy', $item->id))
            ->assertRedirect();

        $this->assertDatabaseHas('items', ['id' => $item->id]);
    }
}
