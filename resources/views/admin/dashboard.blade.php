@extends('layout')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Dashboard Admin - Data Tempat Wisata</h2>

    <!-- Tombol Tambah -->
    <div class="d-flex justify-content-between mb-3 w-100">
    <button class="btn btn-success" onclick="showTambahModal()">+ Tambah Wisata</button>
    <button class="btn btn-danger" onclick="logout()">Log Out</button>
    </div>


    <!-- Tabel Data Wisata -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Jenis</th>
                <th>Alamat</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="wisataTableBody">
            <!-- Diisi dari JavaScript -->
        </tbody>
    </table>
</div>

<!-- Modal Tambah/Edit -->
<div class="modal fade" id="wisataModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="wisataForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah/Edit Wisata</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="wisataId">
                <input type="text" class="form-control mb-2" id="nama" placeholder="Nama" required>
                <textarea class="form-control mb-2" id="deskripsi" placeholder="Deskripsi" required></textarea>
                <input type="text" class="form-control mb-2" id="jenis" placeholder="Jenis" required>
                <input type="text" class="form-control mb-2" id="alamat" placeholder="Alamat" required>
                <input type="text" class="form-control mb-2" id="latitude" placeholder="Latitude" required>
                <input type="text" class="form-control mb-2" id="longitude" placeholder="Longitude" required>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const adminToken = localStorage.getItem("admin_token");

    document.addEventListener("DOMContentLoaded", function () {
        loadWisata();

        // Submit form
        document.getElementById("wisataForm").addEventListener("submit", function (e) {
            e.preventDefault();
            const id = document.getElementById("wisataId").value;
            id ? updateWisata(id) : tambahWisata();
        });
    });

    function loadWisata() {
        if (!adminToken) {
            alert("Anda harus login terlebih dahulu.");
            return;
        }

        fetch("http://localhost:8000/admin/wisata/semua", {
            headers: { 
                "Authorization": `Bearer ${adminToken}`
            }
        })
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById("wisataTableBody");
            tbody.innerHTML = "";
            data.forEach(w => {
                tbody.innerHTML += `
                    <tr>
                        <td>${w.nama}</td>
                        <td>${w.deskripsi}</td>
                        <td>${w.jenis}</td>
                        <td>${w.alamat}</td>
                        <td>${w.latitude}</td>
                        <td>${w.longitude}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" onclick='editWisata(${JSON.stringify(w)})'>Edit</button>
                            <button class="btn btn-sm btn-danger" onclick="hapusWisata(${w.id})">Hapus</button>
                        </td>
                    </tr>
                `;
            });
        })
        .catch(err => {
            console.error(err);
            alert("Terjadi kesalahan saat memuat data wisata.");
        });
    }

    function tambahWisata() {
        const data = getFormData();
        fetch("http://localhost:8000/admin/wisata/tambah", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${adminToken}`
            },
            body: JSON.stringify(data)
        })
        .then(() => {
            closeModal();
            loadWisata();
        })
        .catch(err => {
            console.error(err);
            alert("Terjadi kesalahan saat menambah wisata.");
        });
    }

    function updateWisata(id) {
        const data = getFormData();
        fetch(`http://localhost:8000/admin/wisata/update/${id}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${adminToken}`
            },
            body: JSON.stringify(data)
        })
        .then(() => {
            closeModal();
            loadWisata();
        })
        .catch(err => {
            console.error(err);
            alert("Terjadi kesalahan saat memperbarui wisata.");
        });
    }

    function logout() {
    localStorage.removeItem("admin_token");
    alert("Anda berhasil logout.");
    window.location.href = "login";
}


    function hapusWisata(id) {
        if (confirm("Yakin ingin menghapus?")) {
            fetch(`http://localhost:8000/admin/wisata/hapus/${id}`, {
                method: "DELETE",
                headers: {
                    "Authorization": `Bearer ${adminToken}`
                }
            })
            .then(() => loadWisata())
            .catch(err => {
                console.error(err);
                alert("Terjadi kesalahan saat menghapus wisata.");
            });
        }
    }

    function editWisata(w) {
        console.log(w);
        showEditModal(w);
    }

    function getFormData() {
        return {
            nama: document.getElementById("nama").value,
            deskripsi: document.getElementById("deskripsi").value,
            jenis: document.getElementById("jenis").value,
            alamat: document.getElementById("alamat").value,
            latitude: document.getElementById("latitude").value,
            longitude: document.getElementById("longitude").value
        };
    }

    function showTambahModal() {
        document.getElementById("wisataForm").reset();
        document.getElementById("wisataId").value = "";
        const modal = new bootstrap.Modal(document.getElementById('wisataModal'));
        modal.show();
    }

    function showEditModal(w) {
        document.getElementById("wisataId").value = w.id;
        document.getElementById("nama").value = w.nama;
        document.getElementById("deskripsi").value = w.deskripsi;
        document.getElementById("jenis").value = w.jenis;
        document.getElementById("alamat").value = w.alamat;
        document.getElementById("latitude").value = w.latitude;
        document.getElementById("longitude").value = w.longitude;
        const modal = new bootstrap.Modal(document.getElementById('wisataModal'));
        modal.show();
    }

    function closeModal() {
        const modal = bootstrap.Modal.getInstance(document.getElementById('wisataModal'));
        modal.hide();
    }
</script>
@endpush
