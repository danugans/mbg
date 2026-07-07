<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            $table->enum('jenis', ['olahan', 'inventaris']);
            $table->string('kategori');
            $table->string('satuan')->nullable(); // Khusus barang olahan (kg, liter, dll)
            $table->integer('interval_service_bulan')->nullable(); // Khusus inventaris
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};