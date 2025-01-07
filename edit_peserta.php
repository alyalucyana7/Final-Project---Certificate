<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ./login.php');
    exit;
}

require './config/db.php';

// Mendapatkan ID peserta dari parameter URL
$id_peserta = $_GET['id_peserta'];

// Query untuk mengambil data peserta, termasuk nama peringkat dan jenis lomba
$query = "
    SELECT peserta.*, peringkat.peringkat AS nama_peringkat, jenislomba.jenis_lomba AS nama_jenislomba
    FROM peserta
    LEFT JOIN peringkat ON peserta.id_peringkat = peringkat.id_peringkat
    LEFT JOIN jenislomba ON peserta.id_jenislomba = jenislomba.id_jenislomba
    WHERE peserta.id_peserta = $id_peserta
";
$peserta = mysqli_query($db_connect, $query);
$row = mysqli_fetch_assoc($peserta);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Data dari form
    $nama_peserta = mysqli_real_escape_string($db_connect, $_POST['peserta']);
    $no_telepon = mysqli_real_escape_string($db_connect, $_POST['no_telepon']);
    $email = mysqli_real_escape_string($db_connect, $_POST['email']);
    $alamat = mysqli_real_escape_string($db_connect, $_POST['alamat']);
    $status = mysqli_real_escape_string($db_connect, $_POST['status']);
    $peringkat = mysqli_real_escape_string($db_connect, $_POST['peringkat']);
    $jenis_lomba = mysqli_real_escape_string($db_connect, $_POST['jenis_lomba']);

    // Update atau tambahkan data ke tabel peringkat
    $sql_peringkat = "SELECT id_peringkat FROM peringkat WHERE peringkat = '$peringkat'";
    $result_peringkat = mysqli_query($db_connect, $sql_peringkat);

    if (mysqli_num_rows($result_peringkat) > 0) {
        $id_peringkat = mysqli_fetch_assoc($result_peringkat)['id_peringkat'];
    } else {
        $insert_peringkat = "INSERT INTO peringkat (peringkat) VALUES ('$peringkat')";
        if (mysqli_query($db_connect, $insert_peringkat)) {
            $id_peringkat = mysqli_insert_id($db_connect);
        } else {
            echo "Error inserting peringkat: " . mysqli_error($db_connect);
            exit();
        }
    }

    // Update atau tambahkan data ke tabel jenislomba
    $sql_jenislomba = "SELECT id_jenislomba FROM jenislomba WHERE jenis_lomba = '$jenis_lomba'";
    $result_jenislomba = mysqli_query($db_connect, $sql_jenislomba);

    if (mysqli_num_rows($result_jenislomba) > 0) {
        $id_jenislomba = mysqli_fetch_assoc($result_jenislomba)['id_jenislomba'];
    } else {
        $insert_jenislomba = "INSERT INTO jenislomba (jenis_lomba) VALUES ('$jenis_lomba')";
        if (mysqli_query($db_connect, $insert_jenislomba)) {
            $id_jenislomba = mysqli_insert_id($db_connect);
        } else {
            echo "Error inserting jenis lomba: " . mysqli_error($db_connect);
            exit();
        }
    }

    // Update data peserta dengan ID dari tabel peringkat dan jenislomba
    $update_peserta = "
        UPDATE peserta 
        SET 
            nama_peserta = '$nama_peserta', 
            no_telepon = '$no_telepon', 
            email = '$email', 
            alamat = '$alamat', 
            status = '$status', 
            id_peringkat = $id_peringkat, 
            id_jenislomba = $id_jenislomba
        WHERE id_peserta = $id_peserta
    ";

    if (mysqli_query($db_connect, $update_peserta)) {
        // Redirect ke halaman data peserta
        header("Location: up_peserta.php?id=" . $_GET['id']);
        exit();
    } else {
        echo "Error updating peserta: " . mysqli_error($db_connect);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Peserta | Tivicate-DKM Jamie Su'ada</title>
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
            <!-- Header -->
            <h3 class="page-title">Edit Peserta</h3>
            <br>

            <form method="POST">
            <!-- Form Halaman 1 -->
            <div id="page1" class="form-container">
                <h5 class="fw-bold" style="position: relative; display: inline-block; padding-bottom: 4px; border-bottom: 2px solid #000; margin-bottom: 16px;">Peserta</h5>
                <br>
                <div class="col">
                    <label for="peserta">Nama Peserta:</label>
                    <input type="text" id="peserta" name="peserta" value="<?= $row['nama_peserta']; ?>" required style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Nama Peserta">
                    <br><br>

                    <label for="no_telepon">Nomor Telepon:</label>
                    <input type="tel" id="no_telepon" name="no_telepon" value="<?= $row['no_telepon']; ?>" required style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Nomor Telepon">
                    <br><br>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?= $row['email']; ?>" required style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Email">
                    <br><br>

                    <label for="alamat">Alamat:</label>
                    <input type="text" id="alamat" name="alamat" value="<?= $row['alamat']; ?>" required style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Alamat">
                    <br><br>

                    <label for="status">Status:</label>
                    <input type="text" id="status" name="status" value="<?= $row['status']; ?>" required style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Status">
                    <br><br>

                    <label for="peringkat">Peringkat:</label>
                    <input type="text" id="peringkat" name="peringkat" value="<?= $row['nama_peringkat']; ?>" required style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Peringkat">
                    <br><br>

                    <label for="jenis_lomba">Jenis Lomba:</label>
                    <input type="text" id="jenis_lomba" name="jenis_lomba" value="<?= $row['nama_jenislomba']; ?>" required style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Jenis Lomba">
                </div>
            </div>
            <br>
            <div class="d-flex">
                <button type="submit" name="update" class="btn btn-primary ms-auto me-2">Update</button>
                <a href="up_peserta.php?id=<?= $_GET['id']; ?>" type="button" class="btn btn-danger">Kembali</a>
            </div>
            </form>
        </div>
    </div>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            sidebar.classList.toggle('show');
            mainContent.classList.toggle('full');
        }
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
