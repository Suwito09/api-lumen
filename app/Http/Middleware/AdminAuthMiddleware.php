<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminAuthMiddleware
{
    public function handle($request, Closure $next)
    {
        // Ambil header dari request
        $username = $request->header('X-ADMIN-USERNAME');
        $password = $request->header('X-ADMIN-PASSWORD');

        // Cari admin di tabel 'admin' (bukan 'admins', sesuaikan dengan modelmu)
        $admin = DB::table('admin')->where('username', $username)->first();

        // Cek apakah admin ditemukan dan password cocok
        // if (!$admin || !Hash::check($password, $admin->password)) {
        //     return response()->json(['error' => 'Unauthorized admin'], 401);
        // }

        // Lanjut ke proses berikutnya jika valid
        return $next($request);
    }
}
