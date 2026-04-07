<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Espace Hôte - Accommity</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen flex flex-col">
    
    <x-header />

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 w-full">
        <div class="mb-10">
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Bonjour, {{ Auth::user()->prenom }} 👋</h1>
            <p class="text-slate-500 mt-2 text-lg">Bienvenue sur votre espace Hôte.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 h-40 flex flex-col justify-between">
                <span class="text-slate-500 font-semibold text-sm uppercase tracking-wider">Mes Réservations</span>
                <span class="text-4xl font-extrabold text-blue-900">0</span>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 h-40 flex flex-col justify-between">
                <span class="text-slate-500 font-semibold text-sm uppercase tracking-wider">Annonces Actives</span>
                <span class="text-4xl font-extrabold text-blue-900">0</span>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 h-40 flex flex-col justify-between">
                <span class="text-slate-500 font-semibold text-sm uppercase tracking-wider">Total Revenus</span>
                <span class="text-4xl font-extrabold text-green-600">0.00 DH</span>
            </div>
        </div>

        <div class="bg-white border border-slate-100 rounded-3xl p-10 text-center shadow-sm">
            <div class="max-w-md mx-auto">
                <div class="h-20 w-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="h-10 w-10 text-blue-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 mb-4">Créez votre première annonce</h2>
                <p class="text-slate-600 mb-8 leading-relaxed">
                    Félicitations ! Votre profil est validé. Commencez dès maintenant à publier vos logements pour accueillir vos premiers voyageurs.
                </p>
                <button class="bg-blue-900 text-white font-bold py-4 px-10 rounded-full hover:bg-slate-800 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    Publier une annonce
                </button>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
