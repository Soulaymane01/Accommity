<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Photo de profil - Accommity</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">

    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-12 px-8 shadow-sm border border-slate-100 sm:rounded-2xl text-center">
            
            <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-slate-100 border-2 border-dashed border-slate-300 mb-6 overflow-hidden">
                <svg class="h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            
            <h2 class="text-2xl font-bold tracking-tight text-slate-900 mb-2">Ajoutez une photo</h2>
            <p class="text-slate-600 mb-8 leading-relaxed">
                Personnalisez votre profil en ajoutant une photo. Vous pouvez sauter cette étape et l'ajouter plus tard.
            </p>

            <form action="{{ route('register.photo.submit') }}" method="POST" enctype="multipart/form-data" class="mt-4 text-left space-y-4">
                @csrf
                
                <div>
                    <input id="photo_profil" name="photo_profil" type="file" required accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-900 hover:file:bg-blue-100 transition-colors">
                    @error('photo_profil') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4 flex flex-col gap-3">
                    <button type="submit" class="w-full inline-flex justify-center rounded-full bg-blue-900 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-800 transition-colors">
                        Enregistrer la photo
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
