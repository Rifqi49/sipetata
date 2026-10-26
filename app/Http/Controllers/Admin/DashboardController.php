<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wisata;
use App\Models\Kategori;

class DashboardController extends Controller
{
    public function index()
    {
        $totalWisata   = Wisata::count();
        $totalKategori = Kategori::count();

        return view('admin.dashboard', compact('totalWisata', 'totalKategori'));
    }
}