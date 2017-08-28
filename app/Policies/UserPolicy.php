<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the user.
     *
     * @param  \App\User  $user
     * @param  \App\User  $requested_user
     * @return mixed
     */
    public function view(User $user, User $requested_user)
    {
        if( $user->role === 'manager' && $requested_user->role === 'employee' ) {
            return true;
        }
        if( $user->role === 'employee' && $requested_user->role === 'manager' ) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create users.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->role === 'manager';
    }

    /**
     * Determine whether the user can update the user.
     *
     * @param  \App\User  $user
     * @param  \App\User  $requested_user
     * @return mixed
     */
    public function update(User $user, User $requested_useruser)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the user.
     *
     * @param  \App\User  $user
     * @param  \App\User  $requested_user
     * @return mixed
     */
    public function delete(User $user, User $requested_user)
    {
        return false;
    }
}
