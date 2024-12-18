<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentLikesController extends Controller
{
    public function like(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        $commentId = $request->id;
        $userId = Auth::id();
        $comment = Comment::findOrFail($commentId);

        $existingLike = $comment->likedByUsers()->where('user_id', $userId)->first();

        if ($existingLike) {
            // Unlike Comment
            $comment->likedByUsers()->detach($userId);
            $liked = false;
        } else {
            // Like Comment
            $comment->likedByUsers()->attach($userId);
            $liked = true;
        }

        $likeCount = $comment->getNumberOfLikes();

        return response()->json(['success' => true, 'liked' => $liked, 'likeCount' => $likeCount]);
    }
}
