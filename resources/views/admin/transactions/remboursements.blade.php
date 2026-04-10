@extends('admin.layouts.app')

@section('header_title', 'Gestion des Remboursements (Voyageurs)')

@section('content')

    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Historique des Remboursements</h2>
            <p class="text-sm text-slate-500">Supervisez les annulations et l'argent retourné aux voyageurs.</p>
        </div>
        
        <!-- Filtre de Motif -->
        <form method="GET" action="{{ route('admin.transactions.remboursements') }}" class="flex items-center gap-2">
            <select name="motif" class="bg-white px-3 py-2 border border-slate-300 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                <option value="tous" {{ request('motif') == 'tous' ? 'selected' : '' }}>Tous les motifs</option>
                <option value="annulation_voyageur" {{ request('motif') == 'annulation_voyageur' ? 'selected' : '' }}>Annulation (Voyageur)</option>
                <option value="annulation_hote" {{ request('motif') == 'annulation_hote' ? 'selected' : '' }}>Annulation (Hôte)</option>
                <option value="expiration_demande" {{ request('motif') == 'expiration_demande' ? 'selected' : '' }}>Expiration Demande</option>
            </select>
            <button type="submit" class="bg-slate-100 px-3 py-2 border border-slate-300 rounded-lg text-sm text-slate-700 hover:bg-slate-200 font-medium">Filtrer</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Date Remboursement</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Voyageur Remboursé</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider flex justify-end">Montant Retourné</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Motif d'Annulation</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($remboursements as $remboursement)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                            {{ optional($remboursement->date_remboursement)->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-slate-800">{{ optional($remboursement->voyageur)->prenom }} {{ optional($remboursement->voyageur)->nom }}</div>
                            <div class="text-xs text-slate-500">{{ optional($remboursement->voyageur)->email }}</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-sm font-black text-rose-600">-{{ number_format($remboursement->montant, 2, ',', ' ') }} MAD</span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $val = $remboursement->motif->value ?? $remboursement->motif;
                            @endphp
                            <span class="px-2.5 py-1 text-xs font-semibold rounded bg-slate-100 text-slate-700 border border-slate-200">
                                {{ ucfirst(str_replace('_', ' ', $val)) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                            Aucun remboursement trouvé pour ce motif.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $remboursements->links() }}
        </div>
    </div>

@endsection
