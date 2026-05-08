<?php

namespace App\Http\Controllers;

use App\Models\Commentaire;
use App\Models\Projet;
use Illuminate\Http\Request;

class CommentaireController extends Controller
{
    /**
     * Enregistre un nouveau commentaire
     */
    public function store(Request $request, Projet $projet)
    {
        // Vérifier que l'utilisateur est connecté
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour commenter.');
        }

        $validated = $request->validate([
            'contenu' => 'required|string|max:500',
        ], [
            'contenu.required' => 'Le contenu du commentaire est obligatoire.',
            'contenu.max' => 'Le commentaire ne peut pas dépasser 500 caractères.',
        ]);

        $commentaire = Commentaire::create([
            'contenu' => $validated['contenu'],
            'user_id' => auth()->id(),
            'projet_id' => $projet->id,
        ]);

        return redirect()->route('projets.show', $projet)
            ->with('success', 'Commentaire publié avec succès !');
    }

    /**
     * Supprime un commentaire
     */
    public function destroy(Commentaire $commentaire)
    {
        $this->authorize('delete', $commentaire);
        
        $projet = $commentaire->projet;
        $commentaire->delete();

        return redirect()->route('projets.show', $projet)
            ->with('success', 'Commentaire supprimé avec succès !');
    }
}
