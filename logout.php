<?php
session_start();

// Hapus semua session dan redirect ke login
session_unset();
session_destroy();

header("Location: index.php");
exit();