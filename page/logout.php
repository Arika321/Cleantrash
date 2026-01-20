<?php
// logout.php - Halaman Logout CleanTrans
// @Copyright by 23552011408_Arika Azhar_TIF 23 CNS B_WEB1

session_start();

// Simpan username sebelum destroy (untuk log activity)
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'unknown';

// Log activity sebelum logout
require_once('../system/koneksi.php');
$ip = $_SERVER['REMOTE_ADDR'];
$query = "INSERT INTO log_activity (username, activity, ip_address, created_at) 
          VALUES ('$username', 'Logout', '$ip', NOW())";
mysqli_query($koneksi, $query);

// Hapus semua session
session_unset();

// Destroy session
session_destroy();

// Hapus cookie jika ada
if (isset($_COOKIE['remember_me'])) {
    setcookie('remember_me', '', time() - 3600, '/');
}

// Hapus session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Redirect LANGSUNG ke halaman login tanpa konfirmasi
header("Location: login.php?pesan=logout_sukses");
exit();
?>