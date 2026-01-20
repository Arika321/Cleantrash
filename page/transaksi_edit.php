<?php
// transaksi_edit.php - Form Edit Transaksi (UPDATE)
session_start();
require_once('../system/koneksi.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$error = "";

if (isset($_POST['update'])) {
    $id_jenis = mysqli_real_escape_string($koneksi, $_POST['id_jenis']);
    $berat = floatval($_POST['berat']);
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);
    
    // Ambil data transaksi lama untuk update saldo
    $query_old = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE id_transaksi='$id'");
    $old_data = mysqli_fetch_assoc($query_old);
    $old_total = $old_data['total'];
    $id_nasabah = $old_data['id_nasabah'];
    
    // Ambil harga baru
    $query_harga = mysqli_query($koneksi, "SELECT harga_per_kg FROM jenis_sampah WHERE id_jenis='$id_jenis'");
    $harga_per_kg = mysqli_fetch_assoc($query_harga)['harga_per_kg'];
    
    // Hitung total baru
    $new_total = $berat * $harga_per_kg;
    $selisih = $new_total - $old_total;
    
    // Update transaksi
    $query = "UPDATE transaksi SET 
              id_jenis='$id_jenis', 
              berat='$berat', 
              harga_per_kg='$harga_per_kg', 
              total='$new_total', 
              keterangan='$keterangan' 
              WHERE id_transaksi='$id'";
    
    if (mysqli_query($koneksi, $query)) {
        // Update saldo nasabah
        mysqli_query($koneksi, "UPDATE nasabah SET saldo = saldo + $selisih WHERE id_nasabah='$id_nasabah'");
        
        header("Location: transaksi.php?pesan=edit_sukses");
        exit();
    } else {
        $error = "Gagal update transaksi: " . mysqli_error($koneksi);
    }
}

// Ambil data transaksi
$query_data = mysqli_query($koneksi, "
    SELECT t.*, n.nama_nasabah, j.nama_sampah 
    FROM transaksi t 
    JOIN nasabah n ON t.id_nasabah = n.id_nasabah 
    JOIN jenis_sampah j ON t.id_jenis = j.id_jenis 
    WHERE t.id_transaksi='$id'
");
$data = mysqli_fetch_assoc($query_data);

// Ambil jenis sampah
$query_jenis = mysqli_query($koneksi, "SELECT * FROM jenis_sampah ORDER BY nama_sampah");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaksi - CleanTrans</title>
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
                        <h4 class="mb-0"><i class="fas fa-edit"></i> Edit Transaksi</h4>
                    </div>
                    <div class="p-4">
                        <?php if($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">ID Transaksi</label>
                                    <input type="text" class="form-control bg-light" value="<?php echo $data['id_transaksi']; ?>" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Tanggal</label>
                                    <input type="text" class="form-control bg-light" value="<?php echo date('d/m/Y H:i', strtotime($data['tanggal_transaksi'])); ?>" disabled>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nasabah</label>
                                <input type="text" class="form-control bg-light" value="<?php echo $data['nama_nasabah']; ?>" disabled>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Jenis Sampah <span class="text-danger">*</span></label>
                                <select class="form-select" name="id_jenis" id="jenisSampah" required onchange="hitungTotal()">
                                    <?php while($j = mysqli_fetch_assoc($query_jenis)): ?>
                                    <option value="<?php echo $j['id_jenis']; ?>" 
                                            data-harga="<?php echo $j['harga_per_kg']; ?>"
                                            <?php echo ($j['id_jenis']==$data['id_jenis'])?'selected':''; ?>>
                                        <?php echo $j['nama_sampah']; ?> - Rp <?php echo number_format($j['harga_per_kg']); ?>/Kg
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Berat (Kg)</label>
                                    <input type="number" class="form-control" name="berat" id="berat" step="0.01" value="<?php echo $data['berat']; ?>" required onkeyup="hitungTotal()">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Harga/Kg</label>
                                    <input type="text" class="form-control bg-light" id="hargaPerKg" value="Rp <?php echo number_format($data['harga_per_kg']); ?>" readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Total</label>
                                    <input type="text" class="form-control bg-success text-white fw-bold" id="total" value="Rp <?php echo number_format($data['total']); ?>" readonly>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Keterangan</label>
                                <textarea class="form-control" name="keterangan" rows="2"><?php echo $data['keterangan']; ?></textarea>
                            </div>
                            
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> <strong>Perhatian:</strong> Saldo nasabah akan disesuaikan otomatis
                            </div>
                            
                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" name="update" class="btn btn-warning btn-lg">
                                    <i class="fas fa-save"></i> Update
                                </button>
                                <a href="transaksi.php" class="btn btn-secondary btn-lg">
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
    <script>
        function hitungTotal() {
            const jenis = document.getElementById('jenisSampah');
            const berat = parseFloat(document.getElementById('berat').value) || 0;
            const harga = parseFloat(jenis.options[jenis.selectedIndex].getAttribute('data-harga')) || 0;
            const total = berat * harga;
            
            document.getElementById('hargaPerKg').value = 'Rp ' + harga.toLocaleString('id-ID');
            document.getElementById('total').value = 'Rp ' + total.toLocaleString('id-ID');
        }
    </script>
</body>
</html>