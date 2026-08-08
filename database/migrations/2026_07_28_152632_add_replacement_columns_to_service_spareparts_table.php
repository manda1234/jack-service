<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // sparepart_id harus nullable karena komponen kecil tidak punya sparepart
        Schema::table('service_spareparts', function (Blueprint $table) {
            $table->foreignId('sparepart_id')->nullable()->change();
        });

        Schema::table('service_spareparts', function (Blueprint $table) {
            $table->enum('replacement_type', ['sparepart', 'component'])
                ->default('sparepart')
                ->after('sparepart_id');
            $table->string('component_name')->nullable()->after('replacement_type');
            $table->boolean('included_in_service')->default(true)->after('component_name');
        });
    }

    public function down(): void
    {
        Schema::table('service_spareparts', function (Blueprint $table) {
            $table->dropColumn(['replacement_type', 'component_name', 'included_in_service']);
        });

        Schema::table('service_spareparts', function (Blueprint $table) {
            $table->foreignId('sparepart_id')->nullable(false)->change();
        });
    }
};