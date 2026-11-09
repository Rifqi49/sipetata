<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Kategori baru: Alam, Budaya, Religi, Akomodasi.
     * ID dipertahankan agar relasi wisata.kategori_id tidak rusak.
     */
    private array $baru = [
        1 => ['nama_kategori' => 'Alam',      'warna_marker' => '#27ae60'],
        2 => ['nama_kategori' => 'Budaya',    'warna_marker' => '#8e44ad'],
        3 => ['nama_kategori' => 'Religi',    'warna_marker' => '#2980b9'],
        4 => ['nama_kategori' => 'Akomodasi', 'warna_marker' => '#e67e22'],
    ];

    private array $lama = [
        1 => ['nama_kategori' => 'Wisata Alam',   'warna_marker' => '#27ae60'],
        2 => ['nama_kategori' => 'Wisata Bahari', 'warna_marker' => '#2980b9'],
        3 => ['nama_kategori' => 'Wisata Budaya', 'warna_marker' => '#8e44ad'],
        4 => ['nama_kategori' => 'Wisata Buatan', 'warna_marker' => '#e67e22'],
    ];

    public function up(): void
    {
        $this->terapkan($this->baru);
    }

    public function down(): void
    {
        $this->terapkan($this->lama);
    }

    private function terapkan(array $daftar): void
    {
        foreach ($daftar as $id => $data) {
            $ada = DB::table('kategori')->where('id', $id)->exists();

            if ($ada) {
                DB::table('kategori')->where('id', $id)->update([
                    'nama_kategori' => $data['nama_kategori'],
                    'warna_marker'  => $data['warna_marker'],
                    'updated_at'    => now(),
                ]);
            } else {
                DB::table('kategori')->insert([
                    'id'            => $id,
                    'nama_kategori' => $data['nama_kategori'],
                    'warna_marker'  => $data['warna_marker'],
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }
    }
};
