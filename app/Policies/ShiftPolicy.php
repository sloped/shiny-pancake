<?php

namespace App\Policies;

use App\User;
use App\Shift;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShiftPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the shift.
     *
     * @param  \App\User  $user
     * @param  \App\Shift  $shift
     * @return mixed
     */
    public function view(User $user, Shift $shift)
    {
        return $user->role === 'manager' || $shift->employee_id == $user->id;
    }

     /**
     * Determine whether the user list shifts.
     *
     * @param  \App\User  $user
     * @return mixed
     */
     public function list_shifts(User $user)
     {
         return $user->role === 'manager' || $user->role === 'employee';
     }


    /**
     * Determine whether the user can create shifts.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->role === 'manager';
    }

    /**
     * Determine whether the user can update the shift.
     *
     * @param  \App\User  $user
     * @param  \App\Shift  $shift
     * @return mixed
     */
    public function update(User $user, Shift $shift)
    {
        return $user->role === 'manager';
    }

    /**
     * Determine whether the user can delete the shift.
     *
     * @param  \App\User  $user
     * @param  \App\Shift  $shift
     * @return mixed
     */
    public function delete(User $user, Shift $shift)
    {
        return false;
    }
}
