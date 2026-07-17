<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_opnames', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->date('tanggal');

            $table->text('catatan')->nullable();

            $table->enum('status', ['Draft', 'Selesai'])->default('Draft');

            $table->timestamps();

        });

        Schema::create('stock_opname_items', function (Blueprint $table) {

            $table->id();

            $table->foreignId('stock_opname_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->integer('stok_sistem');   // stok di sistem sebelum opname

            $table->integer('stok_fisik');    // stok hasil hitung fisik

            $table->integer('selisih');       // fisik - sistem

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_opname_items');
        Schema::dropIfExists('stock_opnames');
    }
};
