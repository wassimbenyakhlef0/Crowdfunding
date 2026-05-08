<?php

namespace App\Http\Controllers;

use App\Models\Update;
use App\Models\Projet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UpdateController extends Controller
{
    /**
     * Affiche la liste des mises à jour d'un projet
     */
    public function index(Projet $projet)
    {
        $updates = $projet->updates()->with('user')->latest()->paginate(10);
        return view('updates.index', compact('projet', 'updates'));
    }

    /**
     * Enregistre une nouvelle mise à jour
     */
    public function store(Request $request, Projet $projet)
    {
        $this->authorize('create', [Update::class, $projet]);

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'titre.required' => 'Le titre est obligatoire.',
            'titre.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'contenu.required' => 'Le contenu est obligatoire.',
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'L\'image doit être au format jpeg, png, jpg ou gif.',
            'image.max' => 'L\'image ne peut pas dépasser 2Mo.',
        ]);

        // Gestion de l'image
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('updates', 'public');
            $validated['image'] = $imagePath;
        }

        $validated['user_id'] = auth()->id();
        $validated['projet_id'] = $projet->id;

        $update = Update::create($validated);

        return redirect()->route('projets.show', $projet)
            ->with('success', 'Mise à jour publiée avec succès !');
    }

    /**
     * Affiche le formulaire d'édition d'une mise à jour
     */
    public function edit(Update $update)
    {
        $this->authorize('update', $update);
        
        return view('updates.edit', compact('update'));
    }

    /**
     * Met à jour une mise à jour existante
     */
    public function update(Request $request, Update $update)
    {
        $this->authorize('update', $update);

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'titre.required' => 'Le titre est obligatoire.',
            'titre.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'contenu.required' => 'Le contenu est obligatoire.',
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'L\'image doit être au format jpeg, png, jpg ou gif.',
            'image.max' => 'L\'image ne peut pas dépasser 2Mo.',
        ]);

        // Gestion de l'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($update->image) {
                Storage::disk('public')->delete($update->image);
            }
            
            $imagePath = $request->file('image')->store('updates', 'public');
            $validated['image'] = $imagePath;
        }

        $update->update($validated);

        return redirect()->route('projets.show', $update->projet)
            ->with('success', 'Mise à jour modifiée avec succès !');
    }

    /**
     * Supprime une mise à jour
     */
    public function destroy(Update $update)
    {
        $this->authorize('delete', $update);
        
        $projet = $update->projet;

        // Supprimer l'image si elle existe
        if ($update->image) {
            Storage::disk('public')->delete($update->image);
        }

        $update->delete();

        return redirect()->route('projets.show', $projet)
            ->with('success', 'Mise à jour supprimée avec succès !');
    }
}
