<?php
session_start();
include "../config/koneksi.php";

// kalau sudah login, langsung ke dashboard
if(isset($_SESSION['login'])){
    header("Location: ../dashboard.php");
    exit;
}

if(isset($_POST['login'])){
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email'");
    if(mysqli_num_rows($query) > 0){
        $data = mysqli_fetch_assoc($query);
        if(password_verify($password, $data['password'])){
            // ✅ LOGIN BARU BOLEH MASUK DASHBOARD
            $_SESSION['login'] = true;
            $_SESSION['nama']  = $data['nama'];
            $_SESSION['email'] = $data['email'];
            header("Location: ../dashboard.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Email tidak terdaftar!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login | CleanTrash</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="auth-wrap">
<div class="auth-card">
    <h2>🌱 CleanTrash</h2>
    <p class="subtitle">Silakan login ke akun Anda</p>

    <?php if(isset($_GET['register']) && $_GET['register'] == 'success'){ ?>
        <p style="color:green; font-size:14px;">
            ✅ Registrasi berhasil! Silakan login.
        </p>
    <?php } ?>

    <?php if(isset($error)){ ?>
        <p style="color:red; font-size:14px;"><?= $error ?></p>
    <?php } ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button name="login">Login</button>
    </form>

    <p style="margin-top:14px;">
        Belum punya akun? <a href="register.php">Daftar</a>
    </p>
</div>
</div>
</body>
</html>
