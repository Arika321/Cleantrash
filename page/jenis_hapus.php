<?php
session_start();
require_once('../system/koneksi.php');
if (!isset($_SESSION['username'])) exit;

$id = $_GET['id'];
mysqli_query($koneksi, "DELETE FROM jenis_sampah WHERE id_jenis='$id'");
header("Location: jenis_sampah.php?pesan=hapus_sukses");
?>