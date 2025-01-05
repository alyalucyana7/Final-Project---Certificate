<?php 

require './config/db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ./login.php'); // Redirect ke halaman login jika belum login
    exit;
}

$db_connect->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kegiatan | Tivicate-DKM Jamie Su'ada</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="dashboard-style.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            overflow-y: auto;
            margin: 0;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
        }

        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            background: #fff;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <img src="./images/dkm.jpeg" alt="Tivicate Logo">
            <hr>
        </div>
        <ul>
            <li><a href="#"><i class="bi bi-house-door-fill"></i> <b>Dashboard</b></a></li>
            <hr>
            <li><a href="kegiatan.php" class="active"><i class="bi bi-calendar3"></i> <b>Data Kegiatan</b></a></li>
            <li><a href="sertifikat.php"><i class="bi bi-filetype-pdf"></i> <b>Sertifikat</b></a></li>
            <li><a href="javascript:void(0);" onclick="confirmLogout()"><i class="bi bi-box-arrow-left"></i> <b>Logout</b></a></li>
            <hr>
        </ul>
    </div>

    <div class="main-content">
        <div class="topbar">
            <span class="user-name"><?php include './backend/profil_user.php'; ?></span>
            <img src="./images/profil.png" alt="Admin Profile" class="profile-pic">
        </div>
        <div class="content">
            <!-- Header -->
            <h3 class="page-title">Tambah Kegiatan</h3>
            <br>

            <form action="./backend/add_kegiatan.php" method="POST" enctype="multipart/form-data">
                <!-- Form Halaman 1 -->
                <div id="page1" class="form-container">
                    <h5 class="fw-bold" style="position: relative; display: inline-block; padding-bottom: 4px; border-bottom: 2px solid #000; margin-bottom: 16px;">Kegiatan</h5>
                    <br>
                    <div class="col">
                        <label for="penyelenggara">Nama Penyelenggara:</label>
                        <input type="text" id="penyelenggara" name="nama_penyelenggara" required style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Nama Penyelenggara">
                        <br><br>
                        <label for="nama_kegiatan">Nama Kegiatan:</label>
                        <input type="text" id="nama_kegiatan" name="nama_kegiatan" required style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Nama Kegiatan">
                        <br><br>
                        <label for="tema">Tema:</label>
                        <input type="text" id="tema" name="tema" required style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Tema">
                        <br><br>
                        <label for="tanggal_kegiatan">Tanggal Kegiatan:</label>
                        <input type="date" id="tanggal_kegiatan" name="tanggal_kegiatan" required style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Tanggal Kegiatan">
                        <br><br>
                        <label for="tempat">Tempat:</label>
                        <input type="text" id="tempat" name="tempat_via" required style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Tempat Kegiatan">
                        <br><br>
                        <label for="penomoran_sertifikat">Penomoran Sertifikat:</label>
                        <input type="text" id="penomoran_sertifikat" name="penomoran_sertifikat" required style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Nomor Sertifikat">
                    </div>
                </div>

                <!-- Form Halaman 2 -->
                <div id="page2" class="form-container" style="display: none;">
                    <h5 class="fw-bold" style="position: relative; display: inline-block; padding-bottom: 4px; border-bottom: 2px solid #000; margin-bottom: 16px;">Footer</h5>
                    <br>
                    <div class="col">
                        <label for="footer_text">Nama Ttd 1:</label>
                        <input type="text" id="footer_text" name="nama_ttd1" style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Nama Penandatangan 1" required>
                        <br><br>
                        <label for="footer_text">Jabatan 1:</label>
                        <input type="text" id="footer_text" name="jabatan1" style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Jabatan 1" required>
                        <br><br>
                        <label for="image" class="form-label">Scan Ttd 1</label>
                        <input type="file" class="form-control" id="image" name="scan_ttd1" accept=".jpg,.jpeg,.png">
                        <br><br>
                        <label for="footer_text">Nama Ttd 2:</label>
                        <input type="text" id="footer_text" name="nama_ttd2" style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Penandatangan 2" required>
                        <br><br>
                        <label for="footer_text">Jabatan 2:</label>
                        <input type="text" id="footer_text" name="jabatan2" style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Jabatan 2" required>
                        <br><br>
                        <label for="image" class="form-label">Scan Ttd 2</label>
                        <input type="file" class="form-control" id="image" name="scan_ttd2" accept=".jpg,.jpeg,.png">
                    </div>
                </div>

                <!-- Form Halaman 3 -->
                <div id="page3" class="form-container" style="display: none;">
                    <h5 class="fw-bold" style="position: relative; display: inline-block; padding-bottom: 4px; border-bottom: 2px solid #000; margin-bottom: 16px;">Background & Logo</h5>
                    <br>
                    <div class="col">
                        <label for="image" class="form-label">Background</label>
                        <input type="file" class="form-control" id="image" name="background" accept=".jpg,.jpeg,.png" >
                        <br>
                        <label for="logo" class="form-label">Logo Organisasi</label>
                        <input type="file" class="form-control" id="logo" name="logo_organisasi" accept=".jpg,.jpeg,.png">
                        <br>
                        <label for="logo" class="form-label">Logo Sponsor</label>
                        <input type="file" class="form-control" id="logo" name="logo_sponsor" accept=".jpg,.jpeg,.png">
                        <br>
                        <label for="language" class="form-label">Bahasa</label>
                        <select class="form-select" id="language" name="bahasa" required>
                            <option value="" disabled selected>Pilih Bahasa</option>
                            <option value="id">Indonesia</option>
                            <option value="en">English</option>
                        </select>
                        <br>
                        <label for="layout" class="form-label">Ukuran</label>
                        <select class="form-select" id="layout" name="ukuran" required>
                            <option value="" disabled selected>Pilih Ukuran</option>
                            <option value="landscape_center">Landscape Center</option>
                            <option value="landscape_left">Landscape Left</option>
                            <option value="potrait_center">Potrait Center</option>
                        </select>
                        <br>
                        <label for="font" class="form-label">Font Certificate</label>
                        <select class="form-select" id="font" name="font_sertifikat" required>
                            <option value="" disabled selected>Pilih Font</option>
                            <option value="minatur">Minatur</option>
                            <option value="times_new_roman">Times New Roman</option>
                            <option value="garamond">Garamond</option>
                            <option value="boldena">Boldena</option>
                        </select>
                        <br>
                        <label for="font" class="form-label">Font Nama</label>
                        <select class="form-select" id="font" name="font_nama" required>
                            <option value="" disabled selected>Pilih Font</option>
                            <option value="arial">Arial</option>
                            <option value="times_new_roman">Times New Roman</option>
                            <option value="boldena">Boldena</option>
                        </select>
                        <br>
                    </div>
                    <br>
                    <div class="d-flex">
                        <button type="submit" class="btn btn-primary ms-auto me-2" name="submit">Simpan</button>
                        <a href="kegiatan.php" type="button" class="btn btn-danger">Kembali</a>
                    </div>
                </div>
            </form>

            <!-- Navigasi -->
            <div class="navigation text-center" style="margin-top: 20px;">
                <button onclick="prevPage()" id="prevButton" style="padding: 10px 20px;"> &larr;</button>
                <span id="pageIndicator">Page 1 of 3</span>
                <button onclick="nextPage()" id="nextButton" style="padding: 10px 20px;"> &rarr;</button>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>

    // Variabel untuk melacak halaman saat ini dan total halaman
    let currentPage = 1;
    const totalPages = 3;

    function showPage(page) {
        // Sembunyikan semua halaman terlebih dahulu
        for (let i = 1; i <= totalPages; i++) {
            document.getElementById(`page${i}`).style.display = 'none';
        }

        // Tampilkan halaman sesuai parameter
        document.getElementById(`page${page}`).style.display = 'block';

        // Update indikator halaman
        document.getElementById('pageIndicator').textContent = `Page ${page} of ${totalPages}`;

        // Atur state tombol Previous dan Next
        document.getElementById('prevButton').disabled = (page === 1);
        document.getElementById('nextButton').disabled = (page === totalPages);
    }

    function nextPage() {
        if (currentPage < totalPages) {
            currentPage++;
            showPage(currentPage);
        }
    }

    function prevPage() {
        if (currentPage > 1) {
            currentPage--;
            showPage(currentPage);
        }
    }

    // Tampilkan halaman pertama saat pertama kali dimuat
    document.addEventListener('DOMContentLoaded', () => {
        showPage(currentPage);
    });
    </script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script> 
        function confirmLogout() {
    Swal.fire({
        title: 'Apakah Anda yakin ingin keluar?',
        text: "Anda akan keluar dari sesi ini!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, keluar!',
        cancelButtonText: 'Batal',
        backdrop: false // Hilangkan overlay abu-abu
    }).then((result) => {
        if (result.isConfirmed) {
            // Arahkan ke logout.php untuk menghapus session
            window.location.href = 'logout.php';
        }
    });
}
    </script>
</body>
</html>