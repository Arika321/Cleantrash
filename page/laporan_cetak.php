<?php
// laporan_cetak.php - Cetak Laporan Lengkap Admin
// @Copyright by 23552011408_Arika Azhar_TIF 23 CNS B_WEB1

session_start();
require_once('../system/koneksi.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Ambil statistik
$total_nasabah = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM nasabah WHERE status='aktif'"))['total'];
$total_transaksi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM transaksi"))['total'];
$total_saldo = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(saldo) as total FROM nasabah"))['total'];
$total_sampah = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(berat) as total FROM transaksi"))['total'];

// Data transaksi
$query_transaksi = mysqli_query($koneksi, "
    SELECT t.*, n.nama_nasabah, n.id_nasabah as nin, j.nama_sampah 
    FROM transaksi t 
    JOIN nasabah n ON t.id_nasabah = n.id_nasabah 
    JOIN jenis_sampah j ON t.id_jenis = j.id_jenis 
    ORDER BY t.tanggal_transaksi DESC 
    LIMIT 20
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan CleanTrans - <?php echo date('d-m-Y'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
        }
        
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            padding: 20px;
        }
        
        .kop-surat {
            border-bottom: 3px solid #2ecc71;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        
        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
        }
        
        .kop-title {
            text-align: center;
            margin-top: 10px;
        }
        
        .kop-title h2 {
            color: #2ecc71;
            font-weight: bold;
            margin: 0;
        }
        
        .kop-title p {
            margin: 5px 0;
            color: #666;
        }
        
        .stat-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            border-left: 4px solid #2ecc71;
        }
        
        .stat-box h4 {
            margin: 0;
            color: #2ecc71;
            font-weight: bold;
        }
        
        .stat-box p {
            margin: 5px 0 0;
            color: #666;
            font-size: 0.9rem;
        }
        
        .table th {
            background: #2ecc71;
            color: white;
        }
        
        .footer-print {
            margin-top: 50px;
            text-align: center;
            border-top: 2px solid #e0e0e0;
            padding-top: 20px;
        }
        
        .signature-box {
            margin-top: 50px;
            text-align: right;
        }
    </style>
</head>
<body>
    <!-- Button Print -->
    <div class="no-print mb-3 text-end">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Cetak / Simpan PDF
        </button>
        <button onclick="window.close()" class="btn btn-secondary">
            <i class="fas fa-times"></i> Tutup
        </button>
    </div>

    <!-- Kop Surat -->
    <div class="kop-surat">
        <div class="logo-container">
            <div class="logo">
                <i class="fas fa-recycle"></i>
            </div>
        </div>
        <div class="kop-title">
            <h2>CLEANTRANS</h2>
            <p><strong>Bank Sampah Digital</strong></p>
            <p>Jl. Bank Sampah No. 123, Bandung, Jawa Barat | Telp: 0812-3456-7890</p>
            <p>Email: info@cleantrans.com | Website: www.cleantrans.com</p>
        </div>
    </div>

    <!-- Judul Laporan -->
    <div class="text-center mb-4">
        <h3 class="fw-bold">LAPORAN TRANSAKSI BANK SAMPAH</h3>
        <p class="text-muted">Periode: Semua Data s/d <?php echo date('d F Y'); ?></p>
    </div>

    <!-- Statistik -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-box">
                <h4><?php echo number_format($total_nasabah); ?></h4>
                <p>Total Nasabah Aktif</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box">
                <h4><?php echo number_format($total_transaksi); ?></h4>
                <p>Total Transaksi</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box">
                <h4>Rp <?php echo number_format($total_saldo ?? 0); ?></h4>
                <p>Total Saldo</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box">
                <h4><?php echo number_format($total_sampah ?? 0, 2); ?> Kg</h4>
                <p>Total Sampah</p>
            </div>
        </div>
    </div>

    <!-- Tabel Transaksi -->
    <h5 class="fw-bold mb-3">Daftar Transaksi Terbaru (20 Data Terakhir)</h5>
    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Transaksi</th>
                    <th>Tanggal</th>
                    <th>ID Nasabah</th>
                    <th>Nama Nasabah</th>
                    <th>Jenis Sampah</th>
                    <th>Berat (Kg)</th>
                    <th>Total (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while($row = mysqli_fetch_assoc($query_transaksi)): 
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $row['id_transaksi']; ?></td>
                    <td><?php echo date('d/m/Y', strtotime($row['tanggal_transaksi'])); ?></td>
                    <td><?php echo $row['nin']; ?></td>
                    <td><?php echo $row['nama_nasabah']; ?></td>
                    <td><?php echo $row['nama_sampah']; ?></td>
                    <td class="text-end"><?php echo number_format($row['berat'], 2); ?></td>
                    <td class="text-end"><?php echo number_format($row['total']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Tanda Tangan -->
    <div class="signature-box">
        <p>Bandung, <?php echo date('d F Y'); ?></p>
        <p class="fw-bold">Administrator</p>
        <div style="height: 60px;"></div>
        <p class="fw-bold" style="text-decoration: underline;"><?php echo $_SESSION['nama']; ?></p>
        <p>Admin CleanTrans</p>
    </div>

    <!-- Footer -->
    <div class="footer-print">
        <p class="mb-0"><small>@Copyright by 23552011408_Arika Azhar_TIF 23 CNS B_WEB1</small></p>
        <p class="mb-0"><small>Dicetak pada: <?php echo date('d-m-Y H:i:s'); ?> WIB</small></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>