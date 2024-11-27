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
        Schema::create('obats', function (Blueprint $table) {
            $table->id();
            // $table->string('name');
            $table->integer('master_obat_id')->nullable();
            // $table->foreignId('pembelian_id')->constrained();
            $table->foreignId('pembelian_id')
                ->nullable()
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('no_batch')->nullable();
            $table->integer('price')->default(0);
            $table->integer('stock')->default(0);
            $table->integer('margin')->default(0);
            $table->integer('pembelian_price')->default(0);
            $table->integer('pembelian_stock')->default(0);
            $table->integer('pembelian_total')->default(0);
            $table->dateTime('tgl_expired')->nullable();
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
        Schema::dropIfExists('obats');
    }
};
