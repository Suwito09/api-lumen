@extends('layout')

@section('content')
    <h2 class="mb-4">Daftar Tempat Wisata</h2>

    <!-- Filter & Search -->
    <div class="row mb-3">
        <div class="col-md-4">
            <input type="text" id="searchNama" class="form-control" placeholder="Cari nama wisata...">
        </div>
        <div class="col-md-4">
        <select id="filterJenis" class="form-select">
            <option value="">-- Semua Jenis --</option>
            <option value="Wisata Sejarah">Wisata Sejarah </option>
            <option value="Wisata Alam">Wisata Alam</option>
            <option value="Wisata Belanja">Wisata Belanja</option>
        </select>
        </div>
        <div class="col-md-4">
            <button class="btn btn-primary w-100" id="filterBtn">Terapkan Filter</button>
        </div>
    </div>

    <!-- Daftar Wisata -->
    <div id="wisataList" class="row g-3">
        <!-- Data dimuat lewat JS -->
    </div>
@endsection

@push('scripts')
<script>
    const apiKey = localStorage.getItem("api_key") || "fNN22NxhgauX8MBH4ucYZyzukOQw05GdCqJhFy6g";

    document.addEventListener("DOMContentLoaded", function () {
        loadWisata();

        document.getElementById("filterBtn").addEventListener("click", function () {
            const nama = document.getElementById("searchNama").value.trim();
            const jenis = document.getElementById("filterJenis").value;

            if (nama) {
                cariWisata(nama);
            } else if (jenis) {
                filterJenis(jenis);
            } else {
                loadWisata();
            }
        });
    });

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
            container.innerHTML += `
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">${item.nama}</h5>
                            <p class="card-text">${item.deskripsi}</p>
                            <p><strong>Jenis:</strong> ${item.jenis}</p>
                        </div>
                    </div>
                </div>
            `;
        });
    }
</script>
@endpush
