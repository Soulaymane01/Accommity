<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Paiement - Accommity</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .method-card input[type="radio"]:checked ~ .method-label {
            border-color: #1e40af;
            background-color: #eff6ff;
        }
        .card-field { transition: border-color 0.2s; }
        .card-field:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.15); }
        #paypal-section, #card-section { display: none; }
    </style>
</head>
<body class="bg-slate-100 text-slate-900 antialiased min-h-screen flex flex-col">
    <x-header />

    <main class="flex-grow flex items-start justify-center py-12 px-4">
        <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-5 gap-8 items-start">

            {{-- LEFT: Payment Form --}}
            <div class="lg:col-span-3">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8">
                    <h1 class="text-2xl font-extrabold text-slate-900 mb-1">Paiement</h1>
                    <p class="text-slate-500 text-sm mb-8">Complétez votre paiement en toute sécurité.</p>

                    <form id="payment-form" method="POST" action="{{ route('reservations.payment.process', $reservation->id_reservation) }}">
                        @csrf

                        {{-- Payment Method Selection --}}
                        <div class="mb-8">
                            <h2 class="font-bold text-slate-700 text-sm uppercase tracking-widest mb-4">Méthode de paiement</h2>
                            <div class="grid grid-cols-2 gap-4">

                                {{-- Carte Bancaire --}}
                                <label class="method-card relative cursor-pointer">
                                    <input type="radio" name="methode_paiement" value="carte_bancaire" class="sr-only" onchange="switchMethod('card')" checked>
                                    <div class="method-label border-2 border-blue-600 bg-blue-50 rounded-2xl p-4 flex flex-col items-center gap-2 transition-all">
                                        <svg class="w-8 h-8 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                        <span class="font-bold text-sm text-blue-900">Carte Bancaire</span>
                                    </div>
                                </label>

                                {{-- PayPal --}}
                                <label class="method-card relative cursor-pointer">
                                    <input type="radio" name="methode_paiement" value="paypal" class="sr-only" onchange="switchMethod('paypal')">
                                    <div class="method-label border-2 border-slate-200 bg-white rounded-2xl p-4 flex flex-col items-center gap-2 transition-all">
                                        <svg class="w-8 h-8 text-[#003087]" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.968.382-1.05.9l-1.12 7.106zm14.146-14.42a3.35 3.35 0 0 0-.607-.541c-.013.076-.026.175-.041.254-.59 3.025-2.566 6.082-8.558 6.082H9.925l-1.28 8.107h3.504c.46 0 .85-.333.921-.789l.038-.2.733-4.646.048-.254c.071-.457.461-.79.921-.79h.58c3.756 0 6.695-1.526 7.552-5.938.354-1.81.172-3.322-.72-4.285z"/>
                                        </svg>
                                        <span class="font-bold text-sm text-[#003087]">PayPal</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Credit Card Details --}}
                        <div id="card-section" class="space-y-5 mb-8" style="display:block;">
                            <h2 class="font-bold text-slate-700 text-sm uppercase tracking-widest mb-2">Détails de la Carte</h2>

                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Nom sur la carte</label>
                                <input type="text" name="nom_carte" placeholder="Jean Dupont"
                                    class="card-field w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-900 bg-slate-50">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Numéro de carte</label>
                                <input type="text" name="numero_carte" placeholder="1234 5678 9012 3456" maxlength="19"
                                    oninput="formatCard(this)"
                                    class="card-field w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-900 bg-slate-50 tracking-widest font-mono">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-600 mb-1.5">Date d'expiration</label>
                                    <input type="text" name="expiration" placeholder="MM/AA" maxlength="5"
                                        oninput="formatExpiry(this)"
                                        class="card-field w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-900 bg-slate-50">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-600 mb-1.5">CVV</label>
                                    <input type="text" name="cvv" placeholder="123" maxlength="4"
                                        class="card-field w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-900 bg-slate-50">
                                </div>
                            </div>
                        </div>

                        {{-- PayPal Section --}}
                        <div id="paypal-section" class="mb-8 bg-blue-50 border border-blue-200 rounded-2xl p-6 text-center">
                            <svg class="w-16 h-16 text-[#003087] mx-auto mb-3" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.968.382-1.05.9l-1.12 7.106zm14.146-14.42a3.35 3.35 0 0 0-.607-.541c-.013.076-.026.175-.041.254-.59 3.025-2.566 6.082-8.558 6.082H9.925l-1.28 8.107h3.504c.46 0 .85-.333.921-.789l.038-.2.733-4.646.048-.254c.071-.457.461-.79.921-.79h.58c3.756 0 6.695-1.526 7.552-5.938.354-1.81.172-3.322-.72-4.285z"/>
                            </svg>
                            <p class="text-[#003087] font-semibold">Vous serez redirigé vers PayPal pour compléter votre paiement en toute sécurité.</p>
                            <p class="text-slate-400 text-xs mt-2">(Simulation — aucune redirection réelle)</p>
                        </div>

                        {{-- Security Notice --}}
                        <div class="flex items-center gap-2 text-slate-400 text-xs mb-6">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Paiement sécurisé par chiffrement SSL 256-bit. Vos données bancaires ne sont jamais stockées.
                        </div>

                        <button type="submit"
                            class="w-full bg-blue-900 hover:bg-blue-800 text-white font-bold py-4 rounded-2xl text-lg transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            Payer {{ number_format($reservation->montant_total, 2) }} DH
                        </button>

                        <a href="{{ route('voyageur.reservations.index') }}" class="block text-center text-sm text-slate-400 hover:text-slate-600 mt-4">
                            Annuler et retourner à mes voyages
                        </a>
                    </form>
                </div>
            </div>

            {{-- RIGHT: Booking Summary --}}
            <div class="lg:col-span-2 sticky top-8">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                    {{-- Annonce Photo --}}
                    <div class="h-48 w-full overflow-hidden">
                        <img src="{{ $reservation->annonce->photo_url }}" alt="{{ $reservation->annonce->titre }}" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6">
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-1">{{ $reservation->annonce->type_logement }}</p>
                        <h3 class="font-bold text-slate-900 text-lg leading-snug mb-1">{{ $reservation->annonce->titre }}</h3>
                        <p class="text-slate-500 text-sm mb-4">📍 {{ $reservation->annonce->adresse }}</p>

                        <div class="bg-slate-50 rounded-2xl p-4 space-y-2.5 text-sm mb-5">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Arrivée</span>
                                <span class="font-semibold">{{ \Carbon\Carbon::parse($reservation->date_arrivee)->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Départ</span>
                                <span class="font-semibold">{{ \Carbon\Carbon::parse($reservation->date_depart)->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Voyageurs</span>
                                <span class="font-semibold">{{ $reservation->nb_voyageurs }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Mode</span>
                                <span class="font-semibold">{{ $reservation->mode_reservation->value === 'réservation instantanée' ? '⚡ Instantanée' : '📋 Sur demande' }}</span>
                            </div>
                        </div>

                        <div class="border-t border-slate-100 pt-4 space-y-2 text-sm">
                            @php
                                $nights = \Carbon\Carbon::parse($reservation->date_arrivee)->diffInDays($reservation->date_depart);
                                $base = $reservation->montant_total - $reservation->frais_service;
                            @endphp
                            <div class="flex justify-between text-slate-500">
                                <span>{{ number_format($reservation->annonce->tarif_nuit, 2) }} DH × {{ $nights }} nuit(s)</span>
                                <span>{{ number_format($base, 2) }} DH</span>
                            </div>
                            <div class="flex justify-between text-slate-500">
                                <span>Frais de service (10%)</span>
                                <span>{{ number_format($reservation->frais_service, 2) }} DH</span>
                            </div>
                            <div class="flex justify-between font-bold text-slate-900 text-base border-t border-slate-100 pt-3 mt-1">
                                <span>Total</span>
                                <span>{{ number_format($reservation->montant_total, 2) }} DH</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <x-footer />

    <script>
        function switchMethod(method) {
            const cardSection = document.getElementById('card-section');
            const paypalSection = document.getElementById('paypal-section');

            // Update labels
            document.querySelectorAll('.method-label').forEach(el => {
                el.classList.remove('border-blue-600', 'bg-blue-50');
                el.classList.add('border-slate-200', 'bg-white');
            });
            const selected = document.querySelector('input[name="methode_paiement"]:checked');
            if (selected) {
                selected.nextElementSibling.classList.remove('border-slate-200', 'bg-white');
                selected.nextElementSibling.classList.add('border-blue-600', 'bg-blue-50');
            }

            if (method === 'card') {
                cardSection.style.display = 'block';
                paypalSection.style.display = 'none';
            } else {
                cardSection.style.display = 'none';
                paypalSection.style.display = 'block';
            }
        }

        function formatCard(input) {
            let val = input.value.replace(/\D/g, '').substring(0, 16);
            input.value = val.replace(/(.{4})/g, '$1 ').trim();
        }

        function formatExpiry(input) {
            let val = input.value.replace(/\D/g, '').substring(0, 4);
            if (val.length >= 2) val = val.substring(0,2) + '/' + val.substring(2);
            input.value = val;
        }

        // Init — make sure carte_bancaire label is highlighted on load
        document.addEventListener('DOMContentLoaded', () => switchMethod('card'));
    </script>
</body>
</html>
