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

    <x-footer />
</body>
</html>
