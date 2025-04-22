@extends('layout')

@section('content')
    <h2 class="mb-4">Daftar Tempat Wisata</h2>

    <!-- Filter & Search -->
    <div class="row mb-3">
        <div class="col-md-4">
            <input type="text" id="searchNama" class="form-control" placeholder="Cari nama wisata...">
        </div>
        
        <div class="col-md-4">
            <button class="btn btn-primary w-100" id="filterBtn">Terapkan Filter</button>
        </div>

        <div class="col-md-4 text-end">
            <button class="btn btn-danger" onclick="logout()">Log Out</button>
    </div>

    <!-- Daftar Wisata -->
    <div id="wisataList" class="row g-3">
        <!-- Data dimuat lewat JS -->
    </div>
@endsection

@push('scripts')
<script>
    const apiKey = localStorage.getItem("api_key") || "fNN22NxhgauX8MBH4ucYZyzukOQw05GdCqJhFy6g";
    const weatherApiKey = "8de66d2bc88e99abcdfe6c836c27c2eb";


    document.addEventListener("DOMContentLoaded", function () {
        loadWisata();

        document.getElementById("filterBtn").addEventListener("click", function () {
            const nama = document.getElementById("searchNama").value.trim();

            if (nama) {
                cariWisata(nama);
            }  else {
                loadWisata();
            }
        });
    });

    function logout() {
    localStorage.removeItem("api_key");
    alert("Anda berhasil logout.");
    window.location.href = "login";
}

    function loadWisata() {
        fetch("http://localhost:8000/pengguna/wisata", {
            headers: {
                "X-API-KEY": apiKey
            }
        })
        .then(res => res.json())
        .then(renderWisata)
        .catch(err => {
            console.error(err);
            alert("Gagal memuat data wisata.");
        });
    }

    function cariWisata(nama) {
        fetch(`http://localhost:8000/pengguna/wisata/cari/${encodeURIComponent(nama)}`, {
            headers: {
                "X-API-KEY": apiKey
            }
        })
        .then(res => res.json())
        .then(renderWisata)
        .catch(err => {
            console.error(err);
            alert("Gagal mencari wisata.");
        });
    }

    function filterJenis(jenis) {
        fetch(`http://localhost:8000/pengguna/wisata/jenis/${encodeURIComponent(jenis)}`, {
            headers: {
                "X-API-KEY": apiKey
            }
        })
        .then(res => res.json())
        .then(renderWisata)
        .catch(err => {
            console.error(err);
            alert("Gagal memfilter wisata.");
        });
    }

    function renderWisata(data) {
    const container = document.getElementById("wisataList");
    container.innerHTML = "";

    if (!data || data.length === 0) {
        container.innerHTML = `<div class="col-12"><p class="text-center">Data tidak ditemukan.</p></div>`;
        return;
    }

    data.forEach(item => {
        const cardId = `wisata-${item.id}`;
        container.innerHTML += `
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">${item.nama}</h5>
                        <p class="card-text">${item.deskripsi}</p>
                        <p><strong>Jenis:</strong> ${item.jenis}</p>
                        <div id="${cardId}-cuaca" class="mt-2 text-muted">Memuat cuaca...</div>
                    </div>
                </div>
            </div>
        `;

        // Fetch data cuaca berdasarkan koordinat tempat wisata
        if (item.latitude && item.longitude) {
            fetch(`https://api.openweathermap.org/data/2.5/weather?lat=${item.latitude}&lon=${item.longitude}&appid=${weatherApiKey}&units=metric&lang=id`)
                .then(res => res.json())
                .then(weather => {
                    const cuacaText = `${weather.weather[0].description}, ${weather.main.temp}°C`;
                    document.getElementById(`${cardId}-cuaca`).innerText = `Cuaca: ${cuacaText}`;
                })
                .catch(err => {
                    document.getElementById(`${cardId}-cuaca`).innerText = `Cuaca: Tidak tersedia`;
                    console.error("Gagal memuat data cuaca:", err);
                });
        } else {
            document.getElementById(`${cardId}-cuaca`).innerText = `Cuaca: Koordinat tidak tersedia`;
        }
    });
}
</script>
@endpush
