@extends('layout')

@section('content')
<h2 class="mb-4">Login Pengguna</h2>
<form id="loginForm">
    <div class="mb-3">
        <label>Username</label>
        <input type="username" class="form-control" name="username" required />
    </div>
    <div class="mb-3">
        <label>Password</label>
        <input type="password" class="form-control" name="password" required />
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
    <p class="mt-3">Belum punya akun? <a href="/register">Daftar di sini</a></p>
</form>

<script>
document.getElementById("loginForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const form = new FormData(this);
    fetch("http://localhost:8000/pengguna/login", {
        method: "POST",
        body: form,
    })
    .then(res => res.json())
    .then(data => {
        if (data.api_key) {
            localStorage.setItem("api_key", data.api_key);
            alert("Login berhasil!");
            window.location.href = "/";
        } else {
            alert("Login gagal: " + (data.error || "Periksa email dan password"));
        }
    });
});
</script>
@endsection
