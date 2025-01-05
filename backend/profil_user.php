<?php
require './config/db.php'; // Koneksi database

function getUserName($db_connect, $user_id) {
    $default_name = 'Admin'; // Default nama pengguna
    $result = $db_connect->query("SELECT nama_user FROM user WHERE id_user = $user_id");
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc()['nama_user'];
    }
    return $default_name;
}

// Pastikan user_id ada di sesi
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    echo htmlspecialchars(getUserName($db_connect, $user_id));
}

$db_connect->close();
?>
