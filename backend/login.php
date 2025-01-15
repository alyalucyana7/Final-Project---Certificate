<?php
session_start();
require './../config/db.php'; // Koneksi ke database
require 'User.php'; // Memuat class User

if (isset($_POST['submit'])) {
    // Mengambil input username dan password
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Membuat objek User
    $user = new User($db_connect, $username, $password);

    // Memanggil metode login
    $loginResult = $user->login();

    if ($loginResult === true) {
        // Jika login berhasil, redirect ke dashboard
        header("Location: ./../dashboard.php");
        exit();
    } elseif ($loginResult === 'wrong_password') {
        // Jika password salah
        header('Location: ./../login.php?error=wrong_password');
        exit();
    } elseif ($loginResult === 'user_not_found') {
        // Jika username tidak ditemukan
        header('Location: ./../login.php?error=user_not_found');
        exit();
    }
} else {
    echo "Akses tidak diizinkan!";
    die;
}
?>
