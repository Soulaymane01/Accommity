<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gérer le Calendrier | Accommity</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased h-full flex flex-col min-h-screen">
    
    <x-header />

    @if(session('success_dialog'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-xl flex items-center">
            <span class="font-medium text-sm">{{ session('success_dialog') }}</span>
        </div>
    </div>
    @endif
    @if(session('error_dialog'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl flex items-center">
            <span class="font-medium text-sm">{{ session('error_dialog') }}</span>
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <main class="flex-grow py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Gestion des Calendriers</h1>
                    <p class="mt-2 text-sm text-slate-500">Bloquez ou débloquez les dates selon vos besoins.</p>
                </div>
            </div>

            <div class="space-y-8">
                @foreach($annonces as $annonce)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="border-b border-slate-200 bg-slate-50 px-6 py-4 flex justify-between items-center">
                        <h2 class="text-lg font-bold text-slate-900">{{ $annonce->titre }}</h2>
                        <span class="text-xs font-semibold text-slate-500 bg-slate-200 px-2.5 py-1 rounded-md">{{ count($annonce->calendrier) }} créneaux bloqués</span>
                    </div>
                    
                    <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 mb-4">Dates actuellement indisponibles</h3>
                            @if(count($annonce->calendrier->where('est_disponible', false)) > 0)
                                <ul class="divide-y divide-slate-100 border border-slate-100 rounded-xl">
                                    @foreach($annonce->calendrier->where('est_disponible', false) as $cal)
                                    <li class="py-3 px-4 flex justify-between items-center hover:bg-slate-50">
                                        <div>
                                            <span class="text-sm font-semibold text-slate-700">Du {{ \Carbon\Carbon::parse($cal->date_debut)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($cal->date_fin)->format('d/m/Y') }}</span>
                                            <span class="block text-xs text-slate-500 mt-0.5">{{ $cal->type_blockage->value ?? $cal->type_blockage }}</span>
                                        </div>
                                        @if($cal->type_blockage->value === 'Bloque Manuel')
                                        <form action="{{ route('hote.calendrier.debloquer', $annonce->id_annonce) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="date_debut" value="{{ $cal->date_debut->toDateString() }}">
                                            <input type="hidden" name="date_fin" value="{{ $cal->date_fin->toDateString() }}">
                                            <button type="submit" class="text-rose-600 text-xs font-bold hover:underline">Débloquer</button>
                                        </form>
                                        @endif
                                    </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-sm text-slate-500 italic">Toutes les dates sont disponibles pour ce logement.</p>
                            @endif
                        </div>

                        <div class="bg-slate-50 rounded-xl p-5 border border-slate-100">
                            <h3 class="text-sm font-bold text-slate-900 mb-4">Bloquer de nouvelles dates (Manuel)</h3>
                            <form action="{{ route('hote.calendrier.bloquer', $annonce->id_annonce) }}" method="POST">
                                @csrf
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Date début</label>
                                        <input type="date" name="date_debut" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-3 py-2 border" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Date fin</label>
                                        <input type="date" name="date_fin" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-3 py-2 border" required>
                                    </div>
                                </div>
                                <button type="submit" class="w-full inline-flex justify-center items-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-slate-800 transition-colors">
                                    Bloquer ces dates
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
