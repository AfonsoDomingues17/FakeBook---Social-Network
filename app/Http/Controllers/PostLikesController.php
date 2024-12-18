<?php

namespace App\Http\Controllers;

use App\Events\PostLike;
use App\Models\Post;
use App\Models\PostLikes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostLikesController extends Controller
{
    function like(Request $request) {
        if(!Auth::check()) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        $postId = $request->id;
        $userId = Auth::id();
        $post = Post::findOrFail($postId);

        $this->authorize('like', $post);

        $existingLike = $post->likedByUsers()->where('user_id', $userId)->first();

        if ($existingLike) {
            // Unlike Post
            $post->likedByUsers()->detach($userId);
            $liked = false;
        } else {
            // Like Post
            $post->likedByUsers()->attach($userId);
            $liked = true;

            $event = event(new PostLike($request->id));
            Log::info('Event', $event);
        }

        $likeCount = $post->getNumberOfLikes();

        return response()->json(['success'=> true, 'liked' => $liked, 'likeCount' => $likeCount]);
    }
}
