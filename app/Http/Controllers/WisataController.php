<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wisata;

class WisataController extends Controller
{
    // Menampilkan semua wisata
    public function index()
    {
        return response()->json(Wisata::all());
    }

    // Menampilkan wisata berdasarkan jenis
    public function byJenis(Request $request)
    {
        $jenis = trim($request->query('jenis')); // <-- hapus spasi & newline
        return response()->json(
            Wisata::whereRaw('LOWER(jenis) = ?', [strtolower($jenis)])->get()
        );
    }
    
     

    // Cari wisata berdasarkan nama
    public function cari($nama)
    {
        return response()->json(Wisata::where('nama', 'like', '%' . $nama . '%')->get());
    }

    // Menampilkan lokasi (alamat dan koordinat) semua wisata
    public function lokasi()
    {
        return response()->json(
            Wisata::select('nama', 'alamat', 'latitude', 'longitude')->get()
        );
    }
}
