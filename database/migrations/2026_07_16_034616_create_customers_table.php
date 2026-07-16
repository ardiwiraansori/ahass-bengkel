<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->string('id_customer', 40)->primary();
            $table->string('nama_customer', 100);
            $table->string('no_hp', 20);
            $table->string('email')->nullable();
            $table->string('no_identitas', 50)->nullable()->unique();
            $table->text('alamat')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
