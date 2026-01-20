<?php
// dashboard_nasabah.php - Dashboard untuk Level Nasabah
// @Copyright by 23552011408_Arika Azhar_TIF 23 CNS B_WEB1

session_start();
require_once('../system/koneksi.php');

if (!isset($_SESSION['username']) || $_SESSION['level'] != 'nasabah') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Ambil data nasabah berdasarkan username
$query_user = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
$user = mysqli_fetch_assoc($query_user);
$nama = $user['nama'];

// Cari data nasabah berdasarkan nama
$query_nasabah = mysqli_query($koneksi, "SELECT * FROM nasabah WHERE nama_nasabah='$nama' LIMIT 1");
$nasabah = mysqli_fetch_assoc($query_nasabah);

if ($nasabah) {
    $id_nasabah = $nasabah['id_nasabah'];
    $email = $nasabah['email'];
    $saldo = $nasabah['saldo'];
    
    // Hitung total transaksi
    $query_count = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM transaksi WHERE id_nasabah='$id_nasabah'");
    $total_transaksi = mysqli_fetch_assoc($query_count)['total'];
    
    // Hitung total sampah
    $query_sampah = mysqli_query($koneksi, "SELECT SUM(berat) as total FROM transaksi WHERE id_nasabah='$id_nasabah'");
    $total_sampah = mysqli_fetch_assoc($query_sampah)['total'] ?? 0;
    
    // Ambil riwayat transaksi
    $query_riwayat = mysqli_query($koneksi, "
        SELECT t.*, j.nama_sampah 
        FROM transaksi t 
        JOIN jenis_sampah j ON t.id_jenis = j.id_jenis 
        WHERE t.id_nasabah='$id_nasabah' 
        ORDER BY t.tanggal_transaksi DESC 
        LIMIT 10
    ");
} else {
    $id_nasabah = '-';
    $email = '-';
    $saldo = 0;
    $total_transaksi = 0;
    $total_sampah = 0;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Nasabah - CleanTrans</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            min-height: 100vh; 
            padding: 20px; 
        }
        .dashboard-container { 
            max-width: 1200px; 
            margin: 0 auto; 
        }
        .card { 
            border: none; 
            border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.2); 
        }
        .profile-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
        }
        .profile-avatar {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
        }
        .stat-card { 
            background: white; 
            border-radius: 15px; 
            padding: 25px; 
            margin-bottom: 20px; 
            box-shadow: 0 5px 20px rgba(0,0,0,0.1); 
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
        .bg-gradient-1 { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .bg-gradient-2 { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .bg-gradient-3 { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .btn-action {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Profile Card -->
        <div class="profile-card">
            <div class="row align-items-center">
                <div class="col-md-1 text-center">
                    <div class="profile-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
                <div class="col-md-7">
                    <h4 class="mb-1 fw-bold"><?php echo $nama; ?></h4>
                    <p class="text-muted mb-0">
                        <i class="fas fa-id-card"></i> ID Nasabah: <strong><?php echo $id_nasabah; ?></strong>
                    </p>
                    <p class="text-muted mb-0">
                        <i class="fas fa-envelope"></i> <?php echo $email; ?>
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-primary btn-action me-2" data-bs-toggle="modal" data-bs-target="#modalEditProfil">
                        <i class="fas fa-edit"></i> Edit Profil
                    </button>
                    <a href="logout.php" class="btn btn-danger btn-action">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistik -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Saldo Saya</p>
                            <h3 class="fw-bold mb-0">Rp <?php echo number_format($saldo); ?></h3>
                        </div>
                        <div class="stat-icon bg-gradient-1">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Transaksi</p>
                            <h3 class="fw-bold mb-0"><?php echo number_format($total_transaksi); ?>x</h3>
                        </div>
                        <div class="stat-icon bg-gradient-2">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Sampah</p>
                            <h3 class="fw-bold mb-0"><?php echo number_format($total_sampah, 2); ?> Kg</h3>
                        </div>
                        <div class="stat-icon bg-gradient-3">
                            <i class="fas fa-recycle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Riwayat Transaksi -->
        <div class="card">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 fw-bold">Riwayat Transaksi</h5>
            </div>
            <div class="card-body">
                <?php if ($nasabah && mysqli_num_rows($query_riwayat) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>ID Transaksi</th>
                                <th>Jenis Sampah</th>
                                <th>Berat</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($query_riwayat)): ?>
                            <tr>
                                <td><?php echo date('d/m/Y H:i', strtotime($row['tanggal_transaksi'])); ?></td>
                                <td><strong><?php echo $row['id_transaksi']; ?></strong></td>
                                <td><span class="badge bg-info"><?php echo $row['nama_sampah']; ?></span></td>
                                <td><?php echo number_format($row['berat'], 2); ?> Kg</td>
                                <td class="fw-bold text-success">Rp <?php echo number_format($row['total']); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada transaksi</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-white mt-4">
            <small>@Copyright by 23552011408_Arika Azhar_TIF 23 CNS B_WEB1</small>
        </div>
    </div>

    <!-- Modal Edit Profil -->
    <div class="modal fade" id="modalEditProfil" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="profil_update.php">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama" value="<?php echo $nama; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="<?php echo $email; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                            <input type="password" class="form-control" name="password" placeholder="Masukkan password baru">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>