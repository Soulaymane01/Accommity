<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * Changes the 'type_alerte' enum column in the 'notifications' table
     * from ['email', 'sms', 'push']
     * to   ['reservation', 'paiement', 'avis', 'rappel', 'systeme']
     *
     * NOTE: Laravel's Schema Builder does not support modifying enum columns
     * on PostgreSQL via ->change() (known framework limitation — it generates
     * invalid syntax). We use DB::statement() which still goes through
     * Laravel's configured database connection.
     */
    public function up(): void
    {
        // Drop the existing CHECK constraint (Laravel names it <table>_<column>_check)
        DB::statement("ALTER TABLE notifications DROP CONSTRAINT IF EXISTS notifications_type_alerte_check");

        // Add the new CHECK constraint with the updated enum values
        DB::statement("ALTER TABLE notifications ADD CONSTRAINT notifications_type_alerte_check CHECK (type_alerte IN ('reservation', 'paiement', 'avis', 'rappel', 'systeme'))");
    }

    /**
     * Reverse the migrations — restores the original enum values.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE notifications DROP CONSTRAINT IF EXISTS notifications_type_alerte_check");

        DB::statement("ALTER TABLE notifications ADD CONSTRAINT notifications_type_alerte_check CHECK (type_alerte IN ('email', 'sms', 'push'))");
    }
};

