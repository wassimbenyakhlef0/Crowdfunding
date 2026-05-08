@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="py-12">
        <div class="max-w-4xl mx-auto">
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

            <!-- En-tête du projet -->
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <!-- Image principale -->
                @if($projet->image_principale)
                    <div class="relative h-96 w-full">
                        <img src="{{ $projet->image_url }}" 
                             alt="{{ $projet->titre }}" 
                             class="w-full h-full object-cover">
                        
                        <!-- Badge de statut -->
                        <div class="absolute top-4 right-4">
                            @if($projet->statut === 'publie')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>
                                    Publié
                                </span>
                            @elseif($projet->statut === 'brouillon')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-edit mr-1"></i>
                                    Brouillon
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-stop mr-1"></i>
                                    Terminé
                                </span>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Contenu principal -->
                <div class="px-6 py-8">
                    <!-- Titre et métadonnées -->
                    <div class="mb-6">
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $projet->titre }}</h1>
                        
                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-tag mr-1"></i>
                                {{ ucfirst($projet->categorie) }}
                            </span>
                            
                            <span class="inline-flex items-center">
                                <i class="fas fa-user mr-1"></i>
                                Par {{ $projet->porteur->name }}
                            </span>
                            
                            <span class="inline-flex items-center">
                                <i class="fas fa-calendar mr-1"></i>
                                Date limite : {{ \Carbon\Carbon::parse($projet->date_fin)->format('d/m/Y') }}
                            </span>
                            
                            @if(!$projet->est_termine)
                                <span class="inline-flex items-center text-green-600">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $projet->jours_restants }} jours restants
                                </span>
                            @else
                                <span class="inline-flex items-center text-red-600">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Projet terminé
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-3">Description</h2>
                        <div class="prose max-w-none text-gray-600">
                            {!! nl2br(e($projet->description)) !!}
                        </div>
                    </div>

                    <!-- Objectif de financement -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Objectif de financement</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Montant collecté -->
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-900">{{ $projet->formate_montant_collecte }}</div>
                                <div class="text-sm text-gray-600">Collecté</div>
                            </div>
                            
                            <!-- Objectif -->
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-900">{{ $projet->formate_objectif }}</div>
                                <div class="text-sm text-gray-600">Objectif</div>
                            </div>
                            
                            <!-- Pourcentage -->
                            <div class="text-center">
                                <div class="text-2xl font-bold {{ $projet->pourcentage_collecte >= 100 ? 'text-green-600' : 'text-indigo-600' }}">
                                    {{ $projet->pourcentage_collecte }}%
                                </div>
                                <div class="text-sm text-gray-600">Atteint</div>
                            </div>
                        </div>
                        
                        <!-- Barre de progression -->
                        <div class="mt-4">
                            <div class="w-full bg-gray-200 rounded-full h-4">
                                <div class="bg-indigo-600 h-4 rounded-full transition-all duration-500" 
                                     style="width: {{ min($projet->pourcentage_collecte, 100) }}%"></div>
                            </div>
                            <div class="text-center text-sm text-gray-600 mt-2">
                                {{ $projet->pourcentage_collecte }}% de l'objectif atteint
                            </div>
                        </div>
                    </div>

                    <!-- Vidéo -->
                    @if($projet->video_url)
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-3">Vidéo de présentation</h2>
                            <div class="aspect-w-16 aspect-h-9">
                                <iframe src="{{ $projet->video_url }}" 
                                        class="w-full h-full rounded-lg"
                                        frameborder="0" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                        allowfullscreen>
                                </iframe>
                            </div>
                        </div>
                    @endif

                    <!-- Actions pour le porteur -->
                    @if(auth()->check() && (auth()->user()->id === $projet->user_id || auth()->user()->role === 'admin'))
                        <div class="flex flex-wrap gap-3 mb-8">
                            @if($projet->statut === 'brouillon')
                                <form method="POST" action="{{ route('projets.publier', $projet) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <i class="fas fa-paper-plane mr-2"></i>
                                        Publier le projet
                                    </button>
                                </form>
                            @endif
                            
                            <a href="{{ route('projets.edit', $projet) }}" 
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-edit mr-2"></i>
                                Modifier
                            </a>
                            
                            <form method="POST" action="{{ route('projets.destroy', $projet) }}" 
                                  class="inline"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <i class="fas fa-trash mr-2"></i>
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    @endif

                    <!-- Formulaire de contribution -->
                    @if(auth()->check() && $projet->statut === 'publie' && !$projet->est_termine)
                        <div class="bg-blue-50 rounded-lg p-6 mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">
                                <i class="fas fa-heart mr-2"></i>
                                Contribuer à ce projet
                            </h2>
                            <p class="text-gray-600 mb-4">
                                Soutenez ce projet en faisant une contribution financière.
                            </p>
                            
                            <form method="POST" action="{{ route('contributions.store', $projet) }}" class="space-y-4">
                                @csrf
                                
                                <!-- Montant -->
                                <div>
                                    <label for="montant" class="block text-sm font-medium text-gray-700">
                                        Montant de la contribution (€) <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1">
                                        <input type="number" 
                                               id="montant" 
                                               name="montant" 
                                               step="0.01"
                                               min="1"
                                               max="10000"
                                               required
                                               placeholder="10.00"
                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    @if($errors->has('montant'))
                                        <p class="mt-2 text-sm text-red-600">{{ $errors->first('montant') }}</p>
                                    @endif
                                </div>

                                <!-- Message optionnel -->
                                <div>
                                    <label for="message" class="block text-sm font-medium text-gray-700">
                                        Message de soutien (optionnel)
                                    </label>
                                    <div class="mt-1">
                                        <textarea id="message" 
                                                  name="message" 
                                                  rows="3"
                                                  maxlength="500"
                                                  placeholder="Exprimez votre soutien au porteur de ce projet..."
                                                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                    </div>
                                    @if($errors->has('message'))
                                        <p class="mt-2 text-sm text-red-600">{{ $errors->first('message') }}</p>
                                    @endif
                                </div>

                                <!-- Case anonyme -->
                                <div class="flex items-center">
                                    <input id="anonyme" 
                                           name="anonyme" 
                                           type="checkbox" 
                                           value="1"
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="anonyme" class="ml-2 block text-sm text-gray-700">
                                        Contribuer anonymement
                                    </label>
                                </div>

                                <!-- Bouton de soumission -->
                                <div class="flex justify-center">
                                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md shadow-sm text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="fas fa-heart mr-2"></i>
                                        Contribuer maintenant
                                    </button>
                                </div>
                            </form>
                        </div>
                    @elseif(!auth()->check())
                        <div class="bg-blue-50 rounded-lg p-6 mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">
                                <i class="fas fa-heart mr-2"></i>
                                Contribuer à ce projet
                            </h2>
                            <p class="text-gray-600 mb-4">
                                Connectez-vous pour soutenir ce projet.
                            </p>
                            <div class="text-center">
                                <a href="{{ route('login') }}" 
                                   class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md shadow-sm text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fas fa-sign-in-alt mr-2"></i>
                                    Se connecter
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Contributions récentes -->
            @if($projet->contributions->count() > 0)
                <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden mb-8">
                    <div class="px-6 py-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">
                            <i class="fas fa-hand-holding-heart mr-2"></i>
                            Contributions récentes ({{ $projet->contributions->count() }})
                        </h2>
                        
                        <div class="space-y-4">
                            @foreach($projet->contributions as $contribution)
                                <div class="border-b border-gray-200 pb-4 last:border-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full" 
                                                         src="https://ui-avatars.com/api/?name={{ urlencode($contribution->user->name) }}&background=random" 
                                                         alt="{{ $contribution->user->name }}">
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        @if($contribution->anonyme)
                                                            Contributeur anonyme
                                                        @else
                                                            {{ $contribution->user->name }}
                                                        @endif
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $contribution->created_at->format('d/m/Y à H:i') }}
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            @if($contribution->message)
                                                <p class="mt-2 text-gray-600">{{ $contribution->message }}</p>
                                            @endif
                                        </div>
                                        
                                        <div class="ml-4 text-right">
                                            <div class="text-lg font-semibold text-green-600">
                                                {{ number_format($contribution->montant, 2, ',', ' ') }} €
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Mises à jour -->
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden mb-8">
                <div class="px-6 py-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-900">
                            <i class="fas fa-newspaper mr-2"></i>
                            Mises à jour ({{ $projet->updates->count() }})
                        </h2>
                        
                        @if(auth()->check() && (auth()->user()->id === $projet->user_id || auth()->user()->role === 'admin'))
                            <button onclick="document.getElementById('form-update').classList.toggle('hidden')" 
                                    class="inline-flex items-center px-3 py-1 bg-indigo-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-plus mr-1"></i>
                                Ajouter une mise à jour
                            </button>
                        @endif
                    </div>
                    
                    <!-- Formulaire d'ajout de mise à jour -->
                    @if(auth()->check() && (auth()->user()->id === $projet->user_id || auth()->user()->role === 'admin'))
                        <div id="form-update" class="hidden mb-6 p-4 border border-gray-200 rounded-lg">
                            <form method="POST" action="{{ route('updates.store', $projet) }}" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                
                                <!-- Titre -->
                                <div>
                                    <label for="titre" class="block text-sm font-medium text-gray-700">
                                        Titre <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1">
                                        <input type="text" 
                                               id="titre" 
                                               name="titre" 
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
                                                  rows="4"
                                                  required
                                                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                    </div>
                                    @if($errors->has('contenu'))
                                        <p class="mt-2 text-sm text-red-600">{{ $errors->first('contenu') }}</p>
                                    @endif
                                </div>

                                <!-- Image -->
                                <div>
                                    <label for="image" class="block text-sm font-medium text-gray-700">
                                        Image (optionnel)
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
                                </div>

                                <!-- Boutons -->
                                <div class="flex justify-end space-x-3">
                                    <button type="button" 
                                            onclick="document.getElementById('form-update').classList.add('hidden')"
                                            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Annuler
                                    </button>
                                    <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Publier
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                    
                    <!-- Liste des mises à jour -->
                    @if($projet->updates->count() > 0)
                        <div class="space-y-4">
                            @foreach($projet->updates as $update)
                                <div class="border-l-4 border-indigo-500 pl-4">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <div class="flex items-center justify-center h-8 w-8 rounded-full bg-indigo-500 text-white text-sm font-medium">
                                                {{ strtoupper(substr($update->user->name, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $update->user->name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $update->created_at->format('d/m/Y à H:i') }}
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
                                            
                                            <h3 class="text-lg font-semibold text-gray-900 mt-2">
                                                {{ $update->titre }}
                                            </h3>
                                            
                                            @if($update->image)
                                                <div class="mt-2">
                                                    <img src="{{ Storage::url($update->image) }}" 
                                                         alt="{{ $update->titre }}" 
                                                         class="max-w-sm rounded-lg">
                                                </div>
                                            @endif
                                            
                                            <div class="mt-2 text-gray-600">
                                                {!! nl2br(e($update->contenu)) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-newspaper text-4xl mb-2"></i>
                            <p>Aucune mise à jour pour le moment.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Commentaires -->
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="px-6 py-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">
                        <i class="fas fa-comments mr-2"></i>
                        Commentaires ({{ $projet->commentaires->count() }})
                    </h2>
                    
                    <!-- Liste des commentaires -->
                    @if($projet->commentaires->count() > 0)
                        <div class="space-y-4 mb-6">
                            @foreach($projet->commentaires as $commentaire)
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <img class="h-10 w-10 rounded-full" 
                                             src="https://ui-avatars.com/api/?name={{ urlencode($commentaire->user->name) }}&background=random" 
                                             alt="{{ $commentaire->user->name }}">
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start">
                                            <div class="flex items-center space-x-2">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $commentaire->user->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $commentaire->created_at->format('d/m/Y à H:i') }}
                                                </div>
                                            </div>
                                            
                                            @if(auth()->check() && (auth()->user()->id === $commentaire->user_id || auth()->user()->role === 'admin'))
                                                <form method="POST" action="{{ route('commentaires.destroy', $commentaire) }}" 
                                                      class="inline"
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                        <div class="mt-2 text-gray-600">
                                            {{ $commentaire->contenu }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500 mb-6">
                            <i class="fas fa-comments text-4xl mb-2"></i>
                            <p>Soyez le premier à commenter !</p>
                        </div>
                    @endif
                    
                    <!-- Formulaire de commentaire -->
                    @if(auth()->check())
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Laisser un commentaire</h3>
                            <form method="POST" action="{{ route('commentaires.store', $projet) }}" class="space-y-4">
                                @csrf
                                
                                <div>
                                    <textarea id="contenu" 
                                              name="contenu" 
                                              rows="3"
                                              maxlength="500"
                                              required
                                              placeholder="Votre commentaire..."
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('contenu') }}</textarea>
                                    @if($errors->has('contenu'))
                                        <p class="mt-2 text-sm text-red-600">{{ $errors->first('contenu') }}</p>
                                    @endif
                                    <div class="mt-1 text-xs text-gray-500">
                                        {{ 500 - strlen(old('contenu', '')) }} caractères restants
                                    </div>
                                </div>
                                
                                <div class="flex justify-end">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <i class="fas fa-paper-plane mr-2"></i>
                                        Envoyer
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="border-t pt-6 text-center">
                            <p class="text-gray-600 mb-4">
                                Connectez-vous pour laisser un commentaire.
                            </p>
                            <a href="{{ route('login') }}" 
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Se connecter
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
