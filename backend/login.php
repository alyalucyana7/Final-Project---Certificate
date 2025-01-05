<?php
session_start();
require './../config/db.php';

if (isset($_POST['submit'])) {
    // Ambil input dan filter
    $username = mysqli_real_escape_string($db_connect, $_POST['username']);
    $password = $_POST['password'];

    // Hash password dengan SHA2 (256-bit) sebelum dibandingkan
    $hashed_password = hash('sha256', $password);

    // Query untuk mendapatkan user berdasarkan username
    $user = mysqli_query($db_connect, "SELECT * FROM user WHERE username = '$username'");

    if ($user && mysqli_num_rows($user) > 0) {
        $data = mysqli_fetch_assoc($user);

        // Bandingkan password hash
        if ($hashed_password === $data['password']) {
            // Login berhasil
            $_SESSION['user_id'] = $data['id_user']; // Simpan sesi login
            header("Location: ./../dashboard.php");
            exit;
        } else {
            // Password tidak cocok
            header('Location: ./../login.php?error=password');
            exit;
        }
    } else {
        // User tidak ditemukan
        header('Location: ./../login.php?error=username');
        exit;
    }
} else {
    echo "Akses tidak valid.";
    exit;
}
?>
