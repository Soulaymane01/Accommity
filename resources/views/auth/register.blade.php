<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>S'inscrire - Accommity</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">

    <div class="sm:mx-auto sm:w-full sm:max-w-xl">
        <div class="flex justify-center mb-6">
            <a href="{{ route('home') }}" class="text-3xl font-bold text-slate-800 tracking-tight flex items-center gap-2">
                <svg class="w-10 h-10 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Accommity
            </a>
        </div>
        <h2 class="mt-6 text-center text-2xl font-bold tracking-tight text-slate-900">Créez votre compte</h2>
        <p class="mt-2 text-center text-sm text-slate-600">
            Ou
            <a href="{{ route('login') }}" class="font-medium text-blue-900 hover:text-blue-800">connectez-vous si vous avez déjà un compte</a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-xl">
        <div class="bg-white py-8 px-4 shadow-sm border border-slate-100 sm:rounded-xl sm:px-10">
            <form class="space-y-6" action="{{ route('register') }}" method="POST">
                @csrf
                
                <!-- Role Selection -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Je suis un...</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="flex flex-col items-center justify-center p-4 border border-slate-200 rounded-lg cursor-pointer hover:bg-slate-50 transition-colors has-[:checked]:border-blue-900 has-[:checked]:bg-blue-50 has-[:checked]:ring-1 has-[:checked]:ring-blue-900">
                            <input type="radio" name="role" value="voyageur" class="sr-only" required @checked(old('role') == 'voyageur')>
                            <svg class="w-8 h-8 text-blue-900 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span class="font-medium">Voyageur</span>
                        </label>

                        <label class="flex flex-col items-center justify-center p-4 border border-slate-200 rounded-lg cursor-pointer hover:bg-slate-50 transition-colors has-[:checked]:border-blue-900 has-[:checked]:bg-blue-50 has-[:checked]:ring-1 has-[:checked]:ring-blue-900">
                            <input type="radio" name="role" value="hote" class="sr-only" required @checked(old('role') == 'hote')>
                            <svg class="w-8 h-8 text-blue-900 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            <span class="font-medium">Hôte</span>
                        </label>
                    </div>
                    @error('role')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="prenom" class="block text-sm font-medium text-slate-700">Prénom</label>
                        <div class="mt-1">
                            <input id="prenom" name="prenom" type="text" required class="block w-full appearance-none rounded-lg border border-slate-200 px-3 py-2 shadow-sm focus:border-blue-900 focus:outline-none focus:ring-1 focus:ring-blue-900 sm:text-sm" value="{{ old('prenom') }}">
                        </div>
                        @error('prenom') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="nom" class="block text-sm font-medium text-slate-700">Nom</label>
                        <div class="mt-1">
                            <input id="nom" name="nom" type="text" required class="block w-full appearance-none rounded-lg border border-slate-200 px-3 py-2 shadow-sm focus:border-blue-900 focus:outline-none focus:ring-1 focus:ring-blue-900 sm:text-sm" value="{{ old('nom') }}">
                        </div>
                        @error('nom') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700">Adresse e-mail</label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required class="block w-full appearance-none rounded-lg border border-slate-200 px-3 py-2 shadow-sm focus:border-blue-900 focus:outline-none focus:ring-1 focus:ring-blue-900 sm:text-sm" value="{{ old('email') }}">
                    </div>
                    @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label for="telephone" class="block text-sm font-medium text-slate-700">Numéro de téléphone</label>
                    <div class="mt-1">
                        <input id="telephone" name="telephone" type="tel" required class="block w-full appearance-none rounded-lg border border-slate-200 px-3 py-2 shadow-sm focus:border-blue-900 focus:outline-none focus:ring-1 focus:ring-blue-900 sm:text-sm" value="{{ old('telephone') }}">
                    </div>
                    @error('telephone') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="mot_de_passe" class="block text-sm font-medium text-slate-700">Mot de passe</label>
                        <div class="mt-1">
                            <input id="mot_de_passe" name="mot_de_passe" type="password" required class="block w-full appearance-none rounded-lg border border-slate-200 px-3 py-2 shadow-sm focus:border-blue-900 focus:outline-none focus:ring-1 focus:ring-blue-900 sm:text-sm">
                        </div>
                        @error('mot_de_passe') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="mot_de_passe_confirmation" class="block text-sm font-medium text-slate-700">Confirmer mot de passe</label>
                        <div class="mt-1">
                            <input id="mot_de_passe_confirmation" name="mot_de_passe_confirmation" type="password" required class="block w-full appearance-none rounded-lg border border-slate-200 px-3 py-2 shadow-sm focus:border-blue-900 focus:outline-none focus:ring-1 focus:ring-blue-900 sm:text-sm">
                        </div>
                    </div>
                </div>

                <div>
                    <button type="submit" class="mt-4 flex w-full justify-center rounded-lg border border-transparent bg-blue-900 py-2.5 px-4 text-sm font-medium text-white shadow-sm hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:ring-offset-2 transition-all">
                        S'inscrire
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
