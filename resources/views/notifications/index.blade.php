<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mes Notifications - Accommity</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .notification-card { transition: all 0.2s ease-in-out; }
        .notification-card:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01); }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased h-full flex flex-col min-h-screen">
    
    <x-header />

    <!-- Main Content -->
    <main class="flex-grow pb-16">
        <!-- Hero / Title -->
        <div class="bg-gradient-to-tr from-slate-900 to-indigo-950 pt-20 pb-24 shadow-inner">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-4 mb-4">
                    <div class="p-3 bg-white/10 rounded-2xl backdrop-blur-md">
                        <svg class="w-8 h-8 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </div>
                    <h1 class="text-4xl font-extrabold text-white tracking-tight">Vos Notifications</h1>
                </div>
                <p class="text-indigo-200 text-lg font-medium max-w-2xl">Restez à jour sur vos réservations, vos paiements, et les actualités de votre compte Accommity.</p>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10">
            <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
                @if($notifications->isEmpty())
                    <div class="p-16 text-center">
                        <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-slate-50 mb-6">
                            <svg class="h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Vous êtes à jour !</h3>
                        <p class="text-slate-500">Vous n'avez reçu aucune notification pour le moment.</p>
                    </div>
                @else
                    <ul class="divide-y divide-slate-100">
                        @foreach($notifications as $notif)
                            @php
                                $bgColor = $notif->est_lue ? 'bg-white' : 'bg-indigo-50/50';
                                
                                // Déterminer l'icône selon le type
                                $iconUrl = '';
                                $iconColor = '';
                                $iconBg = '';
                                
                                switch($notif->type_alerte->value) {
                                    case 'reservation':
                                        $iconUrl = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>';
                                        $iconColor = 'text-blue-600';
                                        $iconBg = 'bg-blue-100';
                                        break;
                                    case 'paiement':
                                        $iconUrl = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                                        $iconColor = 'text-emerald-600';
                                        $iconBg = 'bg-emerald-100';
                                        break;
                                    case 'avis':
                                        $iconUrl = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>';
                                        $iconColor = 'text-amber-600';
                                        $iconBg = 'bg-amber-100';
                                        break;
                                    case 'rappel':
                                        $iconUrl = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                                        $iconColor = 'text-purple-600';
                                        $iconBg = 'bg-purple-100';
                                        break;
                                    default:
                                        $iconUrl = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                                        $iconColor = 'text-slate-600';
                                        $iconBg = 'bg-slate-100';
                                        break;
                                }
                            @endphp

                            <li class="{{ $bgColor }} p-6 notification-card relative">
                                @if(!$notif->est_lue)
                                    <div class="absolute top-6 right-6 flex items-center">
                                        <span class="relative flex h-3 w-3">
                                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                          <span class="relative inline-flex rounded-full h-3 w-3 bg-indigo-600"></span>
                                        </span>
                                    </div>
                                @endif
                                
                                <div class="flex gap-5">
                                    <div class="flex-shrink-0">
                                        <div class="h-12 w-12 rounded-2xl flex items-center justify-center {{ $iconBg }} shadow-sm">
                                            <svg class="h-6 w-6 {{ $iconColor }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                {!! $iconUrl !!}
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0 pr-8">
                                        <div class="flex items-baseline justify-between mb-1">
                                            <h3 class="text-lg font-bold {{ $notif->est_lue ? 'text-slate-700' : 'text-slate-900' }}">
                                                {{ $notif->titre }}
                                            </h3>
                                            <span class="text-sm font-medium text-slate-400 tabular-nums">
                                                {{ $notif->date_creation->diffForHumans() }}
                                            </span>
                                        </div>
                                        <p class="text-base {{ $notif->est_lue ? 'text-slate-500' : 'text-slate-700 font-medium' }} mb-3">
                                            {{ $notif->contenu }}
                                        </p>
                                        
                                        @if(!$notif->est_lue)
                                            <form action="{{ route('notifications.read', $notif->id_notification) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-1.5 text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Marquer comme lue
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
