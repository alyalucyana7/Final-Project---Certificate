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
    <title>Data Kegiatan | Tivicate-DKM Jamie Su'ada</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="dashboard-style.css">
    
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



        /* Default untuk laptop/desktop */

        
        .btn-primary .icon {
            font-size: 20px; /* Ukuran ikon */
        }
        
        .btn-primary .text {
            display: inline; /* Teks terlihat di layar besar */
        }
        
        /* Untuk layar kecil (Android atau smartphone) */
        @media (max-width: 768px) {
            .btn-primary .text {
                display: none; /* Sembunyikan teks */
            }
        
            .btn-primary .icon {
                font-size: 24px; /* Perbesar ikon jika diperlukan */
            }
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
            <li><a href="dashboard.php"><i class="bi bi-house-door-fill"></i> <b>Dashboard</b></a></li>
            <hr>
            <li><a href="kegiatan.php" class="active"><i class="bi bi-calendar3"></i> <b>Data Kegiatan</b></a></li>
            <li><a href="sertifikat.php"><i class="bi bi-filetype-pdf"></i> <b>Sertifikat</b></a></li>
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
            <h4 class="page-title">Data Kegiatan</h4>
            <div class="d-flex justify-content-end mb-3">
                <a href="add_kegiatan.php" class="btn btn-primary" onclick="tambahKegiatan()">
                    <span class="icon"><i class="bi bi-plus"></i></span>
                    <span class="text">Tambah Kegiatan</span>
                </a>
            </div>

            <div class="table-container">
                <!-- Table -->
                <table id="dataKegiatan" class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kegiatan</th>
                            <th>Penyelenggara</th>
                            <th>Tanggal</th>
                            <th>Peserta</th>
                            <th>Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            require './config/db.php';
    
                            $kegiatan = mysqli_query($db_connect, "SELECT * FROM kegiatan");
                            $no = 1;
    
                            while($row = mysqli_fetch_assoc($kegiatan)) {
                                $id_kegiatan = $row['id_kegiatan'];
    
                            //Hitung jumlah peserta berdasarkan id_kegiatan
                            $peserta_count_query = mysqli_query($db_connect, "SELECT COUNT(*) as total_peserta FROM peserta WHERE id_kegiatan='$id_kegiatan'");
                            $peserta_count = mysqli_fetch_assoc($peserta_count_query)['total_peserta'];
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $row['nama_kegiatan']; ?></td>
                                <td><?= $row['nama_penyelenggara']; ?></td>
                                <td><?= $row['tanggal_kegiatan']; ?></td>
                                <td><?= $peserta_count; ?></td>
                                <td>
                                    <a href="up_peserta.php?id=<?= $row['id_kegiatan']; ?>" class="btn btn-info btn-sm "><i class="bi bi-upload"></i></a>
                                    <a href="edit_kegiatan.php?id=<?=$row['id_kegiatan'];?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
                                    <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="confirmDelete('delete_kegiatan.php?id=<?= $row['id_kegiatan']; ?>')"><i class="bi bi-trash3"></i></a>
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
            $('#dataKegiatan').DataTable(); // Mengaktifkan fitur interaktif pada tabel
        });
    </script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Periksa parameter status di URL
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');

        if (status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data peserta berhasil diupload.',
            }).then(() => {
                // Hapus parameter status dari URL
                window.history.replaceState(null, null, window.location.pathname);
            });
        } else if (status === 'error') {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat upload data peserta.',
            }).then(() => {
                // Hapus parameter status dari URL
                window.history.replaceState(null, null, window.location.pathname);
            });
        }
    </script>

    <script>
        // sweetalert untuk hapus data kegiatan
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
