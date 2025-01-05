<?php
require './config/db.php';

// Mendapatkan ID kegiatan dari parameter URL
$id_kegiatan = intval($_GET['id']);

// Ambil semua file sertifikat yang terkait dengan kegiatan
$query_files = "
    SELECT s.hasil_sertifikat
    FROM sertifikat s
    INNER JOIN template_sertifikat ts ON s.id_template = ts.id_template
    WHERE ts.id_kegiatan = $id_kegiatan
";

$result_files = mysqli_query($db_connect, $query_files);

if ($result_files) {
    while ($row = mysqli_fetch_assoc($result_files)) {
        $file_path = 'certificates/' . $row['hasil_sertifikat'];
        // Hapus file dari direktori jika ada
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
}

// Hapus data kegiatan (otomatis menghapus data terkait di tabel lain melalui cascading delete)
mysqli_query($db_connect, "
    DELETE FROM kegiatan
    WHERE id_kegiatan = $id_kegiatan
");

// Redirect ke halaman data kegiatan
header("Location: kegiatan.php");
exit();
?>
