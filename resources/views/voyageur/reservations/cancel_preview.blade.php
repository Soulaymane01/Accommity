<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmer l'annulation | Accommity</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-slate-900 antialiased min-h-screen flex flex-col">
    <x-header />

    <main class="flex-grow flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full bg-white border border-slate-200 rounded-3xl p-8 shadow-xl">
            <h1 class="text-2xl font-bold text-slate-900 mb-6">Confirmer l'annulation</h1>
            
            <div class="mb-8">
                <p class="text-sm text-slate-600 mb-4">Vous êtes sur le point d'annuler votre réservation pour :</p>
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 mb-6">
                    <p class="font-bold text-slate-900">{{ $reservation->annonce->titre }}</p>
                    <p class="text-xs text-slate-500 mt-1">{{ \Carbon\Carbon::parse($reservation->date_arrivee)->format('d M') }} - {{ \Carbon\Carbon::parse($reservation->date_depart)->format('d M') }}</p>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Montant payé</span>
                        <span class="font-semibold">{{ number_format($details['montant_total'], 2, ',', ' ') }} DH</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Montant à rembourser</span>
                        <span class="font-bold text-emerald-600">{{ number_format($details['montant_remboursement'], 2, ',', ' ') }} DH</span>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-amber-50 rounded-2xl border border-amber-100">
                    <p class="text-xs font-bold text-amber-800 uppercase tracking-wider mb-1">Politique d'annulation</p>
                    <p class="text-sm text-amber-700 leading-relaxed">{{ $details['message'] }}</p>
                </div>
            </div>

            <div class="flex flex-col gap-3">
                <form action="{{ route('reservations.cancel', $reservation->id_reservation) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full rounded-xl bg-rose-600 px-6 py-3.5 font-bold text-white hover:bg-rose-700 transition-colors shadow-lg shadow-rose-200">
                        Confirmer l'annulation
                    </button>
                </form>
                <a href="{{ route('voyageur.reservations.index') }}" class="text-center py-3 text-sm font-semibold text-slate-500 hover:text-slate-900 transition-colors">
                    Garder ma réservation
                </a>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
