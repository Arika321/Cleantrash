<?php
session_start();
if(!isset($_SESSION['login'])){
    header("Location: auth/login.php");
    exit;
}
$nama = $_SESSION['nama'];
include "config/koneksi.php";

// hitung total saldo
$result = mysqli_query($koneksi,"SELECT SUM(total) as saldo FROM sampah");
$row = mysqli_fetch_assoc($result);
$saldo = $row['saldo'] ? $row['saldo'] : 0;
?>
<!DOCTYPE html>
<html>
<head>
<title>Dashboard | CleanTrash</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="navbar">
<b>🌱 CleanTrash</b>
<div>
    <a href="dashboard.php">Dashboard</a>
    <a href="input.php">Input Sampah</a>
    <a href="laporan.php">Laporan</a>
    <a href="auth/logout.php">Logout</a>
</div>
</div>

<div class="container">
<h2 class="section-title">Selamat Datang, <?= htmlspecialchars($nama) ?> 🌱</h2>
<p style="color:#555;margin-bottom:20px">Kelola sampah digital dan pantau saldo dengan mudah</p>

<div class="grid">
    <div class="card">
        <h4>💰 Saldo Tabungan</h4>
        <p>Rp <?= number_format($saldo,0,",",".") ?></p>
    </div>
    <div class="card">
        <h4>♻ Plastik</h4>
        <p>Sampah plastik dapat ditukar menjadi saldo digital.</p>
    </div>
    <div class="card">
        <h4>🔩 Logam</h4>
        <p>Logam memiliki nilai jual tinggi dan stabil.</p>
    </div>
    <div class="card">
        <h4>🍃 Organik</h4>
        <p>Dapat diolah menjadi pupuk kompos.</p>
    </div>
</div>
</div>
</body>
</html>
