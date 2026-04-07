<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vérification d'Identité - Accommity</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">

    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-12 px-8 shadow-sm border border-slate-100 sm:rounded-2xl text-center">
            
            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-blue-50 mb-6">
                <svg class="h-10 w-10 text-blue-900" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                </svg>
            </div>
            
            <h2 class="text-2xl font-bold tracking-tight text-slate-900 mb-2">Vérification Requise</h2>
            <p class="text-slate-600 mb-8 leading-relaxed">
                En tant qu'Hôte, vous devez vérifier votre identité avant de pouvoir accéder à votre tableau de bord et publier vos annonces.
            </p>

            @if (session('status'))
                <div class="mb-6 p-4 rounded-lg bg-green-50 text-green-800 border border-green-200 text-sm font-medium">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('verification.submit') }}" method="POST" enctype="multipart/form-data" class="mt-4 text-left space-y-4">
                @csrf
                
                <div>
                    <label for="type_piece" class="block text-sm font-medium text-slate-700">Type de pièce d'identité</label>
                    <select id="type_piece" name="type_piece" required readonly class="mt-1 block w-full rounded-md border-slate-300 py-2 pl-3 pr-10 text-base bg-slate-50 text-slate-500 focus:outline-none sm:text-sm">
                        <option value="piece_identite" selected>Pièce d'identité (CIN)</option>
                    </select>
                </div>

                <div>
                    <label for="document_identite" class="block text-sm font-medium text-slate-700">Télécharger le document</label>
                    <div class="mt-1">
                        <input id="document_identite" name="document_identite" type="file" required accept="image/*,.pdf" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-900 hover:file:bg-blue-100 transition-colors">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full inline-flex justify-center rounded-full bg-blue-900 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-800 transition-colors">
                        Soumettre mes documents
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
