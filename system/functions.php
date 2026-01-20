<?php
// system/functions.php - Helper Functions
// @Copyright by 23552011408_Arika Azhar_TIF 23 CNS B_WEB1

// Format Rupiah
function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

// Format Tanggal Indonesia
function tanggalIndo($tanggal) {
    $bulan = array(
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    
    $pecahkan = explode('-', date('Y-m-d', strtotime($tanggal)));
    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}

// Sanitasi Input
function cleanInput($data) {
    global $koneksi;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($koneksi, $data);
    return $data;
}

// Generate ID Otomatis
function generateID($prefix, $table, $column, $length = 4) {
    global $koneksi;
    
    $query = mysqli_query($koneksi, "SELECT $column FROM $table ORDER BY $column DESC LIMIT 1");
    
    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        $lastID = $data[$column];
        $num = (int)substr($lastID, strlen($prefix)) + 1;
        $newID = $prefix . str_pad($num, $length, "0", STR_PAD_LEFT);
    } else {
        $newID = $prefix . str_pad(1, $length, "0", STR_PAD_LEFT);
    }
    
    return $newID;
}

// Cek Session Login
function cekLogin() {
    if (!isset($_SESSION['username'])) {
        header("Location: login.php?pesan=belum_login");
        exit();
    }
}

// Cek Level User
function cekLevel($level) {
    if (!isset($_SESSION['level']) || $_SESSION['level'] != $level) {
        header("Location: login.php?pesan=akses_ditolak");
        exit();
    }
}

// Log Activity
function logActivity($username, $activity) {
    global $koneksi;
    
    $username = cleanInput($username);
    $activity = cleanInput($activity);
    $ip = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    
    $query = "INSERT INTO log_activity (username, activity, ip_address, user_agent, created_at) 
              VALUES ('$username', '$activity', '$ip', '$user_agent', NOW())";
    
    mysqli_query($koneksi, $query);
}

// Update Saldo Nasabah
function updateSaldo($id_nasabah, $jumlah, $tipe = 'tambah') {
    global $koneksi;
    
    $operator = ($tipe == 'tambah') ? '+' : '-';
    $query = "UPDATE nasabah SET saldo = saldo $operator $jumlah WHERE id_nasabah='$id_nasabah'";
    
    return mysqli_query($koneksi, $query);
}

// Kirim Notifikasi (untuk fitur lanjutan)
function kirimNotifikasi($user_id, $judul, $pesan) {
    global $koneksi;
    
    $query = "INSERT INTO notifikasi (user_id, judul, pesan, is_read, created_at) 
              VALUES ('$user_id', '$judul', '$pesan', 0, NOW())";
    
    return mysqli_query($koneksi, $query);
}

// Hitung Persentase
function hitungPersentase($nilai, $total) {
    if ($total == 0) return 0;
    return round(($nilai / $total) * 100, 2);
}

// Validasi Email
function validasiEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Validasi Nomor Telepon
function validasiNoTelp($notelp) {
    // Format: 08xxxxxxxxxx (minimal 10 digit)
    $pattern = '/^08[0-9]{8,12}$/';
    return preg_match($pattern, $notelp);
}

// Generate Random String
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    
    return $randomString;
}

// Compress Image (untuk upload foto)
function compressImage($source, $destination, $quality = 75) {
    $info = getimagesize($source);
    
    if ($info['mime'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($source);
    } elseif ($info['mime'] == 'image/gif') {
        $image = imagecreatefromgif($source);
    } elseif ($info['mime'] == 'image/png') {
        $image = imagecreatefrompng($source);
    }
    
    imagejpeg($image, $destination, $quality);
    
    return $destination;
}

// Time Ago Function
function timeAgo($datetime) {
    $timestamp = strtotime($datetime);
    $difference = time() - $timestamp;
    
    if ($difference < 60) {
        return $difference . " detik yang lalu";
    } elseif ($difference < 3600) {
        return round($difference / 60) . " menit yang lalu";
    } elseif ($difference < 86400) {
        return round($difference / 3600) . " jam yang lalu";
    } elseif ($difference < 604800) {
        return round($difference / 86400) . " hari yang lalu";
    } else {
        return date('d M Y', $timestamp);
    }
}

// Debug Helper
function dd($data) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    die();
}

// Alert Helper
function setAlert($type, $message) {
    $_SESSION['alert'] = [
        'type' => $type, // success, danger, warning, info
        'message' => $message
    ];
}

function showAlert() {
    if (isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert'];
        echo '<div class="alert alert-' . $alert['type'] . ' alert-dismissible fade show" role="alert">';
        echo '<i class="fas fa-' . ($alert['type'] == 'success' ? 'check' : 'exclamation') . '-circle"></i> ';
        echo $alert['message'];
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
        echo '</div>';
        unset($_SESSION['alert']);
    }
}
?>