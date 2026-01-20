<?php
// admin.php - Halaman Admin Transaksi Setor Sampah
// @Copyright by 23552011408_Arika Azhar_TIF 23 CNS B_WEB1

session_start();
require_once('../system/koneksi.php');

if (!isset($_SESSION['username']) || $_SESSION['level'] != 'admin') {
    header("Location: login.php");
    exit();
}

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Ambil statistik untuk dashboard
$total_nasabah = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM nasabah WHERE status='aktif'"))['total'];
$total_transaksi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM transaksi"))['total'];
$total_sampah = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(berat) as total FROM transaksi"))['total'] ?? 0;
$total_saldo = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(saldo) as total FROM nasabah"))['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - CleanTrans</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <style>
        :root {
            --sidebar-width: 250px;
            --header-height: 60px;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: #2c3e50;
            color: white;
            z-index: 1000;
        }
        
        .sidebar-header {
            padding: 20px;
            background: rgba(0,0,0,0.2);
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-header h4 {
            margin: 10px 0 5px;
            color: #2ecc71;
            font-weight: bold;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .sidebar-menu a {
            display: block;
            padding: 12px 20px;
            color: #ecf0f1;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: #34495e;
            border-left-color: #2ecc71;
            padding-left: 25px;
        }
        
        .sidebar-menu a i {
            margin-right: 10px;
            width: 20px;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }
        
        .top-header {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .content-wrapper {
            padding: 30px;
        }
        
        .page-title {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 30px;
            margin: -30px -30px 30px;
            border-radius: 0;
        }
        
        /* Cards */
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }
        
        .bg-blue { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .bg-green { background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%); }
        .bg-orange { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); }
        .bg-red { background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); }
        
        /* Table */
        .table-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 25px;
        }
        
        .table thead {
            background: #2ecc71;
            color: white;
        }
        
        .btn-action {
            padding: 5px 10px;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-recycle fa-2x"></i>
            <h4>CleanTrans</h4>
            <small>Master-admin, administrator</small>
        </div>
        <div class="sidebar-menu">
            <a href="?page=dashboard" class="<?php echo ($page=='dashboard')?'active':''; ?>">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="?page=data-admin" class="<?php echo ($page=='data-admin')?'active':''; ?>">
                <i class="fas fa-user-shield"></i> Data Admin
            </a>
            <a href="?page=data-nasabah" class="<?php echo ($page=='data-nasabah')?'active':''; ?>">
                <i class="fas fa-users"></i> Data Nasabah
            </a>
            <a href="?page=data-sampah" class="<?php echo ($page=='data-sampah')?'active':''; ?>">
                <i class="fas fa-trash-alt"></i> Data Sampah
            </a>
            <a href="?page=data-setor" class="<?php echo ($page=='data-setor')?'active':''; ?>">
                <i class="fas fa-exchange-alt"></i> Transaksi Setor
            </a>
            <a href="?page=data-tarik" class="<?php echo ($page=='data-tarik')?'active':''; ?>">
                <i class="fas fa-money-bill-wave"></i> Transaksi Tarik
            </a>
            <a href="?page=data-report" class="<?php echo ($page=='data-report')?'active':''; ?>">
                <i class="fas fa-chart-bar"></i> Grafik Monitoring
            </a>
            <a href="logout.php" onclick="return confirm('Yakin logout?')">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="top-header">
            <div>
                <h5 class="mb-0">CleanTrans Admin Panel</h5>
            </div>
            <div>
                <span class="text-muted">
                    <i class="fas fa-user"></i> <?php echo $_SESSION['nama']; ?>
                </span>
            </div>
        </div>

        <div class="content-wrapper">
            <?php
            // Routing halaman
            switch($page) {
                case 'data-sampah':
                    include('admin_data_sampah.php');
                    break;
                case 'data-setor':
                    include('admin_data_setor.php');
                    break;
                case 'data-report':
                    include('admin_grafik.php');
                    break;
                case 'data-nasabah':
                    echo '<script>window.location="nasabah.php"</script>';
                    break;
                default:
                    // Dashboard
            ?>
            <div class="page-title">
                <h3 class="mb-0"><i class="fas fa-home"></i> Dashboard</h3>
            </div>

            <!-- Statistics Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Total Nasabah</small>
                                <h3 class="fw-bold mb-0"><?php echo number_format($total_nasabah); ?></h3>
                            </div>
                            <div class="stat-icon bg-blue">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Total Transaksi</small>
                                <h3 class="fw-bold mb-0"><?php echo number_format($total_transaksi); ?></h3>
                            </div>
                            <div class="stat-icon bg-green">
                                <i class="fas fa-exchange-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Total Sampah (Kg)</small>
                                <h3 class="fw-bold mb-0"><?php echo number_format($total_sampah, 2); ?></h3>
                            </div>
                            <div class="stat-icon bg-orange">
                                <i class="fas fa-weight"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Total Saldo</small>
                                <h3 class="fw-bold mb-0">Rp <?php echo number_format($total_saldo); ?></h3>
                            </div>
                            <div class="stat-icon bg-red">
                                <i class="fas fa-wallet"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-trash-alt fa-3x text-success mb-3"></i>
                            <h5>Data Sampah</h5>
                            <p class="text-muted mb-3">Kelola jenis dan harga sampah</p>
                            <a href="?page=data-sampah" class="btn btn-success">Lihat Data</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-exchange-alt fa-3x text-primary mb-3"></i>
                            <h5>Transaksi Setor</h5>
                            <p class="text-muted mb-3">Catat transaksi setor sampah</p>
                            <a href="?page=data-setor" class="btn btn-primary">Input Transaksi</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-chart-bar fa-3x text-warning mb-3"></i>
                            <h5>Grafik Monitoring</h5>
                            <p class="text-muted mb-3">Lihat statistik grafik</p>
                            <a href="?page=data-report" class="btn btn-warning">Lihat Grafik</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-5">
                <small class="text-muted">@Copyright by 23552011408_Arika Azhar_TIF 23 CNS B_WEB1</small>
            </div>
            <?php
                }
            ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
</body>
</html>