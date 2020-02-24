<?php
/**
 * Created by PhpStorm.
 * User: Arun
 * Date: 14.08.19
 * Time: 17:06
 */

namespace Tests\Unit;


use App\Models\Like;
use App\Repository\Like\Likes;
use Tests\TestCase;

class LikesModelTest extends TestCase
{
    /** @test */
    public function save_likes_record()
    {
        $like = factory(Like::class)->create();
        $this->assertDatabaseHas('likes', [
            'user_id' => $like->user_id,
            'created_at' => $like->created_at,
        ]);
    }

    /** @test */
    public function fail_likes_record()
    {
        $likes = new Likes();
        $this->assertFalse($likes->storeRequest(['user_id' => 'test']));
    }

    /** @test */
    public function count_likes_record()
    {
        $likes = new Likes();
        $new_record = factory(Like::class)->create();
        $count = $likes->countLikes($new_record->user_id);
        $this->assertArrayHasKey('total_likes', $count);
        $this->assertArrayHasKey('user_id', $count);
    }
}
