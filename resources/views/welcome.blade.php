<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accommity - Naviguez avec confiance</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-white text-slate-900 antialiased h-full flex flex-col min-h-screen">
    
    <x-header />
    <x-dialogs />

    <!-- ALERTE: En cours de traitement -->

    <!-- Main Content -->
    <main class="flex-grow">
        <!-- Hero / Title & Search -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-8">
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Trouvez votre prochain hébergement</h1>
            <p class="text-slate-500 mb-8">Découvrez des logements uniques pour vos vacances, ou devenez hôte.</p>

            <!-- Custom Search Bar -->
            <form action="{{ route('annonces.index') }}" method="GET" class="max-w-3xl bg-white rounded-full shadow-md border border-slate-200 flex items-center p-2 mx-auto sm:mx-0">
                <div class="flex-1 px-6 py-2 border-r border-slate-200">
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider">Destination</label>
                    <input type="text" name="location" value="{{ request('location') }}" placeholder="Où allez-vous ?" class="w-full text-slate-600 text-sm outline-none bg-transparent placeholder-slate-400 mt-1">
                </div>
                <div class="hidden sm:block flex-1 px-6 py-2 border-r border-slate-200">
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider">Arrivée</label>
                    <input type="date" name="checkin" value="{{ request('checkin') }}" min="{{ date('Y-m-d') }}" class="w-full text-slate-600 text-sm outline-none bg-transparent placeholder-slate-400 mt-1">
                </div>
                <div class="hidden sm:block flex-1 px-6 py-2 border-r border-slate-200">
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider">Départ</label>
                    <input type="date" name="checkout" value="{{ request('checkout') }}" class="w-full text-slate-600 text-sm outline-none bg-transparent placeholder-slate-400 mt-1">
                </div>
                <div class="flex-1 px-6 py-2">
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider">Voyageurs</label>
                    <input type="number" name="nb_voyageurs" value="{{ request('nb_voyageurs') }}" placeholder="Ajouter" min="1" class="w-full text-slate-600 text-sm outline-none bg-transparent placeholder-slate-400 mt-1">
                </div>
                <!-- Submit Button -->
                <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white rounded-full p-4 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </form>

            <!-- Categories / Quick Filters -->
            <div class="mt-12 flex items-center gap-8 overflow-x-auto pb-4 no-scrollbar border-b border-transparent">
                @php
                    $categories = [
                        ['id' => 'Appartement', 'label' => 'Appartements', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                        ['id' => 'Maison', 'label' => 'Maisons', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                        ['id' => 'Villa', 'label' => 'Villas', 'icon' => 'M8 14v10M16 10v14M4 14h16'],
                        ['id' => 'Studio', 'label' => 'Studios', 'icon' => 'M5 3v18l7-3 7 3V3H5z'],
                        ['id' => 'Chambre', 'label' => 'Chambres', 'icon' => 'M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ];
                @endphp

                @foreach($categories as $cat)
                    <a href="{{ route('annonces.index', ['type_logement' => $cat['id']]) }}" 
                       class="flex flex-col items-center gap-2 group min-w-fit transition-all {{ request('type_logement') == $cat['id'] ? 'text-slate-900 border-b-2 border-slate-900 pb-2' : 'text-slate-500 hover:text-slate-900' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $cat['icon'] }}"></path>
                        </svg>
                        <span class="text-xs font-semibold whitespace-nowrap">{{ $cat['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Annonces Grid -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 lg:gap-x-8 xl:grid-cols-5 gap-y-10 gap-x-6">
                @forelse ($annonces as $annonce)
                    <x-annonce-card 
                        :id="$annonce->id_annonce"
                        :titre="$annonce->titre"
                        :image="$annonce->photo_url ?: '/images/annonces/1.jpg'"
                        :type="$annonce->type_logement"
                        :ville="$annonce->categorie->ville ?? ($annonce->ville ?? $annonce->adresse)"
                        :prix="$annonce->tarif_nuit"
                        note="4.8"
                    />
                @empty
                    <div class="col-span-full py-12 text-center border-2 border-dashed border-slate-200 rounded-3xl">
                        <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <h3 class="mt-4 text-sm font-semibold text-slate-900">Aucun résultat</h3>
                        <p class="mt-1 text-sm text-slate-500">Essayez d'ajuster votre recherche de destination ou la capacité.</p>
                        @if(request()->has('location') || request()->has('nb_voyageurs'))
                            <a href="{{ route('annonces.index') }}" class="mt-4 inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-500">Effacer les filtres &rarr;</a>
                        @endif
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Features Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-12 mt-12 border-t border-slate-100">
            <div class="text-center mb-12">
                <h2 class="text-2xl font-bold text-slate-900">Pourquoi choisir Accommity ?</h2>
                <p class="text-slate-500 mt-2">Votre confort et votre sécurité sont nos priorités absolues.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <x-feature-card 
                    icon='<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>'
                    title="Paiement Sécurisé"
                    description="Tous vos paiements sont traités via un système hautement sécurisé pour vous garantir une tranquillité d'esprit totale."
                />
                
                <x-feature-card 
                    icon='<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>'
                    title="Hébergements Vérifiés"
                    description="Chaque annonce et hôte subit une vérification rigoureuse pour garantir la qualité et l'exactitude des logements."
                />

                <x-feature-card 
                    icon='<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>'
                    title="Support Client 24/7"
                    description="Notre équipe est disponible 24h/24 et 7j/7 pour répondre à toutes vos questions et vous accompagner lors de votre séjour."
                />
            </div>
        </div>
    </main>

    <x-footer />
    
    <script>
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
</body>
</html>
