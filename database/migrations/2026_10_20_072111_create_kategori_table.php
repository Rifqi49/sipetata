<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori', 50)->unique();
            $table->text('deskripsi')->nullable();
            $table->string('warna_marker', 7)->default('#3388ff');
            $table->string('icon_marker', 100)->nullable();
            $table->timestamps();
        });

        DB::table('kategori')->insert([
            ['id' => 1, 'nama_kategori' => 'Alam',             'warna_marker' => '#27ae60', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nama_kategori' => 'Religi & Budaya',  'warna_marker' => '#8e44ad', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nama_kategori' => 'Buatan',           'warna_marker' => '#e67e22', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori');
    }
};