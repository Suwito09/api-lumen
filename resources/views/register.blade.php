@extends('layout')

@section('content')
<h2 class="mb-4">Registrasi Pengguna</h2>
<form id="registerForm">
    <div class="mb-3">
        <label>Username</label>
        <input type="text" class="form-control" name="username" required />
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" class="form-control" name="email" required />
    </div>
    <div class="mb-3">
        <label>Password</label>
        <input type="password" class="form-control" name="password" required />
    </div>
    <button type="submit" class="btn btn-success">Register</button>
    <p class="mt-3">Sudah punya akun? <a href="/login">Login di sini</a></p>
</form>

<script>
document.getElementById("registerForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const form = new FormData(this);
    fetch("http://localhost:8000/pengguna/register", {
        method: "POST",
        body: form,
    })
    .then(res => res.json())
    .then(data => {
        if (data.api_key) {
            localStorage.setItem("api_key", data.api_key);
            alert("Registrasi berhasil!");
            window.location.href = "/";
        } else {
            alert("Registrasi gagal: " + (data.error || "Coba lagi"));
        }
    });
});
</script>
@endsection
