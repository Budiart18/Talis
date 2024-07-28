<?php
// Memulai sesi untuk menyimpan data pengguna
session_start();

// Konfigurasi koneksi database
$servername = "localhost"; // Nama server database
$username = "root"; // Username database
$password = ""; // Password database
$dbname = "listrik_pasca_bayar"; // Nama database

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Mengecek koneksi database
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fungsi untuk memeriksa apakah pengguna adalah admin
function isAdmin() {
    // Mengembalikan true jika pengguna memiliki level akses 1 (dianggap sebagai admin)
    return isset($_SESSION['id_level']) && $_SESSION['id_level'] == 1; // Asumsikan 1 untuk admin
}

// Fungsi untuk memeriksa apakah pengguna telah login
function isLoggedIn() {
    // Mengembalikan true jika terdapat sesi username
    return isset($_SESSION['username']);
}

// Redirect ke halaman login jika pengguna belum login dan bukan di halaman login
if (!isLoggedIn() && basename($_SERVER['PHP_SELF']) != 'login.php') {
    header("location: login.php");
    exit;
}
?>
