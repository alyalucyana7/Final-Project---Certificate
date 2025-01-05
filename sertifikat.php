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
    <title>Data Sertifikat | Tivicate-DKM Jamie Su'ada</title>
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
            margin: 0;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1000; /* Pastikan sidebar selalu berada di atas */
        }

        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            background: #fff;
        }

        .content {
            flex-grow: 1;
            background: #fff;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 0px; /* Tambahkan margin bawah agar tidak menabrak footer */
        }

        .footer {
            background-color: #f8f9fa;
            text-align: center;
            padding: 10px 0;
            margin-top: auto; /* Membuat footer berada di bagian bawah */
            box-shadow: 0 -1px 5px rgba(0, 0, 0, 0.1);
            z-index: 10;
            position: relative;
            width: 100%;
            border-top: 1px solid #ddd;
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
            <li><a href="dashboard.php"><i class="bi bi-house-door-fill"></i> <b>Dashboard</b></a></li>
            <hr>
            <li><a href="kegiatan.php" ><i class="bi bi-calendar3"></i> <b>Data Kegiatan</b></a></li>
            <li><a href="sertifikat.php" class="active"><i class="bi bi-filetype-pdf"></i> <b>Sertifikat</b></a></li>
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
            <h4 class="page-title">Data Sertifikat</h4>
            </br>
            </br>
            <!-- Tabel -->
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
                            <td><?php if ($peserta_count == 0): ?>
                                    <a href="up_peserta.php?id=<?= $row['id_kegiatan']; ?>" class="btn btn-info btn-sm"><i class="bi bi-upload"></i></a>
                                <?php else: ?>
                                    <span><?= $peserta_count; ?></span>
                                <?php endif; ?></td>
                            <td>
                                <a href="data_peserta.php?id=<?= $row['id_kegiatan']; ?>" class="btn btn-info btn-sm">Data Peserta</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <footer class="footer">
            <p>&copy; 2024 Su'ada. All Rights Reserved.</p>
        </footer>
    </div>

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