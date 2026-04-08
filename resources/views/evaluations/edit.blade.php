<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier mon avis - Accommity</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen flex flex-col">

    <x-header />

    <main class="flex-grow max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 w-full">
        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Modifier mon avis</h1>
            <p class="text-slate-500 mt-2 text-lg">
                Avis pour <strong>{{ $evaluation->reservation->annonce->titre ?? 'la réservation' }}</strong>
            </p>
            <p class="text-xs text-amber-600 mt-1 bg-amber-50 inline-block px-3 py-1 rounded-full">
                ⏳ Modifiable pendant encore {{ max(0, 14 - $evaluation->date_creation->diffInDays(now())) }} jour(s)
            </p>
        </div>

        <form action="{{ route('evaluations.update', $evaluation->id_evaluation) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-6">
                <h2 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-3">Notes détaillées</h2>

                @foreach([
                    'proprete' => ['label' => 'Propreté', 'icon' => '✨'],
                    'communication' => ['label' => 'Communication', 'icon' => '💬'],
                    'emplacement' => ['label' => 'Emplacement', 'icon' => '📍'],
                    'rapport_qualite_prix' => ['label' => 'Rapport Qualité/Prix', 'icon' => '💰'],
                    'exactitude' => ['label' => 'Exactitude', 'icon' => '🎯'],
                ] as $field => $info)
                    <div>
                        <label class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-3">
                            <span>{{ $info['icon'] }}</span>
                            {{ $info['label'] }}
                        </label>
                        <div class="flex items-center gap-2" id="rating-{{ $field }}">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button"
                                    class="star-btn w-11 h-11 rounded-full border-2 border-slate-200 text-slate-400 text-lg font-bold transition-all duration-200 hover:border-amber-400 hover:text-amber-500 hover:bg-amber-50 focus:outline-none focus:ring-2 focus:ring-amber-300"
                                    data-field="{{ $field }}" data-value="{{ $i }}"
                                    onclick="setRating('{{ $field }}', {{ $i }})">
                                    {{ $i }}
                                </button>
                            @endfor
                            <input type="hidden" name="{{ $field }}" id="input-{{ $field }}" value="{{ old($field, $evaluation->noteDetaillee->$field ?? '') }}" required>
                            <span class="ml-2 text-sm text-slate-400" id="label-{{ $field }}">Sélectionnez</span>
                        </div>
                        @error($field)
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <label for="commentaire" class="block text-sm font-semibold text-slate-700 mb-3">💬 Votre commentaire</label>
                <textarea name="commentaire" id="commentaire" rows="5"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition-colors resize-none"
                    required minlength="10" maxlength="2000">{{ old('commentaire', $evaluation->commentaire) }}</textarea>
                @error('commentaire')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('mes-evaluations') }}" class="text-sm text-slate-500 hover:text-slate-700 transition-colors">← Retour</a>
                <button type="submit"
                    class="bg-indigo-600 text-white font-bold py-3 px-8 rounded-full hover:bg-indigo-700 transition-all shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                    Mettre à jour
                </button>
            </div>
        </form>
    </main>

    <x-footer />

    <script>
        const ratingLabels = {1: 'Mauvais', 2: 'Passable', 3: 'Correct', 4: 'Bien', 5: 'Excellent'};

        function setRating(field, value) {
            document.getElementById('input-' + field).value = value;
            document.getElementById('label-' + field).textContent = ratingLabels[value];
            const buttons = document.querySelectorAll('#rating-' + field + ' .star-btn');
            buttons.forEach(btn => {
                const btnValue = parseInt(btn.dataset.value);
                if (btnValue <= value) {
                    btn.classList.remove('border-slate-200', 'text-slate-400');
                    btn.classList.add('border-amber-400', 'text-amber-600', 'bg-amber-50');
                } else {
                    btn.classList.remove('border-amber-400', 'text-amber-600', 'bg-amber-50');
                    btn.classList.add('border-slate-200', 'text-slate-400');
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            ['proprete', 'communication', 'emplacement', 'rapport_qualite_prix', 'exactitude'].forEach(field => {
                const val = document.getElementById('input-' + field).value;
                if (val) setRating(field, parseInt(parseFloat(val)));
            });
        });
    </script>
</body>
</html>
