<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->date('preferred_visit_date')->nullable()->after('address');
            $table->string('preferred_visit_time', 50)->nullable()->after('preferred_visit_date');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['preferred_visit_date', 'preferred_visit_time']);
        });
    }
};