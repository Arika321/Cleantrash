<?php
session_start();
if(!isset($_SESSION['login'])){ header("Location: auth/login.php"); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Setor Sampah | CleanTrash</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <nav class="navbar">
        <a href="dashboard.php" class="logo">🌱 CleanTrash</a>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="input.php" class="active">Input Sampah</a>
            <a href="laporan.php">Laporan</a>
            <a href="auth/logout.php" style="color:#ef4444">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="form-card">
            <h2 style="margin-bottom: 10px;">♻️ Setor Sampah</h2>
            <p style="color: var(--slate); margin-bottom: 30px;">Konversi sampahmu menjadi saldo digital.</p>
            
            <form action="proses_input.php" method="POST">
                <label>Pilih Jenis Sampah</label>
                <select name="jenis">
                    <option value="Plastik">Plastik</option>
                    <option value="Logam">Logam</option>
                    <option value="Organik">Organik</option>
                </select>

                <label>Berat (Kilogram)</label>
                <input type="number" name="berat" step="0.1" placeholder="Contoh: 2.5" required>

                <label>Harga per Kg (Rp)</label>
                <input type="number" name="harga" placeholder="Contoh: 5000" required>

                <button type="submit" name="submit" class="btn-primary">Simpan & Update Saldo</button>
            </form>
        </div>
    </div>
</body>
</html>