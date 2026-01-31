<?php
session_start();
if(!isset($_SESSION['login'])){
    header("Location: auth/login.php");
    exit;
}
include "config/koneksi.php";

$data = mysqli_query($koneksi,"SELECT * FROM sampah");
?>

<!DOCTYPE html>
<html>
<head>
<title>Laporan | CleanTrash</title>
<link rel="stylesheet" href="assets/style.css">
<script>
function showAlert(message){
    alert(message);
}
</script>
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
<h2 class="section-title">Laporan Data Sampah</h2>

<?php
if(isset($_SESSION['success'])){
    echo "<script>showAlert('".$_SESSION['success']."');</script>";
    unset($_SESSION['success']);
}
?>

<div class="grid">
<?php while($row=mysqli_fetch_assoc($data)){ 
    $class = "";
    if($row['jenis'] == "Plastik") $class="plastik";
    elseif($row['jenis'] == "Logam") $class="logam";
    elseif($row['jenis'] == "Organik") $class="organik";
?>
<div class="card <?= $class ?>">
    <h4><?= $row['jenis'] ?></h4>
    <p>Berat: <?= $row['berat'] ?> Kg</p>
    <p>Harga/Kg: Rp <?= number_format($row['harga'],0,",",".") ?></p>
    <p>Total: Rp <?= number_format($row['total'],0,",",".") ?></p>
</div>
<?php } ?>
</div>
<div class="grid">
<?php while($row=mysqli_fetch_assoc($data)){ 
    $class = "";
    if($row['jenis'] == "Plastik") $class="plastik";
    elseif($row['jenis'] == "Logam") $class="logam";
    elseif($row['jenis'] == "Organik") $class="organik";

    $berat  = number_format($row['berat'], 1);   // 1 decimal
    $harga  = number_format($row['harga'],0,",","."); // ribuan
    $total  = number_format($row['total'],0,",",".");
?>
<div class="card <?= $class ?>">
    <h4><?= $row['jenis'] ?></h4>
    <p><strong>Berat:</strong> <?= $berat ?> Kg</p>
    <p><strong>Harga/Kg:</strong> Rp <?= $harga ?></p>
    <p><strong>Total:</strong> Rp <?= $total ?></p>
</div>
<?php } ?>
</div>


</div>
</body>
</html>
