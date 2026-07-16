<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_jobs', function (Blueprint $table) {
            $table->string('id_job', 30)->primary();
            $table->string('kode_motor', 10)->index();
            $table->string('keterangan');
            $table->unsignedBigInteger('harga');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_jobs');
    }
};
