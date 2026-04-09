@extends('admin.layouts.app')

@section('header_title', 'Gestion des Litiges d\'Évaluations')

@section('content')

@if(session('success_dialog'))
<div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm relative">
    <p class="font-bold">Succès</p>
    <p>{{ session('success_dialog') }}</p>
</div>
@endif

<div class="relative">
    <!-- Barre de Recherche et Filtres -->
    <div class="mb-6 bg-white p-4 rounded-xl shadow-sm border border-slate-100">
        <form method="GET" action="{{ route('admin.litiges.index') }}" class="flex flex-col sm:flex-row gap-4 items-center">
            
            <div class="w-full sm:w-1/2 relative">
                <input type="text" name="search" value="{{ $searchQuery }}" placeholder="Rechercher par motif, nom du déclarant..." 
                       class="w-full rounded-lg border-slate-300 shadow-sm focus:border-red-500 focus:ring-red-500 pl-10">
                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            
            <div class="w-full sm:w-1/4">
                <select name="critere" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-red-500 focus:ring-red-500 bg-slate-50">
                    <option value="tous" {{ $critere == 'tous' ? 'selected' : '' }}>Tous les litiges</option>
                    <option value="En cours" {{ $critere == 'En cours' ? 'selected' : '' }}>En cours de traitement</option>
                    <option value="Clôturé" {{ $critere == 'Clôturé' ? 'selected' : '' }}>Clôturés (Traités)</option>
                </select>
            </div>
            
            <div class="w-full sm:w-1/4">
                <button type="submit" class="w-full bg-slate-800 text-white font-bold px-4 py-2 rounded-lg hover:bg-slate-900 transition shadow-sm">
                    Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Tableau des Litiges -->
    <div class="bg-white overflow-hidden shadow-sm ring-1 ring-slate-200 rounded-xl">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="py-4 pl-6 pr-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Motif du Litige</th>
                    <th scope="col" class="px-3 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Déclarant</th>
                    <th scope="col" class="px-3 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Évaluation Réf</th>
                    <th scope="col" class="px-3 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Date création</th>
                    <th scope="col" class="px-3 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Statut</th>
                    <th scope="col" class="relative py-4 pl-3 pr-6 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($litiges as $litige)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="whitespace-nowrap py-4 pl-6 pr-3">
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 flex-shrink-0 bg-red-50 rounded-lg overflow-hidden flex items-center justify-center border border-red-100">
                                <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <div>
                                <div class="font-bold text-slate-900 truncate max-w-[200px]" title="{{ $litige->motif }}">{{ Str::limit($litige->motif, 30) }}</div>
                                <div class="text-[10px] text-slate-400 mt-1 uppercase tracking-widest">Réf: {{ substr($litige->id_ticket, 0, 8) }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-600">
                        @if($litige->declarant)
                            <div class="font-medium text-slate-800">{{ $litige->declarant->prenom }} {{ $litige->declarant->nom }}</div>
                            <div class="text-[10px] text-slate-500 mt-1">{{ $litige->declarant->email }}</div>
                        @else
                            <div class="text-xs italic text-red-500">Inconnu</div>
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-600">
                        @if($litige->evaluation)
                            <div class="text-xs font-mono text-slate-800">{{ substr($litige->evaluation->id_evaluation, 0, 8) }}</div>
                        @else
                            <div class="text-xs italic text-red-500">Non trouvée</div>
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-600">
                        <div class="text-slate-900 font-medium">{{ optional($litige->date_creation)->format('d F Y') }}</div>
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                        @if($litige->statut === \App\Enums\TicketLitigeStatut::EN_COURS)
                            <span class="inline-flex items-center gap-1.5 text-red-700 font-bold bg-red-50 px-2 py-1 rounded-md border border-red-200 shadow-sm">
                                <span class="w-2 h-2 rounded-full bg-red-600 animate-pulse"></span> En cours
                            </span>
                        @elseif($litige->statut === \App\Enums\TicketLitigeStatut::CLOTURE)
                            <span class="inline-flex items-center gap-1.5 text-slate-700 font-semibold bg-slate-100 px-2 py-1 rounded-md border border-slate-300">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span> Clôturé
                            </span>
                        @else
                            <span class="text-slate-500 uppercase text-xs font-bold">{{ optional($litige->statut)->value }}</span>
                        @endif
                    </td>
                    <td class="relative whitespace-nowrap py-4 pl-3 pr-6 text-right text-sm font-medium flex justify-end">
                        <a href="{{ route('admin.litiges.index', ['selected' => $litige->id_ticket, 'critere' => $critere, 'search' => $searchQuery, 'page' => request()->get('page')]) }}" 
                           class="text-slate-700 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 border border-slate-200 px-3 py-1.5 rounded-md transition font-semibold w-[110px] text-center shadow-sm">
                           Gérer
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                        Aucun ticket litige trouvé avec ces critères.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
             {{ $litiges->appends(['critere' => $critere, 'search' => $searchQuery])->links() }}
        </div>
    </div>


    {{-- ========================================== --}}
    {{-- CARTE FLOTTANTE INTERVENTION (MODALE)      --}}
    {{-- ========================================== --}}
    @if(isset($selectedLitige))
    <div class="fixed inset-0 z-[100] flex items-center justify-center px-4 py-8">
        
        <!-- Overlay flouté pour fermer -->
        <a href="{{ route('admin.litiges.index', ['critere' => $critere, 'search' => $searchQuery, 'page' => request()->get('page')]) }}" 
           class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-default"></a>

        <!-- Contenu de la modale -->
        <div class="relative bg-white w-full max-w-5xl rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-full">
            
            <!-- Entête Simple -->
            <div class="h-16 bg-white relative border-b border-slate-100 flex justify-between items-center px-4 flex-shrink-0">
                <span class="font-bold text-slate-800 px-4">Gestion du Litige <span class="text-slate-400 font-normal">#{{ substr($selectedLitige->id_ticket, 0, 8) }}</span></span>
                <a href="{{ route('admin.litiges.index', ['critere' => $critere, 'search' => $searchQuery, 'page' => request()->get('page')]) }}" 
                   class="text-slate-400 hover:text-red-500 rounded-full p-2 transition">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </a>
            </div>

            <div class="p-8 overflow-y-auto w-full flex-grow bg-slate-50">
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    
                    <!-- Colonne 1: Le Corps du Litige et de la Réservation/Évaluation -->
                    <div>
                        <!-- Description du Litige -->
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
                            <div class="bg-red-50 border-b border-red-100 px-5 py-3">
                                <h3 class="font-bold text-red-800 text-sm">Motif rapporté : {{ $selectedLitige->motif }}</h3>
                            </div>
                            <div class="p-5">
                                <p class="text-slate-700 text-sm leading-relaxed whitespace-pre-wrap">Le déclarant conteste l'évaluation N° {{ optional($selectedLitige->evaluation)->id_evaluation }}. Ce litige concerne potentiellement un commentaire inapproprié ou faux.</p>
                            </div>
                        </div>

                        <!-- L'Évaluation En Question -->
                        @if($selectedLitige->evaluation)
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
                            <div class="bg-slate-100 border-b border-slate-200 px-5 py-3">
                                <h3 class="font-bold text-slate-700 text-sm">Contenu de l'Évaluation Signalée</h3>
                            </div>
                            <div class="p-5">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="text-2xl font-black text-slate-800">{{ $selectedLitige->evaluation->note }} <span class="text-lg text-slate-400 font-normal">/ 5</span></span>
                                    <div class="text-yellow-400">★★★★★</div>
                                </div>
                                <div class="text-slate-600 bg-slate-50 p-4 border border-slate-100 rounded text-sm italic">
                                    "{{ $selectedLitige->evaluation->commentaire }}"
                                </div>
                                @if($selectedLitige->evaluation->motif_signalement)
                                <div class="mt-4 p-3 bg-red-50 text-red-700 text-xs border border-red-100 rounded">
                                    <span class="font-bold">Motif initial de signalement:</span> {{ $selectedLitige->evaluation->motif_signalement }}
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Les Personnes Concernées par le Litige -->
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                            <div class="bg-slate-100 border-b border-slate-200 px-5 py-3 flex justify-between items-center">
                                <h3 class="font-bold text-slate-700 text-sm">Le Déclarant (Auteur du ticket)</h3>
                            </div>
                            
                            <div class="p-5">
                                @if($selectedLitige->declarant)
                                <div class="flex items-center gap-3 bg-slate-50 p-3 rounded-lg border border-slate-100">
                                    <div class="h-10 w-10 bg-white rounded-full flex items-center justify-center text-slate-400 font-bold border border-slate-200">
                                        @if($selectedLitige->declarant->profil && $selectedLitige->declarant->profil->photo_url)
                                            <img src="{{ asset($selectedLitige->declarant->profil->photo_url) }}" class="h-full w-full object-cover rounded-full">
                                        @else
                                            U
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-800">{{ $selectedLitige->declarant->prenom }} {{ $selectedLitige->declarant->nom }}</div>
                                        <div class="text-xs text-slate-500">{{ $selectedLitige->declarant->email }}</div>
                                    </div>
                                </div>
                                @else
                                <div class="text-red-500 italic text-sm">Déclarant introuvable</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Colonne 2: Panneau de Résolution Admin -->
                    <div class="flex flex-col">
                        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden flex-grow flex flex-col">
                            <div class="bg-slate-800 px-6 py-4">
                                <h3 class="text-white font-bold text-lg">Arbitrage et Résolution</h3>
                            </div>
                            
                            <div class="p-6 flex-grow flex flex-col justify-center">
                                
                                @if($selectedLitige->statut === \App\Enums\TicketLitigeStatut::EN_COURS)
                                    <div class="mb-6 flex items-center gap-3 bg-red-50 text-red-800 p-4 border border-red-100 rounded-lg">
                                        <svg class="w-6 h-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        <p class="text-sm font-medium">Ce litige nécessite une intervention de l'administrateur. Renseignez la décision finale pour le clôturer (Les parties seront notifiées).</p>
                                    </div>
                                    
                                    <form method="POST" action="{{ route('admin.litiges.close', $selectedLitige->id_ticket) }}" class="flex flex-col gap-4">
                                        @csrf
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-2">Décision ou Message :</label>
                                            <textarea name="decision" required placeholder="Ex: Après vérification, le commentaire a été supprimé pour non-respect des règles. Le litige est clos." 
                                                      class="w-full border-slate-300 rounded-lg text-sm focus:ring-slate-800 focus:border-slate-800 min-h-[140px] p-4 shadow-sm"></textarea>
                                        </div>
                                        
                                        <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-3.5 rounded-lg transition-colors text-base shadow-sm mt-4">
                                            Clôturer le Litige
                                        </button>
                                    </form>
                                    
                                @elseif($selectedLitige->statut === \App\Enums\TicketLitigeStatut::CLOTURE)
                                    
                                    <div class="flex flex-col items-center justify-center text-center py-8">
                                        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-6">
                                            <svg class="w-10 h-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                        <h3 class="text-xl font-bold text-slate-800 mb-2">Litige Résolu et Clôturé</h3>
                                        <p class="text-slate-500 text-sm max-w-sm mb-6">L'administration a examiné et clôturé ce ticket.</p>
                                        
                                        <div class="w-full max-w-xs mx-auto border-t border-slate-200 mt-2 pt-6 flex justify-between">
                                            <div>
                                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Date Modération (Clôture)</div>
                                                <div class="font-medium text-slate-700 text-sm">{{ optional($selectedLitige->date_cloture)->format('d F Y à H:i') ?: 'Non définie' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    
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
