<?php
require('./config/db.php');
require('./vendor/autoload.php');

// Fungsi untuk menampilkan gambar proporsional dalam kotak
function imageFixedSize($pdf, $imagePath, $x, $y, $maxWidth, $maxHeight) {
    list($width, $height) = getimagesize($imagePath); // Ambil ukuran asli gambar
    $ratio = min($maxWidth / $width, $maxHeight / $height); // Hitung rasio agar tetap proporsional
    $newWidth = $width * $ratio; // Lebar baru
    $newHeight = $height * $ratio; // Tinggi baru
    $pdf->Image($imagePath, $x, $y, $newWidth, $newHeight); // Tampilkan gambar
}

// Ambil data berdasarkan ID peserta
if (isset($_GET['id_peserta'])) {
    $id_peserta = intval($_GET['id_peserta']);
} else {
    die("ID Peserta tidak valid.");
}

$sql = "
    SELECT 
        peserta.nama_peserta, 
        peringkat.peringkat,
        jenislomba.jenis_lomba, 
        peserta.id_kegiatan, 
        template_sertifikat.id_template,
        kegiatan.nama_kegiatan,
        kegiatan.nama_penyelenggara, 
        kegiatan.tema, 
        kegiatan.tanggal_kegiatan, 
        kegiatan.tempat_via, 
        kegiatan.penomoran_sertifikat, 
        template_sertifikat.logo_organisasi, 
        template_sertifikat.logo_sponsor, 
        template_sertifikat.background, 
        template_sertifikat.ukuran,
        template_sertifikat.fontCertificate, 
        template_sertifikat.fontNama, 
        template_sertifikat.bahasa, 
        template_sertifikat.NamaTtd1, 
        template_sertifikat.jabatan1, 
        template_sertifikat.scanTtd1, 
        template_sertifikat.NamaTtd2, 
        template_sertifikat.jabatan2, 
        template_sertifikat.scanTtd2
    FROM peserta
    JOIN kegiatan ON peserta.id_kegiatan = kegiatan.id_kegiatan
    JOIN template_sertifikat ON template_sertifikat.id_kegiatan = kegiatan.id_kegiatan
    JOIN peringkat ON peserta.id_peringkat = peringkat.id_peringkat
    JOIN jenislomba ON peserta.id_jenislomba = jenislomba.id_jenislomba
    WHERE peserta.id_peserta = ?
";

$stmt = $db_connect->prepare($sql);
$stmt->bind_param("i", $id_peserta);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Data dari database
    $nama_peserta = $row['nama_peserta'];
    $peringkat = $row['peringkat'];
    $jenis_lomba = $row['jenis_lomba'];
    $nama_kegiatan = $row['nama_kegiatan'];
    $nama_penyelenggara = $row['nama_penyelenggara'];
    $tema = $row['tema'];
    $tanggal_kegiatan = $row['tanggal_kegiatan'];
    $tempat_via = $row['tempat_via'];
    $penomoran_sertifikat = $row['penomoran_sertifikat'];
    $logo_organisasi = $row['logo_organisasi'];
    $logo_sponsor = $row['logo_sponsor'];
    $ukuran = $row['ukuran'];
    $font_certificate = $row['fontCertificate'];
    $font_nama = $row['fontNama'];
    $language = $row['bahasa'];
    $NamaTtd1 = $row['NamaTtd1'];
    $jabatan1 = $row['jabatan1'];
    $scanTtd1 = $row['scanTtd1'];
    $NamaTtd2 = $row['NamaTtd2'];
    $jabatan2 = $row['jabatan2'];
    $scanTtd2 = $row['scanTtd2'];

    // Tentukan layout berdasarkan template
    if ($ukuran === 'landscape_center') {
        $pdf = new FPDF('L', 'mm', 'A4'); // Landscape
        $pdf->AddPage();

        // Menambahkan font custom
        $pdf->AddFont('garamond', 'B', 'TribalGaramond.php');
        $pdf->AddFont('boldena', 'B', 'BoldenaBoldPersonalUse.php');
        $pdf->AddFont('minatur', 'B', 'Minaturfreeversion-Regular.php');

        // Validasi file background
        $background_path = 'uploads/' . $row['background'];
        $pdf->Image($background_path, 0, 0, 297, 210);

        // Validasi file logo
        $logo_organisasi_path = 'uploads/' . $row['logo_organisasi'];
        $logo_sponsor_path = 'uploads/' . $row['logo_sponsor'];

        // Tentukan ukuran maksimal logo 
        $maxWidth = 40; // Lebar maksimum (mm)
        $maxHeight = 20; // Tinggi maksimum (mm)

        // Gambar logo sponsor
        imageFixedSize($pdf, $logo_sponsor_path, 113, 10, $maxWidth, $maxHeight);
        // Gambar logo organisasi 
        imageFixedSize($pdf, $logo_organisasi_path, 156, 10, $maxWidth, $maxHeight);

        // Judul Sertifikat
        // Pilih font
        if ($font_certificate == 'garamond' || $font_certificate == 'boldena' || $font_certificate == 'minatur') {
            $pdf->SetFont($font_certificate, 'B', 40 ); // Gunakan font custom
        } elseif ($font_certificate == 'arial') {
            $pdf->SetFont('Arial', 'B', 40); // Gunakan font bawaan FPDF
        } elseif ($font_certificate == 'times_new_roman') {
            $pdf->SetFont('Times', 'B', 40); // Gunakan font bawaan FPDF
        } else {
            $pdf->SetFont('Arial', 'B', 40); // Default jika font tidak valid
        }
        $pdf->SetY(40);
        if ($language === 'id') {
            $pdf->Cell(0, 10, 'SERTIFIKAT', 0, 1, 'C');
        } else {
            $pdf->Cell(0, 10, 'CERTIFICATE', 0, 1, 'C');
        }

        // Nomor Sertifikat
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, "Nomor: $penomoran_sertifikat", 0, 1, 'C');

        // Nama Peserta
        $pdf->Ln(5);
        $pdf->SetFont('Times', '', 15);
        if ($language === 'id') {
            $pdf->Cell(0, 10, 'Diberikan kepada:', 0, 1, 'C');
        } else {
            $pdf->Cell(0, 10, 'Awarded to:', 0, 1, 'C');
        }

        // Pilih font
        if ($font_nama == 'garamond' || $font_nama == 'boldena' || $font_nama == 'minatur') {
            $pdf->SetFont($font_nama, 'B', 25); // Gunakan font custom
        } elseif ($font_nama == 'arial') {
            $pdf->SetFont('Arial', 'B', 25); // Gunakan font bawaan FPDF
        } elseif ($font_nama == 'times_new_roman') {
            $pdf->SetFont('Times', 'B', 25); // Gunakan font bawaan FPDF
        } else {
            $pdf->SetFont('Arial', 'B', 25); // Default jika font tidak valid
        }
        $pdf->Cell(0, 10, $nama_peserta, 0, 1, 'C');

        // Garis di bawah nama peserta
        $x1 = 80;  // Koordinat X awal (disesuaikan)
        $x2 = 217; // Koordinat X akhir (disesuaikan)
        $y = $pdf->GetY() + 2; // Koordinat Y (2 mm di bawah teks terakhir)

        $pdf->Line($x1, $y, $x2, $y);

        // Keterangan
        $pdf->Ln(10);
        $pdf->SetFont('Times', '', 15);
        if ($language === 'id') {
            $pdf->Cell(0, 10, 'Atas partisipasinya sebagai:', 0, 1, 'C');
        } else {
            $pdf->Cell(0, 10, 'For participating as:', 0, 1, 'C');
        }

        $pdf->SetFont('Arial', 'B', 20);
        $pdf->Cell(0, 10, "$peringkat - $jenis_lomba", 0, 1, 'C');
        $pdf->SetFont('Arial', '', 16);
        if ($language === 'id') {
            $pdf->Cell(0, 10, "Pada kegiatan $nama_kegiatan yang diselenggarakan oleh $nama_penyelenggara", 0, 1, 'C');
            $pdf->Cell(0, 5, "dengan tema \"$tema\"", 0, 1, 'C');
            $pdf->Cell(0, 10, " yang diselenggarakan pada: $tanggal_kegiatan di $tempat_via", 0, 1, 'C');
        } else {
            $pdf->Cell(0, 10, "In the event $nama_kegiatan organized by $nama_penyelenggara", 0, 1, 'C');
            $pdf->Cell(0, 5, "with the theme \"$tema\"", 0, 1, 'C');
            $pdf->Cell(0, 10, "on: $tanggal_kegiatan in $tempat_via", 0, 1, 'C');
        }

        // Validasi file tanda tangan 
        $scanTtd1_path = 'uploads/' . $row['scanTtd1'];
        $scanTtd2_path = 'uploads/' . $row['scanTtd2'];

        $pdf->SetFont('Arial', '', 12);
        // Nama tanda tangan kiri
        $pdf->SetY(145); // Sejajarkan posisi vertikal (Y) dengan bawah gambar
        $pdf->SetX(73);  // Sejajarkan posisi horizontal (X) dengan gambar kiri
        $pdf->Cell(40, 10, "$jabatan1", 0, 0, 'C');

        // Nama tanda tangan kanan
        $pdf->SetY(145); // Posisi vertikal sama
        $pdf->SetX(183); // Sejajarkan posisi horizontal (X) dengan gambar kanan
        $pdf->Cell(40, 10, "$jabatan2", 0, 0, 'C');

        // Tanda tangan
        $pdf->Ln(20);

        // Tentukan ukuran maksimal kotak tanda tangan
        $maxWidth = 25; // Lebar maksimum (mm)
        $maxHeight = 23; // Tinggi maksimum (mm)

        // Gambar tanda tangan kiri
        imageFixedSize($pdf, $scanTtd1_path, 80, 155, $maxWidth, $maxHeight);
        // Gambar tanda tangan kanan
        imageFixedSize($pdf, $scanTtd2_path, 190, 155, $maxWidth, $maxHeight);

        $pdf->SetFont('Arial', 'B', 12);

        // Nama tanda tangan kiri
        $pdf->SetY(179); // Sejajarkan posisi vertikal (Y) dengan bawah gambar
        $pdf->SetX(73);  // Sejajarkan posisi horizontal (X) dengan gambar kiri
        $pdf->Cell(40, 10, "$NamaTtd1", 0, 0, 'C');

        // Garis di bawah nama peserta
        $x1 = 70;  // Koordinat X awal (disesuaikan)
        $x2 = 117; // Koordinat X akhir (disesuaikan)
        $y = $pdf->GetY() + 2; // Koordinat Y (2 mm di bawah teks terakhir)

        $pdf->Line($x1, $y, $x2, $y);

        // Garis di bawah nama peserta
        $x1 = 180;  // Koordinat X awal (disesuaikan)
        $x2 = 225; // Koordinat X akhir (disesuaikan)
        $y = $pdf->GetY() + 2; // Koordinat Y (2 mm di bawah teks terakhir)

        $pdf->Line($x1, $y, $x2, $y);

        // Nama tanda tangan kanan
        $pdf->SetY(179); // Posisi vertikal sama
        $pdf->SetX(183); // Sejajarkan posisi horizontal (X) dengan gambar kanan
        $pdf->Cell(40, 10, "$NamaTtd2", 0, 0, 'C');

    } elseif ($ukuran === 'landscape_left') {
        $pdf = new FPDF('L', 'mm', 'A4'); // Landscape
        $pdf->AddPage();

        // Menambahkan font custom
        $pdf->AddFont('garamond', 'B', 'TribalGaramond.php');
        $pdf->AddFont('boldena', 'B', 'BoldenaBoldPersonalUse.php');
        $pdf->AddFont('minatur', 'B', 'Minaturfreeversion-Regular.php');

        // Validasi file background
        $background_path = 'uploads/' . $row['background'];
        $pdf->Image($background_path, 0, 0, 297, 210);

        // Validasi file logo
        $logo_organisasi_path = 'uploads/' . $row['logo_organisasi'];
        $logo_sponsor_path = 'uploads/' . $row['logo_sponsor'];

        // Tentukan ukuran maksimal logo 
        $maxWidth = 40; // Lebar maksimum (mm)
        $maxHeight = 20; // Tinggi maksimum (mm)

        // Gambar logo sponsor
        imageFixedSize($pdf, $logo_sponsor_path, 197, 8, $maxWidth, $maxHeight);
        
        // Gambar logo organisasi 
        imageFixedSize($pdf, $logo_organisasi_path, 240, 10, $maxWidth, $maxHeight);

        // Judul Sertifikat
        // Pilih font
        if ($font_certificate == 'garamond' || $font_certificate == 'boldena' || $font_certificate == 'minatur') {
            $pdf->SetFont($font_certificate, 'B', 40 ); // Gunakan font custom
        } elseif ($font_certificate == 'arial') {
            $pdf->SetFont('Arial', 'B', 40); // Gunakan font bawaan FPDF
        } elseif ($font_certificate == 'times_new_roman') {
            $pdf->SetFont('Times', 'B', 40); // Gunakan font bawaan FPDF
        } else {
            $pdf->SetFont('Arial', 'B', 40); // Default jika font tidak valid
        }
        $pdf->SetY(40);
        $pdf->SetX(20); // Posisi kiri
        if ($language === 'id') {
            $pdf->Cell(0, 10, 'SERTIFIKAT', 0, 1, 'L');
        } else {
            $pdf->Cell(0, 10, 'CERTIFICATE', 0, 1, 'L');
        }


        // Nomor Sertifikat
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetX(20); // Posisi kiri
        $pdf->Cell(0, 10, "Nomor: $penomoran_sertifikat", 0, 1, 'L');

        // Nama Peserta
        $pdf->Ln(5);
        $pdf->SetFont('Times', '', 15);
        $pdf->SetX(20); // Posisi kiri
        if ($language === 'id') {
            $pdf->Cell(0, 10, 'Diberikan kepada:', 0, 1, 'L');
        } else {
            $pdf->Cell(0, 10, 'Awarded to:', 0, 1, 'L');
        }
        
        // Pilih font
        if ($font_nama == 'garamond' || $font_nama == 'boldena' || $font_nama == 'minatur') {
            $pdf->SetFont($font_nama, 'B', 25); // Gunakan font custom
        } elseif ($font_nama == 'arial') {
            $pdf->SetFont('Arial', 'B', 25); // Gunakan font bawaan FPDF
        } elseif ($font_nama == 'times_new_roman') {
            $pdf->SetFont('Times', 'B', 25); // Gunakan font bawaan FPDF
        } else {
            $pdf->SetFont('Arial', 'B', 25); // Default jika font tidak valid
        }
        $pdf->SetX(20); // Posisi kiri
        $pdf->Cell(0, 10, $nama_peserta, 0, 1, 'L');

        // Garis di bawah nama peserta
        $x1 = 20;  // Koordinat X awal (disesuaikan)
        $x2 = 150; // Koordinat X akhir (disesuaikan)
        $y = $pdf->GetY() + 0; // Koordinat Y (0 mm di bawah teks terakhir)

        $pdf->Line($x1, $y, $x2, $y);

        // Keterangan
        $pdf->Ln(10);
        $pdf->SetFont('Arial', '', 15);
        $pdf->SetX(20); // Posisi kiri
        if ($language === 'id') {
            $pdf->Cell(0, 10, 'Atas partisipasinya sebagai:', 0, 1, 'L');
        } else {
            $pdf->Cell(0, 10, 'For participating as:', 0, 1, 'L');
        }
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->SetX(20); // Posisi kiri
        $pdf->Cell(0, 10, "$peringkat - $jenis_lomba", 0, 1, 'L');

        // Garis di bawah Peringkat 
        $x1 = 20;  // Koordinat X awal (disesuaikan)
        $x2 = 120; // Koordinat X akhir (disesuaikan)
        $y = $pdf->GetY() + 0; // Koordinat Y (0 mm di bawah teks terakhir)

        $pdf->Line($x1, $y, $x2, $y);

        $pdf->Ln(3);
        $pdf->SetX(20); // Posisi kiri
        $pdf->SetFont('Arial', '', 16);
        if ($language === 'id') {
            $pdf->Cell(0, 10, "Pada kegiatan $nama_kegiatan yang diselenggarakan oleh $nama_penyelenggara", 0, 1, 'L');
            $pdf->SetX(20); // Posisi kiri
            $pdf->Cell(0, 5, "dengan tema \"$tema\"", 0, 1, 'L');
            $pdf->SetX(20); // Posisi kiri
            $pdf->Cell(0, 10, " yang diselenggarakan pada: $tanggal_kegiatan di $tempat_via", 0, 1, 'L');
        } else {
            $pdf->Cell(0, 10, "In the event $nama_kegiatan organized by $nama_penyelenggara", 0, 1, 'L');
            $pdf->SetX(20); // Posisi kiri
            $pdf->Cell(0, 5, "with the theme \"$tema\"", 0, 1, 'L');
            $pdf->SetX(20); // Posisi kiri
            $pdf->Cell(0, 10, "on: $tanggal_kegiatan in $tempat_via", 0, 1, 'L');
        }

        // Validasi file tanda tangan 
        $scanTtd1_path = 'uploads/' . $row['scanTtd1'];
        $scanTtd2_path = 'uploads/' . $row['scanTtd2'];

        $pdf->SetFont('Arial', '', 12);
        // Nama tanda tangan kiri
        $pdf->SetY(145); // Sejajarkan posisi vertikal (Y) dengan bawah gambar
        $pdf->SetX(73);  // Sejajarkan posisi horizontal (X) dengan gambar kiri
        $pdf->Cell(40, 10, "$jabatan1", 0, 0, 'C');

        // Nama tanda tangan kanan
        $pdf->SetY(145); // Posisi vertikal sama
        $pdf->SetX(183); // Sejajarkan posisi horizontal (X) dengan gambar kanan
        $pdf->Cell(40, 10, "$jabatan2", 0, 0, 'C');

        // Tanda tangan
        $pdf->Ln(20);

        // Tentukan ukuran maksimal kotak tanda tangan
        $maxWidth = 25; // Lebar maksimum (mm)
        $maxHeight = 23; // Tinggi maksimum (mm)

        // Gambar tanda tangan kiri
        imageFixedSize($pdf, $scanTtd1_path, 80, 155, $maxWidth, $maxHeight);

        // Gambar tanda tangan kanan
        imageFixedSize($pdf, $scanTtd2_path, 190, 155, $maxWidth, $maxHeight);

        $pdf->SetFont('Arial', 'B', 12);

        // Nama tanda tangan kiri
        $pdf->SetY(179); // Sejajarkan posisi vertikal (Y) dengan bawah gambar
        $pdf->SetX(73);  // Sejajarkan posisi horizontal (X) dengan gambar kiri
        $pdf->Cell(40, 10, "$NamaTtd1", 0, 0, 'C');

        // Garis di bawah nama peserta
        $x1 = 70;  // Koordinat X awal (disesuaikan)
        $x2 = 117; // Koordinat X akhir (disesuaikan)
        $y = $pdf->GetY() + 2; // Koordinat Y (2 mm di bawah teks terakhir)

        $pdf->Line($x1, $y, $x2, $y);

        // Garis di bawah nama peserta
        $x1 = 180;  // Koordinat X awal (disesuaikan)
        $x2 = 225; // Koordinat X akhir (disesuaikan)
        $y = $pdf->GetY() + 2; // Koordinat Y (2 mm di bawah teks terakhir)

        $pdf->Line($x1, $y, $x2, $y);

        // Nama tanda tangan kanan
        $pdf->SetY(179); // Posisi vertikal sama
        $pdf->SetX(183); // Sejajarkan posisi horizontal (X) dengan gambar kanan
        $pdf->Cell(40, 10, "$NamaTtd2", 0, 0, 'C');

    } elseif ($ukuran === 'potrait_center') {
        $pdf = new FPDF('P', 'mm', 'A4'); // Portrait
        $pdf->AddPage();

        // Menambahkan font custom
        $pdf->AddFont('garamond', 'B', 'TribalGaramond.php');
        $pdf->AddFont('boldena', 'B', 'BoldenaBoldPersonalUse.php');
        $pdf->AddFont('minatur', 'B', 'Minaturfreeversion-Regular.php');

        // Validasi file background
        $background_path = 'uploads/' . $row['background'];
        $pdf->Image($background_path, 0, 0, 210, 297);

        // Validasi file logo
        $logo_organisasi_path = 'uploads/' . $row['logo_organisasi'];
        $logo_sponsor_path = 'uploads/' . $row['logo_sponsor'];

        // Tentukan ukuran maksimal logo 
        $maxWidth = 40; // Lebar maksimum (mm)
        $maxHeight = 20; // Tinggi maksimum (mm)

        // Gambar logo sponsor
        imageFixedSize($pdf, $logo_sponsor_path, 70, 30, $maxWidth, $maxHeight);
        
        // Gambar logo organisasi 
        imageFixedSize($pdf, $logo_organisasi_path, 111, 30, $maxWidth, $maxHeight);

        // Judul Sertifikat
        // Pilih font
        if ($font_certificate == 'garamond' || $font_certificate == 'boldena' || $font_certificate == 'minatur') {
            $pdf->SetFont($font_certificate, 'B', 40 ); // Gunakan font custom
        } elseif ($font_certificate == 'arial') {
            $pdf->SetFont('Arial', 'B', 40); // Gunakan font bawaan FPDF
        } elseif ($font_certificate == 'times_new_roman') {
            $pdf->SetFont('Times', 'B', 40); // Gunakan font bawaan FPDF
        } else {
            $pdf->SetFont('Arial', 'B', 40); // Default jika font tidak valid
        }
        $pdf->SetY(60);
        if ($language === 'id') {
            $pdf->Cell(0, 10, 'SERTIFIKAT', 0, 1, 'C');
        } else {
            $pdf->Cell(0, 10, 'CERTIFICATE', 0, 1, 'C');
        }

        // Nomor Sertifikat
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, "Nomor: $penomoran_sertifikat", 0, 1, 'C');

        // Nama Peserta
        $pdf->Ln(15);
        $pdf->SetFont('Times', '', 15);
        if ($language === 'id') {
            $pdf->Cell(0, 10, 'Diberikan kepada:', 0, 1, 'C');
        } else {
            $pdf->Cell(0, 10, 'Awarded to:', 0, 1, 'C');
        }
        
        // Pilih font
        if ($font_nama == 'garamond' || $font_nama == 'boldena' || $font_nama == 'minatur') {
            $pdf->SetFont($font_nama, 'B', 25); // Gunakan font custom
        } elseif ($font_nama == 'arial') {
            $pdf->SetFont('Arial', 'B', 25); // Gunakan font bawaan FPDF
        } elseif ($font_nama == 'times_new_roman') {
            $pdf->SetFont('Times', 'B', 25); // Gunakan font bawaan FPDF
        } else {
            $pdf->SetFont('Arial', 'B', 25); // Default jika font tidak valid
        }
        $pdf->Cell(0, 10, $nama_peserta, 0, 1, 'C');

        // Garis di bawah nama peserta
        $x1 = 50;  // Koordinat X awal (disesuaikan)
        $x2 = 160; // Koordinat X akhir (disesuaikan)
        $y = $pdf->GetY() + 2; // Koordinat Y (2 mm di bawah teks terakhir)

        $pdf->Line($x1, $y, $x2, $y);

        // Keterangan
        $pdf->Ln(20);
        $pdf->SetFont('Arial', '', 15);
        if ($language === 'id') {
            $pdf->Cell(0, 10, 'Atas partisipasinya sebagai:', 0, 1, 'C');
        } else {
            $pdf->Cell(0, 10, 'For participating as:', 0, 1, 'C');
        }
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->Cell(0, 10, "$peringkat - $jenis_lomba", 0, 1, 'C');

        // Garis di bawah peringkat
        $x1 = 60;  // Koordinat X awal (disesuaikan)
        $x2 = 150; // Koordinat X akhir (disesuaikan)
        $y = $pdf->GetY() + 2; // Koordinat Y (2 mm di bawah teks terakhir)

        $pdf->Line($x1, $y, $x2, $y);

        $pdf->Ln(10);
        $pdf->SetFont('Arial', '', 16);
        if ($language === 'id') {
            $pdf->Cell(0, 10, "Pada kegiatan $nama_kegiatan yang diselenggarakan oleh $nama_penyelenggara", 0, 1, 'C');
            $pdf->Cell(0, 5, "dengan tema \"$tema\"", 0, 1, 'C');
            $pdf->Cell(0, 10, " yang diselenggarakan pada: $tanggal_kegiatan di $tempat_via", 0, 1, 'C');
        } else {
            $pdf->Cell(0, 10, "In the event $nama_kegiatan organized by $nama_penyelenggara", 0, 1, 'C');
            $pdf->Cell(0, 5, "with the theme \"$tema\"", 0, 1, 'C');
            $pdf->Cell(0, 10, "on: $tanggal_kegiatan in $tempat_via", 0, 1, 'C');
        }

        // Validasi file tanda tangan 
        $scanTtd1_path = 'uploads/' . $row['scanTtd1'];
        $scanTtd2_path = 'uploads/' . $row['scanTtd2'];

        $pdf->SetFont('Arial', '', 12);
        // Nama tanda tangan kiri
        $pdf->SetY(203); // Sejajarkan posisi vertikal (Y) dengan bawah gambar
        $pdf->SetX(32);  // Sejajarkan posisi horizontal (X) dengan gambar kiri
        $pdf->Cell(40, 10, "$jabatan1", 0, 0, 'C');

        // Nama tanda tangan kanan
        $pdf->SetY(203); // Posisi vertikal sama
        $pdf->SetX(142); // Sejajarkan posisi horizontal (X) dengan gambar kanan
        $pdf->Cell(40, 10, "$jabatan2", 0, 0, 'C');

        // Tanda tangan
        $pdf->Ln(20);

        // Tentukan ukuran maksimal kotak tanda tangan
        $maxWidth = 25; // Lebar maksimum (mm)
        $maxHeight = 23; // Tinggi maksimum (mm)

        // Gambar tanda tangan kiri
        imageFixedSize($pdf, $scanTtd1_path, 40, 213, $maxWidth, $maxHeight);

        // Gambar tanda tangan kanan
        imageFixedSize($pdf, $scanTtd2_path, 150, 213, $maxWidth, $maxHeight);

        $pdf->SetFont('Arial', 'B', 12);

        // Nama tanda tangan kiri
        $pdf->SetY(237); // Sejajarkan posisi vertikal (Y) dengan bawah gambar
        $pdf->SetX(33);  // Sejajarkan posisi horizontal (X) dengan gambar kiri
        $pdf->Cell(40, 10, "$NamaTtd1", 0, 0, 'C');

        // Garis di bawah nama peserta
        $x1 = 30;  // Koordinat X awal (disesuaikan)
        $x2 = 77; // Koordinat X akhir (disesuaikan)
        $y = $pdf->GetY() + 2; // Koordinat Y (3 mm di bawah teks terakhir)

        $pdf->Line($x1, $y, $x2, $y);

        // Garis di bawah nama peserta
        $x1 = 138;  // Koordinat X awal (disesuaikan)
        $x2 = 185; // Koordinat X akhir (disesuaikan)
        $y = $pdf->GetY() + 2; // Koordinat Y (3 mm di bawah teks terakhir)

        $pdf->Line($x1, $y, $x2, $y);

        // Nama tanda tangan kanan
        $pdf->SetY(237); // Posisi vertikal sama
        $pdf->SetX(142); // Sejajarkan posisi horizontal (X) dengan gambar kanan
        $pdf->Cell(40, 10, "$NamaTtd2", 0, 0, 'C');
    } else {
        die("Template tidak valid.");
    }

// Tentukan nama file untuk sertifikat
$nama_file = 'Sertifikat_' . $nama_kegiatan . '_' . $nama_peserta . '.pdf';

// Simpan file PDF ke folder 'certificates'
$folder_path = 'certificates/';
$file_path = $folder_path . $nama_file;

// Pastikan folder 'certificates' ada
if (!file_exists($folder_path)) {
    mkdir($folder_path, 0755, true); // Buat folder jika belum ada
}

$pdf->Output('F', $file_path); // Simpan file ke folder

// Periksa apakah data sertifikat sudah ada
$sql_check = "
    SELECT COUNT(*) AS count 
    FROM sertifikat 
    WHERE id_peserta = ? AND id_kegiatan = ? AND id_template = ?
";

$stmt_check = $db_connect->prepare($sql_check);
$stmt_check->bind_param('iii', $id_peserta, $id_kegiatan, $id_template);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
$row_check = $result_check->fetch_assoc();

if ($row_check['count'] > 0) {
    // Jika data sudah ada, langsung tampilkan sertifikat
    header("Content-Type: application/pdf");
    header("Content-Disposition: inline; filename=\"$nama_file\"");
    readfile($file_path);
    exit();
}

// Jika data belum ada, simpan ke database
$stmt_check->close();

// Simpan informasi sertifikat ke database
$id_kegiatan = $row['id_kegiatan'];
$id_template = $row['id_template'];

// Menyimpan sertifikat ke database
$sql_insert = "
    INSERT INTO sertifikat (id_peserta, id_kegiatan, id_template, hasil_sertifikat, created_at)
    VALUES (?, ?, ?, ?, NOW())
";

$stmt_insert = $db_connect->prepare($sql_insert);
$stmt_insert->bind_param('iiis', $id_peserta, $id_kegiatan, $id_template, $nama_file);

// Jalankan query dan cek hasil
if ($stmt_insert->execute()) {
    // Jika berhasil disimpan ke database, tampilkan sertifikat di browser
    header("Content-Type: application/pdf");
    header("Content-Disposition: inline; filename=\"$nama_file\"");
    readfile($file_path);
    exit();
} else {
    echo "Gagal menyimpan sertifikat: " . $stmt_insert->error;
}
$stmt_insert->close();

} else {
    echo "Data tidak ditemukan.";
}

$db_connect->close();
?>
