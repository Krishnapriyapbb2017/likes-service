<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\LikeAPIRequest;
use App\Repository\EmailService\Service;
use App\Repository\Like\Likes;
use App\Http\Controllers\Controller;
use App\Repository\Logging\Logging;

class LikesController extends Controller
{
    /**
     * @var Likes
     */
    private $likes;
    /**
     * @var Service
     */
    private $service;

    /**
     * LikesController constructor.
     * @param Likes $likes
     * @param Service $service
     */
    public function __construct(Likes $likes, Service $service)
    {
        $this->likes = $likes;
        $this->service = $service;
    }

    public function store(LikeAPIRequest $likeAPIRequest)
    {
        $validated = $likeAPIRequest->validated();
        $store = $this->likes->storeRequest($validated);
        // return if unable to store
        if(!$store) {
            return response()->json([
                'status' => false,
                'msg' => 'Unable to save likes'
            ]);
        }
        activity()->log(Logging::$LIKE_SAVE);

        $user_id = $validated['user_id'];
        //try to push the email, ideally a queue in real world scenario
        $dispatch = $this->service->sendEmail($user_id);

        //likes count
        $likes_count = $this->likes->countLikes($user_id);

        //return response
        return response()->json([
            'user_id' => (int)$likes_count['user_id'],
            'total_likes' => (int)$likes_count['total_likes'],
            'is_email_sent' => (bool)$dispatch['status']
        ]);

    }
}
