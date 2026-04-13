@extends('admin.layouts.app')

@section('header_title', 'Gestion des Paiements (Voyageurs)')

@section('content')

    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Historique des Paiements</h2>
            <p class="text-sm text-slate-500">Supervisez les transactions entrantes effectuées par les voyageurs.</p>
        </div>
        
        <!-- Filtre de Statut -->
        <form method="GET" action="{{ route('admin.transactions.paiements') }}" class="flex items-center gap-2">
            <select name="statut" class="bg-white px-3 py-2 border border-slate-300 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                <option value="tous" {{ request('statut') == 'tous' ? 'selected' : '' }}>Tous les statuts</option>
                <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                <option value="reussi" {{ request('statut') == 'reussi' ? 'selected' : '' }}>Réussi</option>
                <option value="echoue" {{ request('statut') == 'echoue' ? 'selected' : '' }}>Échoué</option>
                <option value="rembourse" {{ request('statut') == 'rembourse' ? 'selected' : '' }}>Remboursé</option>
            </select>
            <button type="submit" class="bg-slate-100 px-3 py-2 border border-slate-300 rounded-lg text-sm text-slate-700 hover:bg-slate-200 font-medium">Filtrer</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Date Transaction</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Voyageur</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Annonce (Id Réservation)</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider flex justify-end">Montant</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Méthode</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($paiements as $paiement)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                            {{ optional($paiement->date_transaction)->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-slate-800">{{ optional($paiement->voyageur)->prenom }} {{ optional($paiement->voyageur)->nom }}</div>
                            <div class="text-xs text-slate-500">{{ optional($paiement->voyageur)->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-slate-700 font-medium truncate max-w-[200px]" title="{{ optional(optional($paiement->reservation)->annonce)->titre }}">
                                {{ optional(optional($paiement->reservation)->annonce)->titre ?? 'Introuvable' }}
                            </div>
                            <div class="text-xs text-slate-400 font-mono">{{ substr($paiement->id_reservation, 0, 8) }}...</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-sm font-black text-emerald-600">+{{ number_format($paiement->montant, 2, ',', ' ') }} MAD</span>
                        </td>
                        <td class="px-6 py-4 text-center text-sm text-slate-600 uppercase">
                            {{ str_replace('_', ' ', $paiement->methode_paiement->value ?? $paiement->methode_paiement) }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusVal = $paiement->statut->value ?? $paiement->statut;
                                $statusColors = [
                                    'reussi' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                    'en_attente' => 'bg-amber-100 text-amber-800 border-amber-200',
                                    'echoue' => 'bg-red-100 text-red-800 border-red-200',
                                    'rembourse' => 'bg-gray-100 text-gray-800 border-gray-200',
                                ];
                                $colorClass = $statusColors[$statusVal] ?? 'bg-slate-100 text-slate-800 border-slate-200';
                            @endphp
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full border {{ $colorClass }}">
                                {{ ucfirst(str_replace('_', ' ', $statusVal)) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            Aucun paiement trouvé pour ce statut.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $paiements->links() }}
        </div>
    </div>

@endsection
