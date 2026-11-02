<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wisata;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'petugas') {
            $totalWisata = Wisata::where('user_id', $user->id)->count();
        } else {
            $totalWisata = Wisata::count();
        }

        $totalKategori = Kategori::count();

        return view('admin.dashboard', compact('totalWisata', 'totalKategori'));
    }
}