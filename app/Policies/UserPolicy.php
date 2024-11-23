<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;


class UserPolicy
{
    use HandlesAuthorization;
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function update(User $authUser, User $user)
    {
        return $authUser->id === $user->id || $authUser->typeu === 'ADMIN';
    }

    public function delete(User $authUser, User $user)
    {
        return $authUser->id === $user->id || $authUser->typeu === 'ADMIN';
    }
}
