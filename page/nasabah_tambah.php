<?php
// nasabah_tambah.php - Form Tambah Nasabah (CREATE)
session_start();
require_once('../system/koneksi.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$error = "";
$success = "";

if (isset($_POST['simpan'])) {
    // Generate ID Nasabah otomatis
    $query_last = mysqli_query($koneksi, "SELECT id_nasabah FROM nasabah ORDER BY id_nasabah DESC LIMIT 1");
    if (mysqli_num_rows($query_last) > 0) {
        $last_id = mysqli_fetch_assoc($query_last)['id_nasabah'];
        $num = (int)substr($last_id, 3) + 1;
        $id_nasabah = "NSB" . str_pad($num, 4, "0", STR_PAD_LEFT);
    } else {
        $id_nasabah = "NSB0001";
    }
    
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $no_telp = mysqli_real_escape_string($koneksi, $_POST['no_telp']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    
    $query = "INSERT INTO nasabah (id_nasabah, nama_nasabah, alamat, no_telp, email, saldo, tgl_daftar, status) 
              VALUES ('$id_nasabah', '$nama', '$alamat', '$no_telp', '$email', 0, NOW(), 'aktif')";
    
    if (mysqli_query($koneksi, $query)) {
        header("Location: nasabah.php?pesan=tambah_sukses");
        exit();
    } else {
        $error = "Gagal menambah data: " . mysqli_error($koneksi);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Nasabah - CleanTrans</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 50px 0; }
        .form-card { background: white; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); }
        .form-header { background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%); color: white; padding: 20px; border-radius: 15px 15px 0 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-card">
                    <div class="form-header">
                        <h4 class="mb-0"><i class="fas fa-user-plus"></i> Tambah Nasabah Baru</h4>
                    </div>
                    <div class="p-4">
                        <?php if($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" name="nama" placeholder="Masukkan nama lengkap" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Alamat Lengkap <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    <textarea class="form-control" name="alamat" rows="3" placeholder="Masukkan alamat lengkap" required></textarea>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">No. Telepon <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input type="tel" class="form-control" name="no_telp" placeholder="08xxxxxxxxxx" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control" name="email" placeholder="email@example.com">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> <strong>Info:</strong> ID Nasabah akan di-generate otomatis oleh sistem
                            </div>
                            
                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" name="simpan" class="btn btn-success btn-lg">
                                    <i class="fas fa-save"></i> Simpan Data
                                </button>
                                <a href="nasabah.php" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="text-center mt-3 text-white">
                    <small>@Copyright by 23552011408_Arika Azhar_TIF 23 CNS B_WEB1</small>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>