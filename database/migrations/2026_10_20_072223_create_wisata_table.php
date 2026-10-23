<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wisata', function (Blueprint $table) {
            $table->id();
            $table->string('nama_wisata', 150);
            $table->text('deskripsi')->nullable();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('alamat', 255)->nullable();
            $table->foreignId('kategori_id')->constrained('kategori')->cascadeOnDelete();
            $table->foreignId('desa_id')->nullable()->constrained('desa')->nullOnDelete();
            $table->string('foto_utama', 255)->nullable();
            $table->string('jam_buka', 50)->nullable();
            $table->decimal('harga_tiket', 10, 2)->nullable();
            $table->text('fasilitas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wisata');
    }
};