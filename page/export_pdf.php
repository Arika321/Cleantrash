<?php
// export_pdf.php - Export Laporan Nasabah ke PDF (Versi Rapi & Profesional)
// @Copyright by 23552011408_Arika Azhar_TIF 23 CNS B_WEB1

session_start();
require_once('../system/koneksi.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require_once('../system/fpdf/fpdf.php');

class PDF extends FPDF {
    function Header() {
        // Logo (lingkaran dengan icon recycle)
        $this->SetFillColor(46, 204, 113);
        $this->Circle(20, 15, 8, 'F');
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 14);
        $this->Text(16, 18, 'R');
        
        // Company Name
        $this->SetTextColor(46, 204, 113);
        $this->SetFont('Arial', 'B', 20);
        $this->SetXY(35, 10);
        $this->Cell(0, 8, 'CLEANTRANS', 0, 1, 'L');
        
        // Subtitle
        $this->SetTextColor(100, 100, 100);
        $this->SetFont('Arial', '', 9);
        $this->SetX(35);
        $this->Cell(0, 5, 'Bank Sampah Digital - Sistem Pengelolaan Sampah Terintegrasi', 0, 1, 'L');
        
        // Contact info
        $this->SetFont('Arial', '', 8);
        $this->SetX(35);
        $this->Cell(0, 4, 'Jl. Bank Sampah No. 123, Bandung | Telp: 0812-3456-7890 | Email: info@cleantrans.com', 0, 1, 'L');
        
        // Line separator
        $this->SetLineWidth(0.8);
        $this->SetDrawColor(46, 204, 113);
        $this->Line(10, 32, 200, 32);
        
        $this->SetLineWidth(0.3);
        $this->SetDrawColor(200, 200, 200);
        $this->Line(10, 33, 200, 33);
        
        $this->Ln(8);
    }
    
    function Footer() {
        $this->SetY(-20);
        
        // Line
        $this->SetDrawColor(200, 200, 200);
        $this->SetLineWidth(0.3);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(2);
        
        // Copyright
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(52, 152, 219);
        $this->Cell(0, 5, '@Copyright by 23552011408_Arika Azhar_TIF 23 CNS B_WEB1', 0, 1, 'C');
        
        // Page number
        $this->SetTextColor(100, 100, 100);
        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 4, 'Halaman ' . $this->PageNo() . ' dari {nb} | Dicetak: ' . date('d-m-Y H:i') . ' WIB', 0, 0, 'C');
    }
    
    function Circle($x, $y, $r, $style='D') {
        $this->Ellipse($x, $y, $r, $r, $style);
    }
    
    function Ellipse($x, $y, $rx, $ry, $style='D') {
        if($style=='F')
            $op='f';
        elseif($style=='FD' || $style=='DF')
            $op='B';
        else
            $op='S';
        $lx=4/3*(M_SQRT2-1)*$rx;
        $ly=4/3*(M_SQRT2-1)*$ry;
        $k=$this->k;
        $h=$this->h;
        $this->_out(sprintf('%.2F %.2F m %.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x+$rx)*$k,($h-$y)*$k,
            ($x+$rx)*$k,($h-($y-$ly))*$k,
            ($x+$lx)*$k,($h-($y-$ry))*$k,
            $x*$k,($h-($y-$ry))*$k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x-$lx)*$k,($h-($y-$ry))*$k,
            ($x-$rx)*$k,($h-($y-$ly))*$k,
            ($x-$rx)*$k,($h-$y)*$k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x-$rx)*$k,($h-($y+$ly))*$k,
            ($x-$lx)*$k,($h-($y+$ry))*$k,
            $x*$k,($h-($y+$ry))*$k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c %s',
            ($x+$lx)*$k,($h-($y+$ry))*$k,
            ($x+$rx)*$k,($h-($y+$ly))*$k,
            ($x+$rx)*$k,($h-$y)*$k,
            $op));
    }
}

$pdf = new PDF('L', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();

// Title
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(44, 62, 80);
$pdf->Cell(0, 10, 'LAPORAN DATA NASABAH', 0, 1, 'C');

$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(127, 140, 141);
$pdf->Cell(0, 6, 'Periode: Semua Data s/d ' . date('d F Y'), 0, 1, 'C');
$pdf->Ln(5);

// Table Header
$pdf->SetFillColor(52, 152, 219);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 10);

$pdf->Cell(12, 10, 'No', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'ID Nasabah', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'Nama Nasabah', 1, 0, 'C', true);
$pdf->Cell(55, 10, 'Alamat', 1, 0, 'C', true);
$pdf->Cell(32, 10, 'No. Telepon', 1, 0, 'C', true);
$pdf->Cell(35, 10, 'Saldo (Rp)', 1, 0, 'C', true);
$pdf->Cell(25, 10, 'Status', 1, 1, 'C', true);

// Table Data
$query = "SELECT * FROM nasabah ORDER BY id_nasabah";
$result = mysqli_query($koneksi, $query);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

$no = 1;
$total_saldo = 0;
$fill = false;

while ($data = mysqli_fetch_array($result)) {
    if ($fill) {
        $pdf->SetFillColor(245, 245, 245);
    } else {
        $pdf->SetFillColor(255, 255, 255);
    }
    
    $pdf->Cell(12, 8, $no++, 1, 0, 'C', $fill);
    $pdf->Cell(30, 8, $data['id_nasabah'], 1, 0, 'C', $fill);
    $pdf->Cell(50, 8, substr($data['nama_nasabah'], 0, 25), 1, 0, 'L', $fill);
    $pdf->Cell(55, 8, substr($data['alamat'], 0, 30), 1, 0, 'L', $fill);
    $pdf->Cell(32, 8, $data['no_telp'], 1, 0, 'C', $fill);
    
    // Saldo with color
    $pdf->SetTextColor(46, 204, 113);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(35, 8, number_format($data['saldo'], 0, ',', '.'), 1, 0, 'R', $fill);
    
    // Status
    if ($data['status'] == 'aktif') {
        $pdf->SetTextColor(46, 204, 113);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(25, 8, 'AKTIF', 1, 1, 'C', $fill);
    } else {
        $pdf->SetTextColor(231, 76, 60);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(25, 8, 'NON-AKTIF', 1, 1, 'C', $fill);
    }
    
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', '', 9);
    
    $total_saldo += $data['saldo'];
    $fill = !$fill;
}

// Total row
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(46, 204, 113);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(179, 9, 'TOTAL NASABAH: ' . ($no-1) . ' | TOTAL SALDO', 1, 0, 'C', true);
$pdf->Cell(60, 9, 'Rp ' . number_format($total_saldo, 0, ',', '.'), 1, 1, 'R', true);

// Info section
$pdf->Ln(5);
$pdf->SetFont('Arial', 'I', 9);
$pdf->SetTextColor(100, 100, 100);
$pdf->MultiCell(0, 5, "Catatan: Laporan ini digenerate secara otomatis oleh sistem CleanTrans.\nDicetak oleh: " . $_SESSION['nama'] . " (" . $_SESSION['username'] . ")", 0, 'L');

$filename = 'Laporan_Nasabah_CleanTrans_' . date('dmY_His') . '.pdf';
$pdf->Output('D', $filename);
?>