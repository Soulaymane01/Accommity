<header class="bg-white shadow-sm h-16 shrink-0 flex items-center justify-between px-6 z-10 relative">
    <div class="flex items-center">
        <!-- Mobile Sidebar Toggle -->
        <button class="mr-4 text-slate-500 hover:text-slate-700 md:hidden">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <h1 class="text-xl font-bold text-slate-800">
            @yield('header_title', 'Dashboard')
        </h1>
    </div>

    <!-- User Dropdown & Icons -->
    <div class="flex items-center gap-4">
        <span class="text-sm font-medium text-slate-600 block">
            Admimistrateur Connecté
        </span>
        <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold border border-indigo-200">
            AD
        </div>
        
        <form method="POST" action="{{ route('admin.logout') }}" class="ml-4">
            @csrf
            <button type="submit" class="text-sm font-semibold text-rose-600 hover:text-rose-800 flex items-center gap-1 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Déconnexion
            </button>
        </form>
    </div>
</header>
