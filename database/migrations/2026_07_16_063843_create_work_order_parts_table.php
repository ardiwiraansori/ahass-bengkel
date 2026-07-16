<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_order_parts', function (Blueprint $table) {
            $table->id();

            $table->string('id_wo', 40);
            $table->string('part_number', 50);

            /*
             * Snapshot data part.
             * Nama dan harga transaksi tidak berubah
             * ketika master part diperbarui.
             */
            $table->string('nama_part');
            $table->unsignedInteger('qty');
            $table->unsignedBigInteger('harga_satuan');
            $table->unsignedBigInteger('subtotal');

            $table->timestamps();

            $table->foreign('id_wo')
                ->references('id_wo')
                ->on('work_orders')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('part_number')
                ->references('part_number')
                ->on('master_parts')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->unique(
                ['id_wo', 'part_number'],
                'work_order_parts_wo_part_unique'
            );

            $table->index('part_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_order_parts');
    }
};
