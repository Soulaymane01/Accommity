<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tableau de bord - Accommity</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen">
    
    <x-header />

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-slate-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    Bonjour, {{ Auth::user()->prenom }} {{ Auth::user()->nom }} !
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Bienvenue sur votre tableau de bord
                    @if(Auth::user()->est_hote)
                        d'Hôte.
                    @else
                        de Voyageur.
                    @endif
                </p>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm border border-slate-200 sm:rounded-xl">
            <div class="p-6 text-slate-900">
                Vous êtes connecté(e) ! Ceci est un espace de démonstration de la structure. L'interface variera bientôt selon que vous soyez Hôte ou Voyageur.
            </div>
        </div>
    </main>

    <x-footer />

</body>
</html>
