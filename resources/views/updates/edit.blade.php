@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="py-12">
        <div class="max-w-3xl mx-auto">
            <!-- En-tête -->
            <div class="mb-8">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2">
                        <li>
                            <a href="{{ route('projets.show', $update->projet) }}" class="text-gray-500 hover:text-gray-700">
                                {{ $update->projet->titre }}
                            </a>
                        </li>
                        <li>
                            <span class="text-gray-400">/</span>
                        </li>
                        <li>
                            <span class="text-gray-900">Modifier la mise à jour</span>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-bold text-gray-900 mt-4">Modifier la mise à jour</h1>
                <p class="mt-2 text-gray-600">
                    Mettez à jour les informations de cette mise à jour.
                </p>
            </div>

            <!-- Formulaire -->
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <form method="POST" action="{{ route('updates.update', $update) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Informations principales -->
                    <div class="px-6 py-6 space-y-6">
                        <!-- Titre -->
                        <div>
                            <label for="titre" class="block text-sm font-medium text-gray-700">
                                Titre <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <input type="text" 
                                       id="titre" 
                                       name="titre" 
                                       value="{{ old('titre', $update->titre) }}"
                                       required
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            @if($errors->has('titre'))
                                <p class="mt-2 text-sm text-red-600">{{ $errors->first('titre') }}</p>
                            @endif
                        </div>

                        <!-- Contenu -->
                        <div>
                            <label for="contenu" class="block text-sm font-medium text-gray-700">
                                Contenu <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <textarea id="contenu" 
                                          name="contenu" 
                                          rows="6"
                                          required
                                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('contenu', $update->contenu) }}</textarea>
                            </div>
                            @if($errors->has('contenu'))
                                <p class="mt-2 text-sm text-red-600">{{ $errors->first('contenu') }}</p>
                            @endif
                        </div>

                        <!-- Image actuelle -->
                        @if($update->image)
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Image actuelle
                                </label>
                                <div class="flex items-center space-x-4">
                                    <img src="{{ Storage::url($update->image) }}" 
                                         alt="{{ $update->titre }}" 
                                         class="h-32 w-32 object-cover rounded-lg">
                                    <div>
                                        <p class="text-sm text-gray-600">Image actuelle</p>
                                        <p class="text-xs text-gray-500">Laissez vide pour conserver l'image existante</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Image -->
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700">
                                Nouvelle image (optionnel)
                            </label>
                            <div class="mt-1">
                                <input type="file" 
                                       id="image" 
                                       name="image" 
                                       accept="image/*"
                                       class="block w-full text-sm text-gray-900 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            @if($errors->has('image'))
                                <p class="mt-2 text-sm text-red-600">{{ $errors->first('image') }}</p>
                            @endif
                            <p class="mt-2 text-sm text-gray-500">
                                Formats acceptés : JPEG, PNG, JPG, GIF. Taille maximale : 2Mo.
                            </p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="px-6 py-4 bg-gray-50 text-right sm:px-6">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-save mr-2"></i>
                            Mettre à jour
                        </button>
                        
                        <a href="{{ route('projets.show', $update->projet) }}" 
                           class="ml-3 inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-times mr-2"></i>
                            Annuler
                        </a>
                    </div>
                </form>
            </div>

            <!-- Actions supplémentaires -->
            <div class="mt-6 flex justify-between">
                <a href="{{ route('projets.show', $update->projet) }}" 
                   class="inline-flex items-center text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour au projet
                </a>
                
                <form method="POST" action="{{ route('updates.destroy', $update) }}" 
                      class="inline"
                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette mise à jour ? Cette action est irréversible.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-trash mr-2"></i>
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
