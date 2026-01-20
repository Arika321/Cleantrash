<?php
session_start();
require_once('../system/koneksi.php');
if (!isset($_SESSION['username'])) exit;

$id = $_GET['id'];
if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $harga = mysqli_real_escape_string($koneksi, $_POST['harga']);
    $satuan = $_POST['satuan'];
    $desk = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    
    mysqli_query($koneksi, "UPDATE jenis_sampah SET nama_sampah='$nama', harga_per_kg='$harga', satuan='$satuan', deskripsi='$desk' WHERE id_jenis='$id'");
    header("Location: jenis_sampah.php?pesan=edit_sukses");
}
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM jenis_sampah WHERE id_jenis='$id'"));
?>
<!-- Form HTML sama seperti nasabah_edit, sesuaikan field -->