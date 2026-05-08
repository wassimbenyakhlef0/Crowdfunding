<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Projet;
use App\Models\Contribution;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();
        
        // Filtre par rôle
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        // Recherche par nom ou email
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('prenom', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        $users = $query->latest()->paginate(20);
        $roles = ['porteur', 'contributeur', 'admin'];
        
        return view('admin.users.index', compact('users', 'roles'));
    }
}
