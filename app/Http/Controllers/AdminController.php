<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Wisata;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Login admin
    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        $admin = Admin::where('username', $request->username)->first();

        if (!$admin || !app('hash')->check($request->password, $admin->password)) {
            return response()->json(['message' => 'Username atau password salah'], 401);
        }

        // Simulasikan token (misalnya JWT atau token biasa)
        $token = bin2hex(random_bytes(16));
        $admin->update(['token' => $token]); 

        // Simpan token ke session atau return langsung
        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'admin' => $admin
        ]);
    }

    // Tambah tempat wisata
    public function tambahTempat(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required',
            'deskripsi' => 'required',
            'jenis' => 'required',
            'alamat' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        Wisata::create($request->all());

        return response()->json(['message' => 'Tempat wisata berhasil ditambahkan']);
    }

    // Lihat semua wisata
    public function getSemuaWisata()
    {
        return response()->json(Wisata::all());
    }
}
