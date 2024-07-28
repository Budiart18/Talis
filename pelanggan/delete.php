<?php
// Include file konfigurasi database
include '../config.php';

// Ambil ID pelanggan dari parameter GET
$id = $_GET['id'];

// Buat query SQL untuk menghapus pelanggan berdasarkan ID
$sql = "DELETE FROM pelanggan WHERE id_pelanggan=$id";

// Eksekusi query
if ($conn->query($sql) === TRUE) {
    // Redirect ke halaman daftar pelanggan jika berhasil
    header("location: read.php");
} else {
    // Tampilkan pesan error jika gagal
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>
