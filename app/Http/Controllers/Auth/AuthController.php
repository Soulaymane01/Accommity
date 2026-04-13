<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Utilisateurs\User;
use App\Models\Utilisateurs\Profil;

class AuthController
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'mot_de_passe' => ['required'], // using mot_de_passe field
        ]);

        // Note: Laravel `Auth::attempt` checks against the `password` key by default.
        // Even though our database column is `mot_de_passe` and getAuthPassword returns it,
        // we must pass the array key as 'password' to `attempt` so Laravel hashes it correctly.
        $attemptCredentials = [
            'email' => $credentials['email'],
            'password' => $credentials['mot_de_passe']
        ];

        if (Auth::attempt($attemptCredentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            if ($user->est_hote) {
                $status = $user->getStatutVerification();
                
                if ($status === \App\Enums\VerificationStatut::EN_COURS) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect('/')->with('success_dialog', 'Votre identité est toujours en cours de traitement par un administrateur.');
                }
                
                if ($status === \App\Enums\VerificationStatut::REJETE) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect('/')->with('error_dialog', 'Votre compte est rejeté. Veuillez recréer un nouveau compte et essayer de donner une carte d\'identité valide.');
                }

                // If Validé (Valid, Approuvé, Validé)
                if ($status === \App\Enums\VerificationStatut::VALIDE) {
                    return redirect()->route('dashboard');
                }

                // Only other case is if they skipped document submission
                return redirect()->route('verification.notice');
            }

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:utilisateurs,email'],
            'mot_de_passe' => ['required', 'string', 'min:8', 'confirmed'],
            'telephone' => ['required', 'string', 'max:20'],
            'role' => ['required', 'in:hote,voyageur'],
        ]);

        $userId = Str::uuid()->toString();

        // Utilisation de la méthode UML
        $user = User::creerCompte([
            'id_utilisateur' => $userId,
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'mot_de_passe' => Hash::make($validated['mot_de_passe']),
            'telephone' => $validated['telephone'],
            'est_hote' => $validated['role'] === 'hote',
            'est_voyageur' => $validated['role'] === 'voyageur',
            'date_creation' => now(),
        ]);

        // Auto-create profil (Utilisation de la méthode UML)
        Profil::initialiserProfil($userId);

        Auth::login($user);

        // Redirect to photo step
        return redirect()->route('register.photo');
    }

    public function showPhotoUpload()
    {
        return view('auth.upload-photo');
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo_profil' => 'required|image|max:5120',
        ]);

        $user = Auth::user();
        if ($request->hasFile('photo_profil')) {
            $path = $request->file('photo_profil')->store('profiles', 'public');
            // Utilisation de la méthode UML avec préfixe /storage/
            $user->profil->ajouterPhoto('/storage/' . $path);
        }

        return $this->redirectAfterPhotoStep($user);
    }

    private function redirectAfterPhotoStep($user)
    {
        // Utilisation de la méthode UML: getRoleUtilisateur()
        if ($user->getRoleUtilisateur() === 'hote') {
            return redirect()->route('verification.notice');
        }

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function verificationNotice()
    {
        $user = Auth::user();
        if ($user && $user->getStatutVerification() === 'En cours de traitement') {
            return redirect()->route('home')->with('success_dialog', 'Votre identité est en cours de traitement merci de patienter.');
        }

        return view('auth.verification-notice');
    }

    public function submitVerification(Request $request)
    {
        $request->validate([
            'type_piece' => 'required', // Note: user said "hote we have only one possibility which is identity card 'piece d'indentite'" so maybe it's just 'piece_identite' now.
            'document_identite' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        if ($request->hasFile('document_identite')) {
            $path = $request->file('document_identite')->store('verifications', 'public');
            
            // Method from UML avec préfixe /storage/
            \App\Models\Utilisateurs\VerificationIdentite::soumettreDocuments(
                Auth::user()->id_utilisateur, 
                $request->input('type_piece'),
                '/storage/' . $path
            );
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success_dialog', 'Vos documents ont été soumis avec succès ! Votre identité est en cours de traitement par un administrateur.');
    }

    public function dashboard()
    {
        $user = Auth::user();

        if ($user->getRoleUtilisateur() === 'hote') {
            $status = $user->getStatutVerification();
            
            if ($status === \App\Enums\VerificationStatut::EN_COURS) {
                Auth::logout();
                return redirect('/')->with('success_dialog', 'Votre identité est toujours en cours de traitement par un administrateur.');
            }

            if ($status === \App\Enums\VerificationStatut::REJETE) {
                Auth::logout();
                return redirect('/')->with('error_dialog', 'Votre compte est rejeté. Veuillez recréer un nouveau compte et essayer de donner une carte d\'identité valide.');
            }

            // Any other invalid state (like null)
            if ($status !== \App\Enums\VerificationStatut::VALIDE) {
                return redirect()->route('verification.notice');
            }

            return view('hote.dashboard');
        }

        return view('voyageur.dashboard');
    }
}
