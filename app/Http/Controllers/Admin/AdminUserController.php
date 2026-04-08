<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Utilisateurs\User;
use App\Models\Utilisateurs\VerificationIdentite;

class AdminUserController
{
    /**
     * Affiche la liste des utilisateurs avec filtrage
     */
    public function index(Request $request)
    {
        $critere = $request->input('critere', 'tous');
        $searchQuery = $request->input('search');
        $selectedId = $request->input('selected');

        $utilisateurs = User::rechercherUtilisateur($critere, $searchQuery);

        $selectedUser = null;
        if ($selectedId) {
            $selectedUser = User::getUtilisateurById($selectedId);
        }

        return view('admin.utilisateurs.index', compact('utilisateurs', 'critere', 'searchQuery', 'selectedUser'));
    }

    /**
     * Met à jour les informations d'un utilisateur
     */
    public function update(Request $request, $id)
    {
        $user = User::getUtilisateurById($id);
        if ($user) {
            $user->updateUser($request->only(['nom', 'prenom', 'email']));
        }
        return redirect()->back()->with('success_dialog', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Supprime un utilisateur
     */
    public function destroy(Request $request, $id)
    {
        $user = User::getUtilisateurById($id);
        if ($user) {
            $user->deleteUser();
        }

        // Remove the selected ID from query if it was the deleted user
        return redirect()->route('admin.utilisateurs.index', $request->except(['selected']))->with('success_dialog', 'Utilisateur supprimé.');
    }

    /**
     * Valider l'identité de l'hôte
     */
    public function validateIdentity($idVerification)
    {
        $dossier = VerificationIdentite::where('id_verification', $idVerification)->first();
        if ($dossier) {
            $dossier->validerVerification();
        }
        return redirect()->back()->with('success_dialog', 'L\'identité de cet hôte a été parfaitement validée.');
    }

    /**
     * Rejeter l'identité de l'hôte avec motif
     */
    public function rejectIdentity(Request $request, $idVerification)
    {
        $dossier = VerificationIdentite::where('id_verification', $idVerification)->first();

        if ($dossier) {
            $motif = $request->input('motif', 'Motif non précisé par l\'administrateur');
            $dossier->rejeterVerification($motif);
        }

        return redirect()->back()->with('success_dialog', 'L\'identité a été rejetée. L\'hôte devra soumettre un nouveau compte.');
    }
}
