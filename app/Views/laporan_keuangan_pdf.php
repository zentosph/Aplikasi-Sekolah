<?php

require_once(ROOTPATH . 'vendor/autoload.php'); // Pastikan jalur ini benar

// Periksa apakah data tersedia
if (empty($laporan) || !is_array($laporan)) {
    exit('Data tidak tersedia untuk membuat laporan keuangan.');
}

// Hapus buffer output untuk memastikan tidak ada output sebelum PDF
if (ob_get_length()) {
    ob_end_clean();
}

// Buat instance baru dari kelas TCPDF
$pdf = new TCPDF();

// Set informasi dokumen
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('ZentoSPH');
$pdf->SetTitle('Laporan Keuangan');
$pdf->SetSubject('Laporan Keuangan Periode ' . $startDate . ' - ' . $endDate);

// Set margin
$pdf->SetMargins(10, 20, 10);

// Tambah halaman baru
$pdf->AddPage();

// Set font untuk judul
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Laporan Keuangan', 0, 1, 'C');

// Tambah periode
$pdf->SetFont('helvetica', '', 12);
$pdf->Ln(5);
$pdf->Cell(0, 10, 'Periode: ' . $startDate . ' - ' . $endDate, 0, 1, 'L');

// Bagian Pendapatan
$pdf->Ln(5);
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Pendapatan', 0, 1, 'L');

// Buat header tabel untuk Pendapatan
$pdf->SetFont('helvetica', '', 12);
$pdf->SetFillColor(242, 242, 242);
$pdf->Cell(60, 10, 'Kategori', 1, 0, 'C', 1);
$pdf->Cell(40, 10, 'Kuantitas', 1, 0, 'C', 1);
$pdf->Cell(60, 10, 'Total Pendapatan', 1, 1, 'C', 1);

// Isi baris tabel Pendapatan
if (!empty($laporan['pendapatan'])) {
    foreach ($laporan['pendapatan'] as $row) {
        $pdf->Cell(60, 10, htmlspecialchars($row->kategori, ENT_QUOTES, 'UTF-8'), 1, 0, 'L');
        $pdf->Cell(40, 10, $row->kuantitas, 1, 0, 'C'); // Menampilkan kuantitas dari query
        $pdf->Cell(60, 10, 'Rp ' . number_format($row->total_pendapatan, 0, ',', '.'), 1, 1, 'R');
    }
} else {
    $pdf->Cell(160, 10, 'Tidak ada data pendapatan.', 1, 1, 'C');
}

// Bagian Pengeluaran
$pdf->Ln(5);
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Pengeluaran', 0, 1, 'L');

// Buat header tabel untuk Pengeluaran
$pdf->SetFont('helvetica', '', 12);
$pdf->SetFillColor(242, 242, 242);
$pdf->Cell(60, 10, 'Kategori', 1, 0, 'C', 1);
$pdf->Cell(40, 10, 'Kuantitas', 1, 0, 'C', 1);
$pdf->Cell(60, 10, 'Total Pengeluaran', 1, 1, 'C', 1);

// Isi baris tabel Pengeluaran
if (!empty($laporan['pengeluaran'])) {
    foreach ($laporan['pengeluaran'] as $row) {
        $pdf->Cell(60, 10, htmlspecialchars($row->kategori_pengeluaran, ENT_QUOTES, 'UTF-8'), 1, 0, 'L');
        $pdf->Cell(40, 10, $row->kuantitas, 1, 0, 'C'); // Menampilkan kuantitas dari query
        $pdf->Cell(60, 10, 'Rp ' . number_format($row->total_pengeluaran, 0, ',', '.'), 1, 1, 'R');
    }
} else {
    $pdf->Cell(160, 10, 'Tidak ada data pengeluaran.', 1, 1, 'C');
}

// Bagian Laba/Rugi
$pdf->Ln(5);
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Laba/Rugi', 0, 1, 'L');

// Hitung Laba/Rugi
$totalPendapatan = array_sum(array_column($laporan['pendapatan'], 'total_pendapatan'));
$totalPengeluaran = array_sum(array_column($laporan['pengeluaran'], 'total_pengeluaran'));
$labaRugi = $totalPendapatan - $totalPengeluaran;

// Tampilkan Laba/Rugi
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(60, 10, 'Total Pendapatan', 0, 0, 'L');
$pdf->Cell(60, 10, 'Rp ' . number_format($totalPendapatan, 0, ',', '.'), 0, 1, 'R');
$pdf->Cell(60, 10, 'Total Pengeluaran', 0, 0, 'L');
$pdf->Cell(60, 10, 'Rp ' . number_format($totalPengeluaran, 0, ',', '.'), 0, 1, 'R');
$pdf->Cell(60, 10, 'Laba/Rugi', 0, 0, 'L');
$pdf->Cell(60, 10, ($labaRugi < 0 ? 'Rp (' : 'Rp ') . number_format(abs($labaRugi), 0, ',', '.') . ($labaRugi < 0 ? ')' : ''), 0, 1, 'R');

// Hapus buffer output dan kirim PDF
ob_end_clean();
$pdf->Output('laporan_keuangan.pdf', 'I');

exit;
