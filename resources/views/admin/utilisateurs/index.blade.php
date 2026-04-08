@extends('admin.layouts.app')

@section('header_title', 'Gestion des Utilisateurs')

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
        <form method="GET" action="{{ route('admin.utilisateurs.index') }}" class="flex flex-col sm:flex-row gap-4 items-center">
            
            <div class="w-full sm:w-1/2 relative">
                <input type="text" name="search" value="{{ $searchQuery }}" placeholder="Rechercher par nom, prénom ou email..." 
                       class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pl-10">
                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            
            <div class="w-full sm:w-1/4">
                <select name="critere" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-slate-50">
                    <option value="tous" {{ $critere == 'tous' ? 'selected' : '' }}>Tous les utilisateurs</option>
                    <option value="hote" {{ $critere == 'hote' ? 'selected' : '' }}>Hôtes uniquement</option>
                    <option value="voyageur" {{ $critere == 'voyageur' ? 'selected' : '' }}>Voyageurs uniquement</option>
                    <option value="en_attente" {{ $critere == 'en_attente' ? 'selected' : '' }}>Dossiers en attente</option>
                </select>
            </div>
            
            <div class="w-full sm:w-1/4">
                <button type="submit" class="w-full bg-blue-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-blue-700 transition shadow-sm">
                    Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Tableau des utilisateurs -->
    <div class="bg-white overflow-hidden shadow-sm ring-1 ring-slate-200 rounded-xl">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="py-4 pl-6 pr-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Identité</th>
                    <th scope="col" class="px-3 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Contact</th>
                    <th scope="col" class="px-3 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Rôle</th>
                    <th scope="col" class="px-3 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Statut Hôte</th>
                    <th scope="col" class="relative py-4 pl-3 pr-6 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($utilisateurs as $u)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="whitespace-nowrap py-4 pl-6 pr-3">
                        <div class="flex items-center">
                            <div class="h-10 w-10 flex-shrink-0 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold uppercase overflow-hidden">
                                @if($u->profil && $u->profil->photo_url)
                                    <img src="{{ asset('storage/' . $u->profil->photo_url) }}" class="h-full w-full object-cover">
                                @else
                                    {{ substr($u->prenom, 0, 1) }}{{ substr($u->nom, 0, 1) }}
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="font-medium text-slate-900">{{ $u->prenom }} {{ $u->nom }}</div>
                                <div class="text-xs text-slate-500">Inscrit le {{ \Carbon\Carbon::parse($u->date_creation)->format('d/m/Y') }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-600">
                        <div>{{ $u->email }}</div>
                        <div class="text-xs text-slate-400">{{ $u->telephone ?? 'Non renseigné' }}</div>
                    </td>
                    <td class="whitespace-nowrap px-3 py-4">
                        @if($u->est_hote)
                            <span class="inline-flex items-center rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-semibold text-purple-800">Hôte</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-800">Voyageur</span>
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                        @if($u->est_hote)
                            @php $statutVerif = $u->getStatutVerification(); @endphp
                            
                            @if($statutVerif === \App\Enums\VerificationStatut::EN_COURS)
                                <span class="inline-flex items-center gap-1.5 text-blue-600 font-semibold bg-blue-50 px-2 py-1 rounded-md border border-blue-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> En attente
                                </span>
                            @elseif($statutVerif === \App\Enums\VerificationStatut::VALIDE)
                                <span class="inline-flex items-center gap-1.5 text-blue-800 font-semibold bg-blue-100 px-2 py-1 rounded-md border border-blue-300">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-700"></span> Validé
                                </span>
                            @elseif($statutVerif === \App\Enums\VerificationStatut::REJETE)
                                <span class="inline-flex items-center gap-1.5 text-slate-600 font-semibold bg-slate-100 px-2 py-1 rounded-md border border-slate-300">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span> Rejeté
                                </span>
                            @else
                                <span class="text-slate-400 italic">Non soumis</span>
                            @endif
                        @else
                            <span class="text-slate-300">-</span>
                        @endif
                    </td>
                    <td class="relative whitespace-nowrap py-4 pl-3 pr-6 text-right text-sm font-medium flex gap-3 justify-end items-center">
                        <a href="{{ route('admin.utilisateurs.index', ['selected' => $u->id_utilisateur, 'critere' => $critere, 'search' => $searchQuery, 'page' => request()->get('page')]) }}" 
                           class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-md transition font-semibold w-[100px] text-center">
                           Consulter
                        </a>
                        
                        <form action="{{ route('admin.utilisateurs.destroy', $u->id_utilisateur) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement cet utilisateur ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-1.5 rounded-md transition" title="Supprimer">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                        Aucun utilisateur trouvé pour ces critères.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
             {{ $utilisateurs->appends(['critere' => $critere, 'search' => $searchQuery])->links() }}
        </div>
    </div>


    {{-- ========================================== --}}
    {{-- CARTE FLOTTANTE (MODALE 100% HTML/CSS/PHP) --}}
    {{-- ========================================== --}}
    @if(isset($selectedUser))
    <div class="fixed inset-0 z-[100] flex items-center justify-center px-4">
        
        <!-- Overlay flouté pour fermer -->
        <a href="{{ route('admin.utilisateurs.index', ['critere' => $critere, 'search' => $searchQuery, 'page' => request()->get('page')]) }}" 
           class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-default"></a>

        <!-- Contenu de la modale -->
        <div class="relative bg-white w-full max-w-3xl rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
            
            <!-- Entête Simple -->
            <div class="h-16 bg-white relative border-b border-slate-100 flex justify-end items-center px-4">
                <a href="{{ route('admin.utilisateurs.index', ['critere' => $critere, 'search' => $searchQuery, 'page' => request()->get('page')]) }}" 
                   class="text-slate-400 hover:text-blue-600 rounded-full p-2 transition">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </a>
            </div>

            <div class="px-8 pb-8 pt-6 overflow-y-auto w-full">
                
                <!-- Avatar et Rôle -->
                <div class="flex justify-between items-center mb-8">
                    <div class="flex items-center gap-5">
                        <div class="h-20 w-20 rounded-full border-2 border-slate-100 bg-slate-50 overflow-hidden flex items-center justify-center flex-shrink-0 text-3xl font-bold text-slate-400">
                            @if($selectedUser->profil && $selectedUser->profil->photo_url)
                                <img src="{{ asset('storage/' . $selectedUser->profil->photo_url) }}" class="h-full w-full object-cover">
                            @else
                                {{ substr($selectedUser->prenom, 0, 1) }}{{ substr($selectedUser->nom, 0, 1) }}
                            @endif
                        </div>
                        <div>
                            <h2 class="text-3xl font-extrabold text-slate-900">{{ $selectedUser->prenom }} {{ $selectedUser->nom }}</h2>
                            <p class="text-slate-500 font-medium mt-1">Inscrit depuis le {{ \Carbon\Carbon::parse($selectedUser->date_creation)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    
                    <div>
                        @if($selectedUser->est_hote)
                            <span class="rounded-full bg-purple-100 px-4 py-1.5 text-sm font-bold text-purple-800">Compte Hôte</span>
                        @else
                            <span class="rounded-full bg-blue-100 px-4 py-1.5 text-sm font-bold text-blue-800">Compte Voyageur</span>
                        @endif
                    </div>
                </div>

                <!-- Formulaire de modification rapide -->
                <div class="bg-white rounded-xl p-0 mb-8 border-t border-slate-100 pt-6">
                    <h3 class="text-sm font-bold text-slate-800 mb-4">Informations de contact</h3>
                    <form method="POST" action="{{ route('admin.utilisateurs.update', $selectedUser->id_utilisateur) }}">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Prénom</label>
                                <input type="text" name="prenom" value="{{ $selectedUser->prenom }}" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-slate-50">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Nom</label>
                                <input type="text" name="nom" value="{{ $selectedUser->nom }}" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-slate-50">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Email</label>
                                <input type="email" name="email" value="{{ $selectedUser->email }}" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-slate-50">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">Sauvegarder</button>
                        </div>
                    </form>
                </div>

                <!-- Section Dossier Identité HOTE -->
                @if($selectedUser->est_hote)
                    @php $dossier = \App\Models\Utilisateurs\VerificationIdentite::visualiserDossier($selectedUser->id_utilisateur); @endphp
                    
                    <div class="mt-8 border-t border-slate-100 pt-6">
                        <h3 class="text-lg font-bold text-slate-800 mb-4">Dossier d'Identité</h3>
                        
                        @if($dossier)
                            <!-- Statut actuel du dossier -->
                            <div class="flex items-center gap-3 mb-6 p-4 rounded-lg bg-slate-50 border border-slate-200">
                                <span class="font-bold text-slate-600">Statut:</span>
                                @if($dossier->statut === \App\Enums\VerificationStatut::EN_COURS)
                                    <span class="text-blue-600 font-bold bg-blue-100 px-3 py-1 rounded-md border border-blue-200">En attente</span>
                                @elseif($dossier->statut === \App\Enums\VerificationStatut::VALIDE)
                                    <span class="text-blue-800 font-bold bg-blue-100 px-3 py-1 rounded-md border border-blue-300">Validé</span>
                                @elseif($dossier->statut === \App\Enums\VerificationStatut::REJETE)
                                    <span class="text-slate-600 font-bold bg-slate-200 px-3 py-1 rounded-md border border-slate-300">Rejeté</span>
                                @endif
                                <span class="text-sm text-slate-400 ml-auto">{{ \Carbon\Carbon::parse($dossier->date_soumission)->format('d/m/Y') }}</span>
                            </div>

                            @if($dossier->statut === \App\Enums\VerificationStatut::REJETE)
                                <div class="mb-6 bg-slate-50 border border-slate-300 text-slate-700 p-4 rounded-lg text-sm">
                                    <strong class="block mb-1 text-slate-800">Motif de rejet retourné à l'hôte :</strong>
                                    {{ $dossier->motif_rejet }}
                                </div>
                            @endif

                            <!-- Viewer de document -->
                            <div class="bg-slate-100 rounded-lg overflow-hidden flex justify-center items-center mb-6 border border-slate-200 min-h-[300px] p-2">
                                <img src="{{ asset('storage/' . $dossier->chemin_document) }}" alt="Pièce d'identité" class="max-w-full max-h-[400px] object-contain rounded">
                            </div>

                            <!-- Actions Admin (uniquement si En Cours) -->
                            @if($dossier->statut === \App\Enums\VerificationStatut::EN_COURS)
                                <div class="bg-white border border-slate-200 p-5 rounded-xl flex flex-col gap-4">
                                    <h4 class="text-sm font-bold text-slate-800">Décision Administrateur</h4>
                                    
                                    <form method="POST" action="{{ route('admin.utilisateurs.verify', $dossier->id_verification) }}">
                                        @csrf
                                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-8 rounded-lg transition-colors text-base">
                                            Valider
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.utilisateurs.reject', $dossier->id_verification) }}" class="flex gap-2">
                                        @csrf
                                        <input type="text" name="motif" placeholder="Précisez le motif du rejet..." required 
                                               class="flex-1 border-slate-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                                        <button type="submit" class="bg-white border-2 border-blue-600 text-blue-600 hover:bg-blue-50 font-bold py-2 px-6 rounded-lg transition-colors flex-shrink-0">
                                            Rejeter
                                        </button>
                                    </form>
                                    
                                </div>
                            @endif
                        @else
                            <div class="bg-slate-50 p-6 rounded-lg text-center text-slate-500 border border-slate-200">
                                Cet hôte n'a pas encore soumis de documents d'identité.
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
