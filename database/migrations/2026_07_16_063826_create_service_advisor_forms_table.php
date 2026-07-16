<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_advisor_forms', function (Blueprint $table) {
            $table->string('id_sa', 40)->primary();

            $table->string('id_customer', 30);
            $table->foreignId('vehicle_id');

            $table->dateTime('tanggal_kedatangan');
            $table->unsignedInteger('kilometer')->nullable();

            $table->text('keluhan');
            $table->text('catatan_sa')->nullable();

            /*
             * OPEN       = belum dibuatkan Work Order
             * CONVERTED  = sudah dibuatkan Work Order
             * CANCELLED  = dibatalkan
             */
            $table->string('status', 20)->default('OPEN');

            $table->timestamps();

            $table->foreign('id_customer')
                ->references('id_customer')
                ->on('customers')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('vehicle_id')
                ->references('id')
                ->on('vehicles')
                ->restrictOnDelete();

            $table->index('id_customer');
            $table->index('vehicle_id');
            $table->index(['status', 'tanggal_kedatangan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_advisor_forms');
    }
};
