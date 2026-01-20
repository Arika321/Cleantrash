<?php
// nasabah_hapus.php - Hapus Nasabah (DELETE)
session_start();
require_once('../system/koneksi.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

// Cek apakah nasabah memiliki transaksi
$cek_transaksi = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM transaksi WHERE id_nasabah='$id'");
$jumlah_transaksi = mysqli_fetch_assoc($cek_transaksi)['total'];

if ($jumlah_transaksi > 0) {
    // Jika ada transaksi, tidak bisa dihapus
    header("Location: nasabah.php?pesan=hapus_gagal_ada_transaksi");
    exit();
} else {
    // Jika tidak ada transaksi, bisa dihapus
    $query = "DELETE FROM nasabah WHERE id_nasabah='$id'";
    
    if (mysqli_query($koneksi, $query)) {
        // Log activity
        $username = $_SESSION['username'];
        $activity = "Menghapus nasabah: $id";
        mysqli_query($koneksi, "INSERT INTO log_activity (username, activity, created_at) VALUES ('$username', '$activity', NOW())");
        
        header("Location: nasabah.php?pesan=hapus_sukses");
    } else {
        header("Location: nasabah.php?pesan=hapus_gagal");
    }
}
?>