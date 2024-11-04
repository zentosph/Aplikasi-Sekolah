<?php

require_once(ROOTPATH . 'vendor/autoload.php'); // Pastikan jalur ini benar

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// Periksa apakah data tersedia
if (empty($laporan) || !is_array($laporan)) {
    exit('Data tidak tersedia untuk membuat laporan keuangan.');
}

// Buat spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set informasi judul
$sheet->setCellValue('A1', 'Laporan Keuangan');
$sheet->setCellValue('A2', 'Periode: ' . $startDate . ' - ' . $endDate);

// Set styling untuk header
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet->getStyle('A2')->getFont()->setSize(12);
$sheet->getStyle('A4')->getFont()->setBold(true)->setSize(14);

// Set lebar kolom untuk rapi
$sheet->getColumnDimension('A')->setWidth(30); // Kategori
$sheet->getColumnDimension('B')->setWidth(15); // Kuantitas
$sheet->getColumnDimension('C')->setWidth(20); // Total Pendapatan / Pengeluaran

// Pendapatan Section
$sheet->setCellValue('A4', 'Pendapatan');
$sheet->setCellValue('A5', 'Kategori');
$sheet->setCellValue('B5', 'Kuantitas');
$sheet->setCellValue('C5', 'Total Pendapatan');

// Isi tabel Pendapatan
if (!empty($laporan['pendapatan'])) {
    $row = 6;
    foreach ($laporan['pendapatan'] as $item) {
        $sheet->setCellValue('A' . $row, htmlspecialchars($item->kategori, ENT_QUOTES, 'UTF-8'));
        $sheet->setCellValue('B' . $row, $item->kuantitas); // Menampilkan kuantitas
        $sheet->setCellValue('C' . $row, $item->total_pendapatan);
        $row++;
    }
} else {
    $sheet->setCellValue('A6', 'Tidak ada data pendapatan.');
}

// Atur border dan alignment untuk tabel Pendapatan
$sheet->getStyle('A5:C' . ($row - 1))
      ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
$sheet->getStyle('A5:C' . ($row - 1))
      ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Pengeluaran Section
$row += 2; // Tambah spasi antar bagian
$sheet->setCellValue('A' . $row, 'Pengeluaran');
$sheet->setCellValue('A' . ($row + 1), 'Kategori');
$sheet->setCellValue('B' . ($row + 1), 'Kuantitas');
$sheet->setCellValue('C' . ($row + 1), 'Total Pengeluaran');

// Isi tabel Pengeluaran
if (!empty($laporan['pengeluaran'])) {
    $row += 2; // Menambahkan baris untuk header pengeluaran
    foreach ($laporan['pengeluaran'] as $item) {
        $sheet->setCellValue('A' . $row, htmlspecialchars($item->kategori_pengeluaran, ENT_QUOTES, 'UTF-8'));
        $sheet->setCellValue('B' . $row, $item->kuantitas); // Menampilkan kuantitas
        $sheet->setCellValue('C' . $row, $item->total_pengeluaran);
        $row++;
    }
} else {
    $sheet->setCellValue('A' . ($row + 1), 'Tidak ada data pengeluaran.');
}

// Atur border dan alignment untuk tabel Pengeluaran
$sheet->getStyle('A' . ($row - count($laporan['pengeluaran'])) . ':C' . ($row - 1))
      ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
$sheet->getStyle('A' . ($row - count($laporan['pengeluaran'])) . ':C' . ($row - 1))
      ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Laba/Rugi Section

// Hitung total pendapatan dan total pengeluaran
$totalPendapatan = array_sum(array_column($laporan['pendapatan'], 'total_pendapatan'));
$totalPengeluaran = array_sum(array_column($laporan['pengeluaran'], 'total_pengeluaran'));
$labaRugi = $totalPendapatan - $totalPengeluaran;

// Mengisi bagian Laba/Rugi
$row += 2; // Tambah spasi antar bagian
$sheet->setCellValue('A' . $row, 'Laba/Rugi');
$sheet->setCellValue('A' . ($row + 1), 'Total Pendapatan');
$sheet->setCellValue('B' . ($row + 1), 'Rp ' . number_format($totalPendapatan, 0, ',', '.'));
$sheet->setCellValue('A' . ($row + 2), 'Total Pengeluaran');
$sheet->setCellValue('B' . ($row + 2), 'Rp ' . number_format($totalPengeluaran, 0, ',', '.'));
$sheet->setCellValue('A' . ($row + 3), 'Laba/Rugi');
$sheet->setCellValue('B' . ($row + 3), ($labaRugi < 0 ? 'Rp (' : 'Rp ') . number_format(abs($labaRugi), 0, ',', '.') . ($labaRugi < 0 ? ')' : ''));

// Set header untuk download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

// Menghasilkan nama file berdasarkan startDate dan endDate
$filename = 'laporan_keuangan_' . date('Y-m-d', strtotime($startDate)) . '_to_' . date('Y-m-d', strtotime($endDate)) . '.xlsx';
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

// Buat file Excel
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
