<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use App\Models\Profile;

class CommentEditDeleteFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_update_comment()
    {
        /** @var User $user */
        $user = User::factory()->create();
        Profile::factory()->create(['user_id' => $user->id]);
        $item = Item::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => '元のコメント',
        ]);

        $this->actingAs($user)->put(route('comment.update', $comment->id), [
            'comment' => '更新後のコメント',
        ])->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'comment' => '更新後のコメント',
        ]);
    }

    public function test_non_owner_cannot_update_comment()
    {
        $owner = User::factory()->create();
        Profile::factory()->create(['user_id' => $owner->id]);
        /** @var User $otherUser */
        $otherUser = User::factory()->create();
        $item = Item::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $owner->id,
            'item_id' => $item->id,
            'comment' => '元のコメント',
        ]);

        $this->actingAs($otherUser)->put(route('comment.update', $comment->id), [
            'comment' => '不正な更新',
        ])->assertStatus(403);

        $this->assertDatabaseHas('comments', ['id' => $comment->id, 'comment' => '元のコメント']);
    }

    public function test_owner_can_delete_comment()
    {
        /** @var User $user */
        $user = User::factory()->create();
        Profile::factory()->create(['user_id' => $user->id]);
        $item = Item::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->actingAs($user)->delete(route('comment.destroy', $comment->id))
            ->assertRedirect();

        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_non_owner_cannot_delete_comment()
    {
        $owner = User::factory()->create();
        Profile::factory()->create(['user_id' => $owner->id]);
        /** @var User $otherUser */
        $otherUser = User::factory()->create();
        $item = Item::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $owner->id,
            'item_id' => $item->id,
        ]);

        $this->actingAs($otherUser)->delete(route('comment.destroy', $comment->id))
            ->assertStatus(403);

        $this->assertDatabaseHas('comments', ['id' => $comment->id]);
    }
}
