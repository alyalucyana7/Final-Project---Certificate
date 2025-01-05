<?php
class Peserta {
    public $nama;
    public $no_telepon;
    public $email;
    public $alamat;
    public $status;
    public $peringkat;
    public $jenis_lomba;
    public $id_kegiatan;

    // Konstruktor untuk menginisialisasi data peserta
    public function __construct($nama, $no_telepon, $email, $alamat, $status, $peringkat, $jenis_lomba, $id_kegiatan) {
        $this->nama = $nama;
        $this->no_telepon = $no_telepon;
        $this->email = $email;
        $this->alamat = $alamat;
        $this->status = $status;
        $this->peringkat = $peringkat;
        $this->jenis_lomba = $jenis_lomba;
        $this->id_kegiatan = $id_kegiatan;
    }

    // Fungsi untuk memeriksa dan mendapatkan ID peringkat
    private function getPeringkatId($peringkat, $db_connect) {
        $stmt = $db_connect->prepare("SELECT id_peringkat FROM peringkat WHERE peringkat = ?");
        $stmt->bind_param("s", $peringkat);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id_peringkat);
            $stmt->fetch();
            return $id_peringkat;
        } else {
            // Jika peringkat belum ada, simpan dan ambil ID
            $stmt = $db_connect->prepare("INSERT INTO peringkat (peringkat) VALUES (?)");
            $stmt->bind_param("s", $peringkat);
            $stmt->execute();
            return $db_connect->insert_id;  // Mendapatkan ID yang baru dimasukkan
        }
    }

    // Fungsi untuk memeriksa dan mendapatkan ID jenis lomba
    private function getJenisLombaId($jenis_lomba, $db_connect) {
        $stmt = $db_connect->prepare("SELECT id_jenislomba FROM jenislomba WHERE jenis_lomba = ?");
        $stmt->bind_param("s", $jenis_lomba);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id_jenislomba);
            $stmt->fetch();
            return $id_jenislomba;
        } else {
            // Jika jenis lomba belum ada, simpan dan ambil ID
            $stmt = $db_connect->prepare("INSERT INTO jenislomba (jenis_lomba) VALUES (?)");
            $stmt->bind_param("s", $jenis_lomba);
            $stmt->execute();
            return $db_connect->insert_id;  // Mendapatkan ID yang baru dimasukkan
        }
    }

    // Menyimpan data peserta ke dalam database
    public function simpanDataPeserta($db_connect) {
        // Dapatkan ID peringkat dan jenis lomba
        $id_peringkat = $this->getPeringkatId($this->peringkat, $db_connect);
        $id_jenislomba = $this->getJenisLombaId($this->jenis_lomba, $db_connect);

        // Simpan data peserta dengan ID peringkat dan ID jenis lomba
        $stmt = $db_connect->prepare("INSERT INTO peserta (nama_peserta, no_telepon, email, alamat, status, id_peringkat, id_jenislomba, id_kegiatan) 
                                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $this->nama, $this->no_telepon, $this->email, $this->alamat, $this->status, $id_peringkat, $id_jenislomba, $this->id_kegiatan);

        return $stmt->execute();
    }
}
?>
