<?php
session_start();
require_once('../system/koneksi.php');
if (!isset($_SESSION['username'])) exit;

if (isset($_POST['simpan'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $harga = mysqli_real_escape_string($koneksi, $_POST['harga']);
    $satuan = $_POST['satuan'];
    $desk = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    
    mysqli_query($koneksi, "INSERT INTO jenis_sampah (nama_sampah, harga_per_kg, satuan, deskripsi) VALUES ('$nama', '$harga', '$satuan', '$desk')");
    header("Location: jenis_sampah.php?pesan=tambah_sukses");
}
?>
<!-- Form HTML sama seperti nasabah_tambah, sesuaikan field -->