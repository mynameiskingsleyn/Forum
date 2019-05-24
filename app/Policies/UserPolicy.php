<?php

namespace Forum\Policies;

use Forum\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can update the give prfile
     *
     * @param  \Forum\User  $user
     * @return mixed
     */
    public function update(User $user)
    {
        //
        return auth()->id() === $user->id;
    }
}
