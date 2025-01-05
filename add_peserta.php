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
    <title>Tambah Peserta | Tivicate-DKM Jamie Su'ada</title>
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
            <h3 class="page-title">Tambah Peserta</h3>
            <br>
            <?php
                 require './config/db.php';
                // Ambil ID kegiatan dari parameter URL 
                $id_kegiatan = $_GET['id'];

                $kegiatan = mysqli_query($db_connect, "SELECT * FROM kegiatan WHERE id_kegiatan = '$id_kegiatan'");
                while($row = mysqli_fetch_assoc($kegiatan)) {
            ?>
                <form action="./backend/add_peserta.php?id=<?=$row['id_kegiatan'];?>" method="POST" enctype="multipart/form-data">
            <?php }?>
            <!-- Form Halaman 1 -->
            <div id="page1" class="form-container">
                <h5 class="fw-bold" style="position: relative; display: inline-block; padding-bottom: 4px; border-bottom: 2px solid #000; margin-bottom: 16px;">Peserta</h5>
                <br>
                <div class="col">
                    <label for="peserta">Nama Peserta:</label>
                    <input type="text" id="peserta" name="peserta" required style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Nama Peserta">
                    <br><br>

                    <label for="no_telepon">Nomor Telepon:</label>
                    <input type="tel" id="no_telepon" name="no_telepon" required style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Nomor Telepon">
                    <br><br>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Email">
                    <br><br>

                    <label for="alamat">Alamat:</label>
                    <input type="text" id="alamat" name="alamat" required style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Alamat">
                    <br><br>

                    <label for="status">Status:</label>
                    <input type="text" id="status" name="status" required style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Status">
                    <br><br>
                    
                    <label for="peringkat">Peringkat:</label>
                    <input type="text" id="peringkat" name="peringkat" required style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Peringkat">
                    <br><br>
                    
                    <label for="jenis_lomba">Jenis Lomba:</label>
                    <input type="text" id="jenis_lomba" name="jenis_lomba" required style="width: 100%; padding: 5px; box-sizing: border-box;" placeholder="Masukkan Jenis Lomba">
                </div>
            </div>
            <br>
            <div class="d-flex">
                <button type="submit" class="btn btn-primary ms-auto me-2" name="submit">Simpan</button>
                <a href="up_peserta.php?id=<?= $_GET['id']; ?>" type="button" class="btn btn-danger">Kembali</a>
            </div>
            </form>
        </div>
    </div> 
    
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