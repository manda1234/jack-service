<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('payment_method', 20)->nullable()->after('amount');
            $table->decimal('amount_paid', 10, 2)->nullable()->after('payment_method');
            $table->decimal('change_amount', 10, 2)->nullable()->after('amount_paid');
        });

        // Ganti paid_by dari enum terbatas jadi string biasa,
        // supaya bisa menerima 'Pelanggan', 'Admin', 'Teknisi', dst tanpa perlu migration setiap kali nilai baru muncul.
        DB::statement("ALTER TABLE payments MODIFY paid_by VARCHAR(20) NOT NULL");
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'amount_paid', 'change_amount']);
        });

        DB::statement("ALTER TABLE payments MODIFY paid_by ENUM('Admin','Teknisi') NOT NULL");
    }
};