<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'koneksi.php';

// Ambil ID dari URL
$id = $_GET['id'] ?? null;

if (!$id) {
    die("Error: ID tidak ditemukan di URL");
}

// Cek apakah data dengan ID tersebut ada
$cek = mysqli_query($conn, "SELECT * FROM data_barang WHERE id_barang='$id'");
if (!$cek) {
    die("Error query cek data: " . mysqli_error($conn));
}
if (mysqli_num_rows($cek) == 0) {
    die("Error: Data dengan ID $id tidak ditemukan");
}

// Hapus data
$sql = "DELETE FROM data_barang WHERE id_barang='$id'";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error menghapus data: " . mysqli_error($conn));
}

// Jika berhasil, redirect ke index.php
header('Location: index.php');
exit;
?>
