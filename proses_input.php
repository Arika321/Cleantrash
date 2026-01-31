<?php
session_start();
include "config/koneksi.php";
if(!isset($_SESSION['login'])){
    header("Location: auth/login.php");
    exit;
}

if(isset($_POST['submit'])){
    $jenis = $_POST['jenis'];
    $berat = $_POST['berat'];
    $harga = $_POST['harga'];
    $total = $berat * $harga;

    mysqli_query($koneksi, "INSERT INTO sampah (jenis, berat, harga, total)
                            VALUES ('$jenis','$berat','$harga','$total')");

    // flash message alert
    $_SESSION['success'] = "Data berhasil disimpan!";
}

header("Location: laporan.php");
exit;
