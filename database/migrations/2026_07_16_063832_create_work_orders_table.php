<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->string('id_wo', 40)->primary();

            $table->string('id_sa', 40)->unique();
            $table->string('id_mekanik', 30)->nullable();

            /*
             * DRAFT       = WO masih disusun
             * MENUNGGU    = menunggu dikerjakan
             * DIKERJAKAN  = sedang dikerjakan
             * SELESAI     = pekerjaan selesai
             * BATAL       = WO dibatalkan
             */
            $table->string('status', 20)->default('DRAFT');

            $table->unsignedBigInteger('total_jasa')->default(0);
            $table->unsignedBigInteger('total_part')->default(0);
            $table->unsignedBigInteger('diskon')->default(0);
            $table->unsignedBigInteger('grand_total')->default(0);

            $table->string('metode_pembayaran', 30)->nullable();
            $table->unsignedBigInteger('jumlah_bayar')->default(0);
            $table->unsignedBigInteger('kembalian')->default(0);

            $table->text('catatan_mekanik')->nullable();

            $table->dateTime('started_at')->nullable();
            $table->dateTime('finished_at')->nullable();
            $table->dateTime('paid_at')->nullable();

            /*
             * PENDING = belum dikirim
             * SENT    = berhasil dikirim
             * FAILED  = gagal dikirim
             */
            $table->string('dgi_status', 20)->default('PENDING');
            $table->json('dgi_response')->nullable();
            $table->dateTime('dgi_sent_at')->nullable();

            $table->timestamps();

            $table->foreign('id_sa')
                ->references('id_sa')
                ->on('service_advisor_forms')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('id_mekanik')
                ->references('id_mekanik')
                ->on('mechanics')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->index('id_mekanik');
            $table->index('status');
            $table->index('dgi_status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
