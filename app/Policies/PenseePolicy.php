<?php

namespace App\Policies;

use App\User;
use App\Pensee;
use Illuminate\Auth\Access\HandlesAuthorization;

class PenseePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the pensee.
     *
     * @param  \App\User  $user
     * @param  \App\Pensee  $pensee
     * @return mixed
     */
    public function view(User $user, Pensee $pensee)
    {
        //
    }

    /**
     * Determine whether the user can create pensees.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the pensee.
     *
     * @param  \App\User  $user
     * @param  \App\Pensee  $pensee
     * @return mixed
     */
    public function update(User $user, Pensee $pensee)
    {
        //
    }

    /**
     * Determine whether the user can delete the pensee.
     *
     * @param  \App\User  $user
     * @param  \App\Pensee  $pensee
     * @return mixed
     */
    public function delete(User $user, Pensee $pensee)
    {
        return $user->id == $pensee->user_id;
    }

    /**
     * Determine whether the user can restore the pensee.
     *
     * @param  \App\User  $user
     * @param  \App\Pensee  $pensee
     * @return mixed
     */
    public function restore(User $user, Pensee $pensee)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the pensee.
     *
     * @param  \App\User  $user
     * @param  \App\Pensee  $pensee
     * @return mixed
     */
    public function forceDelete(User $user, Pensee $pensee)
    {
        //
    }
}
