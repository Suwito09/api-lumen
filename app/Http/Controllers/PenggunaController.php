<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    // Register
    public function register(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|unique:pengguna',
            'email' => 'required|email|unique:pengguna',
            'password' => 'required|min:6'
        ]);

        $pengguna = Pengguna::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'api_key' => Str::random(40),
        ]);

        return response()->json([
            'message' => 'Registrasi berhasil',
            'api_key' => $pengguna->api_key
        ]);
    }

    // Login
    public function login(Request $request)
    {
        $pengguna = Pengguna::where('username', $request->username)->first();

        if (!$pengguna || !Hash::check($request->password, $pengguna->password)) {
            return response()->json(['error' => 'Username atau password salah'], 401);
        }

        // jika belum punya api_key, buatkan
        if (!$pengguna->api_key) {
            $pengguna->api_key = Str::random(40);
            $pengguna->save();
        }

        return response()->json([
            'message' => 'Login berhasil',
            'api_key' => $pengguna->api_key
        ]);
    }
}
