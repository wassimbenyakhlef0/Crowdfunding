@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Gestion des utilisateurs</h1>
            <p class="mt-2 text-gray-600">
                Consultez et gérez tous les utilisateurs de la plateforme
            </p>
        </div>

        <!-- Filtres -->
        <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-8">
            <form method="GET" action="{{ route('admin.users.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Recherche -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
                            Recherche
                        </label>
                        <input type="text" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Nom, prénom ou email..."
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Filtre par rôle -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                            Rôle
                        </label>
                        <select id="role" name="role" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Tous les rôles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>
                                    {{ ucfirst($role) }}
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
                        <a href="{{ route('admin.users.index') }}" 
                           class="inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-times mr-2"></i>
                            Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Liste des utilisateurs -->
        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Utilisateur
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Rôle
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Téléphone
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date d'inscription
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full" 
                                                     src="https://ui-avatars.com/api/?name={{ urlencode($user->prenom . ' ' . $user->name) }}&background=random" 
                                                     alt="{{ $user->name }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $user->prenom }} {{ $user->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    ID: {{ $user->id }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($user->role === 'admin') bg-red-100 text-red-800
                                            @elseif($user->role === 'porteur') bg-blue-100 text-blue-800
                                            @else bg-green-100 text-green-800 @endif">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $user->telephone ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            @if($user->role === 'porteur')
                                                <a href="{{ route('projets.porteur', $user->id) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900">
                                                    <i class="fas fa-rocket"></i>
                                                </a>
                                            @endif
                                            
                                            @if($user->role === 'contributeur')
                                                <a href="{{ route('contributions.mes-contributions') }}" 
                                                   class="text-green-600 hover:text-green-900">
                                                    <i class="fas fa-hand-holding-usd"></i>
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
            {{ $users->links() }}
        </div>

        <!-- Actions -->
        <div class="mt-8 flex justify-between">
            <a href="{{ route('admin.dashboard') }}" 
               class="inline-flex items-center text-gray-600 hover:text-gray-900">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour au tableau de bord
            </a>
            
            <div class="text-sm text-gray-500">
                {{ $users->count() }} utilisateur(s) sur {{ $users->total() }} au total
            </div>
        </div>
    </div>
</div>
@endsection
