@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Gestion des projets</h1>
            <p class="mt-2 text-gray-600">
                Consultez et gérez tous les projets de la plateforme
            </p>
        </div>

        <!-- Filtres -->
        <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-8">
            <form method="GET" action="{{ route('admin.projects.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Recherche -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
                            Recherche
                        </label>
                        <input type="text" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Titre du projet..."
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Filtre par statut -->
                    <div>
                        <label for="statut" class="block text-sm font-medium text-gray-700 mb-1">
                            Statut
                        </label>
                        <select id="statut" name="statut" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Tous les statuts</option>
                            @foreach($statuts as $statut)
                                <option value="{{ $statut }}" {{ request('statut') == $statut ? 'selected' : '' }}>
                                    {{ ucfirst($statut) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtre par catégorie -->
                    <div>
                        <label for="categorie" class="block text-sm font-medium text-gray-700 mb-1">
                            Catégorie
                        </label>
                        <select id="categorie" name="categorie" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Toutes les catégories</option>
                            @foreach($categories as $categorie)
                                <option value="{{ $categorie }}" {{ request('categorie') == $categorie ? 'selected' : '' }}>
                                    {{ ucfirst($categorie) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="flex-1 inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-search mr-2"></i>
                            Filtrer
                        </button>
                        <a href="{{ route('admin.projects.index') }}" 
                           class="inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-times mr-2"></i>
                            Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Liste des projets -->
        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Projet
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Porteur
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Catégorie
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Objectif
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Collecté
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Progression
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($projets as $projet)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if($projet->image_principale)
                                                    <img src="{{ $projet->image_url }}" alt="{{ $projet->titre }}" class="h-10 w-10 rounded object-cover">
                                                @else
                                                    <div class="h-10 w-10 bg-gray-200 rounded flex items-center justify-center">
                                                        <i class="fas fa-image text-gray-400 text-sm"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $projet->titre }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    ID: {{ $projet->id }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <img class="h-8 w-8 rounded-full mr-2" 
                                                 src="https://ui-avatars.com/api/?name={{ urlencode($projet->porteur->name) }}&background=random" 
                                                 alt="{{ $projet->porteur->name }}">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $projet->porteur->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $projet->porteur->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ ucfirst($projet->categorie) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $projet->formate_objectif }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $projet->formate_montant_collecte }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-1">
                                                <div class="text-sm text-gray-900 font-medium">
                                                    {{ $projet->pourcentage_collecte }}%
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ min($projet->pourcentage_collecte, 100) }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($projet->statut === 'publie') bg-green-100 text-green-800
                                            @elseif($projet->statut === 'brouillon') bg-gray-100 text-gray-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($projet->statut) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('projets.show', $projet) }}" 
                                               class="text-indigo-600 hover:text-indigo-900"
                                               title="Voir le projet">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if(auth()->user()->role === 'admin' || auth()->user()->id === $projet->user_id)
                                                <a href="{{ route('projets.edit', $projet) }}" 
                                                   class="text-blue-600 hover:text-blue-900"
                                                   title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                            
                                            <button class="text-gray-400 hover:text-gray-600" title="Plus d'options">
                                                <i class="fas fa-ellipsis-h"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $projets->links() }}
        </div>

        <!-- Actions -->
        <div class="mt-8 flex justify-between">
            <a href="{{ route('admin.dashboard') }}" 
               class="inline-flex items-center text-gray-600 hover:text-gray-900">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour au tableau de bord
            </a>
            
            <div class="text-sm text-gray-500">
                {{ $projets->count() }} projet(s) sur {{ $projets->total() }} au total
            </div>
        </div>
    </div>
</div>
@endsection
