<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ticket_litiges', function (Blueprint $table) {
            $table->uuid('id_admin')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('ticket_litiges', function (Blueprint $table) {
            $table->uuid('id_admin')->nullable(false)->change();
        });
    }
};
