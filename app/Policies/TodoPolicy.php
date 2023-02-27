<?php

namespace App\Policies;

use App\Models\AuthModel\User;
use App\Models\AuthModel\Todo;
use App\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class TodoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\AuthModel\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->user_rl_id == Role::IS_ADMIN;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\AuthModel\User  $user
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Todo $todo)
    {
        if($user->user_rl_id == Role::IS_ADMIN) {
            return true;
        } else {
            return $user->id == $todo->td_user_id;
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\AuthModel\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\AuthModel\User  $user
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Todo $todo)
    {
        if($user->user_rl_id == Role::IS_ADMIN) {
            return true;
        } else {
            return $user->id == $todo->td_user_id;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\AuthModel\User  $user
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Todo $todo)
    {
        if($user->user_rl_id == Role::IS_ADMIN) {
            return true;
        } else {
            return $user->id == $todo->td_user_id;
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\AuthModel\User  $user
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Todo $todo)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\AuthModel\User  $user
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Todo $todo)
    {
        //
    }
}
