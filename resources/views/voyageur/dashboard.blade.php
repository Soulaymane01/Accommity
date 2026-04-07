<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Espace Voyageur - Accommity</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen flex flex-col">
    
    <x-header />

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 w-full">
        <div class="mb-10">
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Bonjour, {{ Auth::user()->prenom }} !</h1>
            <p class="text-slate-500 mt-2 text-lg">Bienvenue sur votre espace Voyageur.</p>
        </div>

        <div class="bg-white overflow-hidden shadow-sm border border-slate-200 sm:rounded-xl">
            <div class="p-8 text-center">
                <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-emerald-50 mb-4">
                    <svg class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Prêt pour l'aventure ?</h3>
                <p class="text-slate-500 max-w-md mx-auto mb-6">
                    Trouvez votre prochain logement parmi nos hébergements vérifiés et réservez en toute sécurité.
                </p>
                <a href="/" class="bg-emerald-600 text-white font-bold py-3 px-8 rounded-full hover:bg-emerald-700 transition-colors shadow-sm inline-block">
                    Explorer les annonces
                </a>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
