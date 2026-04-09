@extends('admin.layouts.app')

@section('header_title', 'Gestion des Annonces')

@section('content')

@if(session('success_dialog'))
<div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm relative">
    <p class="font-bold">Succès</p>
    <p>{{ session('success_dialog') }}</p>
</div>
@endif

@if(session('error_dialog'))
<div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm relative">
    <p class="font-bold">Erreur</p>
    <p>{{ session('error_dialog') }}</p>
</div>
@endif

<div class="relative">
    <!-- Barre de Recherche et Filtres -->
    <div class="mb-6 bg-white p-4 rounded-xl shadow-sm border border-slate-100">
        <form method="GET" action="{{ route('admin.annonces.index') }}" class="flex flex-col sm:flex-row gap-4 items-center">
            
            <div class="w-full sm:w-1/2 relative">
                <input type="text" name="search" value="{{ $searchQuery }}" placeholder="Rechercher par titre, nom de l'hôte..." 
                       class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 pl-10">
                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            
            <div class="w-full sm:w-1/4">
                <select name="critere" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-slate-50">
                    <option value="tous" {{ $critere == 'tous' ? 'selected' : '' }}>Toutes les annonces</option>
                    <option value="En cours de vérification" {{ $critere == 'En cours de vérification' ? 'selected' : '' }}>En vérification</option>
                    <option value="Publié" {{ $critere == 'Publié' ? 'selected' : '' }}>Publiées</option>
                    <option value="Suspendu" {{ $critere == 'Suspendu' ? 'selected' : '' }}>Suspendues</option>
                    <option value="Rejeté" {{ $critere == 'Rejeté' ? 'selected' : '' }}>Rejetées</option>
                </select>
            </div>
            
            <div class="w-full sm:w-1/4">
                <button type="submit" class="w-full bg-blue-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-blue-700 transition shadow-sm">
                    Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Tableau des annonces -->
    <div class="bg-white overflow-hidden shadow-sm ring-1 ring-slate-200 rounded-xl">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="py-4 pl-6 pr-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Annonce</th>
                    <th scope="col" class="px-3 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Hôte Propriétaire</th>
                    <th scope="col" class="px-3 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Date & Tarif</th>
                    <th scope="col" class="px-3 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Statut Actual</th>
                    <th scope="col" class="relative py-4 pl-3 pr-6 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($annonces as $annonce)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="whitespace-nowrap py-4 pl-6 pr-3">
                        <div class="flex items-center gap-4">
                            <div class="h-12 w-12 flex-shrink-0 bg-slate-200 rounded-lg overflow-hidden flex items-center justify-center border border-slate-300">
                                @if($annonce->photo_url)
                                <img src="{{ asset($annonce->photo_url) }}" class="h-full w-full object-cover">
                                @else
                                    <svg class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                @endif
                            </div>
                            <div>
                                <div class="font-bold text-slate-900 truncate max-w-[200px]" title="{{ $annonce->titre }}">{{ Str::limit($annonce->titre, 30) }}</div>
                                <div class="text-xs text-slate-500 mt-1 uppercase">{{ $annonce->type_logement }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-600">
                        @if($annonce->hote)
                            <div class="font-medium text-slate-900">{{ $annonce->hote->prenom }} {{ $annonce->hote->nom }}</div>
                            <div class="text-xs text-slate-400">{{ $annonce->hote->email }}</div>
                        @else
                            <div class="text-xs italic text-red-500">Hôte inconnu</div>
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-600">
                        <div class="text-slate-900 font-bold">{{ number_format($annonce->tarif_nuit, 2, ',', ' ') }} MAD</div>
                        <div class="text-xs text-slate-400">Créé le {{ \Carbon\Carbon::parse($annonce->date_creation)->format('d/m/Y') }}</div>
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                        @if($annonce->statut === \App\Enums\StatutAnnonce::EN_VERIFICATION)
                            <span class="inline-flex items-center gap-1.5 text-blue-600 font-semibold bg-blue-50 px-2 py-1 rounded-md border border-blue-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> En Vérification
                            </span>
                        @elseif($annonce->statut === \App\Enums\StatutAnnonce::PUBLIE)
                            <span class="inline-flex items-center gap-1.5 text-blue-800 font-semibold bg-blue-100 px-2 py-1 rounded-md border border-blue-300">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-700"></span> Publié
                            </span>
                        @elseif($annonce->statut === \App\Enums\StatutAnnonce::SUSPENDU)
                            <span class="inline-flex items-center gap-1.5 text-slate-700 font-semibold bg-slate-200 px-2 py-1 rounded-md border border-slate-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-600"></span> Suspendu
                            </span>
                        @elseif($annonce->statut === \App\Enums\StatutAnnonce::REJETE)
                            <span class="inline-flex items-center gap-1.5 text-slate-600 font-semibold bg-slate-100 px-2 py-1 rounded-md border border-slate-300">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span> Rejeté
                            </span>
                        @else
                            <span class="text-slate-500 uppercase text-xs font-bold">{{ $annonce->statut->value }}</span>
                        @endif
                    </td>
                    <td class="relative whitespace-nowrap py-4 pl-3 pr-6 text-right text-sm font-medium flex justify-end">
                        <a href="{{ route('admin.annonces.index', ['selected' => $annonce->id_annonce, 'critere' => $critere, 'search' => $searchQuery, 'page' => request()->get('page')]) }}" 
                           class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-md transition font-semibold w-[100px] text-center">
                           Consulter
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                        Aucune annonce trouvée avec ces critères.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
             {{ $annonces->appends(['critere' => $critere, 'search' => $searchQuery])->links() }}
        </div>
    </div>


    {{-- ========================================== --}}
    {{-- CARTE FLOTTANTE (MODALE 100% HTML/CSS/PHP) --}}
    {{-- ========================================== --}}
    @if(isset($selectedAnnonce))
    <div class="fixed inset-0 z-[100] flex items-center justify-center px-4 py-8">
        
        <!-- Overlay flouté pour fermer -->
        <a href="{{ route('admin.annonces.index', ['critere' => $critere, 'search' => $searchQuery, 'page' => request()->get('page')]) }}" 
           class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-default"></a>

        <!-- Contenu de la modale -->
        <div class="relative bg-white w-full max-w-4xl rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-full">
            
            <!-- Entête Simple -->
            <div class="h-16 bg-white relative border-b border-slate-100 flex justify-between items-center px-4 flex-shrink-0">
                <span class="font-bold text-slate-800 px-4">Détails de l'annonce</span>
                <a href="{{ route('admin.annonces.index', ['critere' => $critere, 'search' => $searchQuery, 'page' => request()->get('page')]) }}" 
                   class="text-slate-400 hover:text-blue-600 rounded-full p-2 transition">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </a>
            </div>

            <div class="p-8 overflow-y-auto w-full flex-grow">
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Colonne 1: Infos Annonce -->
                    <div>
                        <!-- Photo principale -->
                        <div class="bg-slate-100 rounded-xl overflow-hidden mb-6 border border-slate-200 min-h-[250px] flex items-center justify-center">
                            @if($selectedAnnonce->photo_url)
                                <img src="{{ asset($selectedAnnonce->photo_url) }}" class="w-full h-full object-cover">
                            @else
                                <svg class="h-16 w-16 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            @endif
                        </div>

                        <h2 class="text-2xl font-extrabold text-slate-900 mb-2">{{ $selectedAnnonce->titre }}</h2>
                        <div class="text-xs uppercase font-bold text-blue-600 mb-4 bg-blue-50 inline-block px-2 py-1 rounded">{{ $selectedAnnonce->type_logement }} • Max {{ $selectedAnnonce->capacite }} pers.</div>

                        <p class="text-slate-600 text-sm mb-6 leading-relaxed bg-slate-50 p-4 rounded-lg border border-slate-100">
                            {{ $selectedAnnonce->description ?: 'Aucune description fournie.' }}
                        </p>
                        
                        <div class="flex items-center gap-4 text-sm font-semibold text-slate-800 bg-white p-4 rounded-lg border border-slate-200 shadow-sm mb-6">
                            <span>Tarif de base:</span>
                            <span class="text-2xl font-bold text-blue-700 ml-auto">{{ number_format($selectedAnnonce->tarif_nuit, 2, ',', ' ') }} MAD</span> <span class="text-slate-400 font-normal">/ nuit</span>
                        </div>
                        
                        <div class="text-xs text-slate-500 mb-2">
                            Adresse: <span class="font-medium text-slate-700">{{ $selectedAnnonce->adresse }}</span>
                        </div>
                    </div>
                    
                    <!-- Colonne 2: Infos Hôte & Décision -->
                    <div class="flex flex-col">
                        
                        <!-- Mini Profil de l'hôte -->
                        <div class="bg-white border text-center lg:text-left border-slate-200 p-6 rounded-xl shadow-sm mb-6 flex flex-col lg:flex-row items-center gap-5">
                            <div class="h-16 w-16 flex-shrink-0 bg-blue-100 text-blue-700 font-bold text-xl rounded-full flex items-center justify-center">
                                @if($selectedAnnonce->hote && $selectedAnnonce->hote->profil && $selectedAnnonce->hote->profil->photo_url)
                                    <img src="{{ asset(optional($selectedAnnonce->hote->profil)->photo_url) }}" class="h-full w-full object-cover rounded-full">
                                @else
                                    {{ substr(optional($selectedAnnonce->hote)->prenom ?? '?', 0, 1) }}{{ substr(optional($selectedAnnonce->hote)->nom ?? '?', 0, 1) }}
                                @endif
                            </div>
                            <div class="flex-1">
                                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Propriétaire de l'annonce</h4>
                                <div class="font-extrabold text-slate-900 text-lg">{{ optional($selectedAnnonce->hote)->prenom }} {{ optional($selectedAnnonce->hote)->nom }}</div>
                                <div class="text-sm text-slate-500">{{ optional($selectedAnnonce->hote)->email }}</div>
                                
                                @if($selectedAnnonce->hote)
                                    <div class="mt-2 text-xs">
                                        @php $hoteVerif = $selectedAnnonce->hote->getStatutVerification(); @endphp
                                        @if($hoteVerif === \App\Enums\VerificationStatut::VALIDE)
                                            <span class="text-blue-700 bg-blue-100 font-bold px-2 py-1 rounded">Identité Validée ✓</span>
                                        @elseif($hoteVerif === \App\Enums\VerificationStatut::EN_COURS)
                                            <span class="text-slate-700 bg-slate-200 font-bold px-2 py-1 rounded">Identité En Cours...</span>
                                        @else
                                            <span class="text-red-600 bg-red-100 font-bold px-2 py-1 rounded">Identité Non Validée ⚠</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Panel de Décision Administrateur -->
                        <div class="bg-slate-50 border border-slate-200 p-6 rounded-xl flex-grow flex flex-col justify-center">
                            
                            <h3 class="text-lg font-bold text-slate-900 mb-4 text-center">Panneau de Modération</h3>
                            
                            <div class="flex justify-center mb-8">
                                @if($selectedAnnonce->statut === \App\Enums\StatutAnnonce::EN_VERIFICATION)
                                    <span class="text-blue-600 font-bold bg-blue-100 px-4 py-2 rounded-lg border border-blue-200 text-sm text-center block">Cette annonce est en attente d'approbation.</span>
                                @elseif($selectedAnnonce->statut === \App\Enums\StatutAnnonce::PUBLIE)
                                    <span class="text-blue-800 font-bold bg-blue-100 px-4 py-2 rounded-lg border border-blue-300 text-sm text-center block">État Actuel: PUBLIÉ</span>
                                @elseif($selectedAnnonce->statut === \App\Enums\StatutAnnonce::SUSPENDU)
                                    <span class="text-slate-800 font-bold bg-slate-200 px-4 py-2 rounded-lg border border-slate-300 text-sm text-center block">État Actuel: SUSPENDU temporairement</span>
                                @elseif($selectedAnnonce->statut === \App\Enums\StatutAnnonce::REJETE)
                                    <span class="text-slate-600 font-bold bg-slate-200 px-4 py-2 rounded-lg border border-slate-300 text-sm text-center block">État Actuel: DÉFINITIVEMENT REJETÉ</span>
                                @endif
                            </div>

                            <div class="flex flex-col gap-4">
                                @if($selectedAnnonce->statut === \App\Enums\StatutAnnonce::EN_VERIFICATION)
                                    <form method="POST" action="{{ route('admin.annonces.publish', $selectedAnnonce->id_annonce) }}">
                                        @csrf
                                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition-colors text-base shadow-sm">
                                            Approuver et Publier cette annonce
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.annonces.reject', $selectedAnnonce->id_annonce) }}" class="mt-4 pt-4 border-t border-slate-200">
                                        @csrf
                                        <button type="submit" class="w-full bg-white border-2 border-blue-600 text-blue-600 hover:bg-blue-50 font-bold py-3 rounded-lg transition-colors shadow-sm">
                                            Rejeter l'Annonce
                                        </button>
                                    </form>
                                
                                @elseif($selectedAnnonce->statut === \App\Enums\StatutAnnonce::PUBLIE)
                                    <p class="text-sm text-slate-500 text-center mb-4">L'annonce est en ligne. Vous pouvez la suspendre en cas d'infraction constatée.</p>
                                    <form method="POST" action="{{ route('admin.annonces.suspend', $selectedAnnonce->id_annonce) }}">
                                        @csrf
                                        <button type="submit" class="w-full bg-white border-2 border-slate-500 text-slate-700 hover:bg-slate-100 font-bold py-3 rounded-lg transition-colors shadow-sm">
                                            Suspendre Temporairement l'Annonce
                                        </button>
                                    </form>

                                @elseif($selectedAnnonce->statut === \App\Enums\StatutAnnonce::SUSPENDU)
                                    <p class="text-sm text-slate-500 text-center mb-4">Pour remettre l'annonce visible sur la plateforme, cliquez ci-dessous.</p>
                                    <form method="POST" action="{{ route('admin.annonces.publish', $selectedAnnonce->id_annonce) }}">
                                        @csrf
                                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition-colors text-base shadow-sm">
                                            Rétablir et Publier l'Annonce
                                        </button>
                                    </form>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
