<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();

            $table->string('id_customer', 40);
            $table->string('no_plat', 15)->unique();
            $table->string('kode_motor', 10);
            $table->string('nama_unit', 100)->nullable();
            $table->year('tahun')->nullable();
            $table->string('no_rangka', 30)->nullable()->unique();
            $table->string('no_mesin', 30)->nullable()->unique();

            $table->timestamps();

            $table->foreign('id_customer')
                ->references('id_customer')
                ->on('customers')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
