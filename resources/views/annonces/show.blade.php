<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $annonce->titre }} | Accommity</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-white text-slate-900 antialiased h-full flex flex-col min-h-screen">
    <x-header />
    <x-dialogs />

    <main class="flex-grow py-8 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4 tracking-tight">{{ $annonce->titre }}</h1>
            
            <div class="flex items-center text-sm text-slate-600 mb-8 font-medium">
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-1 text-slate-900" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    {{ $annonce->calculerNoteGlobale() }}
                </span>
                <span class="mx-2">·</span>
                <span class="underline hover:text-slate-900 cursor-pointer">{{ $annonce->adresse }}</span>
            </div>

            <!-- Photos -->
            <div class="rounded-2xl overflow-hidden aspect-[21/9] bg-slate-200 mb-12 shadow-sm">
                @if($annonce->photo_url)
                    <img src="{{ $annonce->photo_url }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-slate-400 bg-slate-100">Pas de photo disponible</div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Info Column -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="flex justify-between items-start border-b border-slate-200 pb-8">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900">{{ $annonce->type_logement }} proposé par {{ $annonce->hote->nom }}</h2>
                            <p class="text-slate-600 mt-1">{{ $annonce->capacite }} voyageurs</p>
                        </div>
                        <div class="h-14 w-14 rounded-full bg-slate-200 overflow-hidden flex-shrink-0">
                            <!-- Hote Profile Pic Placeholder -->
                            <svg class="h-full w-full text-slate-400 p-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>

                    <div class="border-b border-slate-200 pb-8">
                        <p class="text-slate-600 leading-relaxed text-base">{{ $annonce->description }}</p>
                    </div>

                    <div class="border-b border-slate-200 pb-8">
                        <h2 class="text-xl font-bold text-slate-900 mb-4">Ce que propose ce logement</h2>
                        <p class="text-slate-600">{{ $annonce->equipements ?? "Standard" }}</p>
                    </div>
                </div>

                <!-- Reservation Widget -->
                <div class="lg:col-span-1">
                    <div class="sticky top-8 bg-white border border-slate-200 shadow-xl rounded-2xl p-6">
                        <div class="flex items-baseline mb-6">
                            <span class="text-2xl font-extrabold text-slate-900">{{ number_format($annonce->tarif_nuit, 2, ',', ' ') }} MAD</span>
                            <span class="text-slate-500 ml-1">/ nuit</span>
                        </div>

                        <form action="{{ route('reservations.store') }}" method="POST" id="reservation-form">
                            @csrf
                            <input type="hidden" name="id_annonce" value="{{ $annonce->id_annonce }}">
                            
                            <div class="border border-slate-300 rounded-xl overflow-hidden mb-4">
                                <div class="flex border-b border-slate-300">
                                    <div class="flex-1 p-3 border-r border-slate-300">
                                        <label class="block text-[10px] font-bold uppercase text-slate-900">Arrivée</label>
                                        <input type="date" name="date_arrivee" id="date_arrivee" required min="{{ date('Y-m-d') }}" class="w-full text-sm outline-none bg-transparent">
                                    </div>
                                    <div class="flex-1 p-3">
                                        <label class="block text-[10px] font-bold uppercase text-slate-900">Départ</label>
                                        <input type="date" name="date_depart" id="date_depart" required class="w-full text-sm outline-none bg-transparent">
                                    </div>
                                </div>
                                <div class="p-3">
                                    <label class="block text-[10px] font-bold uppercase text-slate-900">Voyageurs</label>
                                    <input type="number" name="nb_voyageurs" value="1" min="1" max="{{ $annonce->capacite }}" class="w-full text-sm outline-none bg-transparent pt-1">
                                </div>
                            </div>

                            <div id="price-summary" class="hidden space-y-3 mb-6 animate-in fade-in duration-500">
                                <div class="flex justify-between text-slate-600 text-sm">
                                    <span id="price-details">0 MAD x 0 nuits</span>
                                    <span id="base-price">0 MAD</span>
                                </div>
                                <div class="flex justify-between text-slate-600 text-sm">
                                    <span>Frais de service (10%)</span>
                                    <span id="service-fees">0 MAD</span>
                                </div>
                                <div class="pt-3 border-t border-slate-200 flex justify-between font-bold text-slate-900">
                                    <span>Total</span>
                                    <span id="total-price">0 MAD</span>
                                </div>
                            </div>

                            <button type="submit" class="w-full rounded-xl bg-rose-600 px-4 py-3.5 text-[15px] font-bold text-white shadow-sm hover:bg-rose-700 transition-colors active:scale-[0.98]">
                                @if($annonce->mode_reservation->value == 'réservation instantanée')
                                    Réserver (Instantané)
                                @else
                                    Demander une réservation
                                @endif
                            </button>
                            
                            <p class="text-center text-xs text-slate-500 mt-4">Aucun montant ne vous sera débité pour le moment</p>
                        </form>

                        <script>
                            const checkIn = document.getElementById('date_arrivee');
                            const checkOut = document.getElementById('date_depart');
                            const priceSummary = document.getElementById('price-summary');
                            const priceDetails = document.getElementById('price-details');
                            const basePriceEl = document.getElementById('base-price');
                            const serviceFeesEl = document.getElementById('service-fees');
                            const totalPriceEl = document.getElementById('total-price');
                            
                            const pricePerNight = {{ $annonce->tarif_nuit }};

                            function updatePrice() {
                                if (checkIn.value && checkOut.value) {
                                    const start = new Date(checkIn.value);
                                    const end = new Date(checkOut.value);
                                    
                                    if (end > start) {
                                        const diffTime = Math.abs(end - start);
                                        const nights = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                                        
                                        const basePrice = pricePerNight * nights;
                                        const fees = basePrice * 0.1;
                                        const total = basePrice + fees;

                                        priceDetails.innerText = `${pricePerNight.toLocaleString()} MAD x ${nights} nuits`;
                                        basePriceEl.innerText = `${basePrice.toLocaleString()} MAD`;
                                        serviceFeesEl.innerText = `${fees.toLocaleString()} MAD`;
                                        totalPriceEl.innerText = `${total.toLocaleString()} MAD`;
                                        
                                        priceSummary.classList.remove('hidden');
                                    } else {
                                        priceSummary.classList.add('hidden');
                                    }
                                }
                            }

                            checkIn.addEventListener('change', (e) => {
                                checkOut.min = e.target.value;
                                updatePrice();
                            });
                            checkOut.addEventListener('change', updatePrice);
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
