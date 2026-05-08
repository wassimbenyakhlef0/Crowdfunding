@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="py-12">
        <!-- En-tête -->
        <div class="mb-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li>
                        <a href="{{ route('projets.show', $projet) }}" class="text-gray-500 hover:text-gray-700">
                            {{ $projet->titre }}
                        </a>
                    </li>
                    <li>
                        <span class="text-gray-400">/</span>
                    </li>
                    <li>
                        <span class="text-gray-900">Mises à jour</span>
                    </li>
                </ol>
            </nav>
            <div class="flex justify-between items-center mt-4">
                <h1 class="text-3xl font-bold text-gray-900">Mises à jour du projet</h1>
                @if(auth()->check() && (auth()->user()->id === $projet->user_id || auth()->user()->role === 'admin'))
                    <a href="{{ route('projets.show', $projet) }}#form-update" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-plus mr-2"></i>
                        Ajouter une mise à jour
                    </a>
                @endif
            </div>
        </div>

        <!-- Messages de succès/erreur -->
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Informations du projet -->
        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden mb-8">
            <div class="px-6 py-6">
                <div class="flex items-center space-x-4">
                    @if($projet->image_principale)
                        <img src="{{ $projet->image_url }}" 
                             alt="{{ $projet->titre }}" 
                             class="h-16 w-16 object-cover rounded-lg">
                    @else
                        <div class="h-16 w-16 bg-gray-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-image text-gray-400"></i>
                        </div>
                    @endif
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">{{ $projet->titre }}</h2>
                        <p class="text-gray-600">Par {{ $projet->porteur->name }}</p>
                        <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                            <span>
                                <i class="fas fa-euro-sign mr-1"></i>
                                {{ $projet->formate_montant_collecte }} / {{ $projet->formate_objectif }}
                            </span>
                            <span>
                                <i class="fas fa-percentage mr-1"></i>
                                {{ $projet->pourcentage_collecte }}%
                            </span>
                            @if(!$projet->est_termine)
                                <span>
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $projet->jours_restants }} jours
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des mises à jour -->
        @if($updates->count() > 0)
            <div class="space-y-6">
                @foreach($updates as $update)
                    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                        <div class="px-6 py-6">
                            <!-- En-tête de la mise à jour -->
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-indigo-500 text-white text-sm font-medium">
                                            {{ strtoupper(substr($update->user->name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $update->user->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $update->created_at->format('d/m/Y à H:i') }}
                                            @if($update->updated_at != $update->created_at)
                                                <span class="text-xs text-gray-400 ml-2">
                                                    (modifié le {{ $update->updated_at->format('d/m/Y à H:i') }})
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                @if(auth()->check() && (auth()->user()->id === $update->user_id || auth()->user()->role === 'admin'))
                                    <div class="flex space-x-2">
                                        <a href="{{ route('updates.edit', $update) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 text-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('updates.destroy', $update) }}" 
                                              class="inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette mise à jour ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Contenu -->
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">
                                {{ $update->titre }}
                            </h3>
                            
                            @if($update->image)
                                <div class="mb-4">
                                    <img src="{{ Storage::url($update->image) }}" 
                                         alt="{{ $update->titre }}" 
                                         class="max-w-md rounded-lg">
                                </div>
                            @endif
                            
                            <div class="prose max-w-none text-gray-700">
                                {!! nl2br(e($update->contenu)) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-8">
                {{ $updates->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune mise à jour</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Ce projet n'a pas encore de mise à jour.
                </p>
                @if(auth()->check() && (auth()->user()->id === $projet->user_id || auth()->user()->role === 'admin'))
                    <div class="mt-6">
                        <a href="{{ route('projets.show', $projet) }}#form-update" 
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-plus mr-2"></i>
                            Ajouter la première mise à jour
                        </a>
                    </div>
                @endif
            </div>
        @endif

        <!-- Actions -->
        <div class="mt-8 flex justify-between">
            <a href="{{ route('projets.show', $projet) }}" 
               class="inline-flex items-center text-gray-600 hover:text-gray-900">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour au projet
            </a>
            
            @if(auth()->check() && (auth()->user()->id === $projet->user_id || auth()->user()->role === 'admin'))
                <a href="{{ route('projets.show', $projet) }}#form-update" 
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-plus mr-2"></i>
                    Ajouter une mise à jour
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
