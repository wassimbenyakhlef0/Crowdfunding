@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Mes Contributions</h1>
            <p class="mt-2 text-gray-600">
                Consultez l'historique de toutes vos contributions aux projets.
            </p>
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

        <!-- Liste des contributions -->
        @if($contributions->count() > 0)
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:p-6">
                    <div class="space-y-6">
                        @foreach($contributions as $contribution)
                            <div class="border-l-4 border-green-500 pl-4 py-4">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <!-- Projet -->
                                        <div class="mb-3">
                                            <h3 class="text-lg font-semibold text-gray-900">
                                                <a href="{{ route('projets.show', $contribution->projet) }}" class="hover:text-indigo-600">
                                                    {{ $contribution->projet->titre }}
                                                </a>
                                            </h3>
                                            <p class="text-sm text-gray-600">
                                                Par {{ $contribution->projet->porteur->name }}
                                            </p>
                                        </div>
                                        
                                        <!-- Détails de la contribution -->
                                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                                            <div class="flex items-center">
                                                <i class="fas fa-euro-sign mr-1"></i>
                                                <span class="font-semibold text-green-600">
                                                    {{ number_format($contribution->montant, 2, ',', ' ') }} €
                                                </span>
                                            </div>
                                            
                                            <div class="flex items-center">
                                                <i class="fas fa-calendar mr-1"></i>
                                                {{ $contribution->created_at->format('d/m/Y à H:i') }}
                                            </div>
                                            
                                            @if($contribution->anonyme)
                                                <div class="flex items-center">
                                                    <i class="fas fa-user-secret mr-1"></i>
                                                    <span class="text-gray-500">Anonyme</span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Message de soutien -->
                                        @if($contribution->message)
                                            <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                                <p class="text-gray-700 italic">
                                                    "{{ $contribution->message }}"
                                                </p>
                                            </div>
                                        @endif
                                        
                                        <!-- Statut du projet -->
                                        <div class="mt-3 flex items-center space-x-2">
                                            @if($contribution->projet->statut === 'publie')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check mr-1"></i>
                                                    Publié
                                                </span>
                                            @elseif($contribution->projet->statut === 'termine')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-stop mr-1"></i>
                                                    Terminé
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    <i class="fas fa-edit mr-1"></i>
                                                    Brouillon
                                                </span>
                                            @endif
                                            
                                            <!-- Progression du projet -->
                                            <div class="flex items-center text-sm text-gray-600">
                                                <span>{{ $contribution->projet->pourcentage_collecte }}% financé</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Actions -->
                                    <div class="ml-4">
                                        <a href="{{ route('projets.show', $contribution->projet) }}" 
                                           class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <i class="fas fa-eye mr-1"></i>
                                            Voir
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="mt-6">
                {{ $contributions->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune contribution</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Vous n'avez pas encore contribué à des projets.
                </p>
                <div class="mt-6">
                    <a href="{{ route('projets.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-indigo-600 bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-search mr-2"></i>
                        Découvrir les projets
                    </a>
                </div>
            </div>
        @endif
        
        <!-- Statistiques -->
        @if($contributions->count() > 0)
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                            <i class="fas fa-hand-holding-usd text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Total contribué
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ number_format($contributions->sum('montant'), 2, ',', ' ') }} €
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                            <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Nombre de contributions
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ $contributions->count() }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                            <i class="fas fa-star text-purple-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Projets soutenus
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ $contributions->pluck('projet_id')->unique()->count() }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
