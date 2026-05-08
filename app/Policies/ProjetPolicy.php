<?php

namespace App\Policies;

use App\Models\Projet;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjetPolicy
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
        return true; // Tout le monde peut voir la liste des projets
    }

    /**
     * Determine whether user can view model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Projet  $projet
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Projet $projet)
    {
        return true; // Tout le monde peut voir les détails d'un projet
    }

    /**
     * Determine whether user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->role === 'porteur' || $user->role === 'admin';
    }

    /**
     * Determine whether user can update model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Projet  $projet
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Projet $projet)
    {
        return $user->id === $projet->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether user can delete model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Projet  $projet
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Projet $projet)
    {
        return $user->id === $projet->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether user can publish model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Projet  $projet
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function publish(User $user, Projet $projet)
    {
        return $user->id === $projet->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether user can restore model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Projet  $projet
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Projet $projet)
    {
        return $user->id === $projet->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether user can permanently delete model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Projet  $projet
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Projet $projet)
    {
        return $user->id === $projet->user_id || $user->role === 'admin';
    }
}
