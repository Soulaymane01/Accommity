<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mes Annonces - Hôte | Accommity</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased h-full flex flex-col min-h-screen">
    
    <x-header />

    <!-- ALERTE MESSAGES -->
    @if(session('success_dialog'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-xl flex items-center">
            <svg class="h-5 w-5 mr-3 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-medium text-sm">{{ session('success_dialog') }}</span>
        </div>
    </div>
    @endif
    @if(session('error_dialog'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl flex items-center">
            <svg class="h-5 w-5 mr-3 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span class="font-medium text-sm">{{ session('error_dialog') }}</span>
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <main class="flex-grow py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Mes Annonces</h1>
                    <p class="mt-2 text-sm text-slate-500">Gérez vos propriétés et mettez à jour leurs informations.</p>
                </div>
                <div class="flex gap-4">
                    <a href="{{ route('hote.calendrier.index') }}" class="inline-flex justify-center items-center gap-2 rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm border border-slate-200 hover:bg-slate-50 transition-all">
                        <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Gérer les calendriers
                    </a>
                    <a href="{{ route('hote.annonces.create') }}" class="inline-flex justify-center items-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Créer une annonce
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Logement</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Statut</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Prix / Nuit</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @forelse($annonces as $annonce)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-16 w-20 flex-shrink-0 bg-slate-200 rounded-lg overflow-hidden">
                                            @if($annonce->photo_url)
                                            <img class="h-full w-full object-cover" src="{{ $annonce->photo_url }}" alt="">
                                            @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-slate-900">{{ $annonce->titre }}</div>
                                            <div class="text-sm text-slate-500 mt-1 flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                {{ $annonce->adresse }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1 text-xs font-semibold 
                                        @if($annonce->statut->value === 'Publié') bg-emerald-50 text-emerald-700 border border-emerald-200
                                        @elseif($annonce->statut->value === 'En cours de vérification') bg-amber-50 text-amber-700 border border-amber-200
                                        @elseif($annonce->statut->value === 'Désactivé' || $annonce->statut->value === 'Rejeté') bg-rose-50 text-rose-700 border border-rose-200
                                        @else bg-slate-100 text-slate-700 border border-slate-200 @endif
                                    ">
                                        @if($annonce->statut->value === 'Publié')
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        @endif
                                        {{ $annonce->statut->value }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-sm font-medium text-slate-900">
                                    {{ number_format($annonce->tarif_nuit, 2, ',', ' ') }} MAD
                                </td>
                                    <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
                                        @if($annonce->statut->value !== 'Rejeté')
                                            <a href="{{ route('hote.reservations.demandes', ['id_annonce' => $annonce->id_annonce]) }}" class="text-emerald-600 hover:text-emerald-900 mr-4 transition-colors">Demandes</a>
                                        @endif
                                        
                                        @if($annonce->statut->value !== 'Désactivé' && $annonce->statut->value !== 'Rejeté')
                                            <a href="{{ route('hote.annonces.edit', $annonce->id_annonce) }}" class="text-blue-600 hover:text-blue-900 mr-4 transition-colors">Modifier</a>
                                        @endif
                                        
                                        @if($annonce->statut->value !== 'Désactivé')
                                            <form action="{{ route('hote.annonces.destroy', $annonce->id_annonce) }}" method="POST" class="inline-block" onsubmit="return confirm('Confirmez-vous la suppression de cette annonce ? Elle ne sera plus visible par les voyageurs et sera définie comme désactivée.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-rose-600 hover:text-rose-900 transition-colors">Supprimer</button>
                                            </form>
                                        @endif
                                    </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-500 text-sm">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="h-12 w-12 text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                        </svg>
                                        Vous n'avez pas encore publié d'annonce.
                                        <a href="{{ route('hote.annonces.create') }}" class="mt-4 text-blue-600 font-semibold hover:underline">Créer votre première annonce</a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
