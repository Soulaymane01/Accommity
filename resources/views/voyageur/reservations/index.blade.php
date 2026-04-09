<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mes Voyages | Accommity</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-slate-900 antialiased min-h-screen flex flex-col">
    <x-header />

    <!-- ALERTE: En cours de traitement -->
    <x-dialogs />

    <main class="flex-grow max-w-5xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="text-3xl font-bold text-slate-900 mb-8">Mes Voyages</h1>

        @if($reservations->isEmpty())
            <div class="bg-white border text-center border-slate-200 rounded-3xl p-12">
                <h3 class="text-xl font-bold text-slate-900 mb-2">Aucun voyage à venir</h3>
                <p class="text-slate-500 mb-6">Il est temps de dépoussiérer vos valises et de planifier votre prochaine aventure.</p>
                <a href="{{ route('home') }}" class="inline-flex rounded-xl bg-slate-900 px-6 py-3 font-semibold text-white hover:bg-slate-800">
                    Explorer
                </a>
            </div>
        @else
            <div class="space-y-6">
                @foreach($reservations as $reservation)
                    <div class="bg-white border border-slate-200 rounded-3xl p-6 flex flex-col md:flex-row gap-6 hover:shadow-md transition-shadow">
                        <!-- Image -->
                        <div class="w-full md:w-48 h-32 rounded-2xl bg-slate-200 overflow-hidden flex-shrink-0">
                            <img src="{{ $reservation->annonce->photo_url ?: '/images/annonces/1.jpg' }}" class="w-full h-full object-cover">
                        </div>
                        
                        <!-- Info -->
                        <div class="flex-grow">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900">{{ $reservation->annonce->ville ?? $reservation->annonce->adresse }}</h3>
                                    <p class="text-slate-500 font-medium text-sm mt-1">Hébergé par {{ $reservation->annonce->hote->nom }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold 
                                        @if($reservation->statut->value == 'Confirmée') bg-emerald-100 text-emerald-800
                                        @elseif($reservation->statut->value == 'Terminée') bg-blue-100 text-blue-800
                                        @elseif($reservation->statut->value == 'En attente') bg-amber-100 text-amber-800
                                        @elseif($reservation->statut->value == 'Refusée' || $reservation->statut->value == 'Annulée' || $reservation->statut->value == 'Expirée') bg-rose-100 text-rose-800
                                        @else bg-slate-100 text-slate-800 @endif">
                                        {{ $reservation->statut->value }}
                                    </span>
                                    @if($reservation->statut->value == 'Refusée' && $reservation->motif_refus)
                                        <p class="mt-2 text-[11px] font-semibold text-rose-500 bg-rose-50 px-2 py-1 rounded-md">Motif: {{ $reservation->motif_refus }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-4 grid grid-cols-2 gap-4">
                                <div class="border-l-2 border-slate-200 pl-3">
                                    <p class="text-xs text-slate-500 uppercase font-bold tracking-wider mb-1">Dates</p>
                                    <p class="text-sm font-semibold text-slate-900">
                                        {{ \Carbon\Carbon::parse($reservation->date_arrivee)->format('d M.') }} - 
                                        {{ \Carbon\Carbon::parse($reservation->date_depart)->format('d M. Y') }}
                                    </p>
                                </div>
                                <div class="border-l-2 border-slate-200 pl-3">
                                    <p class="text-xs text-slate-500 uppercase font-bold tracking-wider mb-1">Montant</p>
                                    <p class="text-sm font-semibold text-slate-900">{{ number_format($reservation->montant_total, 2, ',', ' ') }} MAD</p>
                                </div>
                            </div>

                            <div class="mt-6 flex gap-2 border-t border-slate-100 pt-4">
                                @if(in_array($reservation->statut->value, ['En attente', 'Confirmée']))
                                    <a href="{{ route('reservations.cancel.preview', $reservation->id_reservation) }}" class="flex-1 text-center py-2.5 rounded-xl border border-slate-200 text-sm font-bold text-slate-600 hover:bg-slate-50 transition-colors">
                                        Annuler
                                    </a>
                                @elseif($reservation->statut->value === 'Terminée')
                                    <a href="{{ route('voyageur.reservations.index', ['action' => 'evaluate', 'id' => $reservation->id_reservation]) }}" class="flex-1 text-center py-2.5 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 transition-colors shadow-sm">
                                        Laisser un avis
                                    </a>
                                @endif
                                <a href="{{ route('annonces.show', $reservation->id_annonce) }}" class="flex-1 text-center py-2.5 rounded-xl bg-slate-900 text-sm font-bold text-white hover:bg-slate-800 transition-colors">
                                    Voir l'annonce
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>

    {{-- MODAL DE CRÉATION D'ÉVALUATION --}}
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
        <a href="{{ route('voyageur.reservations.index') }}" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></a>
        <div class="relative bg-white w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-full border-t-8 border-blue-500">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50 relative">
                <h3 class="font-black text-xl text-slate-800">Évaluer votre séjour</h3>
                <a href="{{ route('voyageur.reservations.index') }}" class="text-slate-400 hover:text-slate-600"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></a>
            </div>
            
            <form action="{{ route('voyageur.evaluations.store') }}" method="POST" class="p-8 overflow-y-auto">
                @csrf
                <input type="hidden" name="id_reservation" value="{{ $evalResId }}">
                
                <div class="mb-6 p-4 bg-blue-50 rounded-xl border border-blue-100 flex items-center justify-between">
                    <div>
                        <h4 class="font-bold text-blue-900">{{ $resForEval->annonce->titre }}</h4>
                        <p class="text-xs text-blue-700 mt-1">Hébergé par {{ $resForEval->annonce->hote->prenom }}</p>
                    </div>
                </div>

                <!-- Notes détaillées -->
                <div class="grid grid-cols-2 gap-4 mb-8">
                    @php 
                    $criteres = ['proprete' => 'Propreté', 'communication' => 'Communication', 'emplacement' => 'Emplacement', 'rapport_qualite_prix' => 'Qualité/Prix', 'exactitude' => 'Exactitude'];
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
                    <textarea name="commentaire" required rows="4" placeholder="Décrivez votre expérience avec le logement et l'hôte..." class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-blue-500 focus:border-blue-500 resize-none"></textarea>
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
