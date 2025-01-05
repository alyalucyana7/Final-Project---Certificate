<?php

require './../config/db.php';

if (isset($_POST['submit'])) {
    global $db_connect;

    // Data dari form "Kegiatan"
    $nama_penyelenggara = mysqli_real_escape_string($db_connect, $_POST['nama_penyelenggara']);
    $nama_kegiatan = mysqli_real_escape_string($db_connect, $_POST['nama_kegiatan']);
    $tema = mysqli_real_escape_string($db_connect, $_POST['tema']);
    $tanggal_kegiatan = date('Y-m-d', strtotime($_POST['tanggal_kegiatan']));
    $tempat_via = mysqli_real_escape_string($db_connect, $_POST['tempat_via']);
    $penomoran_sertifikat = mysqli_real_escape_string($db_connect, $_POST['penomoran_sertifikat']);

    // Data dari form "Footer"
    $nama_ttd1 = mysqli_real_escape_string($db_connect, $_POST['nama_ttd1']);
    $jabatan1 = mysqli_real_escape_string($db_connect, $_POST['jabatan1']);
    $scan_ttd1 = $_FILES['scan_ttd1']['name'];
    $nama_ttd2 = mysqli_real_escape_string($db_connect, $_POST['nama_ttd2']);
    $jabatan2 = mysqli_real_escape_string($db_connect, $_POST['jabatan2']);
    $scan_ttd2 = $_FILES['scan_ttd2']['name'];

    // Data dari form "Background & Logo"
    $background = $_FILES['background']['name'];
    $logo_organisasi = $_FILES['logo_organisasi']['name'];
    $logo_sponsor = $_FILES['logo_sponsor']['name'];
    $bahasa = mysqli_real_escape_string($db_connect, $_POST['bahasa']);
    $ukuran = mysqli_real_escape_string($db_connect, $_POST['ukuran']);
    $font_sertifikat = mysqli_real_escape_string($db_connect, $_POST['font_sertifikat']);
    $font_nama = mysqli_real_escape_string($db_connect, $_POST['font_nama']);

    // Folder tujuan untuk upload file
    $upload_dir = '../uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Fungsi untuk memvalidasi format file
    function validate_image($file_name) {
        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        return in_array($file_extension, $allowed_extensions);
    }

    // Validasi file sebelum diunggah
    if (
        validate_image($scan_ttd1) && validate_image($scan_ttd2) &&
        validate_image($background) && validate_image($logo_organisasi) && validate_image($logo_sponsor)
    ) {
        // Upload file
        move_uploaded_file($_FILES['scan_ttd1']['tmp_name'], $upload_dir . $scan_ttd1);
        move_uploaded_file($_FILES['scan_ttd2']['tmp_name'], $upload_dir . $scan_ttd2);
        move_uploaded_file($_FILES['background']['tmp_name'], $upload_dir . $background);
        move_uploaded_file($_FILES['logo_organisasi']['tmp_name'], $upload_dir . $logo_organisasi);
        move_uploaded_file($_FILES['logo_sponsor']['tmp_name'], $upload_dir . $logo_sponsor);

        // Query untuk menyimpan ke tabel kegiatan
        $sql_kegiatan = "INSERT INTO kegiatan (nama_penyelenggara, nama_kegiatan, tema, tanggal_kegiatan, tempat_via, penomoran_sertifikat) 
                         VALUES ('$nama_penyelenggara', '$nama_kegiatan', '$tema', '$tanggal_kegiatan', '$tempat_via', '$penomoran_sertifikat')";

        if (mysqli_query($db_connect, $sql_kegiatan)) {
            $id_kegiatan = mysqli_insert_id($db_connect);

            // Query untuk menyimpan ke tabel template_sertifikat
            $sql_template = "INSERT INTO template_sertifikat (id_kegiatan, logo_organisasi, logo_sponsor, background, bahasa, ukuran,
             fontCertificate, fontNama, NamaTtd1, jabatan1, scanTtd1, NamaTtd2, jabatan2, scanTtd2) 
                             VALUES ('$id_kegiatan', '$logo_organisasi', '$logo_sponsor', '$background', '$bahasa', '$ukuran', 
                             '$font_sertifikat', '$font_nama', '$nama_ttd1', '$jabatan1', '$scan_ttd1', '$nama_ttd2', '$jabatan2', '$scan_ttd2')";

            if (mysqli_query($db_connect, $sql_template)) {
                header("Location: ../kegiatan.php?status=success");
                exit();
            } else {
                echo "Error pada query template: " . $sql_template . "<br>" . mysqli_error($db_connect);
            }
        } else {
            echo "Error pada query kegiatan: " . $sql_kegiatan . "<br>" . mysqli_error($db_connect);
        }
    } else {
        echo "Hanya file dengan format JPG, JPEG, atau PNG yang diperbolehkan.";
    }

    mysqli_close($db_connect);
} else {
    echo "Form tidak disubmit dengan benar.";
}

?>