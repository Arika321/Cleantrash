<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Saldo - CleanTrash</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="navbar">
    <h3>🌱 CleanTrash</h3>
</div>

<div class="dashboard-container">
    <h2>💰 Saldo Tabungan Sampah</h2>
    <div class="info-card">
        <h3>Rp 150.000</h3>
        <p>Saldo ini merupakan simulasi dari total sampah terkumpul</p>
    </div>
</div>

</body>
</html>
