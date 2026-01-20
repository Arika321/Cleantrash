<?php
// admin_data_setor.php - Transaksi Setor Sampah
// @Copyright by 23552011408_Arika Azhar_TIF 23 CNS B_WEB1

$query_setor = mysqli_query($koneksi, "
    SELECT t.*, n.nama_nasabah, n.id_nasabah as nin, j.nama_sampah 
    FROM transaksi t 
    JOIN nasabah n ON t.id_nasabah = n.id_nasabah 
    JOIN jenis_sampah j ON t.id_jenis = j.id_jenis 
    ORDER BY t.tanggal_transaksi DESC
");
?>

<div class="page-title">
    <h3 class="mb-0"><i class="fas fa-exchange-alt"></i> Transaksi Setor Sampah</h3>
</div>

<div class="table-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Daftar Transaksi Setor</h5>
        <a href="transaksi_tambah.php" class="btn btn-success">
            <i class="fas fa-plus"></i> Tambah
        </a>
    </div>

    <div class="table-responsive">
        <table id="tableSetor" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>NIN</th>
                    <th>Jenis Sampah</th>
                    <th>Berat</th>
                    <th>Harga</th>
                    <th>Total</th>
                    <th>NIA</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if(mysqli_num_rows($query_setor) > 0):
                    while($row = mysqli_fetch_assoc($query_setor)): 
                ?>
                <tr>
                    <td><?php echo $row['id_transaksi']; ?></td>
                    <td><?php echo date('d/m/Y', strtotime($row['tanggal_transaksi'])); ?></td>
                    <td><?php echo $row['nin']; ?></td>
                    <td><?php echo $row['nama_sampah']; ?></td>
                    <td><?php echo number_format($row['berat'], 2); ?> Kg</td>
                    <td>Rp <?php echo number_format($row['harga_per_kg']); ?></td>
                    <td><strong>Rp <?php echo number_format($row['total']); ?></strong></td>
                    <td><?php echo $row['nama_nasabah']; ?></td>
                    <td>
                        <a href="transaksi_edit.php?id=<?php echo $row['id_transaksi']; ?>" class="btn btn-sm btn-warning btn-action">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="transaksi_hapus.php?id=<?php echo $row['id_transaksi']; ?>" class="btn btn-sm btn-danger btn-action" onclick="return confirm('Yakin hapus?')">
                            <i class="fas fa-trash"></i> Hapus
                        </a>
                    </td>
                </tr>
                <?php 
                    endwhile;
                else:
                ?>
                <tr>
                    <td colspan="9" class="text-center">No data available in table</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        <a href="transaksi_tambah.php" class="btn btn-success">
            <i class="fas fa-plus"></i> Tambah
        </a>
        <button class="btn btn-success" onclick="window.open('export_excel_transaksi.php', '_blank')">
            <i class="fas fa-file-excel"></i> Excel
        </button>
        <button class="btn btn-primary" onclick="window.print()">
            <i class="fas fa-print"></i> Cetak
        </button>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#tableSetor').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
        },
        order: [[0, 'desc']]
    });
});
</script>