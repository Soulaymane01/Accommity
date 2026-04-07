<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Accommity - Hébergements en ligne</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

        <!-- Tailwind CSS (CDN for development, ideally use Vite/NPM) -->
        <script src="https://cdn.tailwindcss.com"></script>
        
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                        },
                    }
                }
            }
        </script>
        <style>
            body { font-family: 'Inter', sans-serif; }
        </style>
    </head>
    <body class="bg-white text-slate-900 antialiased h-full flex flex-col min-h-screen">
        
        <!-- Header Component -->
        <x-header />

    @if(session('success_dialog'))
    <div id="success-dialog-overlay" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl transform transition-all text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-6">
                <svg class="h-8 w-8 text-blue-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Vérification en cours</h3>
            <p class="text-sm text-slate-600 mb-6 font-medium">
                {{ session('success_dialog') }}
            </p>
            <button onclick="document.getElementById('success-dialog-overlay').remove()" class="w-full inline-flex justify-center rounded-full bg-blue-900 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-800 transition-colors">
                J'ai compris
            </button>
        </div>
    </div>
    @endif

        <!-- Main Content -->
        <main class="flex-grow">
            <!-- Hero / Title -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-6">
                <h1 class="text-3xl font-bold text-slate-900 mb-2">Trouvez votre prochain hébergement</h1>
                <p class="text-slate-500">Découvrez des logements uniques pour vos vacances, ou devenez hôte.</p>
            </div>

            <!-- Annonces Grid -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 lg:gap-x-8 xl:grid-cols-5 gap-y-10 gap-x-6">
                    
                    @foreach ($annonces as $annonce)
                        <x-annonce-card 
                            :image="$annonce->photo_url ?? '/images/annonces/1.jpg'"
                            :type="$annonce->type_logement"
                            :ville="$annonce->ville ?? $annonce->adresse"
                            :prix="$annonce->tarif_nuit"
                            note="4.8"
                        />
                    @endforeach

                </div>
            </div>

            <!-- Features Section -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-12 mt-12 border-t border-slate-100">
                <div class="text-center mb-12">
                    <h2 class="text-2xl font-bold text-slate-900">Pourquoi choisir Accommity ?</h2>
                    <p class="text-slate-500 mt-2">Votre confort et votre sécurité sont nos priorités absolues.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <x-feature-card 
                        icon='<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>'
                        title="Paiement Sécurisé"
                        description="Tous vos paiements sont traités via un système hautement sécurisé pour vous garantir une tranquillité d'esprit totale."
                    />
                    
                    <x-feature-card 
                        icon='<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>'
                        title="Hébergements Vérifiés"
                        description="Chaque annonce et hôte subit une vérification rigoureuse pour garantir la qualité et l'exactitude des logements."
                    />

                    <x-feature-card 
                        icon='<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>'
                        title="Support Client 24/7"
                        description="Notre équipe est disponible 24h/24 et 7j/7 pour répondre à toutes vos questions et vous accompagner lors de votre séjour."
                    />
                </div>
            </div>
            
        </main>

        <!-- Footer Component -->
        <x-footer />
        
        <script>
            window.addEventListener('pageshow', function (event) {
                if (event.persisted) {
                    window.location.reload();
                }
            });
        </script>
    </body>
</html>
