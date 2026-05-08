@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Tableau de bord Administrateur</h1>
            <p class="mt-2 text-gray-600">
                Vue d'ensemble de la plateforme CampusFund
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

        <!-- Statistiques principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Total Utilisateurs
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ $stats['total_users'] }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                        <i class="fas fa-rocket text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Total Projets
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ $stats['total_projets'] }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                        <i class="fas fa-hand-holding-usd text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Total Contributions
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ $stats['total_contributions'] }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                        <i class="fas fa-euro-sign text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Montant Total
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ number_format($stats['total_montant'], 2, ',', ' ') }} €
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques détaillées -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Projets par statut -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Projets par statut</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Brouillons</span>
                        <span class="text-sm font-medium text-gray-900">{{ $stats['projets_brouillons'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Publiés</span>
                        <span class="text-sm font-medium text-green-600">{{ $stats['projets_publies'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Terminés</span>
                        <span class="text-sm font-medium text-red-600">{{ $stats['projets_termine'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Utilisateurs par rôle -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Utilisateurs par rôle</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Porteurs</span>
                        <span class="text-sm font-medium text-blue-600">{{ $stats['porteurs'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Contributeurs</span>
                        <span class="text-sm font-medium text-green-600">{{ $stats['contributeurs'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Admins</span>
                        <span class="text-sm font-medium text-red-600">{{ $stats['admins'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activités récentes -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Projets récents -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Projets récents</h3>
                <div class="space-y-3">
                    @foreach($recent_projets as $projet)
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                @if($projet->image_principale)
                                    <img src="{{ $projet->image_url }}" alt="{{ $projet->titre }}" class="h-8 w-8 rounded object-cover">
                                @else
                                    <div class="h-8 w-8 bg-gray-200 rounded flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400 text-xs"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $projet->titre }}</p>
                                <p class="text-xs text-gray-500">{{ $projet->porteur->name }}</p>
                            </div>
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($projet->statut === 'publie') bg-green-100 text-green-800
                                    @elseif($projet->statut === 'brouillon') bg-gray-100 text-gray-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $projet->statut }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Utilisateurs récents -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Utilisateurs récents</h3>
                <div class="space-y-3">
                    @foreach($recent_users as $user)
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <img class="h-8 w-8 rounded-full" 
                                     src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" 
                                     alt="{{ $user->name }}">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $user->prenom }} {{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($user->role === 'admin') bg-red-100 text-red-800
                                    @elseif($user->role === 'porteur') bg-blue-100 text-blue-800
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ $user->role }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Contributions récentes -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Contributions récentes</h3>
                <div class="space-y-3">
                    @foreach($recent_contributions as $contribution)
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <img class="h-8 w-8 rounded-full" 
                                     src="https://ui-avatars.com/api/?name={{ urlencode($contribution->user->name) }}&background=random" 
                                     alt="{{ $contribution->user->name }}">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $contribution->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $contribution->projet->titre }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-green-600">
                                    {{ number_format($contribution->montant, 2, ',', ' ') }} €
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="mt-8 flex justify-center space-x-4">
            <a href="{{ route('admin.users.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-users mr-2"></i>
                Gérer les utilisateurs
            </a>
            <a href="{{ route('admin.projects.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <i class="fas fa-rocket mr-2"></i>
                Gérer les projets
            </a>
            <a href="{{ route('projets.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                <i class="fas fa-eye mr-2"></i>
                Voir le site public
            </a>
        </div>
    </div>
</div>
@endsection
