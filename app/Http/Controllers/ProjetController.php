<?php

namespace App\Http\Controllers;

use App\Models\Projet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjetController extends Controller
{
    /**
     * Affiche la liste des projets (publique)
     */
    public function index(Request $request)
    {
        $query = Projet::publies()->with(['porteur', 'contributions']);
        
        // Filtre par catégorie
        if ($request->filled('categorie')) {
            $query->parCategorie($request->categorie);
        }
        
        // Recherche par titre
        if ($request->filled('search')) {
            $query->where('titre', 'like', '%' . $request->search . '%');
        }
        
        $projets = $query->latest()->paginate(12);
        $categories = ['films', 'musique', 'art', 'startup'];
        
        return view('projets.index', compact('projets', 'categories'));
    }

    /**
     * Affiche le formulaire de création (porteur/admin uniquement)
     */
    public function create()
    {
        $this->authorize('create', Projet::class);
        $categories = ['films', 'musique', 'art', 'startup'];
        
        return view('projets.create', compact('categories'));
    }

    /**
     * Enregistre un nouveau projet (porteur/admin uniquement)
     */
    public function store(Request $request)
    {
        $this->authorize('create', Projet::class);
        
        $validated = $request->validate([
            'titre' => 'required|string|max:255|unique:projets',
            'description' => 'required|string',
            'objectif' => 'required|numeric|min:1',
            'date_fin' => 'required|date|after:today',
            'categorie' => 'required|in:films,musique,art,startup',
            'image_principale' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_url' => 'nullable|url',
            'statut' => 'required|in:brouillon,publie',
        ], [
            'titre.required' => 'Le titre est obligatoire.',
            'titre.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'titre.unique' => 'Ce titre existe déjà.',
            'description.required' => 'La description est obligatoire.',
            'objectif.required' => 'L\'objectif financier est obligatoire.',
            'objectif.numeric' => 'L\'objectif doit être un nombre.',
            'objectif.min' => 'L\'objectif doit être supérieur à 0.',
            'date_fin.required' => 'La date de fin est obligatoire.',
            'date_fin.after' => 'La date de fin doit être postérieure à aujourd\'hui.',
            'categorie.required' => 'La catégorie est obligatoire.',
            'categorie.in' => 'La catégorie sélectionnée n\'est pas valide.',
            'image_principale.image' => 'Le fichier doit être une image.',
            'image_principale.mimes' => 'L\'image doit être au format jpeg, png, jpg ou gif.',
            'image_principale.max' => 'L\'image ne peut pas dépasser 2Mo.',
            'video_url.url' => 'L\'URL de la vidéo n\'est pas valide.',
        ]);

        // Gestion de l'image
        if ($request->hasFile('image_principale')) {
            $imagePath = $request->file('image_principale')->store('projets', 'public');
            $validated['image_principale'] = $imagePath;
        }

        // Ajout de l'utilisateur connecté comme porteur
        $validated['user_id'] = auth()->id();
        $validated['montant_collecte'] = 0;
        
        // Le slug sera généré automatiquement dans le modèle
        
        $projet = Projet::create($validated);
        
        return redirect()->route('projets.show', $projet)
            ->with('success', 'Projet créé avec succès !');
    }

    /**
     * Affiche les détails d'un projet (publique)
     */
    public function show(Projet $projet)
    {
        // Charger les relations nécessaires
        $projet->load(['porteur', 'contributions' => function($query) {
            $query->with('user')->latest()->take(5);
        }, 'updates' => function($query) {
            $query->with('user')->latest()->take(3);
        }, 'commentaires' => function($query) {
            $query->with('user')->latest()->take(5);
        }]);
        
        return view('projets.show', compact('projet'));
    }

    /**
     * Affiche le formulaire d'édition (porteur/admin uniquement)
     */
    public function edit(Projet $projet)
    {
        $this->authorize('update', $projet);
        $categories = ['films', 'musique', 'art', 'startup'];
        
        return view('projets.edit', compact('projet', 'categories'));
    }

    /**
     * Met à jour un projet (porteur/admin uniquement)
     */
    public function update(Request $request, Projet $projet)
    {
        $this->authorize('update', $projet);
        
        $validated = $request->validate([
            'titre' => 'required|string|max:255|unique:projets,titre,' . $projet->id,
            'description' => 'required|string',
            'objectif' => 'required|numeric|min:1',
            'date_fin' => 'required|date|after:today',
            'categorie' => 'required|in:films,musique,art,startup',
            'image_principale' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_url' => 'nullable|url',
            'statut' => 'required|in:brouillon,publie,termine',
        ], [
            'titre.required' => 'Le titre est obligatoire.',
            'titre.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'titre.unique' => 'Ce titre existe déjà.',
            'description.required' => 'La description est obligatoire.',
            'objectif.required' => 'L\'objectif financier est obligatoire.',
            'objectif.numeric' => 'L\'objectif doit être un nombre.',
            'objectif.min' => 'L\'objectif doit être supérieur à 0.',
            'date_fin.required' => 'La date de fin est obligatoire.',
            'date_fin.after' => 'La date de fin doit être postérieure à aujourd\'hui.',
            'categorie.required' => 'La catégorie est obligatoire.',
            'categorie.in' => 'La catégorie sélectionnée n\'est pas valide.',
            'image_principale.image' => 'Le fichier doit être une image.',
            'image_principale.mimes' => 'L\'image doit être au format jpeg, png, jpg ou gif.',
            'image_principale.max' => 'L\'image ne peut pas dépasser 2Mo.',
            'video_url.url' => 'L\'URL de la vidéo n\'est pas valide.',
        ]);

        // Gestion de l'image
        if ($request->hasFile('image_principale')) {
            // Supprimer l'ancienne image si elle existe
            if ($projet->image_principale) {
                Storage::disk('public')->delete($projet->image_principale);
            }
            
            $imagePath = $request->file('image_principale')->store('projets', 'public');
            $validated['image_principale'] = $imagePath;
        }

        // Ne pas permettre la modification du montant_collecte manuellement
        unset($validated['montant_collecte']);
        
        // Mettre à jour le slug si le titre change
        if ($validated['titre'] !== $projet->titre) {
            $validated['slug'] = Str::slug($validated['titre']) . '-' . uniqid();
        }
        
        $projet->update($validated);
        
        return redirect()->route('projets.show', $projet)
            ->with('success', 'Projet mis à jour avec succès !');
    }

    /**
     * Supprime un projet (porteur/admin uniquement)
     */
    public function destroy(Projet $projet)
    {
        $this->authorize('delete', $projet);
        
        // Supprimer l'image si elle existe
        if ($projet->image_principale) {
            Storage::disk('public')->delete($projet->image_principale);
        }
        
        $projet->delete();
        
        return redirect()->route('projets.index')
            ->with('success', 'Projet supprimé avec succès !');
    }

    /**
     * Publie un projet (porteur/admin uniquement)
     */
    public function publier(Projet $projet)
    {
        $this->authorize('publish', $projet);
        
        $projet->update(['statut' => 'publie']);
        
        return redirect()->route('projets.show', $projet)
            ->with('success', 'Projet publié avec succès !');
    }

    /**
     * Affiche les projets d'un porteur spécifique
     */
    public function projetsPorteur($userId)
    {
        $user = \App\Models\User::findOrFail($userId);
        $projets = $user->projets()->with('contributions')->latest()->paginate(12);
        
        return view('projets.porteur', compact('user', 'projets'));
    }
}
