@extends('admin.layouts.app')

@section('header_title', 'Suivi des Réservations')

@section('content')

<div class="relative">
    <!-- Barre de Recherche et Filtres -->
    <div class="mb-6 bg-white p-4 rounded-xl shadow-sm border border-slate-100">
        <form method="GET" action="{{ route('admin.reservations.index') }}" class="flex flex-col sm:flex-row gap-4 items-center">
            
            <div class="w-full sm:w-1/2 relative">
                <input type="text" name="search" value="{{ $searchQuery }}" placeholder="Rechercher par nom du voyageur, hôte ou annonce..." 
                       class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 pl-10">
                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            
            <div class="w-full sm:w-1/4">
                <select name="critere" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-slate-50">
                    <option value="tous" {{ $critere == 'tous' ? 'selected' : '' }}>Toutes les réservations</option>
                    <option value="En attente" {{ $critere == 'En attente' ? 'selected' : '' }}>En attente</option>
                    <option value="Confirmée" {{ $critere == 'Confirmée' ? 'selected' : '' }}>Confirmées</option>
                    <option value="En cours" {{ $critere == 'En cours' ? 'selected' : '' }}>En cours</option>
                    <option value="Terminée" {{ $critere == 'Terminée' ? 'selected' : '' }}>Terminées</option>
                    <option value="Annulée" {{ $critere == 'Annulée' ? 'selected' : '' }}>Annulées</option>
                    <option value="Refusée" {{ $critere == 'Refusée' ? 'selected' : '' }}>Refusées</option>
                    <option value="Expirée" {{ $critere == 'Expirée' ? 'selected' : '' }}>Expirées</option>
                </select>
            </div>
            
            <div class="w-full sm:w-1/4">
                <button type="submit" class="w-full bg-blue-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-blue-700 transition shadow-sm">
                    Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Tableau des réservations -->
    <div class="bg-white overflow-hidden shadow-sm ring-1 ring-slate-200 rounded-xl">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="py-4 pl-6 pr-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Réservation & Logement</th>
                    <th scope="col" class="px-3 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Voyageur / Hôte</th>
                    <th scope="col" class="px-3 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Dates (Arr - Dép)</th>
                    <th scope="col" class="px-3 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Statut Actual</th>
                    <th scope="col" class="relative py-4 pl-3 pr-6 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Visualiser</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($reservations as $resa)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="whitespace-nowrap py-4 pl-6 pr-3">
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 flex-shrink-0 bg-blue-50 rounded-lg overflow-hidden flex items-center justify-center border border-blue-100">
                                <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <div class="font-bold text-slate-900 truncate max-w-[200px]" title="{{ optional($resa->annonce)->titre }}">{{ Str::limit(optional($resa->annonce)->titre ?? 'Annonce supprimée', 30) }}</div>
                                <div class="text-xs text-slate-500 mt-1 uppercase">{{ $resa->nb_voyageurs }} pers. • {{ number_format($resa->montant_total, 2, ',', ' ') }} MAD</div>
                            </div>
                        </div>
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-600">
                        <div class="grid grid-cols-1 gap-1">
                            <div class="flex items-center gap-2"><span class="w-4 h-4 rounded-full bg-slate-200 text-[10px] flex items-center justify-center font-bold text-slate-600">V</span> <span class="font-medium text-slate-900">{{ optional($resa->voyageur)->prenom }} {{ optional($resa->voyageur)->nom }}</span></div>
                            <div class="flex items-center gap-2"><span class="w-4 h-4 rounded-full bg-blue-200 text-[10px] flex items-center justify-center font-bold text-blue-700">H</span> <span class="text-slate-500">{{ optional($resa->hote)->prenom }} {{ optional($resa->hote)->nom }}</span></div>
                        </div>
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-600">
                        <div class="text-slate-900 font-medium">Arr : {{ \Carbon\Carbon::parse($resa->date_arrivee)->format('d/m/Y') }}</div>
                        <div class="text-slate-500 text-xs mt-1">Dép : {{ \Carbon\Carbon::parse($resa->date_depart)->format('d/m/Y') }}</div>
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                        @if($resa->statut === \App\Enums\StatutReservation::EN_ATTENTE)
                            <span class="inline-flex items-center gap-1.5 text-blue-600 font-semibold bg-blue-50 px-2 py-1 rounded-md border border-blue-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> En Attente
                            </span>
                        @elseif($resa->statut === \App\Enums\StatutReservation::CONFIRMEE)
                            <span class="inline-flex items-center gap-1.5 text-blue-800 font-semibold bg-blue-100 px-2 py-1 rounded-md border border-blue-300">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-700"></span> Confirmée
                            </span>
                        @elseif($resa->statut === \App\Enums\StatutReservation::EN_COURS)
                            <span class="inline-flex items-center gap-1.5 text-blue-700 font-bold bg-blue-50 px-2 py-1 rounded-md border border-blue-300">
                                <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span> En séjour
                            </span>
                        @elseif($resa->statut === \App\Enums\StatutReservation::TERMINEE)
                            <span class="inline-flex items-center gap-1.5 text-slate-700 font-semibold bg-slate-100 px-2 py-1 rounded-md border border-slate-300">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Terminée
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-slate-500 font-bold bg-slate-50 px-2 py-1 rounded-md border border-slate-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> {{ Str::ucfirst($resa->statut->value) }}
                            </span>
                        @endif
                    </td>
                    <td class="relative whitespace-nowrap py-4 pl-3 pr-6 text-right text-sm font-medium flex justify-end">
                        <a href="{{ route('admin.reservations.index', ['selected' => $resa->id_reservation, 'critere' => $critere, 'search' => $searchQuery, 'page' => request()->get('page')]) }}" 
                           class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-md transition font-semibold w-[100px] text-center">
                           Détails
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                        Aucune réservation trouvée.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
             {{ $reservations->appends(['critere' => $critere, 'search' => $searchQuery])->links() }}
        </div>
    </div>


    {{-- ========================================== --}}
    {{-- CARTE FLOTTANTE LECTURE SEULE (MODALE)     --}}
    {{-- ========================================== --}}
    @if(isset($selectedReservation))
    <div class="fixed inset-0 z-[100] flex items-center justify-center px-4 py-8">
        
        <!-- Overlay flouté pour fermer -->
        <a href="{{ route('admin.reservations.index', ['critere' => $critere, 'search' => $searchQuery, 'page' => request()->get('page')]) }}" 
           class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-default"></a>

        <!-- Contenu de la modale -->
        <div class="relative bg-white w-full max-w-3xl rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-full">
            
            <!-- Entête Simple -->
            <div class="h-16 bg-white relative border-b border-slate-100 flex justify-between items-center px-4 flex-shrink-0">
                <span class="font-bold text-slate-800 px-4">Lecture de Réservation (Réf: {{ substr($selectedReservation->id_reservation, 0, 8) }}...)</span>
                <a href="{{ route('admin.reservations.index', ['critere' => $critere, 'search' => $searchQuery, 'page' => request()->get('page')]) }}" 
                   class="text-slate-400 hover:text-blue-600 rounded-full p-2 transition">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </a>
            </div>

            <div class="p-8 overflow-y-auto w-full flex-grow bg-slate-50">
                
                <!-- Résumé Top -->
                <div class="bg-white border text-center border-slate-200 p-6 rounded-xl shadow-sm mb-6 flex flex-col items-center">
                    <div class="text-xs uppercase font-bold text-slate-400 tracking-widest mb-2">Statut de la réservation</div>
                    @if(in_array($selectedReservation->statut, [\App\Enums\StatutReservation::REFUSEE, \App\Enums\StatutReservation::ANNULEE, \App\Enums\StatutReservation::EXPIREE]))
                        <div class="text-2xl font-black text-slate-700 bg-slate-100 px-6 py-2 rounded-lg inline-block shadow-inner">{{ Str::upper($selectedReservation->statut->value) }}</div>
                        
                        @if($selectedReservation->statut === \App\Enums\StatutReservation::ANNULEE)
                            <p class="mt-3 text-sm text-slate-600">Annulée par : <span class="font-bold">{{ optional($selectedReservation->acteur_annulation)->value ?? 'Inconnu' }}</span></p>
                        @endif
                        
                        @if($selectedReservation->statut === \App\Enums\StatutReservation::REFUSEE && $selectedReservation->motif_refus)
                            <p class="mt-3 text-sm text-slate-600 bg-slate-50 border border-slate-200 p-3 rounded text-left w-full max-w-md mx-auto">
                                <strong class="block mb-1">Motif du refus de l'hôte :</strong>
                                {{ $selectedReservation->motif_refus }}
                            </p>
                        @endif

                    @elseif(in_array($selectedReservation->statut, [\App\Enums\StatutReservation::CONFIRMEE, \App\Enums\StatutReservation::EN_COURS]))
                        <div class="text-2xl font-black text-blue-700 bg-blue-50 px-6 py-2 rounded-lg inline-block shadow-inner">{{ Str::upper($selectedReservation->statut->value) }}</div>
                    @else
                        <div class="text-xl font-bold text-blue-600">{{ $selectedReservation->statut->value }}</div>
                    @endif
                </div>

                <!-- Section Détails -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Détail Logement & Finance -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="bg-slate-100 px-4 py-2 border-b border-slate-200">
                            <h4 class="text-xs font-bold text-slate-600 uppercase">Détails du Séjour</h4>
                        </div>
                        <div class="p-4 flex flex-col gap-3 text-sm">
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-slate-500">Logement :</span>
                                <span class="font-bold text-slate-800 text-right">{{ optional($selectedReservation->annonce)->titre }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-slate-500">Mode :</span>
                                <span class="font-medium text-slate-700">{{ optional($selectedReservation->mode_reservation)->value }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-slate-500">Arrivée :</span>
                                <span class="font-bold text-blue-600">{{ \Carbon\Carbon::parse($selectedReservation->date_arrivee)->format('d F Y') }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-slate-500">Départ :</span>
                                <span class="font-bold text-blue-600">{{ \Carbon\Carbon::parse($selectedReservation->date_depart)->format('d F Y') }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-slate-500">Voyageurs :</span>
                                <span class="font-medium text-slate-700">{{ $selectedReservation->nb_voyageurs }} personnes</span>
                            </div>
                            <div class="flex justify-between pt-2">
                                <span class="text-slate-600 font-bold">Total Payé (ou à payer) :</span>
                                <span class="font-black text-blue-700 text-lg">{{ number_format($selectedReservation->montant_total, 2, ',', ' ') }} DH</span>
                            </div>
                        </div>
                    </div>

                    <!-- Acteurs de la réservation -->
                    <div class="flex flex-col gap-6">
                        
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 flex items-center gap-4 relative overflow-hidden">
                            <div class="absolute right-0 top-0 bottom-0 w-2 bg-slate-300"></div>
                            <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-500 border border-slate-200">
                                @if($selectedReservation->voyageur && $selectedReservation->voyageur->profil && $selectedReservation->voyageur->profil->photo_url)
                                    <img src="{{ asset($selectedReservation->voyageur->profil->photo_url) }}" class="h-full w-full object-cover rounded-full">
                                @else
                                    V
                                @endif
                            </div>
                            <div>
                                <div class="text-[10px] uppercase font-bold text-slate-400">Voyageur Locataire</div>
                                <div class="font-bold text-slate-800">{{ optional($selectedReservation->voyageur)->prenom }} {{ optional($selectedReservation->voyageur)->nom }}</div>
                                <div class="text-xs text-slate-500">{{ optional($selectedReservation->voyageur)->email }}</div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm border border-blue-200 p-4 flex items-center gap-4 relative overflow-hidden">
                            <div class="absolute right-0 top-0 bottom-0 w-2 bg-blue-500"></div>
                            <div class="h-12 w-12 rounded-full bg-blue-50 flex items-center justify-center font-bold text-blue-500 border border-blue-200">
                                @if($selectedReservation->hote && $selectedReservation->hote->profil && $selectedReservation->hote->profil->photo_url)
                                    <img src="{{ asset($selectedReservation->hote->profil->photo_url) }}" class="h-full w-full object-cover rounded-full">
                                @else
                                    H
                                @endif
                            </div>
                            <div>
                                <div class="text-[10px] uppercase font-bold text-blue-400">Hôte Propriétaire</div>
                                <div class="font-bold text-blue-900">{{ optional($selectedReservation->hote)->prenom }} {{ optional($selectedReservation->hote)->nom }}</div>
                                <div class="text-xs text-blue-600">{{ optional($selectedReservation->hote)->email }}</div>
                            </div>
                        </div>

                    </div>
                </div>
                
                @if($selectedReservation->message_optionnel)
                <div class="mt-6 bg-white p-5 rounded-xl border border-slate-200 shadow-sm relative">
                    <svg class="absolute top-4 left-4 w-6 h-6 text-slate-200" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.570 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" /></svg>
                    <h4 class="text-xs font-bold text-slate-400 ml-8 mb-2">Message d'accompagnement du voyageur :</h4>
                    <p class="text-slate-600 font-medium italic ml-8">"{{ $selectedReservation->message_optionnel }}"</p>
                </div>
                @endif
                
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
