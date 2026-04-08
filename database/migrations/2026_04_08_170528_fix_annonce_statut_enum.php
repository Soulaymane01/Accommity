<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // On Postgres, if it was created with $table->enum, it's likely a CHECK constraint
        // We drop it and recreate it with the correct values from the StatutAnnonce enum
        DB::statement('ALTER TABLE annonces DROP CONSTRAINT IF EXISTS annonces_statut_check');
        DB::statement("ALTER TABLE annonces ADD CONSTRAINT annonces_statut_check CHECK (statut IN ('En cours de vérification', 'Publié', 'Suspendu', 'Désactivé', 'Rejeté'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE annonces DROP CONSTRAINT IF EXISTS annonces_statut_check');
        DB::statement("ALTER TABLE annonces ADD CONSTRAINT annonces_statut_check CHECK (statut IN ('En cours de vérification', 'Publié', 'Suspendu', 'supprimer', 'rejeté'))");
    }
};
