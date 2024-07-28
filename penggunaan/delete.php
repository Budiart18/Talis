<?php
// Include file konfigurasi database
include '../config.php';

// Ambil ID penggunaan dari parameter GET
$id = $_GET['id'];

// Buat query SQL untuk menghapus data penggunaan berdasarkan ID
$sql = "DELETE FROM penggunaan WHERE id_penggunaan=$id";

// Eksekusi query
if ($conn->query($sql) === TRUE) {
    // Redirect ke halaman daftar penggunaan jika berhasil
    header("location: read.php");
} else {
    // Tampilkan pesan error jika gagal
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>
