<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Se Connecter - Accommity</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">

    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex justify-center mb-6">
            <a href="{{ route('home') }}" class="text-3xl font-bold text-slate-800 tracking-tight flex items-center gap-2">
                <svg class="w-10 h-10 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Accommity
            </a>
        </div>
        <h2 class="mt-6 text-center text-2xl font-bold tracking-tight text-slate-900">Connectez-vous à votre compte</h2>
        <p class="mt-2 text-center text-sm text-slate-600">
            Ou
            <a href="{{ route('register') }}" class="font-medium text-blue-900 hover:text-blue-800">créez un nouveau compte</a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow-sm border border-slate-100 sm:rounded-xl sm:px-10">
            <form class="space-y-6" action="{{ route('login') }}" method="POST">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700">Adresse e-mail</label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required class="block w-full appearance-none rounded-lg border border-slate-200 px-3 py-2 shadow-sm placeholder-slate-400 focus:border-blue-900 focus:outline-none focus:ring-1 focus:ring-blue-900 sm:text-sm" value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="mot_de_passe" class="block text-sm font-medium text-slate-700">Mot de passe</label>
                    <div class="mt-1">
                        <input id="mot_de_passe" name="mot_de_passe" type="password" autocomplete="current-password" required class="block w-full appearance-none rounded-lg border border-slate-200 px-3 py-2 shadow-sm placeholder-slate-400 focus:border-blue-900 focus:outline-none focus:ring-1 focus:ring-blue-900 sm:text-sm">
                    </div>
                </div>

                <div>
                    <button type="submit" class="flex w-full justify-center rounded-lg border border-transparent bg-blue-900 py-2.5 px-4 text-sm font-medium text-white shadow-sm hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:ring-offset-2 transition-all">
                        Se connecter
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
