<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Espace Hôte - Accommity</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen flex flex-col">
    
    <x-header />
    <x-dialogs />

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 w-full">

        {{-- Header --}}
        <div class="mb-10">
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Bonjour, {{ Auth::user()->prenom }} 👋</h1>
            <p class="text-slate-500 mt-2 text-lg">Bienvenue sur votre espace Hôte.</p>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col gap-2">
                <span class="text-slate-400 font-semibold text-xs uppercase tracking-widest">Annonces Actives</span>
                <span class="text-4xl font-extrabold text-blue-900">{{ $nbAnnonces }}</span>
                <a href="{{ route('hote.annonces.index') }}" class="text-sm text-blue-600 font-semibold hover:underline mt-1">Gérer mes annonces →</a>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col gap-2">
                <span class="text-slate-400 font-semibold text-xs uppercase tracking-widest">Réservations Totales</span>
                <span class="text-4xl font-extrabold text-indigo-700">{{ $nbReservations }}</span>
                <a href="{{ route('hote.reservations.demandes') }}" class="text-sm text-indigo-600 font-semibold hover:underline mt-1">Voir les demandes →</a>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col gap-2">
                <span class="text-slate-400 font-semibold text-xs uppercase tracking-widest">Total Revenus Versés</span>
                <span class="text-4xl font-extrabold text-emerald-600">{{ number_format($totalRevenus, 2) }} DH</span>
                <span class="text-xs text-slate-400">Versements traités uniquement</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

            {{-- Dernières Réservations --}}
            <section>
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-slate-900">Dernières Réservations</h2>
                    <a href="{{ route('hote.reservations.demandes') }}" class="text-sm font-semibold text-blue-600 hover:underline">Voir tout</a>
                </div>

                @if($dernieresReservations->isEmpty())
                    <div class="bg-white border border-slate-200 rounded-2xl p-8 text-center text-slate-500">
                        Aucune réservation pour le moment.
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($dernieresReservations as $res)
                        <div class="bg-white border border-slate-200 rounded-2xl p-4 flex items-center justify-between hover:shadow-md transition-shadow">
                            <div>
                                <p class="font-semibold text-slate-900 text-sm">{{ $res->annonce?->titre ?? 'Annonce supprimée' }}</p>
                                <p class="text-xs text-slate-500">
                                    {{ $res->voyageur?->prenom }} {{ $res->voyageur?->nom }} —
                                    {{ \Carbon\Carbon::parse($res->date_arrivee)->format('d M') }} →
                                    {{ \Carbon\Carbon::parse($res->date_depart)->format('d M Y') }}
                                </p>
                            </div>
                            <span @class([
                                'px-2.5 py-1 rounded-full text-xs font-bold uppercase',
                                'bg-amber-100 text-amber-700' => $res->statut->value === 'En attente',
                                'bg-emerald-100 text-emerald-700' => $res->statut->value === 'Confirmée',
                                'bg-red-100 text-red-700' => $res->statut->value === 'Annulée',
                                'bg-slate-100 text-slate-700' => !in_array($res->statut->value, ['En attente','Confirmée','Annulée']),
                            ])>
                                {{ $res->statut->value }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                @endif
            </section>

            {{-- Versements --}}
            <section>
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-slate-900">Mes Versements</h2>
                </div>

                @if($versements->isEmpty())
                    <div class="bg-white border border-slate-200 rounded-2xl p-8 text-center text-slate-500">
                        Aucun versement enregistré pour le moment. Les versements apparaîtront ici après la fin de chaque séjour.
                    </div>
                @else
                    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 text-slate-400 text-xs uppercase tracking-wider">
                                <tr>
                                    <th class="px-4 py-3 text-left">Annonce</th>
                                    <th class="px-4 py-3 text-right">Montant</th>
                                    <th class="px-4 py-3 text-center">Statut</th>
                                    <th class="px-4 py-3 text-right">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($versements as $versement)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-3 font-medium text-slate-800">
                                        {{ $versement->reservation?->annonce?->titre ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3 text-right font-bold text-emerald-600">
                                        +{{ number_format($versement->montant, 2) }} DH
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span @class([
                                            'px-2 py-0.5 rounded-full text-xs font-bold uppercase',
                                            'bg-amber-100 text-amber-700' => $versement->statut->value === 'en_attente',
                                            'bg-emerald-100 text-emerald-700' => $versement->statut->value === 'traite',
                                            'bg-red-100 text-red-700' => $versement->statut->value === 'echoue',
                                        ])>
                                            {{ match($versement->statut->value) {
                                                'en_attente' => 'En attente',
                                                'traite' => 'Traité',
                                                'echoue' => 'Échoué',
                                                default => $versement->statut->value,
                                            } }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right text-slate-400 text-xs">
                                        {{ \Carbon\Carbon::parse($versement->date_versement)->format('d M Y') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>

        </div>
    </main>

    <x-footer />
</body>
</html>
