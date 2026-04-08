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

    <x-footer />
</body>
</html>
