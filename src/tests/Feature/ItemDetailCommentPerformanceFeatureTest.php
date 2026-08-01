<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Comment;
use App\Models\Profile;

class ItemDetailCommentPerformanceFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_comment_avatars_do_not_trigger_n_plus_one_queries()
    {
        $item = Item::factory()->create();

        foreach (range(1, 5) as $i) {
            $commenter = User::factory()->create();
            Profile::factory()->create(['user_id' => $commenter->id]);
            Comment::factory()->create([
                'item_id' => $item->id,
                'user_id' => $commenter->id,
            ]);
        }

        DB::enableQueryLog();
        $this->get(route('item.show', $item->id))->assertStatus(200);
        $queryCount = count(DB::getQueryLog());
        DB::flushQueryLog();
        DB::disableQueryLog();

        $item2 = Item::factory()->create();
        foreach (range(1, 10) as $i) {
            $commenter = User::factory()->create();
            Profile::factory()->create(['user_id' => $commenter->id]);
            Comment::factory()->create([
                'item_id' => $item2->id,
                'user_id' => $commenter->id,
            ]);
        }

        DB::enableQueryLog();
        $this->get(route('item.show', $item2->id))->assertStatus(200);
        $queryCountForMoreComments = count(DB::getQueryLog());
        DB::disableQueryLog();

        $this->assertEquals(
            $queryCount,
            $queryCountForMoreComments,
            'コメント数が増えてもクエリ数は一定であるべき（N+1が発生していない）'
        );
    }

    public function test_comment_by_user_without_profile_does_not_crash_page()
    {
        $item = Item::factory()->create();
        $commenter = User::factory()->create();

        Comment::factory()->create([
            'item_id' => $item->id,
            'user_id' => $commenter->id,
        ]);

        $response = $this->get(route('item.show', $item->id));

        $response->assertStatus(200);
    }
}
