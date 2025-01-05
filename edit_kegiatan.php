<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ./login.php'); // Redirect ke halaman login jika belum login
    exit;
}

require './config/db.php';

// Mendapatkan ID kegiatan dari parameter URL
$id_kegiatan = $_GET['id'];
$id_template = $_GET['id'];

// Query untuk mengambil data dari tabel 'kegiatan' dan 'template_sertifikat'
$query = mysqli_query($db_connect, "
    SELECT kegiatan.*, template_sertifikat.*
    FROM kegiatan
    LEFT JOIN template_sertifikat 
    ON kegiatan.id_kegiatan = template_sertifikat.id_kegiatan
    WHERE kegiatan.id_kegiatan = $id_kegiatan
");

// Ambil data hasil query
$data = mysqli_fetch_assoc($query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Data kegiatan
    $nama_penyelenggara = mysqli_real_escape_string($db_connect, $_POST['nama_penyelenggara']);
    $nama_kegiatan = mysqli_real_escape_string($db_connect, $_POST['nama_kegiatan']);
    $tema = mysqli_real_escape_string($db_connect, $_POST['tema']);
    $tanggal_kegiatan = mysqli_real_escape_string($db_connect, $_POST['tanggal_kegiatan']);
    $tempat_via = mysqli_real_escape_string($db_connect, $_POST['tempat_via']);
    $penomoran_sertifikat = mysqli_real_escape_string($db_connect, $_POST['penomoran_sertifikat']);

    // Data template sertifikat
    $nama_ttd1 = mysqli_real_escape_string($db_connect, $_POST['nama_ttd1']);
    $jabatan1 = mysqli_real_escape_string($db_connect, $_POST['jabatan1']);
    $nama_ttd2 = mysqli_real_escape_string($db_connect, $_POST['nama_ttd2']);
    $jabatan2 = mysqli_real_escape_string($db_connect, $_POST['jabatan2']);
    $bahasa = mysqli_real_escape_string($db_connect, $_POST['bahasa']);
    $ukuran = mysqli_real_escape_string($db_connect, $_POST['ukuran']);
    $font_sertifikat = mysqli_real_escape_string($db_connect, $_POST['font_sertifikat']);
    $font_nama = mysqli_real_escape_string($db_connect, $_POST['font_nama']);

    // File uploads
    $scan_ttd1 = $_FILES['scan_ttd1']['name'];
    $scan_ttd2 = $_FILES['scan_ttd2']['name'];
    $background = $_FILES['background']['name'];
    $logo_organisasi = $_FILES['logo_organisasi']['name'];
    $logo_sponsor = $_FILES['logo_sponsor']['name'];

    // File upload directory
    $upload_dir = './uploads/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Move uploaded files to server directory
    if (!empty($_FILES['scan_ttd1']['tmp_name'])) move_uploaded_file($_FILES['scan_ttd1']['tmp_name'], $upload_dir . $scan_ttd1);
    if (!empty($_FILES['scan_ttd2']['tmp_name'])) move_uploaded_file($_FILES['scan_ttd2']['tmp_name'], $upload_dir . $scan_ttd2);
    if (!empty($_FILES['background']['tmp_name'])) move_uploaded_file($_FILES['background']['tmp_name'], $upload_dir . $background);
    if (!empty($_FILES['logo_organisasi']['tmp_name'])) move_uploaded_file($_FILES['logo_organisasi']['tmp_name'], $upload_dir . $logo_organisasi);
    if (!empty($_FILES['logo_sponsor']['tmp_name'])) move_uploaded_file($_FILES['logo_sponsor']['tmp_name'], $upload_dir . $logo_sponsor);


    // Query update data kegiatan
    if (!mysqli_query($db_connect, "UPDATE kegiatan SET 
        nama_penyelenggara = '$nama_penyelenggara', 
        nama_kegiatan = '$nama_kegiatan', 
        tema = '$tema', 
        tanggal_kegiatan = '$tanggal_kegiatan', 
        tempat_via = '$tempat_via', 
        penomoran_sertifikat = '$penomoran_sertifikat'
        WHERE id_kegiatan = $id_kegiatan")) {
        die("Error updating kegiatan: " . mysqli_error($db_connect));
    }

    // Query update data template sertifikat
    if (!mysqli_query($db_connect, "UPDATE template_sertifikat SET 
    NamaTtd1 = '$nama_ttd1', 
    jabatan1 = '$jabatan1', 
    NamaTtd2 = '$nama_ttd2', 
    jabatan2 = '$jabatan2', 
    bahasa = '$bahasa', 
    ukuran = '$ukuran', 
    fontCertificate = '$font_sertifikat', 
    fontNama = '$font_nama', 
    scanTtd1 = IF('$scan_ttd1' != '', '$scan_ttd1', scanTtd1), 
    scanTtd2 = IF('$scan_ttd2' != '', '$scan_ttd2', scanTtd2), 
    background = IF('$background' != '', '$background', background), 
    logo_organisasi = IF('$logo_organisasi' != '', '$logo_organisasi', logo_organisasi), 
    logo_sponsor = IF('$logo_sponsor' != '', '$logo_sponsor', logo_sponsor) 
    WHERE id_kegiatan = $id_kegiatan")) {
    die("Error updating template_sertifikat: " . mysqli_error($db_connect));
    }



    // Redirect ke halaman data kegiatan
    header("Location: kegiatan.php");
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kegiatan | Tivicate-DKM Jamie Su'ada</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="dashboard-style.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
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
            <h3 class="page-title">Edit Kegiatan</h3>
            <br>

            <form method="POST" enctype="multipart/form-data">
                <!-- Form Halaman 1 -->
                <div id="page1" class="form-container">
                    <h5 class="fw-bold" style="position: relative; display: inline-block; padding-bottom: 4px; border-bottom: 2px solid #000; margin-bottom: 16px;">Kegiatan</h5>
                    <br>
                    <div class="col">
                        <label for="penyelenggara">Nama Penyelenggara:</label>
                        <input type="text" id="penyelenggara" name="nama_penyelenggara" value="<?= $data['nama_penyelenggara']; ?>" required style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Nama Penyelenggara">
                        <br><br>
                        <label for="nama_kegiatan">Nama Kegiatan:</label>
                        <input type="text" id="nama_kegiatan" name="nama_kegiatan" value="<?= $data['nama_kegiatan']; ?>" required style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Nama Kegiatan">
                        <br><br>
                        <label for="tema">Tema:</label>
                        <input type="text" id="tema" name="tema" value="<?= $data['tema']; ?>" required style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Tema">
                        <br><br>
                        <label for="tanggal_kegiatan">Tanggal Kegiatan:</label>
                        <input type="date" id="tanggal_kegiatan" name="tanggal_kegiatan" value="<?= $data['tanggal_kegiatan']; ?>" required style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Tanggal Kegiatan">
                        <br><br>
                        <label for="tempat">Tempat:</label>
                        <input type="text" id="tempat" name="tempat_via" value="<?= $data['tempat_via']; ?>"required style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Tempat Kegiatan">
                        <br><br>
                        <label for="penomoran_sertifikat">Penomoran Sertifikat:</label>
                        <input type="text" id="penomoran_sertifikat" name="penomoran_sertifikat" value="<?= $data['penomoran_sertifikat']; ?>" required style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Nomor Sertifikat">
                    </div>
                </div>

                <!-- Form Halaman 2 -->
                <div id="page2" class="form-container" style="display: none;">
                    <h5 class="fw-bold" style="position: relative; display: inline-block; padding-bottom: 4px; border-bottom: 2px solid #000; margin-bottom: 16px;">Footer</h5>
                    <br>
                    <div class="col">
                        <label for="footer_text">Nama Ttd 1:</label>
                        <input type="text" id="footer_text" name="nama_ttd1" value="<?= $data['NamaTtd1'] ?? ''; ?>" style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Nama Penandatangan 1" required>
                        <br><br>
                        <label for="footer_text">Jabatan 1:</label>
                        <input type="text" id="footer_text" name="jabatan1" value="<?= $data['jabatan1'] ?? ''; ?>" style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Jabatan 1" required>
                        <br><br>
                        <label for="image" class="form-label">Scan Ttd 1</label>
                        <div style="display: flex; flex-direction: column; gap: 10px">
                            <p>Gambar sebelumnya</p>
                            <img class="img-thumbnail" style="width: 110px" src="uploads/<?= $data['scanTtd1'] ?>" alt="">
                        </div>
                        <input type="file" class="form-control" id="image" name="scan_ttd1" accept=".jpg,.jpeg,.png">
                        <br><br>
                        <label for="footer_text">Nama Ttd 2:</label>
                        <input type="text" id="footer_text" name="nama_ttd2" value="<?= $data['namaTtd2'] ?? ''; ?>" style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Penandatangan 2" required>
                        <br><br>
                        <label for="footer_text">Jabatan 2:</label>
                        <input type="text" id="footer_text" name="jabatan2" value="<?= $data['jabatan2'] ?? ''; ?>" style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Jabatan 2 " required>
                        <br><br>
                        <label for="image" class="form-label">Scan Ttd 2</label>
                        <div style="display: flex; flex-direction: column; gap: 10px">
                            <p>Gambar sebelumnya</p>
                            <img class="img-thumbnail" style="width: 110px" src="uploads/<?= $data['scanTtd2'] ?>" alt="">
                        </div>
                        <input type="file" class="form-control" id="image" name="scan_ttd2" accept=".jpg,.jpeg,.png">
                    </div>
                </div>

                <!-- Form Halaman 3 -->
                <div id="page3" class="form-container" style="display: none;">
                    <h5 class="fw-bold" style="position: relative; display: inline-block; padding-bottom: 4px; border-bottom: 2px solid #000; margin-bottom: 16px;">Background & Logo</h5>
                    <br>
                    <div class="col">
                        <label for="image" class="form-label">Background</label>
                        <div style="display: flex; flex-direction: column; gap: 10px">
                            <p>Gambar sebelumnya</p>
                            <img class="img-thumbnail" style="width: 110px" src="uploads/<?= $data['background'] ?>" alt="">
                        </div>
                        <input type="file" class="form-control" id="image" name="background" accept=".jpg,.jpeg,.png">
                        <br>
                        <label for="logo" class="form-label">Logo Organisasi</label>
                        <div style="display: flex; flex-direction: column; gap: 10px">
                            <p>Gambar sebelumnya</p>
                            <img class="img-thumbnail" style="width: 110px" src="uploads/<?= $data['logo_organisasi'] ?>" alt="">
                        </div>
                        <input type="file" class="form-control" id="logo" name="logo_organisasi" accept=".jpg,.jpeg,.png">
                        <br>
                        <label for="logo" class="form-label">Logo Sponsor</label>
                        <div style="display: flex; flex-direction: column; gap: 10px">
                            <p>Gambar sebelumnya</p>
                            <img class="img-thumbnail" style="width: 110px" src="uploads/<?= $data['logo_sponsor'] ?>" alt="">
                        </div>
                        <input type="file" class="form-control" id="logo" name="logo_sponsor" accept=".jpg,.jpeg,.png">
                        <br>
                        <label for="language" class="form-label">Bahasa</label>
                        <select class="form-select" id="language" name="bahasa" value="<?= $data['bahasa'] ?? ''; ?>" required>
                            <option value="" disabled selected>Pilih Bahasa</option>
                            <option value="id" <?= ($data['bahasa'] == 'id') ? 'selected' : ''; ?>>Indonesia</option>
                            <option value="en" <?= ($data['bahasa'] == 'en') ? 'selected' : ''; ?>>English</option>
                        </select>
                        <br>
                        <label for="layout" class="form-label">Ukuran</label>
                        <select class="form-select" id="layout" name="ukuran" value="<?= $data['ukuran'] ?? ''; ?>" required>
                            <option value="" disabled selected>Pilih Ukuran</option>
                            <option value="landscape_center" <?= ($data['ukuran'] == 'landscape_center') ? 'selected' : ''; ?>>Landscape Center</option>
                            <option value="landscape_left" <?= ($data['ukuran'] == 'landscape_left') ? 'selected' : ''; ?>>Landscape Left</option>
                            <option value="potrait_center" <?= ($data['ukuran'] == 'potrait_center') ? 'selected' : ''; ?>>Potrait Center</option>
                        </select>
                        <br>
                        <label for="font" class="form-label">Font Certificate</label>
                        <select class="form-select" id="font" name="font_sertifikat" value="<?= $data['fontCertificate'] ?? ''; ?>" required>
                            <option value="" disabled selected>Pilih Font</option>
                            <option value="minatur" <?= ($data['fontCertificate'] == 'minatur') ? 'selected' : ''; ?>>Minatur</option>
                            <option value="times_new_roman" <?= ($data['fontCertificate'] == 'times_new_roman') ? 'selected' : ''; ?>>Times New Roman</option>
                            <option value="garamond" <?= ($data['fontCertificate'] == 'garamond') ? 'selected' : ''; ?>>Garamond</option>
                            <option value="boldena" <?= ($data['fontCertificate'] == 'boldena') ? 'selected' : ''; ?>>Boldena</option>
                        </select>
                        <br>
                        <label for="font" class="form-label">Font Nama</label>
                        <select class="form-select" id="font" name="font_nama" value="<?= $data['fontNama'] ?? ''; ?>" required>
                            <option value="" disabled selected>Pilih Font</option>
                            <option value="arial" <?= ($data['fontNama'] == 'arial') ? 'selected' : ''; ?>>Arial</option>
                            <option value="times_new_roman" <?= ($data['fontNama'] == 'times_new_roman') ? 'selected' : ''; ?>>Times New Roman</option>
                            <option value="boldena" <?= ($data['fontNama'] == 'boldena') ? 'selected' : ''; ?>>Boldena</option>
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