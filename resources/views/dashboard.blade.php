@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="mb-6">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Dashboard') }}
                    </h2>
                    <p class="mt-2 text-gray-600">
                        Bienvenue sur votre tableau de bord CampusFund!
                    </p>
                </div>
                
                @if(auth()->user()->role === 'porteur')
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-blue-900 mb-2">Mes Projets</h3>
                            <p class="text-blue-700">Gérez vos projets de crowdfunding</p>
                            <a href="{{ route('projets.porteur', auth()->id()) }}" class="mt-4 inline-flex items-center text-blue-600 hover:text-blue-800">
                                Voir mes projets →
                            </a>
                        </div>
                        
                        <div class="bg-green-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-green-900 mb-2">Créer un Projet</h3>
                            <p class="text-green-700">Lancez votre nouvelle campagne</p>
                            <a href="{{ route('projets.create') }}" class="mt-4 inline-flex items-center text-green-600 hover:text-green-800">
                                Créer →
                            </a>
                        </div>
                        
                        <div class="bg-purple-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-purple-900 mb-2">Mes Contributions</h3>
                            <p class="text-purple-700">Consultez vos contributions</p>
                            <a href="{{ route('contributions.mes-contributions') }}" class="mt-4 inline-flex items-center text-purple-600 hover:text-purple-800">
                                Voir →
                            </a>
                        </div>
                    </div>
                @elseif(auth()->user()->role === 'contributeur')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-orange-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-orange-900 mb-2">Explorer les Projets</h3>
                            <p class="text-orange-700">Découvrez des projets innovants</p>
                            <a href="{{ route('projets.index') }}" class="mt-4 inline-flex items-center text-orange-600 hover:text-orange-800">
                                Explorer →
                            </a>
                        </div>
                        
                        <div class="bg-purple-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-purple-900 mb-2">Mes Contributions</h3>
                            <p class="text-purple-700">Consultez vos contributions</p>
                            <a href="{{ route('contributions.mes-contributions') }}" class="mt-4 inline-flex items-center text-purple-600 hover:text-purple-800">
                                Voir →
                            </a>
                        </div>
                    </div>
                @elseif(auth()->user()->role === 'admin')
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-red-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-red-900 mb-2">Administration</h3>
                            <p class="text-red-700">Gérez la plateforme</p>
                            <a href="{{ route('admin.dashboard') }}" class="mt-4 inline-flex items-center text-red-600 hover:text-red-800">
                                Admin →
                            </a>
                        </div>
                        
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-blue-900 mb-2">Tous les Projets</h3>
                            <p class="text-blue-700">Consultez tous les projets</p>
                            <a href="{{ route('projets.index') }}" class="mt-4 inline-flex items-center text-blue-600 hover:text-blue-800">
                                Voir →
                            </a>
                        </div>
                        
                        <div class="bg-green-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-green-900 mb-2">Utilisateurs</h3>
                            <p class="text-green-700">Gérez les utilisateurs</p>
                            <a href="{{ route('admin.users.index') }}" class="mt-4 inline-flex items-center text-green-600 hover:text-green-800">
                                Gérer →
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
