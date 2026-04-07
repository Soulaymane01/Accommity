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
                    <a href="{{ route('dashboard') }}" class="text-slate-600 hover:text-blue-900 font-medium transition-colors px-4 py-2 rounded-md">
                        Tableau de bord
                    </a>
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
