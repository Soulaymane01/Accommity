@extends('admin.layouts.app')

@section('header_title', 'Avis Signalés')

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

    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">Modération des Avis</h2>
        <p class="text-sm text-slate-500 mt-1">Avis signalés par les utilisateurs nécessitant une investigation (RG31).</p>
    </div>

    @if($avisSignales->isEmpty())
        <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center shadow-sm">
            <div class="inline-flex items-center justify-center h-14 w-14 rounded-full bg-emerald-50 mb-4">
                <svg class="h-7 w-7 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-1">Aucun avis signalé</h3>
            <p class="text-sm text-slate-500">Tous les avis sont conformes. Rien à modérer pour le moment.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($avisSignales as $avis)
                <div class="bg-white rounded-xl shadow-sm border border-rose-200 overflow-hidden">
                    <div class="p-5">
                        {{-- En-tête --}}
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-rose-100 flex items-center justify-center text-rose-700 font-bold text-xs">
                                    {{ strtoupper(substr($avis->auteur->prenom ?? '?', 0, 1)) }}{{ strtoupper(substr($avis->auteur->nom ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800 text-sm">
                                        {{ $avis->auteur->prenom ?? '' }} {{ $avis->auteur->nom ?? '' }}
                                        <span class="text-slate-400 font-normal">→</span>
                                        {{ $avis->cible->prenom ?? '' }} {{ $avis->cible->nom ?? '' }}
                                    </p>
                                    <p class="text-xs text-slate-400">
                                        {{ $avis->type_auteur->value === 'voyageur' ? '🧳 Voyageur' : '🏠 Hôte' }}
                                        · {{ $avis->date_creation->format('d/m/Y à H:i') }}
                                        · Note : <span class="text-amber-500 font-semibold">{{ number_format($avis->note, 1) }}★</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Commentaire --}}
                        <div class="bg-slate-50 rounded-lg p-3 mb-3">
                            <p class="text-sm text-slate-700">« {{ $avis->commentaire }} »</p>
                        </div>

                        {{-- Motif de signalement --}}
                        <div class="bg-rose-50 rounded-lg p-3 flex items-start gap-2">
                            <span class="text-rose-500 text-sm mt-0.5">⚠️</span>
                            <div>
                                <p class="text-xs font-semibold text-rose-700">Motif du signalement :</p>
                                <p class="text-sm text-rose-600">{{ $avis->motif_signalement }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Actions admin --}}
                    <div class="bg-slate-50 px-5 py-3 flex items-center justify-end gap-3 border-t border-slate-100">
                        {{-- Conserver l'avis --}}
                        <form action="{{ route('admin.evaluations.conserver', $avis->id_evaluation) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-emerald-600 hover:text-emerald-800 transition-colors px-4 py-1.5 rounded-lg hover:bg-emerald-50">
                                ✅ Conserver
                            </button>
                        </form>

                        {{-- Supprimer l'avis (RG31) --}}
                        <form action="{{ route('admin.evaluations.supprimer', $avis->id_evaluation) }}" method="POST"
                            onsubmit="return confirm('Supprimer définitivement cet avis ? Cette action est irréversible.')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800 transition-colors px-4 py-1.5 rounded-lg hover:bg-red-50">
                                🗑️ Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection
