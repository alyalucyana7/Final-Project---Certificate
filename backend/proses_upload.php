<?php
require '../config/db.php'; // Koneksi ke database
require '../vendor/autoload.php'; // Memuat autoload dari PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;

// Memeriksa apakah file diunggah
if (isset($_FILES['fileUpload']['name'])) {
    $fileName = $_FILES['fileUpload']['name'];
    $fileTmp = $_FILES['fileUpload']['tmp_name'];
    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);

    // Memeriksa ekstensi file
    if ($fileExt === 'xlsx') {
        try {
            // Validasi apakah parameter id_kegiatan ada di URL
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $id_kegiatan = $_GET['id'];

                // Validasi apakah ID kegiatan ada di database
                $kegiatan = mysqli_query($db_connect, "SELECT * FROM kegiatan WHERE id_kegiatan = '$id_kegiatan'");
                if (mysqli_num_rows($kegiatan) > 0) {
                    // Membaca file Excel
                    $spreadsheet = IOFactory::load($fileTmp);
                    $worksheet = $spreadsheet->getActiveSheet();
                    $rows = $worksheet->toArray(null, true, true, true);

                    // Memulai transaksi
                    mysqli_begin_transaction($db_connect);

                    // Memasukkan data dari Excel ke database
                    foreach ($rows as $index => $row) {
                        if ($index === 1) continue; // Lewati baris header

                        $namaPeserta = mysqli_real_escape_string($db_connect, $row['A']);
                        $noTelepon = mysqli_real_escape_string($db_connect, $row['B']);
                        $email = mysqli_real_escape_string($db_connect, $row['C']);
                        $alamat = mysqli_real_escape_string($db_connect, $row['D']);
                        $status = mysqli_real_escape_string($db_connect, $row['E']);
                        $peringkat = mysqli_real_escape_string($db_connect, $row['F']);
                        $jenis_lomba = mysqli_real_escape_string($db_connect, $row['G']);

                        // Simpan data peringkat ke tabel `peringkat` jika belum ada
                        $sql_peringkat = "SELECT id_peringkat FROM peringkat WHERE peringkat = '$peringkat'";
                        $result_peringkat = mysqli_query($db_connect, $sql_peringkat);

                        if (mysqli_num_rows($result_peringkat) > 0) {
                            $row_peringkat = mysqli_fetch_assoc($result_peringkat);
                            $id_peringkat = $row_peringkat['id_peringkat'];
                        } else {
                            $insert_peringkat = "INSERT INTO peringkat (peringkat) VALUES ('$peringkat')";
                            if (mysqli_query($db_connect, $insert_peringkat)) {
                                $id_peringkat = mysqli_insert_id($db_connect);
                            } else {
                                echo "Error pada query peringkat: " . mysqli_error($db_connect);
                                exit();
                            }
                        }

                        // Simpan data jenis_lomba ke tabel `jenislomba` jika belum ada
                        $sql_jenis_lomba = "SELECT id_jenislomba FROM jenislomba WHERE jenis_lomba = '$jenis_lomba'";
                        $result_jenis_lomba = mysqli_query($db_connect, $sql_jenis_lomba);

                        if (mysqli_num_rows($result_jenis_lomba) > 0) {
                            $row_jenis_lomba = mysqli_fetch_assoc($result_jenis_lomba);
                            $id_jenis_lomba = $row_jenis_lomba['id_jenislomba'];
                        } else {
                            $insert_jenis_lomba = "INSERT INTO jenislomba (jenis_lomba) VALUES ('$jenis_lomba')";
                            if (mysqli_query($db_connect, $insert_jenis_lomba)) {
                                $id_jenis_lomba = mysqli_insert_id($db_connect);
                            } else {
                                echo "Error pada query jenis lomba: " . mysqli_error($db_connect);
                                exit();
                            }
                        }

                        // Query insert dengan id_kegiatan, id_peringkat, dan id_jenis_lomba
                        $queryPeserta = "INSERT INTO peserta (id_kegiatan, nama_peserta, no_telepon, email, alamat, status, id_peringkat, id_jenislomba) 
                                         VALUES ('$id_kegiatan', '$namaPeserta', '$noTelepon', '$email', '$alamat', '$status', '$id_peringkat', '$id_jenis_lomba')";

                        if (!mysqli_query($db_connect, $queryPeserta)) {
                            throw new Exception("Gagal menyimpan data peserta: " . mysqli_error($db_connect));
                        }
                    }

                    // Komit transaksi
                    mysqli_commit($db_connect);

                    // Redirect kembali dengan pesan sukses
                    header('Location: ../up_peserta.php?status=success&id=' . $id_kegiatan);
                    exit();
                } else {
                    throw new Exception("ID kegiatan tidak ditemukan.");
                }
            } else {
                throw new Exception("Parameter ID kegiatan tidak ditemukan di URL.");
            }
        } catch (Exception $e) {
            // Rollback transaksi jika terjadi error
            mysqli_rollback($db_connect);
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Format file tidak valid. Harap unggah file dengan format .xlsx";
    }
} else {
    echo "Harap unggah file terlebih dahulu.";
}
?>
