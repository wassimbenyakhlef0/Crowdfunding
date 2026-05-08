<?php

namespace App\Policies;

use App\Models\Update;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UpdatePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true; // Tout le monde peut voir les mises à jour
    }

    /**
     * Determine whether user can view model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Update  $update
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Update $update)
    {
        return true; // Tout le monde peut voir une mise à jour
    }

    /**
     * Determine whether user can create models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Projet  $projet
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, $projet = null)
    {
        // Seul le porteur du projet ou un admin peuvent créer des mises à jour
        if ($projet) {
            return $user->id === $projet->user_id || $user->role === 'admin';
        }
        
        return $user->role === 'porteur' || $user->role === 'admin';
    }

    /**
     * Determine whether user can update model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Update  $update
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Update $update)
    {
        return $user->id === $update->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether user can delete model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Update  $update
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Update $update)
    {
        return $user->id === $update->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether user can restore model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Update  $update
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Update $update)
    {
        return $user->id === $update->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether user can permanently delete model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Update  $update
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Update $update)
    {
        return $user->role === 'admin'; // Seul un admin peut supprimer définitivement
    }
}
