<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mes Évaluations | Accommity</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col">

    <x-header />
    <x-dialogs />

    <main class="flex-grow max-w-5xl mx-auto w-full px-4 py-10">
        
        <div class="mb-10">
            <h1 class="text-3xl font-black text-slate-900">Mes Évaluations</h1>
            <p class="text-slate-500 mt-2">Partagez votre expérience et regardez ce que les autres pensent de vous.</p>
        </div>

        @if(session('success_dialog'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl shadow-sm">
                <p class="font-bold">Information</p>
                <p>{{ session('success_dialog') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-xl shadow-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Tabs -->
        <div class="flex space-x-4 mb-8 border-b border-slate-200">
            <a href="{{ route('voyageur.evaluations.index', ['tab' => 'donnees']) }}" 
               class="pb-4 px-2 {{ $tab === 'donnees' ? 'border-b-2 border-blue-600 text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-700 font-medium' }}">
                Avis rédigés (Auteur)
            </a>
            <a href="{{ route('voyageur.evaluations.index', ['tab' => 'recues']) }}" 
               class="pb-4 px-2 {{ $tab === 'recues' ? 'border-b-2 border-blue-600 text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-700 font-medium' }}">
                Avis reçus (Cible)
            </a>
        </div>

        <!-- Contenu des Tabs -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8">
            
            @if($tab === 'donnees')
                <h2 class="text-xl font-bold mb-6 text-slate-800">Évaluations que vous avez partagées</h2>
                
                @forelse($evaluationsDonnees as $eval)
                    <div class="mb-6 p-6 border border-slate-100 bg-slate-50 rounded-2xl relative">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-bold text-slate-900">Annonce : {{ $eval->annonce ? $eval->annonce->titre : 'Annonce supprimée' }}</h3>
                                <p class="text-xs text-slate-500">Laissé à : {{ $eval->cible ? $eval->cible->prenom . ' ' . $eval->cible->nom : 'Utilisateur inconnu' }} • Modifié le {{ optional($eval->date_modification ?? $eval->date_creation)->format('d M Y') }}</p>
                            </div>
                            <div class="bg-blue-600 text-white font-black text-xl px-3 py-1 rounded-xl shadow-sm">{{ $eval->note }} <span class="text-sm font-normal text-blue-200">/ 5</span></div>
                        </div>
                        <p class="text-slate-700 italic border-l-4 border-slate-200 pl-4 py-1 mb-4 text-sm whitespace-pre-wrap">"{{ $eval->commentaire }}"</p>
                        
                        <div class="flex gap-4 border-t border-slate-200 pt-4 mt-4">
                            <a href="{{ route('voyageur.evaluations.index', ['tab' => 'donnees', 'action' => 'edit', 'id' => $eval->id_evaluation]) }}" 
                               class="text-sm font-bold text-blue-600 hover:text-blue-800 transition">
                                Modifier l'évaluation
                            </a>
                            <form method="POST" action="{{ route('voyageur.evaluations.destroy', $eval->id_evaluation) }}" onsubmit="return confirm('Supprimer cet avis définitivement ?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-sm font-bold text-red-500 hover:text-red-700 transition">Supprimer</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 text-slate-500">
                        Vous n'avez rédigé aucun avis pour le moment. Allez dans vos voyages terminés pour laisser un avis !
                    </div>
                @endforelse
                
                <div class="mt-4">{{ $evaluationsDonnees->links() }}</div>

            @else
                <!-- SECTION REÇUES -->
                <h2 class="text-xl font-bold mb-6 text-slate-800">Évaluations laissées sur votre profil</h2>
                
                @forelse($evaluationsRecues as $eval)
                    <div class="mb-6 p-6 border border-slate-100 bg-slate-50 rounded-2xl">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 bg-slate-200 rounded-full flex items-center justify-center font-bold text-slate-500">
                                    {{ substr(optional($eval->auteur)->prenom ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-900">{{ optional($eval->auteur)->prenom }} {{ optional($eval->auteur)->nom }}</h3>
                                    <p class="text-xs text-slate-500">{{ optional($eval->date_creation)->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="bg-blue-100 text-blue-800 font-black text-xl px-3 py-1 rounded-xl">{{ $eval->note }} <span class="text-sm font-normal">/ 5</span></div>
                        </div>
                        <p class="text-slate-700 italic text-sm mb-4">"{{ $eval->commentaire }}"</p>
                        
                        <div class="border-t border-slate-200 pt-4 mt-4 flex justify-end">
                            @if($eval->est_signale)
                                <span class="text-xs font-bold text-red-500 bg-red-50 px-3 py-1 rounded-full border border-red-100">En cours d'investigation</span>
                            @else
                                <a href="{{ route('voyageur.evaluations.index', ['tab' => 'recues', 'action' => 'report', 'id' => $eval->id_evaluation]) }}" 
                                   class="text-sm font-bold text-red-500 hover:text-white hover:bg-red-500 border border-red-500 px-4 py-1.5 rounded-lg transition">
                                    Signaler cet avis
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 text-slate-500">
                        Personne n'a encore laissé d'avis sur vous.
                    </div>
                @endforelse
                
                <div class="mt-4">{{ $evaluationsRecues->links() }}</div>
            @endif

        </div>

    </main>

    {{-- CARTE FLOTTANTE POUR MODIFIER UN AVIS --}}
    @if($action === 'edit' && $selectedEvaluation)
    <div class="fixed inset-0 z-50 flex items-center justify-center px-4 py-8">
        <a href="{{ route('voyageur.evaluations.index', ['tab' => 'donnees']) }}" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></a>
        <div class="relative bg-white w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-full">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="font-black text-xl text-slate-800">Modifier mon évaluation</h3>
                <a href="{{ route('voyageur.evaluations.index', ['tab' => 'donnees']) }}" class="text-slate-400 hover:text-slate-600"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></a>
            </div>
            
            <form action="{{ route('voyageur.evaluations.update', $selectedEvaluation->id_evaluation) }}" method="POST" class="p-8 overflow-y-auto">
                @csrf
                @method('PUT')
                
                <!-- Notes détaillées -->
                <div class="grid grid-cols-2 gap-4 mb-8">
                    @php 
                    $details = $selectedEvaluation->details; 
                    $criteres = ['proprete' => 'Propreté', 'communication' => 'Communication', 'emplacement' => 'Emplacement', 'rapport_qualite_prix' => 'Qualité/Prix', 'exactitude' => 'Exactitude'];
                    @endphp

                    @foreach($criteres as $key => $label)
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">{{ $label }} (1-5)</label>
                        <select name="{{ $key }}" class="w-full border border-slate-300 rounded-xl px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                            @for($i=1; $i<=5; $i++)
                                <option value="{{ $i }}" {{ ($details && (int)$details->{$key} === $i) ? 'selected' : '' }}>{{ $i }} Étoiles</option>
                            @endfor
                        </select>
                    </div>
                    @endforeach
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Commentaire libre</label>
                    <textarea name="commentaire" required rows="5" class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-blue-500 focus:border-blue-500">{{ $selectedEvaluation->commentaire }}</textarea>
                </div>
                
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-xl text-lg shadow-md transition-colors">Enregistrer les modifications</button>
            </form>
        </div>
    </div>
    @endif

    {{-- CARTE FLOTTANTE POUR SIGNALER UN AVIS --}}
    @if($action === 'report' && $selectedEvaluation)
    <div class="fixed inset-0 z-50 flex items-center justify-center px-4 py-8">
        <a href="{{ route('voyageur.evaluations.index', ['tab' => 'recues']) }}" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></a>
        <div class="relative bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-full border-t-8 border-red-500">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="font-black text-xl text-red-600">Signaler un avis abusif</h3>
                <a href="{{ route('voyageur.evaluations.index', ['tab' => 'recues']) }}" class="text-slate-400 hover:text-slate-600"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></a>
            </div>
            
            <div class="p-6 bg-red-50 border-b border-red-100">
                <p class="text-sm font-medium text-red-800">Vous êtes sur le point de signaler l'avis de <strong>{{ optional($selectedEvaluation->auteur)->prenom }}</strong>. Un administrateur l'examinera attentivement et pourra le supprimer s'il contrevient à nos règles.</p>
            </div>

            <form action="{{ route('voyageur.evaluations.signaler', $selectedEvaluation->id_evaluation) }}" method="POST" class="p-8">
                @csrf
                <div class="mb-8">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Motif du signalement :</label>
                    <textarea name="motif" required rows="4" placeholder="Veuillez expliquer pourquoi cet avis est frauduleux, vulgaire ou inexact..." class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-red-500 focus:border-red-500"></textarea>
                </div>
                
                <button type="submit" class="w-full bg-slate-900 hover:bg-black text-white font-black py-4 rounded-xl text-lg shadow-md transition-colors">Envoyer le signalement</button>
            </form>
        </div>
    </div>
    @endif

    <x-footer />
</body>
</html>
