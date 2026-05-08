<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Projet;
use App\Models\Contribution;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects for admin.
     */
    public function index(Request $request)
    {
        $query = Projet::with(['porteur', 'contributions']);
        
        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        
        // Filtre par catégorie
        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }
        
        // Recherche par titre
        if ($request->filled('search')) {
            $query->where('titre', 'like', '%' . $request->search . '%');
        }
        
        $projets = $query->latest()->paginate(20);
        $categories = ['films', 'musique', 'art', 'startup'];
        $statuts = ['brouillon', 'publie', 'termine'];
        
        return view('admin.projects.index', compact('projets', 'categories', 'statuts'));
    }
}
