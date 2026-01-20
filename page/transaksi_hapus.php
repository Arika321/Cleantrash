<?php
// transaksi_hapus.php - Hapus Transaksi (DELETE)
session_start();
require_once('../system/koneksi.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

// Ambil data transaksi untuk update saldo
$query_data = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE id_transaksi='$id'");
$data = mysqli_fetch_assoc($query_data);

if ($data) {
    $id_nasabah = $data['id_nasabah'];
    $total = $data['total'];
    
    // Hapus transaksi
    $query_delete = "DELETE FROM transaksi WHERE id_transaksi='$id'";
    
    if (mysqli_query($koneksi, $query_delete)) {
        // Kurangi saldo nasabah
        mysqli_query($koneksi, "UPDATE nasabah SET saldo = saldo - $total WHERE id_nasabah='$id_nasabah'");
        
        // Log activity
        $username = $_SESSION['username'];
        $activity = "Menghapus transaksi: $id";
        mysqli_query($koneksi, "INSERT INTO log_activity (username, activity, created_at) VALUES ('$username', '$activity', NOW())");
        
        header("Location: transaksi.php?pesan=hapus_sukses");
    } else {
        header("Location: transaksi.php?pesan=hapus_gagal");
    }
} else {
    header("Location: transaksi.php?pesan=data_tidak_ditemukan");
}
?>