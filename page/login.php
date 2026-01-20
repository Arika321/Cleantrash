<?php
// login.php - Halaman Login CleanTrans dengan Design Aesthetic
// @Copyright by 23552011408_Arika Azhar_TIF 23 CNS B_WEB1

session_start();

if (isset($_SESSION['username'])) {
    if ($_SESSION['level'] == 'admin') {
        header("Location: dashboard.php");
    } else {
        header("Location: dashboard_nasabah.php");
    }
    exit();
}

require_once('../system/koneksi.php');

$error = "";

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($koneksi, trim($_POST['email']));
    $password = mysqli_real_escape_string($koneksi, trim($_POST['password']));
    
    $password_hash = md5($password);
    
    $query = "SELECT u.*, n.email FROM users u 
              LEFT JOIN nasabah n ON u.nama = n.nama_nasabah 
              WHERE (u.username='$email' OR n.email='$email') AND u.password='$password_hash' 
              LIMIT 1";
    $result = mysqli_query($koneksi, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['level'] = $user['level'];
        $_SESSION['login_time'] = time();
        
        mysqli_query($koneksi, "UPDATE users SET last_login=NOW() WHERE id_user='{$user['id_user']}'");
        
        $ip = $_SERVER['REMOTE_ADDR'];
        mysqli_query($koneksi, "INSERT INTO log_activity (username, activity, ip_address, created_at) VALUES ('{$user['username']}', 'Login', '$ip', NOW())");
        
        if ($user['level'] == 'admin') {
            header("Location: dashboard.php");
        } else {
            header("Location: dashboard_nasabah.php");
        }
        exit();
    } else {
        $error = "Email/Username atau password salah!";
    }
}

$pesan = isset($_GET['pesan']) ? $_GET['pesan'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CleanTrans</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow: hidden;
        }
        
        .login-container {
            background: white;
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            overflow: hidden;
            max-width: 1100px;
            width: 100%;
            display: flex;
            position: relative;
        }
        
        /* Left Side - Illustration */
        .login-left {
            flex: 0 0 55%;
            background: linear-gradient(135deg, #FF6B35 0%, #FF8C42 100%);
            padding: 40px;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .logo-section {
            z-index: 2;
        }
        
        .logo {
            background: white;
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }
        
        .logo i {
            font-size: 2rem;
            color: #FF6B35;
        }
        
        .welcome-text {
            color: white;
            z-index: 2;
        }
        
        .welcome-text h2 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .welcome-text p {
            font-size: 1rem;
            opacity: 0.95;
            margin-bottom: 0;
        }
        
        /* City Illustration */
        .city-illustration {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60%;
            z-index: 1;
        }
        
        .building {
            position: absolute;
            bottom: 60px;
            background: rgba(255,255,255,0.2);
            border-radius: 8px 8px 0 0;
        }
        
        .building-1 { width: 80px; height: 180px; left: 50px; }
        .building-2 { width: 60px; height: 150px; left: 150px; }
        .building-3 { width: 70px; height: 200px; left: 230px; }
        .building-4 { width: 50px; height: 130px; left: 320px; }
        .building-5 { width: 65px; height: 160px; left: 390px; }
        
        .building::before {
            content: '';
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 8px;
            height: 15px;
            background: rgba(255,255,255,0.4);
        }
        
        /* Trash Bin */
        .trash-bin {
            position: absolute;
            bottom: 50px;
            right: 80px;
            width: 60px;
            height: 70px;
            background: rgba(255,255,255,0.3);
            border-radius: 8px 8px 12px 12px;
            z-index: 2;
        }
        
        .trash-bin::before {
            content: '';
            position: absolute;
            top: -8px;
            left: -5px;
            right: -5px;
            height: 8px;
            background: rgba(255,255,255,0.4);
            border-radius: 4px 4px 0 0;
        }
        
        .trash-bin i {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 1.8rem;
            color: rgba(255,255,255,0.8);
        }
        
        /* Ground */
        .ground {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: rgba(255,255,255,0.15);
            border-radius: 50% 50% 0 0 / 20px 20px 0 0;
        }
        
        /* Right Side - Form */
        .login-right {
            flex: 1;
            padding: 50px 40px;
        }
        
        .form-title {
            margin-bottom: 10px;
        }
        
        .form-title h3 {
            color: #2c3e50;
            font-weight: bold;
            font-size: 1.8rem;
            margin-bottom: 5px;
        }
        
        .form-subtitle {
            color: #7f8c8d;
            margin-bottom: 30px;
            font-size: 0.95rem;
        }
        
        .form-label {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }
        
        .form-control {
            padding: 12px 15px;
            border: 2px solid #e8e8e8;
            border-radius: 10px;
            transition: all 0.3s;
            font-size: 0.95rem;
        }
        
        .form-control:focus {
            border-color: #FF6B35;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.15);
        }
        
        .input-group-text {
            background: #f8f9fa;
            border: 2px solid #e8e8e8;
            border-right: none;
            border-radius: 10px 0 0 10px;
            color: #7f8c8d;
        }
        
        .input-group .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #FF6B35 0%, #FF8C42 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 10px;
            color: white;
            width: 100%;
            transition: all 0.3s;
            font-size: 1rem;
            margin-top: 10px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 107, 53, 0.3);
        }
        
        .divider {
            text-align: center;
            margin: 20px 0;
            position: relative;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 45%;
            height: 1px;
            background: #e8e8e8;
        }
        
        .divider::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            width: 45%;
            height: 1px;
            background: #e8e8e8;
        }
        
        .register-link {
            text-align: center;
            margin-top: 15px;
            font-size: 0.9rem;
        }
        
        .register-link a {
            color: #FF6B35;
            text-decoration: none;
            font-weight: 600;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .login-container { flex-direction: column; }
            .login-left { flex: 1; padding: 30px 20px; min-height: 300px; }
            .login-right { padding: 35px 25px; }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Side - Illustration -->
        <div class="login-left">
            <div class="logo-section">
                <div class="logo">
                    <i class="fas fa-recycle"></i>
                </div>
            </div>
            
            <div class="welcome-text">
                <h2>AYO<br>BERANGKAT</h2>
                <p>Siap untuk perjalanan<br>daur ulang!</p>
            </div>
            
            <!-- City Illustration -->
            <div class="city-illustration">
                <div class="building building-1"></div>
                <div class="building building-2"></div>
                <div class="building building-3"></div>
                <div class="building building-4"></div>
                <div class="building building-5"></div>
                
                <!-- Trash Bin -->
                <div class="trash-bin">
                    <i class="fas fa-trash-alt"></i>
                </div>
                
                <div class="ground"></div>
            </div>
        </div>
        
        <!-- Right Side - Form -->
        <div class="login-right">
            <div class="form-title">
                <h3>Masuk Akun</h3>
            </div>
            <p class="form-subtitle">Gunakan email terdaftar Anda</p>
            
            <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <?php if ($pesan == 'logout_sukses'): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle"></i> Logout berhasil!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php elseif ($pesan == 'belum_login'): ?>
            <div class="alert alert-warning alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle"></i> Silakan login terlebih dahulu!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">EMAIL / USERNAME</label>
                    <input type="text" class="form-control" name="email" placeholder="email@anda.com" required autofocus>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">PASSWORD</label>
                    <div class="input-group">
                        <input type="password" class="form-control" name="password" id="password" placeholder="••••••••" required>
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()" style="border: 2px solid #e8e8e8; border-left: none; border-radius: 0 10px 10px 0;">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>
                
                <button type="submit" name="login" class="btn btn-login">
                    MASUK SEKARANG
                </button>
            </form>
            
            <div class="divider">
                <span style="background: white; padding: 0 10px; color: #7f8c8d; font-size: 0.85rem;">atau</span>
            </div>
            
            <div class="register-link">
                Belum punya akun? <a href="register.php">Daftar Disini</a>
            </div>
            
            <div class="text-center mt-4">
                <small class="text-muted" style="font-size: 0.75rem;">
                    @Copyright by 23552011408_Arika Azhar_TIF 23 CNS B_WEB1
                </small>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (password.type === 'password') {
                password.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
        
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>