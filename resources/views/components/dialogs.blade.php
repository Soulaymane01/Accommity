<!-- Success Dialog -->
@if(session('success_dialog'))
<div id="success-dialog-overlay" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white rounded-3xl p-8 max-w-md w-full mx-4 shadow-2xl transform transition-all text-center">
        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-emerald-50 mb-6 border-8 border-emerald-100">
            <svg class="h-10 w-10 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <h3 class="text-2xl font-bold text-slate-900 mb-3 tracking-tight">Succès</h3>
        <p class="text-[15px] leading-relaxed text-slate-600 mb-8 font-medium">
            {{ session('success_dialog') }}
        </p>
        <button onclick="document.getElementById('success-dialog-overlay').remove()" class="w-full inline-flex justify-center items-center gap-2 rounded-xl bg-emerald-600 px-6 py-3.5 text-[15px] font-bold text-white shadow-sm hover:bg-emerald-700 transition-all active:scale-[0.98]">
            Continuer
        </button>
    </div>
</div>
@endif

<!-- Error Dialog -->
@if(session('error_dialog'))
<div id="error-dialog-overlay" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/70 backdrop-blur-md">
    <div class="bg-white rounded-3xl p-8 max-w-md w-full mx-4 shadow-2xl transform transition-all text-center border-t-8 border-rose-500">
        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-rose-50 mb-6 border-8 border-rose-100">
            <svg class="h-10 w-10 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <h3 class="text-2xl font-black text-rose-600 mb-3 uppercase tracking-tight">Erreur</h3>
        <div class="bg-rose-50/50 rounded-xl p-4 mb-8">
            <p class="text-[15px] leading-relaxed text-rose-900 font-semibold">
                {{ session('error_dialog') }}
            </p>
        </div>
        <button onclick="document.getElementById('error-dialog-overlay').remove()" class="w-full inline-flex justify-center items-center gap-2 rounded-xl bg-rose-600 px-6 py-3.5 text-[15px] font-bold text-white shadow-sm hover:bg-rose-700 transition-all active:scale-[0.98]">
            Fermer
        </button>
    </div>
</div>
@endif
