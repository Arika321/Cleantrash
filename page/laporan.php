<?php
session_start();
require_once('../system/koneksi.php');
if (!isset($_SESSION['username'])) exit;

// Statistik
$total_nasabah = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM nasabah"))['total'];
$total_transaksi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM transaksi"))['total'];
$total_pendapatan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(total) as total FROM transaksi"))['total'];
$total_sampah = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(berat) as total FROM transaksi"))['total'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Laporan - CleanTrans</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
    <!-- Sidebar (copy dari dashboard.php) -->
    
    <div class="main-content" style="margin-left:250px;padding:30px;">
        <h2 class="fw-bold mb-4">Laporan & Statistik</h2>
        
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-users fa-3x text-primary mb-3"></i>
                        <h3><?php echo number_format($total_nasabah); ?></h3>
                        <p class="text-muted">Total Nasabah</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-exchange-alt fa-3x text-success mb-3"></i>
                        <h3><?php echo number_format($total_transaksi); ?></h3>
                        <p class="text-muted">Total Transaksi</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-wallet fa-3x text-warning mb-3"></i>
                        <h3>Rp <?php echo number_format($total_pendapatan??0); ?></h3>
                        <p class="text-muted">Total Pendapatan</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-recycle fa-3x text-info mb-3"></i>
                        <h3><?php echo number_format($total_sampah??0,2); ?> Kg</h3>
                        <p class="text-muted">Total Sampah</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-white">
                <h5>Export Laporan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Laporan Nasabah</h6>
                        <a href="export_pdf.php" class="btn btn-danger me-2" target="_blank">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                        <a href="export_excel.php" class="btn btn-success" target="_blank">
                            <i class="fas fa-file-excel"></i> Excel
                        </a>
                    </div>
                    <div class="col-md-6">
                        <h6>Laporan Transaksi</h6>
                        <a href="export_pdf_transaksi.php" class="btn btn-danger me-2" target="_blank">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                        <a href="export_excel_transaksi.php" class="btn btn-success" target="_blank">
                            <i class="fas fa-file-excel"></i> Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <?php include('components/footer.php'); ?>
    </div>
</body>
</html>