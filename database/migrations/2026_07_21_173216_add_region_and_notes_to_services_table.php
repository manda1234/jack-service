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
        Schema::table('services', function (Blueprint $table) {
            // Wilayah operasional Home Service (contoh: "Mandiangin Koto Selayan")
            // Nullable karena Workshop tidak butuh field ini.
            $table->string('region')->nullable()->after('address');

            // Catatan tambahan dari pelanggan (opsional, di luar keluhan utama)
            $table->text('notes')->nullable()->after('preferred_visit_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['region', 'notes']);
        });
    }
};