<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_spareparts', function (Blueprint $table) {
            $table->text('reason')->nullable()->after('subtotal');
            $table->string('approval_status', 30)->default('Menunggu Persetujuan')->after('reason');
        });
    }

    public function down(): void
    {
        Schema::table('service_spareparts', function (Blueprint $table) {
            $table->dropColumn(['reason', 'approval_status']);
        });
    }
};