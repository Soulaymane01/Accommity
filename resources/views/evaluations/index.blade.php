<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mes Évaluations - Accommity</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen flex flex-col">

    <x-header />

    <main class="flex-grow max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 w-full">
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

        <div class="mb-10">
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Mes Évaluations</h1>
            <p class="text-slate-500 mt-2 text-lg">Les avis que vous avez reçus de la part d'autres utilisateurs.</p>
        </div>

        @if($evaluations->isEmpty())
            <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center shadow-sm">
                <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-slate-100 mb-4">
                    <svg class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Aucune évaluation pour le moment</h3>
                <p class="text-slate-500 max-w-md mx-auto">Vos évaluations apparaîtront ici une fois que vos hôtes ou voyageurs vous auront noté.</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach($evaluations as $eval)
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="p-6">
                            {{-- En-tête de l'évaluation --}}
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm">
                                            {{ strtoupper(substr($eval->auteur->prenom ?? '?', 0, 1)) }}{{ strtoupper(substr($eval->auteur->nom ?? '?', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800">{{ $eval->auteur->prenom ?? '' }} {{ $eval->auteur->nom ?? '' }}</p>
                                            <p class="text-xs text-slate-400">
                                                {{ $eval->type_auteur->value === 'voyageur' ? '🧳 Voyageur' : '🏠 Hôte' }}
                                                · {{ $eval->date_creation->format('d/m/Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="text-2xl font-extrabold text-amber-500">{{ number_format($eval->note, 1) }}</span>
                                    <span class="text-amber-400 text-lg">★</span>
                                </div>
                            </div>

                            {{-- Notes détaillées --}}
                            @if($eval->noteDetaillee)
                                <div class="grid grid-cols-5 gap-3 mb-4">
                                    @foreach([
                                        'proprete' => 'Propreté',
                                        'communication' => 'Communication',
                                        'emplacement' => 'Emplacement',
                                        'rapport_qualite_prix' => 'Qualité/Prix',
                                        'exactitude' => 'Exactitude',
                                    ] as $field => $label)
                                        <div class="text-center bg-slate-50 rounded-xl py-2 px-1">
                                            <span class="text-xs text-slate-500 block">{{ $label }}</span>
                                            <span class="text-sm font-bold text-slate-700">{{ number_format($eval->noteDetaillee->$field, 1) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Commentaire --}}
                            <p class="text-sm text-slate-600 leading-relaxed">{{ $eval->commentaire }}</p>

                            {{-- Badge signalé --}}
                            @if($eval->est_signale)
                                <div class="mt-3 inline-flex items-center gap-1 bg-rose-50 text-rose-600 text-xs font-medium px-3 py-1 rounded-full">
                                    ⚠️ Signalé : {{ $eval->motif_signalement }}
                                </div>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="bg-slate-50 px-6 py-3 flex items-center justify-end gap-3 border-t border-slate-100">
                            {{-- Signaler (si ce n'est pas notre propre avis et pas déjà signalé) --}}
                            @if($eval->id_auteur !== Auth::id() && !$eval->est_signale)
                                <form action="{{ route('evaluations.signaler', $eval->id_evaluation) }}" method="POST" 
                                    onsubmit="return confirm('Êtes-vous sûr de vouloir signaler cet avis ?')" class="inline">
                                    @csrf
                                    <input type="hidden" name="motif_signalement" value="Contenu inapproprié signalé par l'utilisateur">
                                    <button type="submit" class="text-xs text-rose-500 hover:text-rose-700 transition-colors font-medium">
                                        🚩 Signaler
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>

    <x-footer />
</body>
</html>
