<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Profile;

class ItemDetailFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_detail_page_displays_all_required_information()
    {
        $user = User::factory()->create();
        Profile::factory()->create(['user_id' => $user->id]);

        $item = Item::factory()->create([
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'price' => 1500,
        ]);

        $categories = Category::factory()->count(2)->create();
        $item->categories()->attach($categories->pluck('id'));

        Comment::factory()->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'comment' => 'テストコメントです'
        ]);

        $response = $this->get("/item/{$item->id}");

        $response->assertStatus(200);
        $response->assertSee('テスト商品');
        $response->assertSee('テストブランド');
        $response->assertSee('1,500');
        $response->assertSee('テストコメントです');
        foreach ($categories as $category) {
            $response->assertSee($category->name);
        }
    }

    public function test_like_functionality()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post("/favorite/{$item->id}");
        $this->assertDatabaseHas('favorites', ['user_id' => $user->id, 'item_id' => $item->id]);

        $response = $this->get("/item/{$item->id}");
        $response->assertSee('ハートロゴ_ピンク.png');
        $response->assertSee('1');

        $this->actingAs($user)->delete("/favorite/{$item->id}");
        $this->assertDatabaseMissing('favorites', ['user_id' => $user->id, 'item_id' => $item->id]);

        $response = $this->get("/item/{$item->id}");
        $response->assertSee('ハートロゴ_デフォルト.png');
        $response->assertSee('0');
    }

    public function test_comment_submission()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)->post(route('comment.store', ['item_id' => $item->id]), [
            'comment' => 'テストコメント投下'
        ]);
        $this->assertDatabaseHas('comments', ['comment' => 'テストコメント投下']);

        $this->post(route('comment.store', ['item_id' => $item->id]), ['comment' => '未ログイン'])
            ->assertRedirect('/');

        $response = $this->actingAs($user)
            ->from(route('item.show', ['id' => $item->id]))
            ->post(route('comment.store', ['item_id' => $item->id]), ['comment' => '']);

        $response->assertSessionHasErrors(['comment']);
    }
}
