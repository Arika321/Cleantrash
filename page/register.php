<?php
// register.php - Halaman Registrasi Compact
// @Copyright by 23552011408_Arika Azhar_TIF 23 CNS B_WEB1

session_start();
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

require_once('../system/koneksi.php');

$error = "";
$success = "";

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($koneksi, trim($_POST['username']));
    $password = mysqli_real_escape_string($koneksi, trim($_POST['password']));
    $konfirmasi = mysqli_real_escape_string($koneksi, trim($_POST['konfirmasi_password']));
    $nama = mysqli_real_escape_string($koneksi, trim($_POST['nama']));
    $alamat = mysqli_real_escape_string($koneksi, trim($_POST['alamat']));
    $no_telp = mysqli_real_escape_string($koneksi, trim($_POST['no_telp']));
    $email = mysqli_real_escape_string($koneksi, trim($_POST['email']));
    
    if (empty($username) || empty($password) || empty($nama) || empty($alamat) || empty($no_telp)) {
        $error = "Semua field wajib diisi!";
    } elseif ($password !== $konfirmasi) {
        $error = "Password tidak cocok!";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter!";
    } else {
        $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
        if (mysqli_num_rows($cek) > 0) {
            $error = "Username sudah digunakan!";
        } else {
            $query_last = mysqli_query($koneksi, "SELECT id_nasabah FROM nasabah ORDER BY id_nasabah DESC LIMIT 1");
            if (mysqli_num_rows($query_last) > 0) {
                $last_id = mysqli_fetch_assoc($query_last)['id_nasabah'];
                $num = (int)substr($last_id, 3) + 1;
                $id_nasabah = "NSB" . str_pad($num, 4, "0", STR_PAD_LEFT);
            } else {
                $id_nasabah = "NSB0001";
            }
            
            $password_hash = md5($password);
            
            $q1 = "INSERT INTO users (username, password, nama, level, created_at) VALUES ('$username', '$password_hash', '$nama', 'nasabah', NOW())";
            $q2 = "INSERT INTO nasabah (id_nasabah, nama_nasabah, alamat, no_telp, email, saldo, tgl_daftar, status) VALUES ('$id_nasabah', '$nama', '$alamat', '$no_telp', '$email', 0, NOW(), 'aktif')";
            
            if (mysqli_query($koneksi, $q1) && mysqli_query($koneksi, $q2)) {
                $success = "Registrasi berhasil! ID: <strong>$id_nasabah</strong>";
                header("refresh:2;url=login.php");
            } else {
                $error = "Registrasi gagal!";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - CleanTrans</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .register-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            max-width: 850px;
            width: 100%;
            display: flex;
        }
        .register-left {
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
            padding: 40px 30px;
            color: white;
            flex: 0 0 40%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .register-left i {
            font-size: 3.5rem;
            margin-bottom: 15px;
        }
        .register-left h2 {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .register-left p {
            font-size: 0.9rem;
            text-align: center;
            margin-bottom: 20px;
        }
        .register-left ul {
            list-style: none;
            padding: 0;
            font-size: 0.85rem;
        }
        .register-left ul li {
            margin-bottom: 10px;
            padding-left: 22px;
            position: relative;
        }
        .register-left ul li:before {
            content: "\f00c";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            left: 0;
        }
        .register-right {
            padding: 35px 30px;
            flex: 1;
        }
        .register-right h3 {
            color: #333;
            font-size: 1.5rem;
            margin-bottom: 5px;
            font-weight: 600;
        }
        .register-right .subtitle {
            color: #666;
            margin-bottom: 20px;
            font-size: 0.85rem;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-label {
            font-weight: 500;
            color: #333;
            margin-bottom: 5px;
            font-size: 0.85rem;
        }
        .form-control, .form-select {
            padding: 8px 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 0.85rem;
        }
        .form-control:focus {
            border-color: #2ecc71;
            box-shadow: 0 0 0 0.15rem rgba(46, 204, 113, 0.25);
        }
        .input-group-text {
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            border-right: none;
            border-radius: 8px 0 0 8px;
            padding: 8px 10px;
        }
        .input-group .form-control {
            border-left: none;
            border-radius: 0 8px 8px 0;
        }
        .btn-register {
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
            border: none;
            padding: 10px;
            font-weight: 600;
            border-radius: 8px;
            color: white;
            width: 100%;
            font-size: 0.9rem;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(46, 204, 113, 0.4);
        }
        .login-link {
            text-align: center;
            margin-top: 15px;
            font-size: 0.85rem;
        }
        .login-link a {
            color: #2ecc71;
            text-decoration: none;
            font-weight: 500;
        }
        @media (max-width: 768px) {
            .register-container { flex-direction: column; }
            .register-left { padding: 30px 20px; }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-left">
            <i class="fas fa-user-plus"></i>
            <h2>Daftar Sekarang</h2>
            <p>Bergabung dengan CleanTrans!</p>
            <ul>
                <li>Kelola sampah mudah</li>
                <li>Pantau saldo real-time</li>
                <li>Riwayat transaksi</li>
                <li>Dashboard informatif</li>
            </ul>
        </div>
        
        <div class="register-right">
            <h3>Buat Akun Baru</h3>
            <p class="subtitle">Isi form untuk mendaftar</p>
            
            <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
            <div class="alert alert-success py-2"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="form-label">Username <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                            <input type="text" class="form-control" name="nama" required>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="form-label">Konfirmasi <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" name="konfirmasi_password" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Alamat <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="alamat" rows="2" required></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="form-label">No. Telepon <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input type="tel" class="form-control" name="no_telp" required>
                        </div>
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" name="email">
                        </div>
                    </div>
                </div>
                
                <button type="submit" name="register" class="btn btn-register">
                    <i class="fas fa-user-plus"></i> Daftar Sekarang
                </button>
                
                <div class="login-link">
                    Sudah punya akun? <a href="login.php">Login</a> | 
                    <a href="../index.php" class="text-muted"><i class="fas fa-home"></i> Beranda</a>
                </div>
            </form>
        </div>
    </div>
    
    <div style="position: fixed; bottom: 8px; width: 100%; text-align: center; color: white; font-size: 10px;">
        @Copyright by 23552011408_Arika Azhar_TIF 23 CNS B_WEB1
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>