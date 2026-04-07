@extends('admin.layouts.app')

@section('header_title', 'Vue Globale des Statistiques')

@section('content')

    <!-- Section Utilisateurs -->
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-slate-800 mb-4 border-b pb-2">Utilisateurs de la plateforme</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-indigo-100 p-6 flex items-center justify-between">
                <div>
                    <span class="text-sm font-medium text-slate-500 block">Nombre d'Hôtes</span>
                    <span class="text-3xl font-bold text-indigo-700">{{ $statsUtilisateurs['hotes'] }}</span>
                </div>
                <div class="h-12 w-12 rounded-full bg-indigo-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-emerald-100 p-6 flex items-center justify-between">
                <div>
                    <span class="text-sm font-medium text-slate-500 block">Nombre de Voyageurs</span>
                    <span class="text-3xl font-bold text-emerald-700">{{ $statsUtilisateurs['voyageurs'] }}</span>
                </div>
                <div class="h-12 w-12 rounded-full bg-emerald-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Annonces -->
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-slate-800 mb-4 border-b pb-2">Suivi des Annonces</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                <span class="text-xs font-semibold text-slate-500 block uppercase tracking-wider">Total Annonces</span>
                <span class="text-2xl font-bold text-slate-800">{{ $statsAnnonces['total'] }}</span>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-emerald-100 p-6">
                <span class="text-xs font-semibold text-emerald-500 block uppercase tracking-wider">Publiées</span>
                <span class="text-2xl font-bold text-emerald-700">{{ $statsAnnonces['publiees'] }}</span>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-amber-100 p-6">
                <span class="text-xs font-semibold text-amber-500 block uppercase tracking-wider">En Attente</span>
                <span class="text-2xl font-bold text-amber-700">{{ $statsAnnonces['en_attente'] }}</span>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-red-100 p-6">
                <span class="text-xs font-semibold text-red-500 block uppercase tracking-wider">Suspendues</span>
                <span class="text-2xl font-bold text-red-700">{{ $statsAnnonces['suspendues'] }}</span>
            </div>
        </div>
    </div>

    <!-- Section Reservations -->
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-slate-800 mb-4 border-b pb-2">État des Réservations</h2>
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-blue-100 p-5 text-center">
                <span class="text-2xl font-bold text-blue-700 block">{{ $statsReservations['en_cours'] }}</span>
                <span class="text-xs font-medium text-slate-500 uppercase">En cours</span>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-emerald-100 p-5 text-center">
                <span class="text-2xl font-bold text-emerald-700 block">{{ $statsReservations['confirmees'] }}</span>
                <span class="text-xs font-medium text-slate-500 uppercase">Confirmées</span>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 text-center">
                <span class="text-2xl font-bold text-slate-700 block">{{ $statsReservations['terminees'] }}</span>
                <span class="text-xs font-medium text-slate-500 uppercase">Terminées</span>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-amber-100 p-5 text-center">
                <span class="text-2xl font-bold text-amber-600 block">{{ $statsReservations['annulees'] }}</span>
                <span class="text-xs font-medium text-slate-500 uppercase">Annulées</span>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-red-100 p-5 text-center">
                <span class="text-2xl font-bold text-red-600 block">{{ $statsReservations['refusees'] }}</span>
                <span class="text-xs font-medium text-slate-500 uppercase">Refusées</span>
            </div>
        </div>
    </div>

    <!-- Section Transactions -->
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-slate-800 mb-4 border-b pb-2">Transactions Financières</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-emerald-200 p-6 flex flex-col justify-between">
                <div>
                    <span class="text-sm font-medium text-emerald-800 block mb-1">Paiements Effectués</span>
                    <span class="text-3xl font-bold text-emerald-600">{{ number_format($statsTransactions['paiements_total'], 2, ',', ' ') }} DH</span>
                </div>
                <div class="mt-4 text-xs font-medium text-emerald-600 bg-emerald-50 self-start px-2 py-1 rounded-full">
                    {{ $statsTransactions['paiements_count'] }} transactions
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-indigo-200 p-6 flex flex-col justify-between">
                <div>
                    <span class="text-sm font-medium text-indigo-800 block mb-1">Versements Hôtes</span>
                    <span class="text-3xl font-bold text-indigo-600">{{ number_format($statsTransactions['versements_total'], 2, ',', ' ') }} DH</span>
                </div>
                <div class="mt-4 text-xs font-medium text-indigo-600 bg-indigo-50 self-start px-2 py-1 rounded-full">
                    {{ $statsTransactions['versements_count'] }} versements
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-amber-200 p-6 flex flex-col justify-between">
                <div>
                    <span class="text-sm font-medium text-amber-800 block mb-1">Remboursements</span>
                    <span class="text-3xl font-bold text-amber-600">{{ number_format($statsTransactions['remboursements_total'], 2, ',', ' ') }} DH</span>
                </div>
                <div class="mt-4 text-xs font-medium text-amber-600 bg-amber-50 self-start px-2 py-1 rounded-full">
                    {{ $statsTransactions['remboursements_count'] }} remboursements
                </div>
            </div>
        </div>
    </div>

    <!-- Moderation et Litiges -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        
        <div>
            <h2 class="text-lg font-semibold text-slate-800 mb-4 border-b pb-2">Avis Signalés</h2>
            <div class="bg-white rounded-xl shadow-sm border border-rose-200 p-6 flex items-center justify-between">
                <div>
                    <span class="text-sm font-medium text-rose-800 block mb-1">Nombre d'avis signalés à modérer</span>
                    <span class="text-3xl font-bold text-rose-600">{{ $statsAvis['signales'] }}</span>
                </div>
                <div class="h-12 w-12 rounded-full bg-rose-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
        </div>

        <div>
            <h2 class="text-lg font-semibold text-slate-800 mb-4 border-b pb-2">Gestion des Litiges</h2>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 grid grid-cols-3 gap-2 text-center divide-x divide-slate-100">
                <div>
                    <span class="text-2xl font-bold text-blue-600 block">{{ $statsLitiges['en_cours'] }}</span>
                    <span class="text-xs font-medium text-slate-500 uppercase">En cours</span>
                </div>
                <div>
                    <span class="text-2xl font-bold text-emerald-600 block">{{ $statsLitiges['resolus'] }}</span>
                    <span class="text-xs font-medium text-slate-500 uppercase">Résolus</span>
                </div>
                <div>
                    <span class="text-2xl font-bold text-slate-700 block">{{ $statsLitiges['clotures'] }}</span>
                    <span class="text-xs font-medium text-slate-500 uppercase">Clôturés</span>
                </div>
            </div>
        </div>

    </div>

@endsection
