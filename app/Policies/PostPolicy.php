<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Post;


class PostPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function view(?User $user, Post $post)
    {
        // Allow viewing if the post is public or if the user is the owner or an admin
        return $post->is_public || ($user && ($user->id === $post->owner_id || $user->typeu === 'ADMIN'));
    }

    /**
     * Determine whether the user can create posts.
     */
    public function create(User $user)
    {
        // Allow creation if the user is authenticated
        return $user !== null;
    }

    /**
     * Determine whether the user can update the post.
     */
    public function update(User $user, Post $post)
    {
        return $user->id === $post->owner_id || $user->typeu === 'ADMIN';
    }

    /**
     * Determine whether the user can delete the post.
     */
    public function delete(User $user, Post $post)
    {
        return $user->id === $post->owner_id || $user->typeu === 'ADMIN';
    }

    /**
     * Determine whether the user can like the post.
     */
    public function like(User $user)
    {
        return $user !== null;
    }
}
