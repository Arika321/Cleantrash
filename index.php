<?php
session_start();

// Jika user sudah login, langsung lempar ke dashboard
if(isset($_SESSION['login'])){
    header("Location: dashboard.php");
    exit;
} else {
    // Jika belum login, langsung lempar ke halaman login
    header("Location: auth/login.php");
    exit;
}
?>