<?php
session_start();
require './../config/db.php'; // Koneksi ke database

class User {
    private $db_connect;
    private $username;
    private $password;

    // Konstruktor untuk menerima koneksi database dan data user
    public function __construct($db_connect, $username, $password) {
        $this->db_connect = $db_connect;
        $this->username = $username;
        $this->password = $password;
    }

    // Metode untuk mengecek apakah username dan password valid
    public function login() {
        // Sanitasi input
        $stmt = $this->db_connect->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->bind_param("s", $this->username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();

            // Verifikasi password
            if ($this->verifyPassword($this->password, $data['password'])) {
                // Simpan sesi login
                $_SESSION['user_id'] = $data['id_user'];
                return true;
            } else {
                return 'wrong_password';
            }
        } else {
            return 'user_not_found';
        }
    }

    // Metode untuk memverifikasi password
    private function verifyPassword($password, $hashedPassword) {
        return hash('sha256', $password) === $hashedPassword;
    }
}