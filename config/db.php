<?php

// Konfigurasi database
$DBHOST = 'localhost';
$DBUSER = 'etivicatemasjids_sertifikat';
$DBPASSWORD = '#et$Ga!#hG{8';
$DBNAME = 'etivicatemasjids_db certificate';

// Koneksi menggunakan mysqli
$db_connect = mysqli_connect($DBHOST, $DBUSER, $DBPASSWORD, $DBNAME);

if (mysqli_connect_errno()) {
    die("Koneksi ke MySQL gagal: " . mysqli_connect_error());
}
?>