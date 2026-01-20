<?php
// profil_update.php - Update Profil Nasabah
// @Copyright by 23552011408_Arika Azhar_TIF 23 CNS B_WEB1

session_start();
require_once('../system/koneksi.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['update'])) {
    $username = $_SESSION['username'];
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    
    // Update tabel users
    if (!empty($password)) {
        // Jika password diisi, update dengan password baru
        $password_hash = md5($password);
        $query_user = "UPDATE users SET nama='$nama', password='$password_hash' WHERE username='$username'";
    } else {
        // Jika password kosong, hanya update nama
        $query_user = "UPDATE users SET nama='$nama' WHERE username='$username'";
    }
    
    // Update tabel nasabah
    $query_nasabah = "UPDATE nasabah SET nama_nasabah='$nama', email='$email' WHERE nama_nasabah='{$_SESSION['nama']}'";
    
    if (mysqli_query($koneksi, $query_user) && mysqli_query($koneksi, $query_nasabah)) {
        // Update session nama
        $_SESSION['nama'] = $nama;
        
        // Redirect dengan pesan sukses
        header("Location: dashboard_nasabah.php?pesan=update_sukses");
    } else {
        header("Location: dashboard_nasabah.php?pesan=update_gagal");
    }
} else {
    header("Location: dashboard_nasabah.php");
}
?>