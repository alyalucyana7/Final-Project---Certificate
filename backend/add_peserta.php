<?php

require './../config/db.php';

if (isset($_POST['submit'])) {
    global $db_connect;

    // Validasi apakah parameter id ada di URL
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id_kegiatan = $_GET['id'];

        // Validasi apakah ID kegiatan ada di database
        $kegiatan = mysqli_query($db_connect, "SELECT * FROM kegiatan WHERE id_kegiatan = '$id_kegiatan'");
        if (mysqli_num_rows($kegiatan) > 0) {
            // Ambil data dari form
            $nama_peserta = mysqli_real_escape_string($db_connect, $_POST['peserta']);
            $no_telepon = mysqli_real_escape_string($db_connect, $_POST['no_telepon']);
            $email = mysqli_real_escape_string($db_connect, $_POST['email']);
            $alamat = mysqli_real_escape_string($db_connect, $_POST['alamat']);
            $status = mysqli_real_escape_string($db_connect, $_POST['status']);
            $peringkat = mysqli_real_escape_string($db_connect, $_POST['peringkat']);
            $jenis_lomba = mysqli_real_escape_string($db_connect, $_POST['jenis_lomba']);

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

            // Simpan data jenis lomba ke tabel `jenislomba` jika belum ada
            $sql_jenislomba = "SELECT id_jenislomba FROM jenislomba WHERE jenis_lomba = '$jenis_lomba'";
            $result_jenislomba = mysqli_query($db_connect, $sql_jenislomba);

            if (mysqli_num_rows($result_jenislomba) > 0) {
                $row_jenislomba = mysqli_fetch_assoc($result_jenislomba);
                $id_jenislomba = $row_jenislomba['id_jenislomba'];
            } else {
                $insert_jenislomba = "INSERT INTO jenislomba (jenis_lomba) VALUES ('$jenis_lomba')";
                if (mysqli_query($db_connect, $insert_jenislomba)) {
                    $id_jenislomba = mysqli_insert_id($db_connect);
                } else {
                    echo "Error pada query jenislomba: " . mysqli_error($db_connect);
                    exit();
                }
            }

            // Simpan data ke tabel peserta
            $sql_peserta = "INSERT INTO peserta (id_kegiatan, nama_peserta, no_telepon, email, alamat, status, id_peringkat, id_jenislomba) 
                            VALUES ('$id_kegiatan', '$nama_peserta', '$no_telepon', '$email', '$alamat', '$status', '$id_peringkat', '$id_jenislomba')";

            if (mysqli_query($db_connect, $sql_peserta)) {
                $id_peserta = mysqli_insert_id($db_connect);

                // Redirect ke halaman daftar peserta dengan pesan sukses
                header("Location: ../up_peserta.php?message=Data peserta berhasil disimpan&id=$id_kegiatan");
                exit();
            } else {
                // Tampilkan error jika query gagal
                echo "Error pada query peserta: " . $sql_peserta . "<br>" . mysqli_error($db_connect);
            }
        } else {
            // Jika ID kegiatan tidak valid
            echo "ID kegiatan tidak ditemukan.";
        }
    } else {
        // Jika parameter ID tidak ditemukan atau kosong
        echo "Parameter ID tidak ditemukan di URL.";
    }

    // Tutup koneksi
    mysqli_close($db_connect);
} else {
    // Pesan jika form tidak disubmit dengan benar
    echo "Form tidak disubmit dengan benar.";  
}
?>