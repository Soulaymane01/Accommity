<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Créer une annonce | Accommity</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased h-full flex flex-col min-h-screen">
    <x-header />

    <main class="flex-grow py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-slate-900 mb-8 tracking-tight">Publier une annonce</h1>

            @if($errors->any())
            <div class="mb-8 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl">
                <ul class="list-disc list-inside text-sm font-medium">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('hote.annonces.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8 bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
                @csrf
                
                <div>
                    <h2 class="text-lg font-bold text-slate-900 mb-4 border-b pb-2">Informations Générales</h2>
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700">Titre de l'annonce</label>
                            <input type="text" name="titre" value="{{ old('titre') }}" class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-4 py-3 border" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700">Description détaillée</label>
                            <textarea name="description" rows="4" class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-4 py-3 border" required>{{ old('description') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700">Photo du logement</label>
                            <input type="file" name="photo" class="mt-2 block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all border border-slate-200 p-2 rounded-xl">
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="text-lg font-bold text-slate-900 mb-4 border-b pb-2">Détails du Logement</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700">Type de logement</label>
                            <select name="type_logement" class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-4 py-3 border">
                                <option value="Appartement entier">Appartement entier</option>
                                <option value="Chambre privée">Chambre privée</option>
                                <option value="Villa">Villa</option>
                                <option value="Maison d'hôtes">Maison d'hôtes</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700">Capacité (personnes)</label>
                            <input type="number" name="capacite" value="{{ old('capacite', 1) }}" min="1" class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-4 py-3 border" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700">Adresse</label>
                            <input type="text" name="adresse" value="{{ old('adresse') }}" class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-4 py-3 border" required>
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="text-lg font-bold text-slate-900 mb-4 border-b pb-2">Classification et Règles</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700">Catégorie géographique</label>
                            <select name="id_categorie" class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-4 py-3 border" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id_categorie }}">{{ $cat->ville }} ({{ $cat->pays }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700">Politique d'annulation</label>
                            <select name="id_politique" class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-4 py-3 border" required>
                                @foreach($politiques as $pol)
                                    <option value="{{ $pol->id_politique }}">{{ $pol->type_politique->value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="text-lg font-bold text-slate-900 mb-4 border-b pb-2">Tarification & Modes</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700">Tarif par nuit (MAD)</label>
                            <input type="number" step="0.01" name="tarif_nuit" value="{{ old('tarif_nuit') }}" class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-4 py-3 border" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700">Mode de réservation</label>
                            <select name="mode_reservation" class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-4 py-3 border" required>
                                <option value="réservation instantanée">Instantanée</option>
                                <option value="demande de réservation">Demande</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <a href="{{ route('hote.annonces.index') }}" class="mr-4 inline-flex justify-center items-center rounded-xl bg-white px-6 py-3 text-sm font-bold text-slate-700 shadow-sm border border-slate-300 hover:bg-slate-50">Annuler</a>
                    <button type="submit" class="inline-flex justify-center items-center rounded-xl bg-blue-600 px-8 py-3 text-sm font-bold text-white shadow-sm hover:bg-blue-700 transition-colors">Publier l'annonce</button>
                </div>
            </form>
        </div>
    </main>
    <x-footer />
</body>
</html>
