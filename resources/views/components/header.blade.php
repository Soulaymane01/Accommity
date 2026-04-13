<header class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="/" class="text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-2">
                    <svg class="w-8 h-8 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Accommity
                </a>
            </div>
            
            <!-- Navigation / Buttons -->
            <div class="flex items-center gap-4">
                @guest
                    <a href="{{ route('login') }}" class="text-slate-600 hover:text-blue-900 font-medium transition-colors px-4 py-2 rounded-md">
                        Se connecter
                    </a>
                    <a href="{{ route('register') }}" class="bg-blue-900 hover:bg-blue-800 text-white px-6 py-2.5 rounded-full font-medium transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5">
                        S'inscrire
                    </a>
                @endguest

                @auth
                    <!-- Notifications Bell -->
                    @php
                        $unreadCount = \App\Facades\AppNotification::getNotifications(auth()->user())->where('est_lue', false)->count();
                    @endphp
                    <a href="{{ route('notifications.index') }}" class="relative p-2 text-slate-500 hover:text-blue-900 transition-colors mr-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        @if($unreadCount > 0)
                            <span class="absolute top-1.5 right-1.5 flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500 border-2 border-white"></span>
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('dashboard') }}" class="text-slate-600 hover:text-blue-900 font-medium transition-colors px-4 py-2 rounded-md">
                        Tableau de bord
                    </a>
                    
                    @if(auth()->user()->getRoleUtilisateur() !== 'admin')
                        @if(auth()->user()->getRoleUtilisateur() === 'hote')
                            <a href="{{ route('hote.evaluations.index') }}" class="text-slate-600 hover:text-blue-900 font-medium transition-colors px-4 py-2 rounded-md">
                                Mes Évaluations
                            </a>
                        @else
                            <a href="{{ route('voyageur.evaluations.index') }}" class="text-slate-600 hover:text-blue-900 font-medium transition-colors px-4 py-2 rounded-md">
                                Mes Évaluations
                            </a>
                        @endif
                    @endif

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-slate-100 hover:bg-slate-200 text-slate-900 px-6 py-2.5 rounded-full font-medium transition-all shadow-sm">
                            Déconnexion
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</header>
