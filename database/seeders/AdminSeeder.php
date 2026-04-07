<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = \Illuminate\Support\Str::uuid()->toString();
        $adminId = \Illuminate\Support\Str::uuid()->toString();

        \App\Models\Utilisateurs\User::create([
            'id_utilisateur' => $userId,
            'nom' => 'System',
            'prenom' => 'Administrator',
            'email' => 'admin@accommity.com',
            'mot_de_passe' => \Illuminate\Support\Facades\Hash::make('admin123'),
            'telephone' => '0000000000',
            'est_hote' => false,
            'est_voyageur' => false,
            'date_creation' => now(),
        ]);

        \App\Models\Administration\Administrateur::create([
            'id_admin' => $adminId,
            'id_utilisateur' => $userId,
            'email' => 'admin@accommity.com',
            'mot_de_passe' => \Illuminate\Support\Facades\Hash::make('admin123'),
            'derniere_connexion' => now(),
        ]);
    }
}
