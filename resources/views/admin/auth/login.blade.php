<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>S'identifier - Espace Administration</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">

    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <a href="/" class="flex justify-center items-center gap-2 mb-8 group">
            <svg class="h-10 w-10 text-indigo-800 transition-transform group-hover:scale-105" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span class="text-3xl font-bold tracking-tight text-slate-900">Accommity</span>
        </a>
        <h2 class="mt-2 text-center text-3xl font-bold tracking-tight text-slate-900">Espace Administration</h2>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow-sm border border-slate-100 sm:rounded-xl sm:px-10">
            <form class="space-y-6" action="{{ route('admin.login.submit') }}" method="POST">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700">Adresse e-mail admin</label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required class="block w-full appearance-none rounded-lg border border-slate-200 px-3 py-2 shadow-sm focus:border-indigo-800 focus:outline-none focus:ring-1 focus:ring-indigo-800 sm:text-sm" value="{{ old('email') }}">
                    </div>
                    @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="mot_de_passe" class="block text-sm font-medium text-slate-700">Mot de passe sécurisé</label>
                    <div class="mt-1">
                        <input id="mot_de_passe" name="mot_de_passe" type="password" autocomplete="current-password" required class="block w-full appearance-none rounded-lg border border-slate-200 px-3 py-2 shadow-sm focus:border-indigo-800 focus:outline-none focus:ring-1 focus:ring-indigo-800 sm:text-sm">
                    </div>
                </div>

                <div>
                    <button type="submit" class="flex w-full justify-center rounded-lg bg-indigo-800 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-800 transition-colors">
                        S'authentifier
                    </button>
                    <p class="mt-4 text-center text-sm text-slate-500">
                        <a href="/" class="font-medium text-indigo-800 hover:text-indigo-700">Retour au site public</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
