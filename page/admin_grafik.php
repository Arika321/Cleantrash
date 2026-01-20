<?php
// admin_grafik.php - Grafik Monitoring
// @Copyright by 23552011408_Arika Azhar_TIF 23 CNS B_WEB1

// Data untuk grafik - ambil jumlah nasabah per bulan atau per jenis sampah
$query_grafik = mysqli_query($koneksi, "
    SELECT j.nama_sampah, SUM(t.berat) as total_berat, COUNT(*) as jumlah
    FROM transaksi t
    JOIN jenis_sampah j ON t.id_jenis = j.id_jenis
    GROUP BY j.id_jenis
");

$labels = [];
$data_berat = [];

while($row = mysqli_fetch_assoc($query_grafik)) {
    $labels[] = $row['nama_sampah'];
    $data_berat[] = $row['total_berat'];
}
?>

<div class="page-title">
    <h3 class="mb-0"><i class="fas fa-chart-bar"></i> Grafik Monitoring</h3>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="table-card">
            <h5 class="mb-4">Data Jumlah Sampah Per Jenis</h5>
            <canvas id="chartSampah" height="100"></canvas>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="table-card">
            <h5 class="mb-4">Statistik</h5>
            <div class="mb-3">
                <small class="text-muted">Total Sampah Terkumpul</small>
                <h3 class="text-success"><?php echo number_format($total_sampah, 2); ?> Kg</h3>
            </div>
            <div class="mb-3">
                <small class="text-muted">Total Transaksi</small>
                <h3 class="text-primary"><?php echo number_format($total_transaksi); ?></h3>
            </div>
            <div class="mb-3">
                <small class="text-muted">Total Nasabah Aktif</small>
                <h3 class="text-warning"><?php echo number_format($total_nasabah); ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="table-card">
            <h5 class="mb-4">Detail Per Jenis Sampah</h5>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis Sampah</th>
                            <th>Total Berat (Kg)</th>
                            <th>Jumlah Transaksi</th>
                            <th>Harga/Kg</th>
                            <th>Total Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        mysqli_data_seek($query_grafik, 0);
                        $no = 1;
                        while($row = mysqli_fetch_assoc($query_grafik)):
                            $harga = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT harga_per_kg FROM jenis_sampah WHERE nama_sampah='{$row['nama_sampah']}'"))['harga_per_kg'];
                            $total_nilai = $row['total_berat'] * $harga;
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><strong><?php echo $row['nama_sampah']; ?></strong></td>
                            <td><?php echo number_format($row['total_berat'], 2); ?> Kg</td>
                            <td><?php echo $row['jumlah']; ?>x</td>
                            <td>Rp <?php echo number_format($harga); ?></td>
                            <td class="text-success fw-bold">Rp <?php echo number_format($total_nilai); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('chartSampah');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
            label: 'Total Berat (Kg)',
            data: <?php echo json_encode($data_berat); ?>,
            backgroundColor: [
                'rgba(46, 204, 113, 0.7)',
                'rgba(52, 152, 219, 0.7)',
                'rgba(155, 89, 182, 0.7)',
                'rgba(241, 196, 15, 0.7)',
                'rgba(231, 76, 60, 0.7)',
                'rgba(26, 188, 156, 0.7)'
            ],
            borderColor: [
                'rgba(46, 204, 113, 1)',
                'rgba(52, 152, 219, 1)',
                'rgba(155, 89, 182, 1)',
                'rgba(241, 196, 15, 1)',
                'rgba(231, 76, 60, 1)',
                'rgba(26, 188, 156, 1)'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            },
            title: {
                display: true,
                text: 'Grafik Sampah Per Jenis (Dalam Kg)',
                font: {
                    size: 16
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value + ' Kg';
                    }
                }
            }
        }
    }
});
</script>