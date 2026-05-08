@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- En-tête avec recherche et filtres -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="px-4 py-5 sm:p-6">
                    <h1 class="text-2xl font-bold text-gray-900 mb-6">Découvrir les projets</h1>
                    
                    <!-- Formulaire de recherche et filtres -->
                    <form method="GET" action="{{ route('projets.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Recherche -->
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700">Rechercher</label>
                                <input type="text" 
                                       id="search" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Rechercher un projet..."
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <!-- Filtre par catégorie -->
                            <div>
                                <label for="categorie" class="block text-sm font-medium text-gray-700">Catégorie</label>
                                <select id="categorie" 
                                        name="categorie" 
                                        value="{{ request('categorie') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Toutes les catégories</option>
                                    @foreach($categories as $categorie)
                                        <option value="{{ $categorie }}" {{ request('categorie') == $categorie ? 'selected' : '' }}>
                                            {{ ucfirst($categorie) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Bouton de recherche -->
                            <div class="flex items-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Rechercher
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Messages de succès/erreur -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Grille des projets -->
            @if($projets->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($projets as $projet)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow duration-300">
                            <div class="p-6">
                                <!-- Image du projet -->
                                <div class="mb-4">
                                    <img src="{{ $projet->image_url }}" 
                                         alt="{{ $projet->titre }}" 
                                         class="w-full h-48 object-cover rounded-lg">
                                </div>
                                
                                <!-- Informations principales -->
                                <div class="space-y-2">
                                    <!-- Catégorie et statut -->
                                    <div class="flex justify-between items-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ ucfirst($projet->categorie) }}
                                        </span>
                                        @if($projet->statut === 'publie')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Publié
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Brouillon
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Titre -->
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        <a href="{{ route('projets.show', $projet) }}" class="hover:text-indigo-600">
                                            {{ Str::limit($projet->titre, 50) }}
                                        </a>
                                    </h3>
                                    
                                    <!-- Description -->
                                    <p class="text-gray-600 text-sm">
                                        {{ Str::limit($projet->description, 100) }}
                                    </p>
                                    
                                    <!-- Barre de progression -->
                                    <div class="mt-4">
                                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                                            <span>{{ $projet->formate_montant_collecte }}</span>
                                            <span>{{ $projet->formate_objectif }}</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-indigo-600 h-2 rounded-full transition-all duration-500" 
                                                 style="width: {{ min($projet->pourcentage_collecte, 100) }}%"></div>
                                        </div>
                                        <div class="text-center text-sm text-gray-600 mt-1">
                                            {{ $projet->pourcentage_collecte }}% collecté
                                        </div>
                                    </div>
                                    
                                    <!-- Jours restants -->
                                    @if(!$projet->est_termine)
                                        <div class="text-sm text-gray-500">
                                            <i class="far fa-clock"></i>
                                            {{ $projet->jours_restants }} jours restants
                                        </div>
                                    @else
                                        <div class="text-sm text-red-500">
                                            <i class="far fa-times-circle"></i>
                                            Terminé
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Bouton d'action -->
                                <div class="mt-4">
                                    <a href="{{ route('projets.show', $projet) }}" 
                                       class="w-full flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Voir le projet
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $projets->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m0 0v6m0-6h3m-6 0h3a2 2 0 002 2m0 0a2 2 0 00-2-2m0 0H5a2 2 0 00-2 2v6a2 2 0 002 2h3m-3-3h6"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun projet trouvé</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Essayez de modifier vos critères de recherche ou de filtre.
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Bouton "Créer un projet" pour les porteurs -->
@if(auth()->check() && auth()->user()->role === 'porteur')
    <div class="fixed bottom-4 right-4">
        <a href="{{ route('projets.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fas fa-plus mr-2"></i>
            Créer un projet
        </a>
    </div>
@endif
@endsection
