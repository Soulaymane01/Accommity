@extends('admin.layouts.app')

@section('header_title', 'Gestion des Litiges')

@section('content')

    {{-- Messages flash --}}
    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm">
            ✅ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- Statistiques --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-blue-100 p-6 text-center">
            <span class="text-3xl font-bold text-blue-600 block">{{ $stats['en_cours'] }}</span>
            <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">En cours</span>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 text-center">
            <span class="text-3xl font-bold text-slate-600 block">{{ $stats['clotures'] }}</span>
            <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">Clôturés</span>
        </div>
    </div>

    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">Tickets de Litige</h2>
        <p class="text-sm text-slate-500 mt-1">Litiges générés automatiquement suite à des signalements d'avis (RO10).</p>
    </div>

    @if($litiges->isEmpty())
        <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center shadow-sm">
            <div class="inline-flex items-center justify-center h-14 w-14 rounded-full bg-slate-100 mb-4">
                <svg class="h-7 w-7 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-1">Aucun litige</h3>
            <p class="text-sm text-slate-500">Aucun ticket de litige n'a été créé pour le moment.</p>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Déclarant</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Motif</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Avis concerné</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Date</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Statut</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($litiges as $litige)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs">
                                        {{ strtoupper(substr($litige->declarant->prenom ?? '?', 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-medium text-slate-700">{{ $litige->declarant->prenom ?? '' }} {{ $litige->declarant->nom ?? '' }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <p class="text-sm text-slate-600 max-w-xs truncate">{{ $litige->motif }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <p class="text-xs text-slate-500">
                                    Par : {{ $litige->evaluation->auteur->prenom ?? '?' }}
                                    → {{ $litige->evaluation->cible->prenom ?? '?' }}
                                </p>
                                <p class="text-xs text-amber-500 font-semibold">{{ number_format($litige->evaluation->note ?? 0, 1) }}★</p>
                            </td>
                            <td class="px-5 py-4">
                                <span class="text-sm text-slate-500">{{ $litige->date_creation->format('d/m/Y') }}</span>
                            </td>
                            <td class="px-5 py-4">
                                @if($litige->statut === \App\Enums\TicketLitigeStatut::EN_COURS)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        En cours
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                        Clôturé
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                @if($litige->statut === \App\Enums\TicketLitigeStatut::EN_COURS)
                                    <form action="{{ route('admin.litiges.update', $litige->id_ticket) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="statut" value="Clôturé">
                                        <button type="submit" class="text-xs font-medium text-indigo-600 hover:text-indigo-800 transition-colors bg-indigo-50 px-3 py-1.5 rounded-lg hover:bg-indigo-100">
                                            Clôturer
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-400">{{ $litige->date_cloture ? $litige->date_cloture->format('d/m/Y') : '—' }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

@endsection
