<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Comment;

class CommentPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function view()
    {
        // Everyone can view comments
        return true;
    }

    /**
     * Determine whether the user can create comments.
     */
    public function create(User $user)
    {
        return $user !== null;
    }

    /**
     * Determine whether the user can update the comment.
     */
    public function update(User $user, Comment $comment)
    {
        return $user->id === $comment->author_id || $user->typeu === 'ADMIN';
    }

    /**
     * Determine whether the user can delete the comment.
     */
    public function delete(User $user, Comment $comment)
    {
        return $user->id === $comment->author_id || $user->typeu === 'ADMIN' || $user->id === $comment->post->author_id;
    }
}
