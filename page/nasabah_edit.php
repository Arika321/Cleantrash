<?php
// nasabah_edit.php - Form Edit Nasabah (UPDATE)
session_start();
require_once('../system/koneksi.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$error = "";

if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $no_telp = mysqli_real_escape_string($koneksi, $_POST['no_telp']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $status = $_POST['status'];
    
    $query = "UPDATE nasabah SET 
              nama_nasabah='$nama', 
              alamat='$alamat', 
              no_telp='$no_telp', 
              email='$email', 
              status='$status' 
              WHERE id_nasabah='$id'";
    
    if (mysqli_query($koneksi, $query)) {
        header("Location: nasabah.php?pesan=edit_sukses");
        exit();
    } else {
        $error = "Gagal update data: " . mysqli_error($koneksi);
    }
}

$query_data = mysqli_query($koneksi, "SELECT * FROM nasabah WHERE id_nasabah='$id'");
$data = mysqli_fetch_assoc($query_data);

if (!$data) {
    header("Location: nasabah.php?pesan=data_tidak_ditemukan");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Nasabah - CleanTrans</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 50px 0; }
        .form-card { background: white; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); }
        .form-header { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: white; padding: 20px; border-radius: 15px 15px 0 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-card">
                    <div class="form-header">
                        <h4 class="mb-0"><i class="fas fa-edit"></i> Edit Data Nasabah</h4>
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
                                <label class="form-label fw-bold">ID Nasabah</label>
                                <input type="text" class="form-control bg-light" value="<?php echo $data['id_nasabah']; ?>" disabled>
                                <small class="text-muted">ID tidak dapat diubah</small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" name="nama" value="<?php echo $data['nama_nasabah']; ?>" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Alamat Lengkap <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    <textarea class="form-control" name="alamat" rows="3" required><?php echo $data['alamat']; ?></textarea>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">No. Telepon <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input type="tel" class="form-control" name="no_telp" value="<?php echo $data['no_telp']; ?>" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control" name="email" value="<?php echo $data['email']; ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                                    <select class="form-select" name="status" required>
                                        <option value="aktif" <?php echo $data['status']=='aktif'?'selected':''; ?>>Aktif</option>
                                        <option value="nonaktif" <?php echo $data['status']=='nonaktif'?'selected':''; ?>>Non-Aktif</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Saldo Saat Ini</label>
                                    <input type="text" class="form-control bg-light" value="Rp <?php echo number_format($data['saldo']); ?>" disabled>
                                    <small class="text-muted">Saldo tidak dapat diubah manual</small>
                                </div>
                            </div>
                            
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> <strong>Perhatian:</strong> Pastikan data yang diubah sudah benar sebelum menyimpan
                            </div>
                            
                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" name="update" class="btn btn-warning btn-lg">
                                    <i class="fas fa-save"></i> Update Data
                                </button>
                                <a href="nasabah.php" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="text-center mt-3 text-white">
                    <small>@Copyright by 23552011408_Arika Azhar_TIF 23 CNS B_WEB1</small>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>