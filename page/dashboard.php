<?php
// dashboard.php - Dashboard Admin dengan Logout & Cetak Laporan
// @Copyright by 23552011408_Arika Azhar_TIF 23 CNS B_WEB1

session_start();
require_once('../system/koneksi.php');

// Proteksi halaman - hanya admin yang bisa akses
if (!isset($_SESSION['username']) || $_SESSION['level'] != 'admin') {
    header("Location: login.php?pesan=akses_ditolak");
    exit();
}

// Ambil data statistik
$query_nasabah = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM nasabah WHERE status='aktif'");
$total_nasabah = mysqli_fetch_assoc($query_nasabah)['total'];

$query_transaksi = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM transaksi");
$total_transaksi = mysqli_fetch_assoc($query_transaksi)['total'];

$query_saldo = mysqli_query($koneksi, "SELECT SUM(saldo) as total FROM nasabah");
$total_saldo = mysqli_fetch_assoc($query_saldo)['total'];

$query_sampah = mysqli_query($koneksi, "SELECT SUM(berat) as total FROM transaksi");
$total_sampah = mysqli_fetch_assoc($query_sampah)['total'];

// Transaksi terbaru
$query_recent = mysqli_query($koneksi, "
    SELECT t.*, n.nama_nasabah, j.nama_sampah 
    FROM transaksi t 
    JOIN nasabah n ON t.id_nasabah = n.id_nasabah 
    JOIN jenis_sampah j ON t.id_jenis = j.id_jenis 
    ORDER BY t.tanggal_transaksi DESC 
    LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - CleanTrans</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { background: #f8f9fa; }
        
        .sidebar { 
            min-height: 100vh; 
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%); 
            padding: 0; 
            position: fixed; 
            width: 250px; 
        }
        
        .sidebar-header { 
            padding: 20px; 
            background: rgba(0,0,0,0.2); 
            color: white; 
            text-align: center; 
        }
        
        .sidebar-header h4 { margin: 0; color: #2ecc71; }
        
        .sidebar-menu { padding: 20px 0; }
        
        .sidebar-menu a { 
            color: #ecf0f1; 
            text-decoration: none; 
            padding: 15px 25px; 
            display: block; 
            transition: all 0.3s; 
        }
        
        .sidebar-menu a:hover, .sidebar-menu a.active { 
            background: #2ecc71; 
            color: white; 
            border-left: 4px solid white; 
        }
        
        .sidebar-menu a i { margin-right: 10px; width: 20px; }
        
        .main-content { margin-left: 250px; padding: 30px; }
        
        .top-header {
            background: white;
            padding: 15px 30px;
            margin: -30px -30px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .stat-card { 
            background: white; 
            border-radius: 10px; 
            padding: 25px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
            transition: transform 0.3s; 
        }
        
        .stat-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 5px 20px rgba(0,0,0,0.15); 
        }
        
        .stat-icon { 
            width: 60px; 
            height: 60px; 
            border-radius: 10px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-size: 1.5rem; 
            color: white; 
        }
        
        .bg-primary-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .bg-success-gradient { background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%); }
        .bg-warning-gradient { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); }
        .bg-info-gradient { background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); }
        
        .card { 
            border: none; 
            border-radius: 10px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
        }
        
        .table { background: white; }
        
        .btn-action {
            padding: 8px 15px;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-recycle fa-2x mb-2"></i>
            <h4>CleanTrans</h4>
            <small>Admin Panel</small>
        </div>
        <div class="sidebar-menu">
            <a href="dashboard.php" class="active">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="nasabah.php">
                <i class="fas fa-users"></i> Data Nasabah
            </a>
            <a href="transaksi.php">
                <i class="fas fa-exchange-alt"></i> Transaksi
            </a>
            <a href="jenis_sampah.php">
                <i class="fas fa-trash-alt"></i> Jenis Sampah
            </a>
            <a href="laporan.php">
                <i class="fas fa-chart-bar"></i> Laporan
            </a>
            <a href="profil.php">
                <i class="fas fa-user"></i> Profil
            </a>
            <a href="logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Header -->
        <div class="top-header">
            <div>
                <h4 class="mb-0 fw-bold">Dashboard Admin</h4>
                <p class="text-muted mb-0">Selamat datang, <?php echo $_SESSION['nama']; ?>!</p>
            </div>
            <div>
                <button class="btn btn-primary btn-action me-2" onclick="window.open('laporan_cetak.php', '_blank')">
                    <i class="fas fa-print"></i> Cetak Laporan
                </button>
                <a href="logout.php" class="btn btn-danger btn-action">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>

        <!-- Statistik Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Nasabah</p>
                            <h3 class="fw-bold mb-0"><?php echo number_format($total_nasabah); ?></h3>
                        </div>
                        <div class="stat-icon bg-primary-gradient">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Transaksi</p>
                            <h3 class="fw-bold mb-0"><?php echo number_format($total_transaksi); ?></h3>
                        </div>
                        <div class="stat-icon bg-success-gradient">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Saldo</p>
                            <h3 class="fw-bold mb-0">Rp <?php echo number_format($total_saldo ?? 0); ?></h3>
                        </div>
                        <div class="stat-icon bg-warning-gradient">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Sampah (Kg)</p>
                            <h3 class="fw-bold mb-0"><?php echo number_format($total_sampah ?? 0, 2); ?></h3>
                        </div>
                        <div class="stat-icon bg-info-gradient">
                            <i class="fas fa-recycle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaksi Terbaru -->
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Transaksi Terbaru</h5>
                <div>
                    <button class="btn btn-sm btn-success" onclick="window.open('export_excel_transaksi.php', '_blank')">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="window.open('export_pdf_transaksi.php', '_blank')">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID Transaksi</th>
                                <th>Nasabah</th>
                                <th>Jenis Sampah</th>
                                <th>Berat (Kg)</th>
                                <th>Total</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($query_recent)): ?>
                            <tr>
                                <td><strong><?php echo $row['id_transaksi']; ?></strong></td>
                                <td><?php echo $row['nama_nasabah']; ?></td>
                                <td><span class="badge bg-info"><?php echo $row['nama_sampah']; ?></span></td>
                                <td><?php echo number_format($row['berat'], 2); ?> Kg</td>
                                <td class="fw-bold text-success">Rp <?php echo number_format($row['total']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($row['tanggal_transaksi'])); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <div class="text-center mt-3">
                    <a href="transaksi.php" class="btn btn-primary">Lihat Semua Transaksi</a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-4">
            <small class="text-muted">@Copyright by 23552011408_Arika Azhar_TIF 23 CNS B_WEB1</small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>