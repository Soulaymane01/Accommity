@extends('admin.layouts.app')

@section('header_title', 'Gestion des Versements (Hôtes)')

@section('content')

    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Historique des Versements</h2>
            <p class="text-sm text-slate-500">Supervisez les montants reversés aux hôtes après le début du séjour.</p>
        </div>
        
        <!-- Filtre de Statut -->
        <form method="GET" action="{{ route('admin.transactions.versements') }}" class="flex items-center gap-2">
            <select name="statut" class="bg-white px-3 py-2 border border-slate-300 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                <option value="tous" {{ request('statut') == 'tous' ? 'selected' : '' }}>Tous les statuts</option>
                <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                <option value="traite" {{ request('statut') == 'traite' ? 'selected' : '' }}>Traité</option>
                <option value="echoue" {{ request('statut') == 'echoue' ? 'selected' : '' }}>Échoué</option>
            </select>
            <button type="submit" class="bg-slate-100 px-3 py-2 border border-slate-300 rounded-lg text-sm text-slate-700 hover:bg-slate-200 font-medium">Filtrer</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Date Versement</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Hôte Bénéficiaire</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Réf Bancaire</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider flex justify-end">Montant Versé</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($versements as $versement)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                            {{ optional($versement->date_versement)->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-slate-800">{{ optional($versement->hote)->prenom }} {{ optional($versement->hote)->nom }}</div>
                            <div class="text-xs text-slate-500">{{ optional($versement->hote)->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-slate-700 font-mono bg-slate-100 px-2 py-1 rounded inline-block">
                                {{ $versement->reference_bancaire ?: 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-sm font-black text-indigo-600">-{{ number_format($versement->montant, 2, ',', ' ') }} MAD</span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusVal = $versement->statut->value ?? $versement->statut;
                                $statusColors = [
                                    'traite' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                    'en_attente' => 'bg-amber-100 text-amber-800 border-amber-200',
                                    'echoue' => 'bg-red-100 text-red-800 border-red-200',
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
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            Aucun versement trouvé pour ce statut.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $versements->links() }}
        </div>
    </div>

@endsection
