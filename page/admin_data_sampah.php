<?php
// admin_data_sampah.php - Halaman Data Sampah
// @Copyright by 23552011408_Arika Azhar_TIF 23 CNS B_WEB1

$query_sampah = mysqli_query($koneksi, "SELECT * FROM jenis_sampah ORDER BY id_jenis");
?>

<div class="page-title">
    <h3 class="mb-0"><i class="fas fa-trash-alt"></i> Data Sampah</h3>
</div>

<div class="table-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Daftar Jenis Sampah</h5>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fas fa-plus"></i> Tambah
        </button>
    </div>

    <div class="table-responsive">
        <table id="tableSampah" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis Sampah</th>
                    <th>Satuan</th>
                    <th>Harga</th>
                    <th>Gambar</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while($row = mysqli_fetch_assoc($query_sampah)): 
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><strong><?php echo $row['nama_sampah']; ?></strong></td>
                    <td><?php echo strtoupper($row['satuan']); ?></td>
                    <td><span class="badge bg-success">Rp <?php echo number_format($row['harga_per_kg']); ?></span></td>
                    <td>
                        <?php if(isset($row['gambar'])): ?>
                        <img src="../uploads/<?php echo $row['gambar']; ?>" width="50" class="rounded">
                        <?php else: ?>
                        <i class="fas fa-image fa-2x text-muted"></i>
                        <?php endif; ?>
                    </td>
                    <td><?php echo substr($row['deskripsi'], 0, 40); ?>...</td>
                    <td>
                        <button class="btn btn-sm btn-warning btn-action">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-danger btn-action" onclick="return confirm('Yakin hapus?')">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        <button class="btn btn-success">
            <i class="fas fa-plus"></i> Tambah
        </button>
        <button class="btn btn-danger" onclick="window.open('export_pdf.php', '_blank')">
            <i class="fas fa-file-pdf"></i> Excel
        </button>
        <button class="btn btn-primary" onclick="window.print()">
            <i class="fas fa-print"></i> Cetak
        </button>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#tableSampah').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
        }
    });
});
</script>