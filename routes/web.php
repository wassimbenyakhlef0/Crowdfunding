<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Page d'accueil - liste des projets
Route::get('/', [App\Http\Controllers\ProjetController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Routes admin - must come before regular routes
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', function () {
        $stats = [
            'total_users' => \App\Models\User::count(),
            'total_projets' => \App\Models\Projet::count(),
            'total_contributions' => \App\Models\Contribution::count(),
            'total_montant' => \App\Models\Contribution::sum('montant'),
            'projets_publies' => \App\Models\Projet::where('statut', 'publie')->count(),
            'projets_brouillons' => \App\Models\Projet::where('statut', 'brouillon')->count(),
            'projets_termine' => \App\Models\Projet::where('statut', 'termine')->count(),
            'porteurs' => \App\Models\User::where('role', 'porteur')->count(),
            'contributeurs' => \App\Models\User::where('role', 'contributeur')->count(),
            'admins' => \App\Models\User::where('role', 'admin')->count(),
        ];

        $recent_projets = \App\Models\Projet::latest()->take(5)->get();
        $recent_users = \App\Models\User::latest()->take(5)->get();
        $recent_contributions = \App\Models\Contribution::with(['user', 'projet'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_projets', 'recent_users', 'recent_contributions'));
    })->name('admin.dashboard');
    
    Route::get('/users', function () {
        $query = \App\Models\User::query();
        
        // Filtre par rôle
        if (request()->filled('role')) {
            $query->where('role', request()->role);
        }
        
        // Recherche par nom ou email
        if (request()->filled('search')) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . request()->search . '%')
                  ->orWhere('prenom', 'like', '%' . request()->search . '%')
                  ->orWhere('email', 'like', '%' . request()->search . '%');
            });
        }
        
        $users = $query->latest()->paginate(20);
        $roles = ['porteur', 'contributeur', 'admin'];
        
        return view('admin.users.index', compact('users', 'roles'));
    })->name('admin.users.index');
    
    Route::get('/projects', function () {
        $query = \App\Models\Projet::with(['porteur', 'contributions']);
        
        // Filtre par statut
        if (request()->filled('statut')) {
            $query->where('statut', request()->statut);
        }
        
        // Filtre par catégorie
        if (request()->filled('categorie')) {
            $query->where('categorie', request()->categorie);
        }
        
        // Recherche par titre
        if (request()->filled('search')) {
            $query->where('titre', 'like', '%' . request()->search . '%');
        }
        
        $projets = $query->latest()->paginate(20);
        $categories = ['films', 'musique', 'art', 'startup'];
        $statuts = ['brouillon', 'publie', 'termine'];
        
        return view('admin.projects.index', compact('projets', 'categories', 'statuts'));
    })->name('admin.projects.index');
});

// Routes pour les projets (publiques pour index/show, protégées pour CRUD)
Route::resource('projets', App\Http\Controllers\ProjetController::class)->names([
    'index' => 'projets.index',
    'create' => 'projets.create',
    'store' => 'projets.store',
    'show' => 'projets.show',
    'edit' => 'projets.edit',
    'update' => 'projets.update',
    'destroy' => 'projets.destroy',
]);

// Route personnalisée pour publier un projet
Route::post('/projets/{projet}/publier', [App\Http\Controllers\ProjetController::class, 'publier'])
    ->name('projets.publier')
    ->middleware(['auth']);

// Route pour les projets d'un porteur spécifique
Route::get('/projets/porteur/{user}', [App\Http\Controllers\ProjetController::class, 'projetsPorteur'])
    ->name('projets.porteur');

// Routes pour les contributions
Route::middleware(['auth'])->group(function () {
    Route::post('/projets/{projet}/contribuer', [App\Http\Controllers\ContributionController::class, 'store'])
        ->name('contributions.store');
    
    Route::get('/mes-contributions', [App\Http\Controllers\ContributionController::class, 'mesContributions'])
        ->name('contributions.mes-contributions');
});

// Routes pour les mises à jour (updates)
Route::middleware(['auth'])->group(function () {
    Route::get('/projets/{projet}/updates', [App\Http\Controllers\UpdateController::class, 'index'])
        ->name('updates.index');
    
    Route::post('/projets/{projet}/updates', [App\Http\Controllers\UpdateController::class, 'store'])
        ->name('updates.store');
    
    Route::get('/updates/{update}/edit', [App\Http\Controllers\UpdateController::class, 'edit'])
        ->name('updates.edit');
    
    Route::put('/updates/{update}', [App\Http\Controllers\UpdateController::class, 'update'])
        ->name('updates.update');
    
    Route::delete('/updates/{update}', [App\Http\Controllers\UpdateController::class, 'destroy'])
        ->name('updates.destroy');
});

// Routes pour les commentaires
Route::middleware(['auth'])->group(function () {
    Route::post('/projets/{projet}/commentaires', [App\Http\Controllers\CommentaireController::class, 'store'])
        ->name('commentaires.store');
    
    Route::delete('/commentaires/{commentaire}', [App\Http\Controllers\CommentaireController::class, 'destroy'])
        ->name('commentaires.destroy');
});

// Routes pour les porteurs de projets
Route::middleware(['auth', 'role:porteur'])->prefix('porteur')->name('porteur.')->group(function () {
    Route::get('/mes-projets', function () {
        return redirect()->route('projets.porteur', auth()->id());
    })->name('projets.index');
});

require __DIR__.'/auth.php';
