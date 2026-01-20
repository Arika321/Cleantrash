<?php
// jenis_sampah.php - Data Jenis Sampah (READ)
session_start();
require_once('../system/koneksi.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$query = mysqli_query($koneksi, "SELECT * FROM jenis_sampah ORDER BY nama_sampah");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jenis Sampah - CleanTrans</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <style>
        body { background: #f8f9fa; }
        .sidebar { min-height: 100vh; background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%); padding: 0; position: fixed; width: 250px; }
        .sidebar-header { padding: 20px; background: rgba(0,0,0,0.2); color: white; text-align: center; }
        .sidebar-header h4 { margin: 0; color: #2ecc71; }
        .sidebar-menu { padding: 20px 0; }
        .sidebar-menu a { color: #ecf0f1; text-decoration: none; padding: 15px 25px; display: block; transition: all 0.3s; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: #2ecc71; color: white; border-left: 4px solid white; }
        .sidebar-menu a i { margin-right: 10px; width: 20px; }
        .main-content { margin-left: 250px; padding: 30px; }
        .card { border: none; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
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
            <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
            <a href="nasabah.php"><i class="fas fa-users"></i> Data Nasabah</a>
            <a href="transaksi.php"><i class="fas fa-exchange-alt"></i> Transaksi</a>
            <a href="jenis_sampah.php" class="active"><i class="fas fa-trash-alt"></i> Jenis Sampah</a>
            <a href="laporan.php"><i class="fas fa-chart-bar"></i> Laporan</a>
            <a href="profil.php"><i class="fas fa-user"></i> Profil</a>
            <a href="logout.php" onclick="return confirm('Yakin ingin logout?')"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <?php if(isset($_GET['pesan'])): ?>
        <div class="alert alert-<?php 
            echo ($_GET['pesan']=='tambah_sukses' || $_GET['pesan']=='edit_sukses' || $_GET['pesan']=='hapus_sukses') ? 'success' : 'danger'; 
        ?> alert-dismissible fade show">
            <?php
            switch($_GET['pesan']) {
                case 'tambah_sukses': echo '<i class="fas fa-check-circle"></i> Jenis sampah berhasil ditambahkan!'; break;
                case 'edit_sukses': echo '<i class="fas fa-check-circle"></i> Jenis sampah berhasil diupdate!'; break;
                case 'hapus_sukses': echo '<i class="fas fa-check-circle"></i> Jenis sampah berhasil dihapus!'; break;
                default: echo '<i class="fas fa-exclamation-circle"></i> Terjadi kesalahan!';
            }
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">Jenis Sampah</h2>
                <p class="text-muted">Kelola jenis sampah dan harga</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Jenis Sampah</h5>
                <a href="jenis_tambah.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Jenis Sampah
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tableJenis" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID</th>
                                <th>Nama Sampah</th>
                                <th>Harga/Satuan</th>
                                <th>Satuan</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            while($row = mysqli_fetch_assoc($query)): 
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><strong>#<?php echo $row['id_jenis']; ?></strong></td>
                                <td>
                                    <i class="fas fa-recycle text-success"></i> 
                                    <?php echo $row['nama_sampah']; ?>
                                </td>
                                <td class="fw-bold text-success">Rp <?php echo number_format($row['harga_per_kg']); ?></td>
                                <td><span class="badge bg-info"><?php echo strtoupper($row['satuan']); ?></span></td>
                                <td><?php echo substr($row['deskripsi'], 0, 40); ?>...</td>
                                <td>
                                    <a href="jenis_edit.php?id=<?php echo $row['id_jenis']; ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="jenis_hapus.php?id=<?php echo $row['id_jenis']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php include('components/footer.php'); ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#tableJenis').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                }
            });
        });
    </script>
</body>
</html>