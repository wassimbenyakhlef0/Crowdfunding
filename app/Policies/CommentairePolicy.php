<?php

namespace App\Policies;

use App\Models\Commentaire;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentairePolicy
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
        return true; // Tout le monde peut voir les commentaires
    }

    /**
     * Determine whether user can view model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Commentaire  $commentaire
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Commentaire $commentaire)
    {
        return true; // Tout le monde peut voir un commentaire
    }

    /**
     * Determine whether user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true; // Tout utilisateur connecté peut commenter
    }

    /**
     * Determine whether user can update model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Commentaire  $commentaire
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Commentaire $commentaire)
    {
        return $user->id === $commentaire->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether user can delete model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Commentaire  $commentaire
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Commentaire $commentaire)
    {
        return $user->id === $commentaire->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether user can restore model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Commentaire  $commentaire
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Commentaire $commentaire)
    {
        return $user->id === $commentaire->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether user can permanently delete model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Commentaire  $commentaire
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Commentaire $commentaire)
    {
        return $user->role === 'admin'; // Seul un admin peut supprimer définitivement
    }
}
