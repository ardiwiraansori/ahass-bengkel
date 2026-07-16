<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mechanics', function (Blueprint $table) {
            $table->string('id_mekanik', 30)->primary();
            $table->string('honda_id_mekanik', 30)->unique();
            $table->string('nama_mekanik', 100);
            $table->string('no_hp', 20)->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mechanics');
    }
};
