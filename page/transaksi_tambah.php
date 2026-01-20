<?php
// transaksi_tambah.php - Form Tambah Transaksi (CREATE)
session_start();
require_once('../system/koneksi.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$error = "";

if (isset($_POST['simpan'])) {
    // Generate ID Transaksi
    $tanggal = date('Ymd');
    $query_last = mysqli_query($koneksi, "SELECT id_transaksi FROM transaksi WHERE id_transaksi LIKE 'TRX$tanggal%' ORDER BY id_transaksi DESC LIMIT 1");
    
    if (mysqli_num_rows($query_last) > 0) {
        $last_id = mysqli_fetch_assoc($query_last)['id_transaksi'];
        $num = (int)substr($last_id, -4) + 1;
        $id_transaksi = "TRX" . $tanggal . str_pad($num, 4, "0", STR_PAD_LEFT);
    } else {
        $id_transaksi = "TRX" . $tanggal . "0001";
    }
    
    $id_nasabah = mysqli_real_escape_string($koneksi, $_POST['id_nasabah']);
    $id_jenis = mysqli_real_escape_string($koneksi, $_POST['id_jenis']);
    $berat = floatval($_POST['berat']);
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);
    
    // Ambil harga per kg dari tabel jenis_sampah
    $query_harga = mysqli_query($koneksi, "SELECT harga_per_kg FROM jenis_sampah WHERE id_jenis='$id_jenis'");
    $harga_per_kg = mysqli_fetch_assoc($query_harga)['harga_per_kg'];
    
    // Hitung total
    $total = $berat * $harga_per_kg;
    
    // Insert transaksi
    $query = "INSERT INTO transaksi (id_transaksi, id_nasabah, id_jenis, tanggal_transaksi, berat, harga_per_kg, total, keterangan, created_by) 
              VALUES ('$id_transaksi', '$id_nasabah', '$id_jenis', NOW(), '$berat', '$harga_per_kg', '$total', '$keterangan', '{$_SESSION['username']}')";
    
    if (mysqli_query($koneksi, $query)) {
        // Update saldo nasabah (akan otomatis via trigger, tapi kita bisa manual juga)
        mysqli_query($koneksi, "UPDATE nasabah SET saldo = saldo + $total WHERE id_nasabah='$id_nasabah'");
        
        header("Location: transaksi.php?pesan=tambah_sukses");
        exit();
    } else {
        $error = "Gagal menambah transaksi: " . mysqli_error($koneksi);
    }
}

// Ambil data nasabah aktif
$query_nasabah = mysqli_query($koneksi, "SELECT * FROM nasabah WHERE status='aktif' ORDER BY nama_nasabah");

// Ambil data jenis sampah
$query_jenis = mysqli_query($koneksi, "SELECT * FROM jenis_sampah ORDER BY nama_sampah");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Transaksi - CleanTrans</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 50px 0; }
        .form-card { background: white; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); }
        .form-header { background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; padding: 20px; border-radius: 15px 15px 0 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-card">
                    <div class="form-header">
                        <h4 class="mb-0"><i class="fas fa-plus-circle"></i> Tambah Transaksi Baru</h4>
                    </div>
                    <div class="p-4">
                        <?php if($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>
                        
                        <form method="POST" id="formTransaksi">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nasabah <span class="text-danger">*</span></label>
                                <select class="form-select" name="id_nasabah" required>
                                    <option value="">-- Pilih Nasabah --</option>
                                    <?php while($n = mysqli_fetch_assoc($query_nasabah)): ?>
                                    <option value="<?php echo $n['id_nasabah']; ?>">
                                        <?php echo $n['id_nasabah']; ?> - <?php echo $n['nama_nasabah']; ?> (Saldo: Rp <?php echo number_format($n['saldo']); ?>)
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Jenis Sampah <span class="text-danger">*</span></label>
                                <select class="form-select" name="id_jenis" id="jenisSampah" required onchange="hitungTotal()">
                                    <option value="">-- Pilih Jenis Sampah --</option>
                                    <?php while($j = mysqli_fetch_assoc($query_jenis)): ?>
                                    <option value="<?php echo $j['id_jenis']; ?>" data-harga="<?php echo $j['harga_per_kg']; ?>" data-satuan="<?php echo $j['satuan']; ?>">
                                        <?php echo $j['nama_sampah']; ?> - Rp <?php echo number_format($j['harga_per_kg']); ?>/<?php echo $j['satuan']; ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Berat <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="berat" id="berat" step="0.01" min="0.01" placeholder="0.00" required onkeyup="hitungTotal()">
                                        <span class="input-group-text" id="satuan">Kg</span>
                                    </div>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Harga/Satuan</label>
                                    <input type="text" class="form-control bg-light" id="hargaPerKg" placeholder="Rp 0" readonly>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Total</label>
                                    <input type="text" class="form-control bg-success text-white fw-bold" id="total" placeholder="Rp 0" readonly>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Keterangan</label>
                                <textarea class="form-control" name="keterangan" rows="2" placeholder="Keterangan tambahan (opsional)"></textarea>
                            </div>
                            
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> <strong>Info:</strong> Saldo nasabah akan otomatis bertambah sesuai total transaksi
                            </div>
                            
                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" name="simpan" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i> Simpan Transaksi
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
            const jenisSampah = document.getElementById('jenisSampah');
            const berat = parseFloat(document.getElementById('berat').value) || 0;
            
            if (jenisSampah.selectedIndex > 0) {
                const option = jenisSampah.options[jenisSampah.selectedIndex];
                const harga = parseFloat(option.getAttribute('data-harga')) || 0;
                const satuan = option.getAttribute('data-satuan');
                
                const total = berat * harga;
                
                document.getElementById('satuan').textContent = satuan;
                document.getElementById('hargaPerKg').value = 'Rp ' + harga.toLocaleString('id-ID');
                document.getElementById('total').value = 'Rp ' + total.toLocaleString('id-ID');
            }
        }
    </script>
</body>
</html>