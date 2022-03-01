<?php

namespace App\Policies;

use App\Modules\Models\User;
use App\Setting;
use Illuminate\Auth\Access\HandlesAuthorization;

class SettingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Modules\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Modules\Models\User  $user
     * @param  \App\Setting  $setting
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Setting $setting)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Modules\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Modules\Models\User  $user
     * @param  \App\Setting  $setting
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Setting $setting)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Modules\Models\User  $user
     * @param  \App\Setting  $setting
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Setting $setting)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Modules\Models\User  $user
     * @param  \App\Setting  $setting
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Setting $setting)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Modules\Models\User  $user
     * @param  \App\Setting  $setting
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Setting $setting)
    {
        //
    }
}
