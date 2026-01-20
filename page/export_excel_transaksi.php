<?php
session_start();
require_once('../system/koneksi.php');

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Transaksi_".date('dmY').".xls");

$query = mysqli_query($koneksi, "SELECT t.*, n.nama_nasabah, j.nama_sampah FROM transaksi t JOIN nasabah n ON t.id_nasabah=n.id_nasabah JOIN jenis_sampah j ON t.id_jenis=j.id_jenis ORDER BY t.tanggal_transaksi DESC");
?>
<table border="1">
    <tr><th colspan="8" style="text-align:center;font-size:16px;"><b>LAPORAN TRANSAKSI - CLEANTRANS</b></th></tr>
    <tr><th colspan="8" style="text-align:center;">Tanggal: <?php echo date('d-m-Y'); ?></th></tr>
    <tr></tr>
    <tr style="background-color:#3498db;color:white;font-weight:bold;">
        <th>No</th><th>ID Transaksi</th><th>Nasabah</th><th>Jenis Sampah</th><th>Berat (Kg)</th><th>Harga/Kg</th><th>Total (Rp)</th><th>Tanggal</th>
    </tr>
    <?php 
    $no = 1;
    $total_all = 0;
    while($row = mysqli_fetch_assoc($query)): 
        $total_all += $row['total'];
    ?>
    <tr>
        <td><?php echo $no++; ?></td>
        <td><?php echo $row['id_transaksi']; ?></td>
        <td><?php echo $row['nama_nasabah']; ?></td>
        <td><?php echo $row['nama_sampah']; ?></td>
        <td style="text-align:right;"><?php echo number_format($row['berat'],2); ?></td>
        <td style="text-align:right;"><?php echo number_format($row['harga_per_kg']); ?></td>
        <td style="text-align:right;"><?php echo number_format($row['total']); ?></td>
        <td><?php echo date('d/m/Y H:i',strtotime($row['tanggal_transaksi'])); ?></td>
    </tr>
    <?php endwhile; ?>
    <tr style="background-color:#f0f0f0;font-weight:bold;">
        <td colspan="6" style="text-align:center;">TOTAL</td>
        <td style="text-align:right;"><?php echo number_format($total_all); ?></td>
        <td></td>
    </tr>
</table>
<br><br>
<div style="text-align:center;font-size:10px;">@Copyright by 23552011408_Arika Azhar_TIF 23 CNS B_WEB1</div>