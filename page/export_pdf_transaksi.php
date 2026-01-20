<?php
session_start();
require_once('../system/koneksi.php');
require_once('../system/fpdf/fpdf.php');

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial','B',16);
        $this->Cell(0,10,'LAPORAN TRANSAKSI - CLEANTRANS',0,1,'C');
        $this->SetFont('Arial','',10);
        $this->Cell(0,5,'Tanggal: '.date('d-m-Y'),0,1,'C');
        $this->Ln(5);
    }
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'@Copyright by 23552011408_Arika Azhar_TIF 23 CNS B_WEB1',0,0,'C');
    }
}

$pdf = new PDF('L','mm','A4');
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(15,8,'No',1);
$pdf->Cell(40,8,'ID Transaksi',1);
$pdf->Cell(50,8,'Nasabah',1);
$pdf->Cell(40,8,'Sampah',1);
$pdf->Cell(25,8,'Berat (Kg)',1);
$pdf->Cell(35,8,'Total (Rp)',1);
$pdf->Cell(40,8,'Tanggal',1,1);

$query = mysqli_query($koneksi, "SELECT t.*, n.nama_nasabah, j.nama_sampah FROM transaksi t JOIN nasabah n ON t.id_nasabah=n.id_nasabah JOIN jenis_sampah j ON t.id_jenis=j.id_jenis ORDER BY t.tanggal_transaksi DESC");

$pdf->SetFont('Arial','',9);
$no = 1;
while($row = mysqli_fetch_assoc($query)) {
    $pdf->Cell(15,7,$no++,1);
    $pdf->Cell(40,7,$row['id_transaksi'],1);
    $pdf->Cell(50,7,substr($row['nama_nasabah'],0,25),1);
    $pdf->Cell(40,7,$row['nama_sampah'],1);
    $pdf->Cell(25,7,number_format($row['berat'],2),1,'R');
    $pdf->Cell(35,7,number_format($row['total']),1,'R');
    $pdf->Cell(40,7,date('d/m/Y',strtotime($row['tanggal_transaksi'])),1,1);
}

$pdf->Output('D','Laporan_Transaksi_'.date('dmY').'.pdf');
?>