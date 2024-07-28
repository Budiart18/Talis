<?php
// Include file konfigurasi database
include '../config.php';

// Ambil ID tarif dari parameter GET
$id = $_GET['id'];

// Buat query SQL untuk menghapus tarif berdasarkan ID
$sql = "DELETE FROM tarif WHERE id_tarif=$id";

// Eksekusi query
if ($conn->query($sql) === TRUE) {
    // Redirect ke halaman read.php jika berhasil dihapus
    header("location: read.php");
} else {
    // Tampilkan pesan error jika gagal menghapus
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>
