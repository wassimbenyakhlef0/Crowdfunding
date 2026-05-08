<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Projet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContributionController extends Controller
{
    /**
     * Enregistre une nouvelle contribution
     */
    public function store(Request $request, Projet $projet)
    {
        // Vérifier que l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour contribuer.');
        }

        // Vérifier que le projet est publié et pas terminé
        if ($projet->statut !== 'publie' || $projet->est_termine) {
            return redirect()->route('projets.show', $projet)
                ->with('error', 'Ce projet n\'accepte plus de contributions.');
        }

        $validated = $request->validate([
            'montant' => 'required|numeric|min:1|max:10000',
            'message' => 'nullable|string|max:500',
            'anonyme' => 'sometimes|boolean',
        ], [
            'montant.required' => 'Le montant est obligatoire.',
            'montant.numeric' => 'Le montant doit être un nombre.',
            'montant.min' => 'Le montant doit être supérieur à 0.',
            'montant.max' => 'Le montant ne peut pas dépasser 10 000 €.',
            'message.max' => 'Le message ne peut pas dépasser 500 caractères.',
        ]);

        // Créer la contribution
        $contribution = Contribution::create([
            'montant' => $validated['montant'],
            'message' => $validated['message'] ?? null,
            'anonyme' => $validated['anonyme'] ?? false,
            'user_id' => Auth::id(),
            'projet_id' => $projet->id,
        ]);

        // Mettre à jour le montant collecté du projet
        $projet->increment('montant_collecte', $validated['montant']);

        // Vérifier si l'objectif est atteint
        if ($projet->montant_collecte >= $projet->objectif) {
            $projet->update(['statut' => 'termine']);
        }

        return redirect()->route('projets.show', $projet)
            ->with('success', 'Merci pour votre contribution de ' . number_format($validated['montant'], 2, ',', ' ') . ' € !');
    }

    /**
     * Affiche les contributions d'un utilisateur
     */
    public function mesContributions()
    {
        $contributions = Contribution::where('user_id', Auth::id())
            ->with('projet')
            ->latest()
            ->paginate(10);

        return view('contributions.mes-contributions', compact('contributions'));
    }
}
