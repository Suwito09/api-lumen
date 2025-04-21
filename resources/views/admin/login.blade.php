@extends('layout')

@section('content')
<div class="container mt-5" style="max-width: 400px;">
    <h3 class="mb-4 text-center">Login Admin</h3>
    <form id="adminLoginForm">
        <div class="mb-3">
            <label for="username" class="form-label">Username Admin</label>
            <input type="text" class="form-control" id="username" placeholder="" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById("adminLoginForm").addEventListener("submit", function (e) {
        e.preventDefault();

        const username = document.getElementById("username").value;
        const password = document.getElementById("password").value;

        fetch("http://localhost:8000/admin/login", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ username, password })
        })
        .then(res => res.json())
        .then(data => {
            if (data.token) {
                localStorage.setItem("admin_token", data.token);
                alert("Login berhasil!");
                window.location.href = "/admin/dashboard"; // arahkan ke halaman dashboard
            } else {
                alert("Login gagal: " + (data.message || "Periksa email dan password."));
            }
        })
        .catch(err => {
            console.error(err);
            alert("Terjadi kesalahan saat login.");
        });
    });
</script>
@endpush
