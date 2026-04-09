@extends('admin.layouts.app')

@section('header_title', 'Modération des Avis Signalés')

@section('content')

@if(session('success_dialog'))
<div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm relative">
    <p class="font-bold">Succès</p>
    <p>{{ session('success_dialog') }}</p>
</div>
@endif

<div class="relative">
    <!-- Barre de Recherche -->
    <div class="mb-6 bg-white p-4 rounded-xl shadow-sm border border-slate-100">
        <form method="GET" action="{{ route('admin.avis_signales.index') }}" class="flex flex-col sm:flex-row gap-4 items-center">
            
            <div class="w-full sm:w-3/4 relative">
                <input type="text" name="search" value="{{ $searchQuery }}" placeholder="Rechercher par commentaire, motif, nom de l'auteur..." 
                       class="w-full rounded-lg border-slate-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 pl-10">
                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            
            <div class="w-full sm:w-1/4">
                <button type="submit" class="w-full bg-slate-800 text-white font-bold px-4 py-2 rounded-lg hover:bg-slate-900 transition shadow-sm">
                    Rechercher
                </button>
            </div>
        </form>
    </div>

    <!-- Tableau des Avis Signalés -->
    <div class="bg-white overflow-hidden shadow-sm ring-1 ring-slate-200 rounded-xl">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="py-4 pl-6 pr-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Commentaire (Extrait)</th>
                    <th scope="col" class="px-3 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Auteur</th>
                    <th scope="col" class="px-3 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Motif Signalement</th>
                    <th scope="col" class="px-3 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Date création</th>
                    <th scope="col" class="relative py-4 pl-3 pr-6 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($avis as $evaluation)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="whitespace-nowrap py-4 pl-6 pr-3">
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 flex-shrink-0 bg-yellow-50 rounded-lg overflow-hidden flex items-center justify-center border border-yellow-200">
                                <span class="text-sm font-black text-yellow-700">{{ $evaluation->note }}</span>
                            </div>
                            <div>
                                <div class="font-semibold text-slate-800 truncate max-w-[250px]" title="{{ $evaluation->commentaire }}">"{{ Str::limit($evaluation->commentaire, 35) }}"</div>
                                <div class="text-[10px] text-slate-400 mt-1 uppercase tracking-widest">Réf: {{ substr($evaluation->id_evaluation, 0, 8) }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-600">
                        @if($evaluation->auteur)
                            <div class="font-medium text-slate-800">{{ $evaluation->auteur->prenom }} {{ $evaluation->auteur->nom }}</div>
                            <div class="text-[10px] text-slate-500 mt-1">{{ Str::limit($evaluation->auteur->email, 20) }}</div>
                        @else
                            <div class="text-xs italic text-red-500">Inconnu</div>
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-600">
                        <div class="bg-red-50 text-red-700 px-2 py-1 rounded text-xs border border-red-100 truncate max-w-[150px]" title="{{ $evaluation->motif_signalement }}">
                            {{ Str::limit($evaluation->motif_signalement ?? 'Aucun motif', 20) }}
                        </div>
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-600">
                        <div class="text-slate-900 font-medium">{{ optional($evaluation->date_creation)->format('d/m/Y') }}</div>
                    </td>
                    <td class="relative whitespace-nowrap py-4 pl-3 pr-6 text-right text-sm font-medium flex justify-end">
                        <a href="{{ route('admin.avis_signales.index', ['selected' => $evaluation->id_evaluation, 'search' => $searchQuery, 'page' => request()->get('page')]) }}" 
                           class="text-slate-700 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 border border-slate-200 px-3 py-1.5 rounded-md transition font-semibold w-[110px] text-center shadow-sm">
                           Investiguer
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                        Aucun avis signalé pour le moment.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
             {{ $avis->appends(['search' => $searchQuery])->links() }}
        </div>
    </div>


    {{-- ========================================== --}}
    {{-- CARTE FLOTTANTE INTERVENTION (MODALE)      --}}
    {{-- ========================================== --}}
    @if(isset($selectedAvis))
    <div class="fixed inset-0 z-[100] flex items-center justify-center px-4 py-8">
        
        <!-- Overlay flouté pour fermer -->
        <a href="{{ route('admin.avis_signales.index', ['search' => $searchQuery, 'page' => request()->get('page')]) }}" 
           class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-default"></a>

        <!-- Contenu de la modale -->
        <div class="relative bg-white w-full max-w-4xl rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-full">
            
            <!-- Entête Simple -->
            <div class="h-16 bg-white relative border-b border-slate-100 flex justify-between items-center px-4 flex-shrink-0">
                <span class="font-bold text-slate-800 px-4">Investigation de l'Évaluation <span class="text-slate-400 font-normal">#{{ substr($selectedAvis->id_evaluation, 0, 8) }}</span></span>
                <a href="{{ route('admin.avis_signales.index', ['search' => $searchQuery, 'page' => request()->get('page')]) }}" 
                   class="text-slate-400 hover:text-red-500 rounded-full p-2 transition">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </a>
            </div>

            <div class="p-8 overflow-y-auto w-full flex-grow bg-slate-50">
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    
                    <!-- Colonne 1: Contenu du l'avis et les Acteurs -->
                    <div>
                        <!-- Contenu du signalement -->
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
                            <div class="bg-yellow-50 border-b border-yellow-100 px-5 py-3 flex justify-between items-center">
                                <h3 class="font-bold text-yellow-800 text-sm">Contenu Explicite de l'Avis</h3>
                                <div class="font-black text-2xl text-yellow-600">{{ $selectedAvis->note }} <span class="text-sm">/ 5</span></div>
                            </div>
                            <div class="p-5">
                                <p class="text-slate-700 italic text-sm leading-relaxed whitespace-pre-wrap">"{{ $selectedAvis->commentaire }}"</p>
                            </div>
                            <div class="bg-red-50 p-4 border-t border-red-100">
                                <div class="text-xs text-red-500 font-bold uppercase mb-1">Motif de Signalement :</div>
                                <div class="text-sm font-medium text-red-800">{{ $selectedAvis->motif_signalement ?: 'Non précisé par l\'utilisateur.' }}</div>
                            </div>
                        </div>

                        <!-- Les Utilisateurs -->
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                            <div class="bg-slate-100 border-b border-slate-200 px-5 py-3">
                                <h3 class="font-bold text-slate-700 text-sm">Acteurs concernés</h3>
                            </div>
                            
                            <div class="p-5 flex flex-col gap-4">
                                <div class="flex items-center gap-3 bg-red-50 p-3 rounded-lg border border-red-100 relative">
                                    <div class="absolute -top-2 -right-2 bg-red-500 text-white text-[9px] font-bold px-2 py-0.5 rounded shadow">Auteur (Sanctionnable)</div>
                                    <div class="h-10 w-10 bg-white rounded-full flex items-center justify-center text-red-400 font-bold border border-red-200">
                                        @if($selectedAvis->auteur && $selectedAvis->auteur->profil && $selectedAvis->auteur->profil->photo_url)
                                            <img src="{{ asset($selectedAvis->auteur->profil->photo_url) }}" class="h-full w-full object-cover rounded-full">
                                        @else
                                            A
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-800">{{ optional($selectedAvis->auteur)->prenom }} {{ optional($selectedAvis->auteur)->nom }}</div>
                                        <div class="text-xs text-slate-500">{{ optional($selectedAvis->auteur)->email }}</div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 bg-blue-50 p-3 rounded-lg border border-blue-100 relative mt-2">
                                    <div class="absolute -top-2 -right-2 bg-blue-500 text-white text-[9px] font-bold px-2 py-0.5 rounded shadow">Cible (Protégée)</div>
                                    <div class="h-10 w-10 bg-white rounded-full flex items-center justify-center text-blue-400 font-bold border border-blue-200">
                                        @if($selectedAvis->cible && $selectedAvis->cible->profil && $selectedAvis->cible->profil->photo_url)
                                            <img src="{{ asset($selectedAvis->cible->profil->photo_url) }}" class="h-full w-full object-cover rounded-full">
                                        @else
                                            C
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-800">{{ optional($selectedAvis->cible)->prenom }} {{ optional($selectedAvis->cible)->nom }}</div>
                                        <div class="text-xs text-slate-500">{{ optional($selectedAvis->cible)->email }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Colonne 2: Panneau de Modération Admin -->
                    <div class="flex flex-col">
                        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden flex-grow flex flex-col">
                            <div class="bg-slate-800 px-6 py-4">
                                <h3 class="text-white font-bold text-lg">Décision de Modération</h3>
                            </div>
                            
                            <div class="p-6 flex-grow flex flex-col grid gap-6">
                                
                                <div class="bg-slate-50 p-4 border border-slate-200 rounded-lg text-sm text-slate-600">
                                    Veuillez analyser le motif de signalement face au contenu de l'avis. Si ce contenu est abusif, diffamatoire ou faux, <strong class="text-red-600">supprimez-le</strong>. S'il respecte les CGU, <strong class="text-slate-800">conservez-le</strong>.
                                </div>

                                <!-- Action Conserver -->
                                <form method="POST" action="{{ route('admin.avis_signales.keep', $selectedAvis->id_evaluation) }}">
                                    @csrf
                                    <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-3.5 rounded-lg transition-colors text-base shadow-sm">
                                        Conserver l'avis
                                    </button>
                                    <p class="text-xs text-slate-500 mt-2 text-center">L'avis restera affiché publiquement. Le drapeau de signalement sera retiré.</p>
                                </form>
                                
                                <div class="relative flex items-center justify-center my-2">
                                    <div class="border-t border-slate-200 w-full absolute"></div>
                                    <div class="bg-white px-3 relative text-xs text-slate-400 font-bold uppercase tracking-widest">OU</div>
                                </div>

                                <!-- Action Supprimer -->
                                <form method="POST" action="{{ route('admin.avis_signales.delete', $selectedAvis->id_evaluation) }}">
                                    @csrf
                                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3.5 rounded-lg transition-colors text-base shadow-sm"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet avis DÉFINITIVEMENT ?');">
                                        Supprimer l'avis
                                    </button>
                                    <p class="text-xs text-red-500 mt-2 text-center">Cette action est irréversible. L'auteur et la cible seront notifiés de votre décision.</p>
                                </form>
                                
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
