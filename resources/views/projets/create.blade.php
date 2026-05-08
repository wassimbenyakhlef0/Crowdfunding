@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- En-tête -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Créer un nouveau projet</h1>
                <p class="mt-2 text-gray-600">
                    Remplissez les informations ci-dessous pour créer votre projet de crowdfunding.
                </p>
            </div>

            <!-- Formulaire -->
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <form method="POST" action="{{ route('projets.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <!-- Informations principales -->
                    <div class="px-6 py-6 space-y-6">
                        <!-- Titre -->
                        <div>
                            <label for="titre" class="block text-sm font-medium text-gray-700">
                                Titre du projet <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <input type="text" 
                                       id="titre" 
                                       name="titre" 
                                       value="{{ old('titre') }}"
                                       required
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            @if($errors->has('titre'))
                                <p class="mt-2 text-sm text-red-600">{{ $errors->first('titre') }}</p>
                            @endif
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                Description <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <textarea id="description" 
                                          name="description" 
                                          rows="6"
                                          required
                                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                            </div>
                            @if($errors->has('description'))
                                <p class="mt-2 text-sm text-red-600">{{ $errors->first('description') }}</p>
                            @endif
                        </div>

                        <!-- Objectif et Date fin -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Objectif financier -->
                            <div>
                                <label for="objectif" class="block text-sm font-medium text-gray-700">
                                    Objectif financier (€) <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <input type="number" 
                                           id="objectif" 
                                           name="objectif" 
                                           value="{{ old('objectif') }}"
                                           step="0.01"
                                           min="1"
                                           required
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                @if($errors->has('objectif'))
                                    <p class="mt-2 text-sm text-red-600">{{ $errors->first('objectif') }}</p>
                                @endif
                            </div>

                            <!-- Date de fin -->
                            <div>
                                <label for="date_fin" class="block text-sm font-medium text-gray-700">
                                    Date de fin <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <input type="date" 
                                           id="date_fin" 
                                           name="date_fin" 
                                           value="{{ old('date_fin') }}"
                                           required
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                @if($errors->has('date_fin'))
                                    <p class="mt-2 text-sm text-red-600">{{ $errors->first('date_fin') }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Catégorie et Statut -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Catégorie -->
                            <div>
                                <label for="categorie" class="block text-sm font-medium text-gray-700">
                                    Catégorie <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <select id="categorie" 
                                            name="categorie" 
                                            required
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Sélectionner une catégorie</option>
                                        @foreach($categories as $categorie)
                                            <option value="{{ $categorie }}" {{ old('categorie') == $categorie ? 'selected' : '' }}>
                                                {{ ucfirst($categorie) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @if($errors->has('categorie'))
                                    <p class="mt-2 text-sm text-red-600">{{ $errors->first('categorie') }}</p>
                                @endif
                            </div>

                            <!-- Statut -->
                            <div>
                                <label for="statut" class="block text-sm font-medium text-gray-700">
                                    Statut <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <select id="statut" 
                                            name="statut" 
                                            required
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="brouillon" {{ old('statut') == 'brouillon' ? 'selected' : '' }}>
                                            Brouillon
                                        </option>
                                        <option value="publie" {{ old('statut') == 'publie' ? 'selected' : '' }}>
                                            Publié
                                        </option>
                                    </select>
                                </div>
                                @if($errors->has('statut'))
                                    <p class="mt-2 text-sm text-red-600">{{ $errors->first('statut') }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Image principale -->
                        <div>
                            <label for="image_principale" class="block text-sm font-medium text-gray-700">
                                Image principale (max 2Mo)
                            </label>
                            <div class="mt-1 flex items-center">
                                <input type="file" 
                                       id="image_principale" 
                                       name="image_principale" 
                                       accept="image/*"
                                       class="block w-full text-sm text-gray-900 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @if($errors->has('image_principale'))
                                    <p class="mt-2 text-sm text-red-600">{{ $errors->first('image_principale') }}</p>
                                @endif
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                Formats acceptés : JPEG, PNG, JPG, GIF. Taille maximale : 2Mo.
                            </p>
                        </div>

                        <!-- URL vidéo -->
                        <div>
                            <label for="video_url" class="block text-sm font-medium text-gray-700">
                                URL de la vidéo (YouTube/Vimeo)
                            </label>
                            <div class="mt-1">
                                <input type="url" 
                                       id="video_url" 
                                       name="video_url" 
                                       value="{{ old('video_url') }}"
                                       placeholder="https://www.youtube.com/watch?v=..."
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            @if($errors->has('video_url'))
                                <p class="mt-2 text-sm text-red-600">{{ $errors->first('video_url') }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="px-6 py-4 bg-gray-50 text-right sm:px-6">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-save mr-2"></i>
                            Créer le projet
                        </button>
                        
                        <a href="{{ route('projets.index') }}" 
                           class="ml-3 inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-times mr-2"></i>
                            Annuler
                        </a>
                    </div>
                </form>
            </div>

            <!-- Informations complémentaires -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="text-lg font-medium text-blue-900 mb-2">
                    <i class="fas fa-info-circle mr-2"></i>
                    Informations importantes
                </h3>
                <div class="text-sm text-blue-800 space-y-2">
                    <p>
                        <strong>Statut "Brouillon" :</strong> Votre projet ne sera pas visible publiquement. Vous pourrez le modifier et le publier plus tard.
                    </p>
                    <p>
                        <strong>Statut "Publié" :</strong> Votre projet sera visible publiquement et les contributeurs pourront financer.
                    </p>
                    <p>
                        <strong>Objectif financier :</strong> Le montant que vous souhaitez collecter. Une fois publié, ce montant ne pourra plus être modifié.
                    </p>
                    <p>
                        <strong>Date de fin :</strong> Doit être postérieure à la date du jour.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
