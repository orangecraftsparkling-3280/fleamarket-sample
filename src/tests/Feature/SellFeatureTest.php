<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Item;

class SellFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_sell_item_with_all_required_info()
    {
        Storage::fake('public');

        /** @var User $user */
        $user = User::factory()->create(['email_verified_at' => now()]);
        $category = Category::create(['name' => 'ファッション']);
        \App\Models\Condition::firstOrCreate(['id' => 1], ['condition' => '良好']);
        $file = UploadedFile::fake()->create('test_item.jpg', 100, 'image/jpeg');

        $sellData = [
            'name'         => 'テスト商品名',
            'brand'        => 'テストブランド名',
            'description'  => '商品の説明文です。',
            'price'        => 5000,
            'condition_id' => 1,
            'category_ids' => [$category->id],
            'item_image'   => $file,
        ];

        $response = $this->actingAs($user)->post(route('item.store'), $sellData);

        if (session('errors')) {
            dump(session('errors')->getMessages());
        }

        $response->assertRedirect('/');

        $this->assertDatabaseHas('items', [
            'user_id'     => $user->id,
            'name'        => 'テスト商品名',
            'brand'       => 'テストブランド名',
            'price'       => 5000,
            'condition_id'=> 1,
        ]);

        $this->assertDatabaseHas('category_item', [
            'category_id' => $category->id,
        ]);

        $item = Item::where('name', 'テスト商品名')->first();
        $this->assertNotNull($item->image_url, 'image_urlカラムが空です。');

        $this->assertTrue(
            Storage::disk('public')->exists($item->image_url),
            "ファイル [{$item->image_url}] がストレージに存在しません。"
        );
    }
}
