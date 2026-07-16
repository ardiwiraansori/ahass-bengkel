<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_parts', function (Blueprint $table) {
            $table->string('part_number', 30)->primary();
            $table->string('nama_part');
            $table->unsignedBigInteger('harga');

            $table->unsignedInteger('qty_stock')->default(0);
            $table->unsignedInteger('qty_rfs')->default(0);
            $table->unsignedInteger('qty_book')->default(0);

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_parts');
    }
};
