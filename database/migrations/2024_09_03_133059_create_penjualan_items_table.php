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
        Schema::create('penjualan_items', function (Blueprint $table) {
            $table->id();
            $table->string('obat_id');
            $table->foreignId('penjualan_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            // $table->foreignId('penjualan_id')
            //     ->constrained()
            //     ->onUpdate('cascade')
            //     ->onDelete('cascade');
            // $table->foreign('penjualan_id')->references('id')->on('penjualan')->cascadeOnDelete();
            $table->integer('price')->default(0);
            $table->integer('qty')->default(0);
            $table->integer('total')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan_items');
    }
};
