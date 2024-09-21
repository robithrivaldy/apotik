<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pembelians', function (Blueprint $table) {
            $table->id();
            $table->integer('supplier_id')->nullable();
            $table->integer('jenis_pembelian_id');
            $table->string('no_faktur')->nullable();
            $table->string('no_faktur_pajak')->nullable();
            $table->integer('total')->default(0);
            $table->text('keterangan')->nullable();
            $table->dateTime('tgl_diterima')->nullable();
            $table->dateTime('tgl_faktur')->nullable();
            $table->dateTime('tgl_jatuh_tempo')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelians');
    }
};
