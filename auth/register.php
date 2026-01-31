<?php
session_start();
include "../config/koneksi.php";

if(isset($_POST['register'])){
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $konfirmasi = trim($_POST['konfirmasi']);

    if($password !== $konfirmasi){
        $error = "Konfirmasi password tidak cocok!";
    } else {
        $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email'");
        if(mysqli_num_rows($cek) > 0){
            $error = "Email sudah terdaftar!";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = mysqli_query(
                $koneksi,
                "INSERT INTO users (nama, email, password)
                 VALUES ('$nama','$email','$hash')"
            );

            if($insert){
                // ✅ SETELAH REGISTER → KE LOGIN (BUKAN DASHBOARD)
                header("Location: login.php?register=success");
                exit;
            } else {
                $error = "Registrasi gagal!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register | CleanTrash</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="auth-wrap">
<div class="auth-card">
    <h2>🌱 CleanTrash</h2>
    <p class="subtitle">Daftar akun baru CleanTrash</p>

    <?php if(isset($error)){ ?>
        <p style="color:red; font-size:14px;"><?= $error ?></p>
    <?php } ?>

    <form method="POST">
        <input type="text" name="nama" placeholder="Nama Lengkap" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="konfirmasi" placeholder="Konfirmasi Password" required>
        <button name="register">Daftar</button>
    </form>

    <p style="margin-top:14px;">
        Sudah punya akun? <a href="login.php">Login</a>
    </p>
</div>
</div>
</body>
</html>
