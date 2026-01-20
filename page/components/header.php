<?php
// koneksi.php - Database Connection
// @Copyright by 23552011408_Arika Azhar_TIF 23 CNS B_WEB1

// Konfigurasi Database
$host = "localhost";      // Hostname database
$user = "root";           // Username database
$pass = "";               // Password database
$db   = "db_cleantrans";  // Nama database

// Koneksi ke database
$koneksi = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Set charset UTF-8 untuk mendukung karakter Indonesia
mysqli_set_charset($koneksi, "utf8");

// Set timezone
date_default_timezone_set('Asia/Jakarta');

// Fungsi untuk mencegah SQL Injection
function escape_string($data) {
    global $koneksi;
    return mysqli_real_escape_string($koneksi, $data);
}

// Fungsi untuk eksekusi query
function query($sql) {
    global $koneksi;
    $result = mysqli_query($koneksi, $sql);
    
    if (!$result) {
        error_log("Query Error: " . mysqli_error($koneksi));
        return false;
    }
    
    return $result;
}

// Fungsi untuk fetch data
function fetch_assoc($result) {
    return mysqli_fetch_assoc($result);
}

// Fungsi untuk fetch all data
function fetch_all($result) {
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}

// Fungsi untuk count rows
function num_rows($result) {
    return mysqli_num_rows($result);
}

// Fungsi untuk insert dan return last ID
function insert_id() {
    global $koneksi;
    return mysqli_insert_id($koneksi);
}

// Fungsi untuk close connection (opsional, auto close saat script selesai)
function close_connection() {
    global $koneksi;
    if ($koneksi) {
        mysqli_close($koneksi);
    }
}
?>