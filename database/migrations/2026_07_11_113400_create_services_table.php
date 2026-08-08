<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('technician_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('service_code', 50)->unique();
            $table->enum('service_type', ['Workshop', 'Home Service']);
            $table->string('item_name', 100);
            $table->string('brand', 50)->nullable();
            $table->text('complaint');
            $table->text('address')->nullable(); // alamat untuk Home Service
            $table->text('diagnosis')->nullable();
            $table->text('repair_action')->nullable();
            $table->decimal('service_fee', 10, 2)->default(0);
            $table->decimal('sparepart_cost', 10, 2)->default(0);
            $table->decimal('total_cost', 10, 2)->default(0);
            $table->string('status', 50)->default('Menunggu Konfirmasi Admin');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};