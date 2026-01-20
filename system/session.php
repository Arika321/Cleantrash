<?php
// session.php - Session Management & Security
// @Copyright by 23552011408_Arika Azhar_TIF 23 CNS B_WEB1

// Start session jika belum dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Fungsi untuk cek apakah user sudah login
function cekLogin() {
    if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
        header("Location: login.php?pesan=belum_login");
        exit();
    }
}

// Fungsi untuk cek level user (admin atau nasabah)
function cekLevel($level_required) {
    if (!isset($_SESSION['level']) || $_SESSION['level'] != $level_required) {
        header("Location: login.php?pesan=akses_ditolak");
        exit();
    }
}

// Fungsi untuk cek session timeout (30 menit = 1800 detik)
function cekTimeout() {
    $timeout_duration = 1800; // 30 menit
    
    if (isset($_SESSION['login_time'])) {
        $elapsed_time = time() - $_SESSION['login_time'];
        
        if ($elapsed_time >= $timeout_duration) {
            // Session timeout - hapus session
            session_unset();
            session_destroy();
            header("Location: login.php?pesan=timeout");
            exit();
        }
    }
    
    // Update login time setiap aktivitas
    $_SESSION['login_time'] = time();
}

// Fungsi untuk mencatat aktivitas login
function logActivity($username, $activity) {
    require_once('koneksi.php');
    
    $username = mysqli_real_escape_string($koneksi, $username);
    $activity = mysqli_real_escape_string($koneksi, $activity);
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Unknown';
    
    // Buat tabel log jika belum ada
    $create_table = "CREATE TABLE IF NOT EXISTS log_activity (
        id_log INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50),
        activity VARCHAR(255),
        ip_address VARCHAR(45),
        user_agent TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_username (username),
        INDEX idx_created (created_at)
    )";
    mysqli_query($koneksi, $create_table);
    
    // Insert log
    $query = "INSERT INTO log_activity (username, activity, ip_address, user_agent) 
              VALUES ('$username', '$activity', '$ip_address', '$user_agent')";
    mysqli_query($koneksi, $query);
}

// Fungsi untuk update last login
function updateLastLogin($username) {
    require_once('koneksi.php');
    
    $username = mysqli_real_escape_string($koneksi, $username);
    $query = "UPDATE users SET last_login = NOW() WHERE username = '$username'";
    mysqli_query($koneksi, $query);
}

// Fungsi untuk generate CSRF token
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Fungsi untuk validasi CSRF token
function validateCSRFToken($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token! Possible CSRF attack detected.");
    }
    return true;
}

// Fungsi untuk sanitasi input (mencegah XSS & SQL Injection)
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Fungsi untuk sanitasi SQL
function cleanSQL($data) {
    global $koneksi;
    return mysqli_real_escape_string($koneksi, $data);
}

// Fungsi untuk format tanggal Indonesia
function tanggalIndo($tanggal) {
    $bulan = array(
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    
    $pecahkan = explode('-', date('Y-m-d', strtotime($tanggal)));
    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}

// Fungsi untuk format rupiah
function formatRupiah($angka, $prefix = 'Rp ') {
    return $prefix . number_format($angka, 0, ',', '.');
}

// Fungsi untuk generate alert message
function setFlashMessage($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type, // success, danger, warning, info
        'message' => $message
    ];
}

// Fungsi untuk menampilkan alert message
function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $flash = $_SESSION['flash_message'];
        $icons = [
            'success' => 'check-circle',
            'danger' => 'exclamation-circle',
            'warning' => 'exclamation-triangle',
            'info' => 'info-circle'
        ];
        
        echo '<div class="alert alert-' . $flash['type'] . ' alert-dismissible fade show" role="alert">';
        echo '<i class="fas fa-' . $icons[$flash['type']] . '"></i> ';
        echo $flash['message'];
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
        echo '</div>';
        
        unset($_SESSION['flash_message']);
    }
}

// Fungsi untuk hash password (MD5 - untuk kompatibilitas)
function hashPassword($password) {
    return md5($password);
}

// Fungsi untuk verifikasi password
function verifyPassword($password, $hash) {
    return (md5($password) === $hash);
}

// Fungsi untuk regenerate session ID (mencegah session fixation)
function regenerateSession() {
    if (isset($_SESSION['username'])) {
        session_regenerate_id(true);
    }
}

// Fungsi untuk destroy session (logout)
function destroySession() {
    // Unset all session variables
    $_SESSION = array();
    
    // Destroy session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 42000, '/');
    }
    
    // Destroy session
    session_destroy();
}

// Auto check session timeout untuk halaman yang memerlukan login
if (isset($_SESSION['username'])) {
    cekTimeout();
    regenerateSession();
}

// Fungsi untuk redirect dengan pesan
function redirectWithMessage($url, $type, $message) {
    setFlashMessage($type, $message);
    header("Location: $url");
    exit();
}

// Fungsi untuk cek apakah request adalah AJAX
function isAjaxRequest() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

// Fungsi untuk JSON response
function jsonResponse($status, $message, $data = null) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => $status,
        'message' => $message,
        'data' => $data
    ]);
    exit();
}

// Security headers
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");

// Prevent access to this file directly
if (basename($_SERVER['PHP_SELF']) == 'session.php') {
    die('Direct access not permitted');
}
?>