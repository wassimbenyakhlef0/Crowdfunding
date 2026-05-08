<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Projet;
use App\Models\Contribution;
use App\Models\Commentaire;
use App\Models\Update;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_projets' => Projet::count(),
            'total_contributions' => Contribution::count(),
            'total_montant' => Contribution::sum('montant'),
            'projets_publies' => Projet::where('statut', 'publie')->count(),
            'projets_brouillons' => Projet::where('statut', 'brouillon')->count(),
            'projets_termine' => Projet::where('statut', 'termine')->count(),
            'porteurs' => User::where('role', 'porteur')->count(),
            'contributeurs' => User::where('role', 'contributeur')->count(),
            'admins' => User::where('role', 'admin')->count(),
        ];

        $recent_projets = Projet::latest()->take(5)->get();
        $recent_users = User::latest()->take(5)->get();
        $recent_contributions = Contribution::with(['user', 'projet'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_projets', 'recent_users', 'recent_contributions'));
    }
}
