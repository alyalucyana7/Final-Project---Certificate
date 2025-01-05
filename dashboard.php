<?php 

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ./login.php'); // Redirect ke halaman login jika belum login
    exit;
}

require './config/db.php';

class Dashboard {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }

    public function getTotalKegiatan() {
        $sql_kegiatan = "SELECT COUNT(*) AS total_kegiatan FROM kegiatan";
        $result_kegiatan = $this->db->query($sql_kegiatan);

        $total_kegiatan = 0;
        if ($result_kegiatan->num_rows > 0) {
            $row_kegiatan = $result_kegiatan->fetch_assoc();
            $total_kegiatan = $row_kegiatan['total_kegiatan'];
        }
        return $total_kegiatan;
    }

    public function getTotalSertifikat() {
        $sql_sertifikat = "SELECT COUNT(*) AS total_sertifikat FROM peserta";
        $result_sertifikat = $this->db->query($sql_sertifikat);

        $total_sertifikat = 0;
        if ($result_sertifikat->num_rows > 0) {
            $row_sertifikat = $result_sertifikat->fetch_assoc();
            $total_sertifikat = $row_sertifikat['total_sertifikat'];
        }
        return $total_sertifikat;
    }
}

// Membuat objek Dashboard
$dashboard = new Dashboard($db_connect);

// Mendapatkan jumlah data kegiatan dan sertifikat
$total_kegiatan = $dashboard->getTotalKegiatan();
$total_sertifikat = $dashboard->getTotalSertifikat();

// Menutup koneksi database
$db_connect->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Tivicate-DKM Jamie Su'ada</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="dashboard-style.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">


</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <img src="./images/dkm.jpeg" alt="Tivicate Logo">
            <hr>
        </div>
        <ul>
            <li><a href="#" class="active"><i class="bi bi-house-door-fill"></i> <b>Dashboard</b></a></li>
            <hr>
            <li><a href="kegiatan.php"><i class="bi bi-calendar3"></i> <b>Data Kegiatan</b></a></li>
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
        <div class="d-flex">
            <!-- Content -->
            <div class="p-4 flex-fill">
                <h3 class="bi bi-house-door-fill"> <i>Dashboard</i></h3>
                <hr>
                <br>
                <div class="row mt-4">
                    <div class="col-md-6 mb-4">
                        <a href="kegiatan.php" style="text-decoration: none;">
                            <div class="dashboard-card d-flex align-items-center" style="background-color: #FF9F43; padding: 15px; border-radius: 8px; color: white;">
                                <i class="bi bi-calendar3" style="font-size: 2.5rem; margin-right: 15px;"></i>
                                <div>   
                                    <h6 style="font-size: 1.1rem; font-weight: bold;">DATA KEGIATAN</h6>
                                    <p style="font-size: 1rem; margin: 0;">Jumlah: <?php echo $total_kegiatan; ?></b></p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Card Sertifikat -->
                    <div class="col-md-6 mb-4">
                        <a href="sertifikat.php" style="text-decoration: none;">
                            <div class="dashboard-card d-flex align-items-center" style="background-color: #4CAF50; padding: 15px; border-radius: 8px; color: white; font-family: 'Dancing Script', cursive;">
                                <i class="bi bi-filetype-pdf" style="font-size: 2.5rem; margin-right: 15px;"></i>
                                <div>
                                    <h6 style="font-size: 1.1rem; font-weight: bold;">SERTIFIKAT</h6>
                                    <p style="font-size: 1rem; margin: 0;">Jumlah: <?php echo $total_sertifikat; ?></b></p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <footer class="footer">
            <p>&copy; 2024 Su'ada. All Rights Reserved.</p>
        </footer>
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
