<?php 

require './config/db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ./login.php'); // Redirect ke halaman login jika belum login
    exit;
}

// Periksa apakah parameter id tersedia
if (!isset($_GET['id'])) {
    die("Parameter ID kegiatan tidak ditemukan di URL.");
}

$id_kegiatan = $_GET['id']; // Pastikan parameter id tersedia sebelum digunakan
$db_connect->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Peserta | Tivicate-DKM Jamie Su'ada</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="dashboard-style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    
    <!-- Google Fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>
        body {
            overflow-y: auto;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: #607d9a;
            color: #fff;
            transition: transform 0.3s ease-in-out;
            z-index: 1000;
        }

        .sidebar.hidden {
            transform: translateX(-250px);
        }

        .sidebar .logo img {
            max-width: 100%;
            height: auto;
        }

        .main-content {
            margin-left: 250px;
            transition: margin-left 0.3s ease-in-out;
        }

        .main-content.full {
            margin-left: 0;
        }

        .menu-toggle {
            position: absolute;
            top: 15px;
            left: 15px;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #fff;
            z-index: 1100;
            transition: left 0.3s ease-in-out;
        }

        .sidebar.show ~ .menu-toggle {
            left: 265px;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-250px);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .menu-toggle {
                display: block;
                color: #343a40;
            }

            .sidebar.show ~ .menu-toggle {
                left: 265px;
            }
        }

        @media (min-width: 769px) {
            .menu-toggle {
                display: none;
            }
        }

        .content {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            white-space: nowrap;
        }

        td:last-child {
            max-width: 120px;
        }
        
        .dataTables_wrapper .dataTables_length label {
            display: flex !important; /* Gunakan flex untuk memastikan label rapi */
            align-items: center; /* Pusatkan dropdown secara vertikal */
            gap: 0.5rem; /* Atur jarak antar elemen */
        }
        
        @media (max-width: 768px) {
            .dataTables_wrapper .dataTables_length {
                display: block !important; /* Buat satu baris di layar kecil */
            }
        }

        .dataTables_wrapper .dataTables_filter {
            float: right; /* Kolom pencarian di kanan */
            margin-bottom: 1rem;
        }
        
        .dataTables_wrapper .dataTables_info {
            float: left; /* Informasi pagination di kiri bawah */
            margin-top: 1rem;
        }
        
        .dataTables_wrapper .dataTables_paginate {
            float: right; /* Navigasi "Previous/Next" di kanan bawah */
            margin-top: 1rem;
        }
        
        /* Atur tabel agar tetap responsif */
        table.dataTable {
            width: 100%;
        }
        
        /* Membuat kontainer tabel dengan scroll horizontal */
        .content .table-container {
            overflow-x: auto; /* Scroll horizontal */
            -webkit-overflow-scrolling: touch; /* Smooth scrolling untuk perangkat mobile */
        }

        /* Pastikan tabel tidak memengaruhi elemen lain */
        .content .table-container table {
            width: 100%; /* Optional: Supaya tabel menyesuaikan */
            min-width: 600px; /* Optional: Tetapkan lebar minimum */
        }


        .footer {
            background-color: #f8f9fa;
            text-align: center;
            padding: 10px 0;
            margin-top: auto;
            box-shadow: 0 -1px 5px rgba(0, 0, 0, 0.1);
            z-index: 10;
            position: relative;
            width: 100%;
            border-top: 1px solid #ddd;
        }
    </style>

</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="logo">
            <img src="./images/dkm.jpeg" alt="Tivicate Logo">
          <hr>
        </div>
        <ul>
            <li><a href="dashboard.php" ><i class="bi bi-house-door-fill"></i> <b>Dashboard</b></a></li>
            <hr>
            <li><a href="kegiatan.php" class="active"><i class="bi bi-calendar3"></i> <b>Data Kegiatan</b></a></li>
            <li><a href="sertifikat.php" ><i class="bi bi-filetype-pdf"></i> <b>Sertifikat</b></a></li>
            <li><a href="javascript:void(0);" onclick="confirmLogout()"><i class="bi bi-box-arrow-left"></i> <b>Logout</b></a></li>
            <hr>
        </ul>
    </div>

    <button class="menu-toggle btn btn-primary ms-2 btn-lg" type="button" onclick="toggleSidebar()" style="background-color: #0d6efd; border-color: #0d6efd; color: white;">
        <i class="bi bi-list"></i>
    </button> 
    
    <div class="main-content" id="main-content">
        <div class="topbar">
            <span class="user-name"><?php include './backend/profil_user.php'; ?></span>
            <img src="./images/profil.png" alt="Admin Profile" class="profile-pic">
        </div>
        <div class="content">
            <h4 class="page-title">Upload Peserta</h4>
                <div class="card">
                    <div class="card-body">
                        <?php
                            require './config/db.php';
                            // Ambil ID kegiatan dari parameter URL 
                            $id_kegiatan = $_GET['id'];

                            $kegiatan = mysqli_query($db_connect, "SELECT * FROM kegiatan WHERE id_kegiatan = '$id_kegiatan'");
                            while($row = mysqli_fetch_assoc($kegiatan)) {
                        ?>
                            <form action="./backend/proses_upload.php?id=<?=$row['id_kegiatan'];?>" method="POST" enctype="multipart/form-data">
                        <?Php }?>

                        <div class="row mb-3">
                            <div class="col-lg-2">
                                <label for="fileUpload" class="form-label">File Excel (.XLSX)</label>
                            </div>
                            <div class="col-lg-10">
                                <input type="file" class="form-control" id="fileUpload" name="fileUpload" accept=".xlsx">
                                <small class="form-text text-muted">*Gunakan Template Excel yang tersedia</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-10 offset-lg-2">
                                <a href="template_data.php" class="btn btn-success" download>Download Template</a>
                                <button type="submit" class="btn btn-primary me-2" name="submit">Upload Data</button>
                                <a href="kegiatan.php" type="button" class="btn btn-danger">Kembali</a>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <?php
                                require './config/db.php';

                                // Ambil ID kegiatan dari parameter URL 
                                $id_kegiatan = $_GET['id'];

                                $kegiatan = mysqli_query($db_connect, "SELECT * FROM kegiatan WHERE id_kegiatan = '$id_kegiatan'");
                                while($row = mysqli_fetch_assoc($kegiatan)) {
                            ?>
                            <a href="add_peserta.php?id=<?=$row['id_kegiatan'];?>" class="btn btn-info">Tambah Peserta</a>
                            <?php }?>
                        </div>
                    </div>
                </div>
            </br>
            
            <div class="table-container">
                <!-- Tabel -->
                <table id="uploadPeserta" class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Peserta</th>
                            <th>No Hp</th>
                            <th>Email</th>
                            <th>Alamat</th>
                            <th>Status</th>
                            <th>Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            require './config/db.php';
    
                            // Ambil id_kegiatan dari request (pastikan nilai ini berasal dari input yang valid)
                            $id_kegiatan = $_GET['id'];
                            
                            // Query untuk memfilter peserta berdasarkan id_kegiatan
                            $peserta = mysqli_query($db_connect, "SELECT * FROM peserta WHERE id_kegiatan = '$id_kegiatan'");
                            $no = 1;
    
                            while($row = mysqli_fetch_assoc($peserta)) {
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $row['nama_peserta']; ?></td>
                                <td><?= $row['no_telepon']; ?></td>
                                <td><?= $row['email']; ?></td>
                                <td><?= $row['alamat']; ?></td>
                                <td><?= $row['status']; ?></td>
                                <td>
                                    <a href="edit_peserta.php?id_peserta=<?=$row['id_peserta'];?>&id=<?=$id_kegiatan;?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
                                    <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="confirmDelete('delete_peserta.php?id_peserta=<?= $row['id_peserta']; ?>&id=<?= $id_kegiatan; ?>')"><i class="bi bi-trash3"></i></a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <footer class="footer">
            <p>&copy; 2024 Su'ada. All Rights Reserved.</p>
        </footer>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            sidebar.classList.toggle('show');
            mainContent.classList.toggle('full');
        }
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#uploadPeserta').DataTable(); // Mengaktifkan fitur interaktif pada tabel
        });
    </script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    
    <script>
    // Sweet alert untuk upload dan tambah peserta
    // Periksa parameter status dan id di URL
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    const id = urlParams.get('id'); // Ambil id kegiatan dari URL

        if (status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data peserta berhasil diupload.',
            }).then(() => {
                // Redirect ke halaman up_peserta.php dengan id kegiatan
                window.location.href = `up_peserta.php?id=${id}`;
            });
        } else if (status === 'error') {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat upload data peserta.',
            }).then(() => {
                // Redirect ke halaman up_peserta.php dengan id kegiatan
                window.location.href = `up_peserta.php?id=${id}`;
            });
        }
    </script>

    <script>
        // sweetalert untuk hapus data peserta
        function confirmDelete(deleteUrl) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data peserta yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Arahkan ke URL hapus
                    window.location.href = deleteUrl;
                }
            });
        }
    </script>

    <script> 
    // Sweetalert untuk logout
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
            //Arahkan ke logout.php untuk menghapus session
            window.location.href = 'logout.php';
        }
    });
}
    </script>

</body>
</html>
