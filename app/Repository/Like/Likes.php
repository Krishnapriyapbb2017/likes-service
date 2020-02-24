<?php
/**
 * Created by PhpStorm.
 * User: Arun
 * Date: 14.08.19
 * Time: 13:46
 */

namespace App\Repository\Like;


use App\Models\Like;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class Likes
{
    /**
     * @param $request
     * @return bool
     */
    public function storeRequest($request)
    {
        /*
         * Try to create the record or return error
         */
        try {
            $user_id = $request['user_id'];
            $created_at = Carbon::now()->toDateTimeString();

            $like = Like::insert([
                'user_id' => $user_id,
                'created_at' => $created_at
            ]);
        } catch (Exception $exception) {
            Log::error('Unable to insert ' .$user_id .' to likes ' .$created_at);
            return false;
        }
        return $like;
    }

    public function countLikes($user_id)
    {
        $likes = Like::where('user_id', $user_id)->count();
        return [
            'user_id' => $user_id,
            'total_likes' => $likes
        ];
    }
}