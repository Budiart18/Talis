<?php
// Memulai sesi untuk mengakses dan memanipulasi data sesi
session_start();

// Menghancurkan semua data sesi yang ada
session_destroy();

// Mengarahkan pengguna ke halaman login
header("location: login.php");
?>
