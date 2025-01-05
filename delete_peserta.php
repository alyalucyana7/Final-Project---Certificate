<?php
require './config/db.php';

// Periksa apakah parameter id_peserta dan id ada
if (!isset($_GET['id_peserta']) || !isset($_GET['id'])) {
    die("Parameter ID peserta atau ID kegiatan tidak ditemukan di URL.");
}

$id_peserta = intval($_GET['id_peserta']);
$id_kegiatan = intval($_GET['id']);

// Ambil nama file sertifikat terkait dengan peserta
$query = "SELECT hasil_sertifikat FROM sertifikat WHERE id_peserta = $id_peserta";
$result = mysqli_query($db_connect, $query);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $file_path = 'certificates/' . $row['hasil_sertifikat'];

        // Periksa apakah file ada di folder sebelum dihapus
        if (file_exists($file_path)) {
            unlink($file_path); // Hapus file dari folder
        }
    }
}

// Hapus data sertifikat terkait peserta
mysqli_query($db_connect, "DELETE FROM sertifikat WHERE id_peserta = $id_peserta");

// Hapus peserta berdasarkan ID
mysqli_query($db_connect, "DELETE FROM peserta WHERE id_peserta = $id_peserta");

if ($delete_sertifikat && $delete_peserta) {
    header("Location: up_peserta.php?id=$id_kegiatan&status=success&action=delete");
} else {
    header("Location: up_peserta.php?id=$id_kegiatan&status=error&action=delete");
}

// Redirect kembali ke halaman peserta
header("Location: up_peserta.php?id=$id_kegiatan");
exit();


?>