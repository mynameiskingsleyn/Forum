<?php

namespace Forum\Policies;

use Forum\User;
use Forum\Reply;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the reply.
     *
     * @param  \Forum\User  $user
     * @param  \Forum\Reply  $reply
     * @return mixed
     */
    public function view(User $user, Reply $reply)
    {
        //
    }

    /**
     * Determine whether the user can create replies.
     *
     * @param  \Forum\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
        if (!$lastReply = $user->fresh()->lastReply) {
            return true;
        }
        return !$user->lastReply->wasJustPublished();
    }

    /**
     * Determine whether the user can update the reply.
     *
     * @param  \Forum\User  $user
     * @param  \Forum\Reply  $reply
     * @return mixed
     */
    public function update(User $user, Reply $reply)
    {
        //
        return $user->id == $reply->user_id;
    }

    /**
     * Determine whether the user can delete the reply.
     *
     * @param  \Forum\User  $user
     * @param  \Forum\Reply  $reply
     * @return mixed
     */
    public function delete(User $user, Reply $reply)
    {
        //
    }
}
