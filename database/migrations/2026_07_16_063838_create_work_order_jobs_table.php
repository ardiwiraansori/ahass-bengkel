<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_order_jobs', function (Blueprint $table) {
            $table->id();

            $table->string('id_wo', 40);
            $table->string('id_job', 30);

            /*
             * Snapshot data jasa.
             * Tetap tersimpan meskipun nama atau harga master berubah.
             */
            $table->string('keterangan_job');
            $table->unsignedInteger('qty')->default(1);
            $table->unsignedBigInteger('harga_satuan');
            $table->unsignedBigInteger('subtotal');

            $table->timestamps();

            $table->foreign('id_wo')
                ->references('id_wo')
                ->on('work_orders')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('id_job')
                ->references('id_job')
                ->on('master_jobs')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->unique(
                ['id_wo', 'id_job'],
                'work_order_jobs_wo_job_unique'
            );

            $table->index('id_job');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_order_jobs');
    }
};
