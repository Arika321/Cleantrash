<?php
// export_excel.php - Export Laporan Nasabah ke Excel
// @Copyright by 23552011408_Arika Azhar_TIF 23 CNS B_WEB1

session_start();
require_once('../system/koneksi.php');

// Cek login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Set header untuk download Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Nasabah_CleanTrans_" . date('dmY_His') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

// Ambil data dari database
$query = "SELECT * FROM nasabah ORDER BY id_nasabah";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Query error: " . mysqli_error($koneksi));
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Nasabah CleanTrans</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th {
            background-color: #3498db;
            color: white;
            font-weight: bold;
            padding: 10px;
            border: 1px solid #000;
            text-align: center;
        }
        td {
            padding: 8px;
            border: 1px solid #000;
        }
        .header-title {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 5px;
            color: #2ecc71;
        }
        .header-subtitle {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
        }
        .header-date {
            font-size: 11px;
            text-align: center;
            margin-bottom: 20px;
        }
        .footer-copyright {
            font-size: 10px;
            text-align: center;
            margin-top: 20px;
            font-style: italic;
            color: #3498db;
        }
        .total-row {
            background-color: #2ecc71;
            color: white;
            font-weight: bold;
        }
        .info-section {
            font-size: 10px;
            margin-top: 15px;
            font-style: italic;
        }
        .status-aktif {
            background-color: #2ecc71;
            color: white;
            font-weight: bold;
            padding: 5px;
            text-align: center;
        }
        .status-nonaktif {
            background-color: #e74c3c;
            color: white;
            font-weight: bold;
            padding: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header-title">CLEANTRANS - BANK SAMPAH DIGITAL</div>
    <div class="header-subtitle">LAPORAN DATA NASABAH</div>
    <div class="header-date">Tanggal Cetak: <?php echo date('d-m-Y H:i:s'); ?></div>
    
    <!-- Tabel Data -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Nasabah</th>
                <th>Nama Nasabah</th>
                <th>Alamat</th>
                <th>No. Telepon</th>
                <th>Email</th>
                <th>Saldo (Rp)</th>
                <th>Tanggal Daftar</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $total_saldo = 0;
            $total_nasabah = 0;
            
            while ($data = mysqli_fetch_array($result)) {
                $total_saldo += $data['saldo'];
                $total_nasabah++;
            ?>
            <tr>
                <td style="text-align: center;"><?php echo $no++; ?></td>
                <td><?php echo $data['id_nasabah']; ?></td>
                <td><?php echo $data['nama_nasabah']; ?></td>
                <td><?php echo $data['alamat']; ?></td>
                <td style="text-align: center;"><?php echo $data['no_telp']; ?></td>
                <td><?php echo isset($data['email']) ? $data['email'] : '-'; ?></td>
                <td style="text-align: right;"><?php echo number_format($data['saldo'], 0, ',', '.'); ?></td>
                <td style="text-align: center;"><?php echo isset($data['tgl_daftar']) ? date('d-m-Y', strtotime($data['tgl_daftar'])) : '-'; ?></td>
                <td class="<?php echo ($data['status'] == 'aktif') ? 'status-aktif' : 'status-nonaktif'; ?>">
                    <?php echo strtoupper($data['status']); ?>
                </td>
            </tr>
            <?php } ?>
            
            <!-- Total Row -->
            <tr class="total-row">
                <td colspan="6" style="text-align: center; font-weight: bold;">
                    TOTAL NASABAH: <?php echo $total_nasabah; ?> | TOTAL SALDO
                </td>
                <td style="text-align: right; font-weight: bold;">
                    <?php echo number_format($total_saldo, 0, ',', '.'); ?>
                </td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>
    
    <!-- Info Section -->
    <div class="info-section">
        <p>Laporan ini digenerate secara otomatis oleh sistem CleanTrans</p>
        <p>Dicetak oleh: <?php echo $_SESSION['nama']; ?> (<?php echo $_SESSION['username']; ?>)</p>
        <p>Level: <?php echo strtoupper($_SESSION['level']); ?></p>
    </div>
    
    <!-- Footer -->
    <div class="footer-copyright">
        @Copyright by 23552011408_Arika Azhar_TIF 23 CNS B_WEB1
    </div>
</body>
</html>

<?php
mysqli_close($koneksi);
?>