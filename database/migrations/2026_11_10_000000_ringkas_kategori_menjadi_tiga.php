<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Ringkas kategori dari 4 menjadi 3:
     * Alam, Religi & Budaya, Buatan.
     *
     * ID 1 (Alam) dan 3 (Religi) dipertahankan agar relasi wisata aman;
     * wisata Religi dipindah ke ID 2 lebih dulu, lalu ID 3 dipakai untuk Buatan.
     */
    public function up(): void
    {
        // 1. Wisata Religi (id 3) masuk ke kategori gabungan (id 2).
        DB::table('wisata')->where('kategori_id', 3)->update(['kategori_id' => 2]);

        // 2. Wisata Akomodasi (id 4, bila ada) dianggap bangunan/buatan.
        DB::table('wisata')->where('kategori_id', 4)->update(['kategori_id' => 3]);

        // 3. Nama & warna kategori baru.
        DB::table('kategori')->where('id', 1)->update([
            'nama_kategori' => 'Alam',
            'warna_marker'  => '#27ae60',
            'updated_at'    => now(),
        ]);

        DB::table('kategori')->where('id', 2)->update([
            'nama_kategori' => 'Religi & Budaya',
            'warna_marker'  => '#8e44ad',
            'updated_at'    => now(),
        ]);

        DB::table('kategori')->where('id', 3)->update([
            'nama_kategori' => 'Buatan',
            'warna_marker'  => '#e67e22',
            'updated_at'    => now(),
        ]);

        // 4. Kategori Akomodasi (id 4) dihapus.
        DB::table('kategori')->where('id', 4)->delete();
    }

    public function down(): void
    {
        DB::table('kategori')->where('id', 1)->update([
            'nama_kategori' => 'Alam',
            'warna_marker'  => '#27ae60',
            'updated_at'    => now(),
        ]);

        DB::table('kategori')->where('id', 2)->update([
            'nama_kategori' => 'Budaya',
            'warna_marker'  => '#8e44ad',
            'updated_at'    => now(),
        ]);

        DB::table('kategori')->where('id', 3)->update([
            'nama_kategori' => 'Religi',
            'warna_marker'  => '#2980b9',
            'updated_at'    => now(),
        ]);

        if (! DB::table('kategori')->where('id', 4)->exists()) {
            DB::table('kategori')->insert([
                'id'            => 4,
                'nama_kategori' => 'Akomodasi',
                'warna_marker'  => '#e67e22',
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }
};
