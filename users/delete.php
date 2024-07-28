<?php
// Include file konfigurasi database
include '../config.php';

// Ambil ID pengguna dari parameter GET
$id = $_GET['id'];

// Buat query SQL untuk menghapus pengguna
$sql = "DELETE FROM users WHERE id_user=$id";

// Eksekusi query
if ($conn->query($sql) === TRUE) {
    // Redirect ke halaman daftar pengguna jika berhasil
    header("location: read.php");
} else {
    // Tampilkan pesan error jika gagal
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>
