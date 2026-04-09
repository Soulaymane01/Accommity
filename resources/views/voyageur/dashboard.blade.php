<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mon Tableau de Bord | Accommity</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen flex flex-col">
    <x-header />
    <x-dialogs />

    <main class="flex-grow max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-10">
        <div class="mb-10">
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Bonjour, {{ auth()->user()->prenom }} !</h1>
            <p class="text-slate-500">Prêt pour votre prochaine aventure ?</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Main Content Area -->
            <div class="lg:col-span-2 space-y-12">
                <!-- Upcoming Trips -->
                <section>
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-slate-900">Mes prochains voyages</h2>
                        <a href="{{ route('voyageur.reservations.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Voir tout</a>
                    </div>

                    @if($prochainsVoyages->isEmpty())
                        <div class="bg-white border border-slate-200 rounded-3xl p-10 text-center shadow-sm">
                            <div class="mx-auto h-16 w-16 bg-blue-50 rounded-full flex items-center justify-center mb-4">
                                <svg class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900">Pas encore de voyage prévu</h3>
                            <p class="text-slate-500 mt-2 mb-6">Découvrez des milliers d'endroits incroyables à visiter.</p>
                            <a href="{{ route('annonces.index') }}" class="inline-flex rounded-xl bg-slate-900 px-6 py-3 font-bold text-white hover:bg-slate-800 transition-colors">
                                Commencer à explorer
                            </a>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($prochainsVoyages as $voyage)
                                <div class="bg-white border border-slate-200 rounded-3xl p-5 flex gap-6 hover:shadow-md transition-shadow">
                                    <div class="h-24 w-24 rounded-2xl bg-slate-200 overflow-hidden flex-shrink-0">
                                        <img src="{{ $voyage->annonce->photo_url ?: '/images/annonces/1.jpg' }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-grow flex flex-col justify-center">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h3 class="font-bold text-slate-900">{{ $voyage->annonce->titre }}</h3>
                                                <p class="text-sm text-slate-500">{{ \Carbon\Carbon::parse($voyage->date_arrivee)->format('d M.') }} - {{ \Carbon\Carbon::parse($voyage->date_depart)->format('d M. Y') }}</p>
                                            </div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 uppercase tracking-wider">
                                                {{ $voyage->statut->value }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>

                <!-- Recommendations -->
                <section>
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-slate-900">Recommandé pour vous</h2>
                        <a href="{{ route('annonces.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Voir toutes les annonces</a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        @foreach($recommandations as $recommandation)
                            <x-annonce-card 
                                :id="$recommandation->id_annonce"
                                :titre="$recommandation->titre"
                                :image="$recommandation->photo_url ?: '/images/annonces/1.jpg'"
                                :type="$recommandation->type_logement"
                                :ville="$recommandation->adresse"
                                :prix="$recommandation->tarif_nuit"
                                note="New"
                            />
                        @endforeach
                    </div>
                </section>

                <!-- Mes Paiements -->
                <section>
                    <h2 class="text-2xl font-bold text-slate-900 mb-6">Mes Paiements</h2>
                    @if($paiements->isEmpty())
                        <div class="bg-white border border-slate-200 rounded-2xl p-8 text-center text-slate-500">
                            Aucun paiement enregistré.
                        </div>
                    @else
                        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                            <table class="w-full text-sm">
                                <thead class="bg-slate-50 text-slate-400 text-xs uppercase tracking-wider">
                                    <tr>
                                        <th class="px-4 py-3 text-left">Annonce</th>
                                        <th class="px-4 py-3 text-right">Montant</th>
                                        <th class="px-4 py-3 text-center">Méthode</th>
                                        <th class="px-4 py-3 text-center">Statut</th>
                                        <th class="px-4 py-3 text-right">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($paiements as $paiement)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-4 py-3 font-medium text-slate-800">
                                            {{ $paiement->reservation?->annonce?->titre ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3 text-right font-bold text-slate-900">
                                            {{ number_format($paiement->montant, 2) }} DH
                                        </td>
                                        <td class="px-4 py-3 text-center text-slate-500 capitalize">
                                            {{ str_replace('_', ' ', $paiement->methode_paiement instanceof \App\Enums\MethodePaiement ? $paiement->methode_paiement->value : $paiement->methode_paiement) }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span @class([
                                                'px-2 py-0.5 rounded-full text-xs font-bold uppercase',
                                                'bg-emerald-100 text-emerald-700' => ($paiement->statut->value ?? '') === 'reussi',
                                                'bg-blue-100 text-blue-700'       => ($paiement->statut->value ?? '') === 'rembourse',
                                                'bg-amber-100 text-amber-700'     => ($paiement->statut->value ?? '') === 'en_attente',
                                                'bg-red-100 text-red-700'         => ($paiement->statut->value ?? '') === 'echoue',
                                            ])>
                                                {{ match($paiement->statut->value ?? '') {
                                                    'reussi'     => 'Réussi',
                                                    'rembourse'  => 'Remboursé',
                                                    'en_attente' => 'En attente',
                                                    'echoue'     => 'Échoué',
                                                    default      => $paiement->statut->value ?? '',
                                                } }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-right text-slate-400 text-xs">
                                            {{ \Carbon\Carbon::parse($paiement->date_transaction)->format('d M Y') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </section>

                <!-- Mes Remboursements -->
                <section>
                    <h2 class="text-2xl font-bold text-slate-900 mb-6">Mes Remboursements</h2>
                    @if($remboursements->isEmpty())
                        <div class="bg-white border border-slate-200 rounded-2xl p-8 text-center text-slate-500">
                            Aucun remboursement enregistré.
                        </div>
                    @else
                        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                            <table class="w-full text-sm">
                                <thead class="bg-slate-50 text-slate-400 text-xs uppercase tracking-wider">
                                    <tr>
                                        <th class="px-4 py-3 text-left">Annonce</th>
                                        <th class="px-4 py-3 text-right">Montant</th>
                                        <th class="px-4 py-3 text-center">Motif</th>
                                        <th class="px-4 py-3 text-right">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($remboursements as $remb)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-4 py-3 font-medium text-slate-800">
                                            {{ $remb->reservation?->annonce?->titre ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3 text-right font-bold text-blue-600">
                                            +{{ number_format($remb->montant, 2) }} DH
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-700 uppercase">
                                                {{ match($remb->motif instanceof \App\Enums\MotifRemboursement ? $remb->motif->value : $remb->motif) {
                                                    'annulation_voyageur' => 'Annulation Voyageur',
                                                    'annulation_hote'     => 'Annulation Hôte',
                                                    'expiration_demande'  => 'Demande Expirée',
                                                    default               => $remb->motif,
                                                } }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-right text-slate-400 text-xs">
                                            {{ \Carbon\Carbon::parse($remb->date_remboursement)->format('d M Y') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </section>

            </div>

            <!-- Sidebar / Stats -->
            <div class="lg:col-span-1 space-y-8">
                <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm">
                    <h3 class="font-bold text-slate-900 mb-6 uppercase text-xs tracking-widest text-slate-400">Mon Activité</h3>
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-slate-900">{{ auth()->user()->reservations->count() }}</p>
                                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Réservations totales</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-slate-900">0</p>
                                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Avis laisses</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-900 to-indigo-900 rounded-3xl p-8 text-white shadow-xl">
                    <h3 class="font-bold text-xl mb-3">Devenez hôte !</h3>
                    <p class="text-blue-100 text-sm mb-6 leading-relaxed">Mettez votre logement en location et commencez à gagner de l'argent dès aujourd'hui.</p>
                    <a href="{{ route('hote.annonces.create') }}" class="w-full inline-flex justify-center bg-white text-blue-900 font-bold py-3 rounded-xl hover:bg-blue-50 transition-colors shadow-lg">
                        En savoir plus
                    </a>
                </div>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
