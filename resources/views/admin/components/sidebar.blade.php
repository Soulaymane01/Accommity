<div class="fixed inset-y-0 flex w-64 flex-col bg-slate-900 pb-4 flex-shrink-0">
    <!-- Sidebar Header -->
    <div class="flex h-16 shrink-0 items-center px-6 border-b border-white/10">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 text-white">
            <svg class="h-8 w-8 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <span class="text-xl font-bold tracking-tight">AdminHub</span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="mt-6 flex-1 px-3" aria-label="Sidebar">
        <!-- Main Dashboard -->
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-2 py-2.5 text-sm font-medium rounded-md mb-1 transition-colors">
            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Vue d'ensemble
        </a>

        <!-- Utilisateurs -->
        <a href="{{ route('admin.utilisateurs.index') }}" class="{{ request()->routeIs('admin.utilisateurs.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-2 py-2.5 text-sm font-medium rounded-md mb-1 transition-colors">
            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            Utilisateurs
        </a>

        <!-- Annonces -->
        <a href="{{ route('admin.annonces.index') }}" class="{{ request()->routeIs('admin.annonces.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-2 py-2.5 text-sm font-medium rounded-md mb-1 transition-colors">
            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            Annonces
        </a>

        <!-- Reservations -->
        <a href="{{ route('admin.reservations.index') }}" class="{{ request()->routeIs('admin.reservations.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-2 py-2.5 text-sm font-medium rounded-md mb-1 transition-colors">
            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Réservations
        </a>

        <!-- Avis signalés -->
        <a href="{{ route('admin.avis_signales.index') }}" class="{{ request()->routeIs('admin.avis_signales.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-2 py-2.5 text-sm font-medium rounded-md mb-1 transition-colors">
            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            Avis Signalés
        </a>

        <!-- Litiges -->
        <a href="{{ route('admin.litiges.index') }}" class="{{ request()->routeIs('admin.litiges.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-2 py-2.5 text-sm font-medium rounded-md mb-1 transition-colors">
            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
            </svg>
            Ticket Litiges
        </a>

        <!-- Transactions Dropdown Group (Simulated) -->
        <div class="mt-4">
            <p class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2" id="transactions-headline">
                Transactions
            </p>
            <div class="space-y-1" aria-labelledby="transactions-headline">
                <a href="{{ route('admin.transactions.paiements') }}" class="{{ request()->routeIs('admin.transactions.paiements') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors">
                    Paiements
                </a>
                <a href="{{ route('admin.transactions.versements') }}" class="{{ request()->routeIs('admin.transactions.versements') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors">
                    Versements
                </a>
                <a href="{{ route('admin.transactions.remboursements') }}" class="{{ request()->routeIs('admin.transactions.remboursements') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors">
                    Remboursements
                </a>
            </div>
        </div>

    </nav>
</div>
