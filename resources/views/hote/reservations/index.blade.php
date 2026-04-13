<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Demandes de Réservations | Espace Hôte</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-slate-900 antialiased min-h-screen flex flex-col">
    <!-- Hote Navigation Bar Container -->
    <x-header />

    <!-- ALERTE: En cours de traitement -->
    <x-dialogs />

    <main class="flex-grow max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 mb-2">Demandes de Réservations</h1>
                <p class="text-slate-500">Gérez les réservations et demandes des voyageurs pour vos annonces.</p>
            </div>
        </div>

        @if($errors->any())
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-xl shadow-sm">
                <ul class="list-disc list-inside text-sm font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($reservations->isEmpty())
            <div class="bg-white border text-center border-slate-200 rounded-3xl p-12 mt-8">
                <div class="mx-auto h-16 w-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Aucune demande pour le moment</h3>
                <p class="text-slate-500 mt-1">Vous n'avez pas de demandes en attente ou récemment confirmées.</p>
            </div>
        @else
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                                <th class="p-4 pl-6">Voyageur</th>
                                <th class="p-4">Annonce</th>
                                <th class="p-4">Dates</th>
                                <th class="p-4">Montant</th>
                                <th class="p-4">Statut</th>
                                <th class="p-4 text-right pr-6">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 align-middle">
                            @foreach($reservations as $reservation)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="p-4 pl-6">
                                        <div class="flex items-center gap-3">
                                            <div class="h-9 w-9 rounded-full bg-slate-200 flex-shrink-0 flex items-center justify-center text-slate-500 font-bold text-sm">
                                                {{ substr($reservation->voyageur->nom, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-slate-900">{{ $reservation->voyageur->nom }}</p>
                                                <p class="text-xs text-slate-500">{{ $reservation->nb_voyageurs }} pers.</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <p class="text-sm font-semibold text-slate-900 max-w-[200px] truncate">{{ $reservation->annonce->titre }}</p>
                                    </td>
                                    <td class="p-4">
                                        <p class="text-sm text-slate-900 font-medium">
                                            {{ \Carbon\Carbon::parse($reservation->date_arrivee)->format('d M') }} - {{ \Carbon\Carbon::parse($reservation->date_depart)->format('d M') }}
                                        </p>
                                    </td>
                                    <td class="p-4 font-semibold text-slate-900 text-sm">
                                        {{ number_format($reservation->montant_total, 2, ',', ' ') }} DH
                                    </td>
                                    <td class="p-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider
                                            @if($reservation->statut->value == 'En attente') bg-amber-100 text-amber-800
                                            @elseif($reservation->statut->value == 'Confirmée') bg-emerald-100 text-emerald-800
                                            @elseif($reservation->statut->value == 'Refusée' || $reservation->statut->value == 'Annulée' || $reservation->statut->value == 'Expirée') bg-rose-100 text-rose-800
                                            @else bg-slate-100 text-slate-800 @endif">
                                            {{ $reservation->statut->value }}
                                        </span>
                                        @if($reservation->statut->value == 'Refusée' && $reservation->motif_refus)
                                            <p class="mt-2 text-[10px] font-semibold text-rose-500 bg-rose-50 px-2 py-1 rounded-md max-w-[120px] truncate" title="{{ $reservation->motif_refus }}">Motif: {{ $reservation->motif_refus }}</p>
                                        @endif
                                    </td>
                                    <td class="p-4 pr-6 text-right">
                                        @if($reservation->statut->value == 'En attente')
                                            <div class="flex items-center justify-end gap-2">
                                                <form action="{{ route('hote.reservations.refuse', $reservation->id_reservation) }}" method="POST" onsubmit="const reason = prompt('Pourquoi refusez-vous cette demande ?', 'Dates non disponibles'); if(!reason) return false; this.motif.value = reason;">
                                                    @csrf
                                                    <input type="hidden" name="motif" value="Non disponible">
                                                    <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Refuser">
                                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </button>
                                                </form>
                                                <form action="{{ route('hote.reservations.accept', $reservation->id_reservation) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="bg-slate-900 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-slate-800 transition-colors shadow-sm">
                                                        Accepter
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif($reservation->statut->value == 'Confirmée')
                                            <form action="{{ route('reservations.cancel', $reservation->id_reservation) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-rose-600 hover:text-rose-700 text-sm font-semibold border border-rose-200 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg transition-colors">
                                                    Annuler
                                                </button>
                                            </form>
                                        @elseif($reservation->statut->value == 'Terminée')
                                            <a href="{{ route('hote.reservations.demandes', ['action' => 'evaluate', 'id' => $reservation->id_reservation]) }}" class="text-blue-600 hover:text-blue-700 text-sm font-semibold border border-blue-200 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors inline-block whitespace-nowrap">
                                                Évaluer locataire
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </main>

    {{-- MODAL DE CRÉATION D'ÉVALUATION PAR L'HÔTE --}}
    @php
        $action = request('action');
        $evalResId = request('id');
        $resForEval = null;
        if($action === 'evaluate' && $evalResId) {
            $resForEval = $reservations->firstWhere('id_reservation', $evalResId);
        }
    @endphp

    @if($action === 'evaluate' && $resForEval)
    <div class="fixed inset-0 z-50 flex items-center justify-center px-4 py-8">
        <a href="{{ route('hote.reservations.demandes') }}" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></a>
        <div class="relative bg-white w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-full border-t-8 border-blue-500">
            <div class="p-6 border-b border-blue-100 flex justify-between items-center bg-blue-50 relative">
                <h3 class="font-black text-xl text-slate-800">Évaluer le locataire</h3>
                <a href="{{ route('hote.reservations.demandes') }}" class="text-slate-400 hover:text-slate-600"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></a>
            </div>
            
            <form action="{{ route('hote.evaluations.store') }}" method="POST" class="p-8 overflow-y-auto">
                @csrf
                <input type="hidden" name="id_reservation" value="{{ $evalResId }}">
                
                <div class="mb-6 p-4 bg-blue-50 rounded-xl border border-blue-100 flex items-center justify-between">
                    <div>
                        <h4 class="font-bold text-blue-900">{{ optional($resForEval->voyageur)->prenom }} {{ optional($resForEval->voyageur)->nom }}</h4>
                        <p class="text-xs text-blue-700 mt-1">Séjour du {{ \Carbon\Carbon::parse($resForEval->date_arrivee)->format('d M') }} au {{ \Carbon\Carbon::parse($resForEval->date_depart)->format('d M Y') }}</p>
                    </div>
                </div>

                <!-- Notes détaillées restreintes -->
                <div class="grid grid-cols-2 gap-4 mb-8">
                    @php 
                    $criteres = ['proprete' => 'Respect des lieux (Propreté)', 'communication' => 'Communication'];
                    @endphp

                    @foreach($criteres as $key => $label)
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">{{ $label }}</label>
                        <select name="{{ $key }}" class="w-full border border-slate-300 rounded-xl px-4 py-2 text-slate-800 focus:ring-blue-500 focus:border-blue-500 font-medium bg-white">
                            <option value="5">5 Étoiles (Parfait)</option>
                            <option value="4">4 Étoiles (Très bien)</option>
                            <option value="3">3 Étoiles (Correct)</option>
                            <option value="2">2 Étoiles (Médiocre)</option>
                            <option value="1">1 Étoile (Mauvais)</option>
                        </select>
                    </div>
                    @endforeach
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Commentaire (public)</label>
                    <textarea name="commentaire" required rows="4" placeholder="Recommanderiez-vous ce voyageur à d'autres hôtes ?" class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-blue-500 focus:border-blue-500 resize-none"></textarea>
                </div>
                
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-xl text-lg shadow-md transition-colors">
                    Publier l'évaluation
                </button>
            </form>
        </div>
    </div>
    @endif


    <x-footer />
</body>
</html>
