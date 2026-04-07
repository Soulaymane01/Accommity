<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Pour les vérifications d'identité
        \Illuminate\Support\Facades\DB::statement("CREATE TYPE enum_statut_verif AS ENUM ('En cours de traitement', 'Validé', 'rejeté');");
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE verification_identites ALTER COLUMN statut TYPE enum_statut_verif USING statut::text::enum_statut_verif;");

        // 2. Pour les tickets litiges
        \Illuminate\Support\Facades\DB::statement("CREATE TYPE enum_statut_litige AS ENUM ('En cours', 'Clôturé');");
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE ticket_litiges ALTER COLUMN statut TYPE enum_statut_litige USING statut::text::enum_statut_litige;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE verification_identites ALTER COLUMN statut TYPE varchar(255) USING statut::text;");
        \Illuminate\Support\Facades\DB::statement("DROP TYPE enum_statut_verif;");

        \Illuminate\Support\Facades\DB::statement("ALTER TABLE ticket_litiges ALTER COLUMN statut TYPE varchar(255) USING statut::text;");
        \Illuminate\Support\Facades\DB::statement("DROP TYPE enum_statut_litige;");
    }
};
