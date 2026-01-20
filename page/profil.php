<?php
session_start();
require_once('../system/koneksi.php');
if (!isset($_SESSION['username'])) exit;

$username = $_SESSION['username'];
$user = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'"));

if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $password_lama = md5($_POST['password_lama']);
    $password_baru = $_POST['password_baru'];
    
    if (!empty($password_baru)) {
        // Cek password lama
        if ($user['password'] == $password_lama) {
            $password_hash = md5($password_baru);
            mysqli_query($koneksi, "UPDATE users SET nama='$nama', password='$password_hash' WHERE username='$username'");
            $_SESSION['nama'] = $nama;
            $pesan = "Profil berhasil diupdate!";
        } else {
            $error = "Password lama salah!";
        }
    } else {
        mysqli_query($koneksi, "UPDATE users SET nama='$nama' WHERE username='$username'");
        $_SESSION['nama'] = $nama;
        $pesan = "Profil berhasil diupdate!";
    }
}
?>
<!-- HTML Form untuk edit profil -->