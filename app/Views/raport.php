<?php

require_once(ROOTPATH . 'vendor/autoload.php'); // Pastikan path sudah benar

// Periksa apakah data tersedia
if (empty($mapel) || !is_array($mapel)) {
    exit('Data tidak tersedia untuk membuat laporan.');
}

// Bersihkan buffer output untuk memastikan tidak ada output sebelum PDF
if (ob_get_length()) {
    ob_end_clean();
}

// Buat instance baru dari kelas TCPDF
$pdf = new TCPDF();

// Atur informasi dokumen
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('ZentoSPH');
$pdf->SetTitle('Rapor Siswa');
$pdf->SetSubject('Rapor Akademik');

// Atur margin
$pdf->SetMargins(10, 20, 10); // Sesuaikan margin sesuai kebutuhan

// Tambah halaman baru
$pdf->AddPage();

// Atur latar belakang menjadi lebih kuning
$pdf->SetFillColor(255, 255, 150); // RGB untuk kuning yang lebih cerah
$pdf->Rect(0, 0, $pdf->getPageWidth(), $pdf->getPageHeight(), 'F'); // Gambar kotak kuning di seluruh halaman

// Atur font untuk judul
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Rapor Siswa', 0, 1, 'C');

// Atur font untuk detail siswa
$pdf->SetFont('helvetica', '', 12);
$pdf->Ln(5); // Line break
$pdf->Cell(0, 10, 'Nama: ' . htmlspecialchars($mapel[0]->nama_anak, ENT_QUOTES, 'UTF-8'), 0, 1, 'L');
$pdf->Ln(-3);
$pdf->Cell(0, 10, 'Kelas: ' . htmlspecialchars($mapel[0]->nama_kelas, ENT_QUOTES, 'UTF-8'), 0, 1, 'L');
$pdf->Ln(-3);
$pdf->Cell(0, 10, 'Tahun Ajaran: ' . htmlspecialchars($mapel[0]->tahun_ajaran, ENT_QUOTES, 'UTF-8'), 0, 1, 'L');
$pdf->Ln(-2); // Line break

// Hitung rata-rata untuk setiap mata pelajaran
$subjectScores = [];
$subjectsWithLowGrades = []; // Untuk menyimpan mata pelajaran dengan predikat C dan D
foreach ($mapel as $item) {
    $subject = $item->mapel;
    if (!isset($subjectScores[$subject])) {
        $subjectScores[$subject] = ['total' => 0, 'count' => 0];
    }
    $subjectScores[$subject]['total'] += $item->nilai;
    $subjectScores[$subject]['count']++;

    // Cek predikat
    $average = $item->nilai; // Anggap nilai item adalah rata-rata untuk setiap item
    $letterGrade = getLetterGrade($average);
    if ($letterGrade === 'C' || $letterGrade === 'D') {
        $subjectsWithLowGrades[] = $subject;
    }
}

// Menggambar tabel menggunakan Cell() untuk kontrol yang lebih baik
$pdf->SetFont('helvetica', '', 12);
$pdf->Ln(5);
$pdf->Cell(0, 10, 'A. Nilai Akademik', 0, 1, 'L');
$pdf->Ln(-2);

// Hitung lebar kolom
$widths = [
    'no' => 10,
    'mapel' => max($pdf->GetStringWidth('Mata Pelajaran') + 10, 90),
    'average' => max($pdf->GetStringWidth('Rata-rata Nilai') + 10, 50),
    'grade' => max($pdf->GetStringWidth('Predikat') + 10, 40),
];

// Header Tabel
$pdf->SetFillColor(255, 255, 153); // Latar belakang header
$pdf->Cell($widths['no'], 10, 'No', 1, 0, 'C', 1);
$pdf->Cell($widths['mapel'], 10, 'Mata Pelajaran', 1, 0, 'C', 1);
$pdf->Cell($widths['average'], 10, 'Rata-rata Nilai', 1, 0, 'C', 1);
$pdf->Cell($widths['grade'], 10, 'Predikat', 1, 1, 'C', 1); // Tambahkan kolom untuk nilai huruf

// Tambahkan rata-rata mata pelajaran ke dalam tabel
$no = 1;
foreach ($subjectScores as $subject => $data) {
    $average = $data['total'] / $data['count'];
    $letterGrade = getLetterGrade($average); // Dapatkan nilai huruf
    $pdf->Cell($widths['no'], 10, $no++, 1, 0, 'C'); // No di center
    $pdf->Cell($widths['mapel'], 10, htmlspecialchars($subject, ENT_QUOTES, 'UTF-8'), 1, 0); // Mata Pelajaran di center
    $pdf->Cell($widths['average'], 10, number_format($average, 2), 1, 0, 'C'); // Rata-rata Nilai di center
    $pdf->Cell($widths['grade'], 10, $letterGrade, 1, 1, 'C'); // Predikat di center
}

// Fungsi untuk menentukan nilai huruf
function getLetterGrade($average) {
    if ($average >= 92) {
        return 'A';
    } elseif ($average >= 85) {
        return 'B';
    } elseif ($average >= 80) {
        return 'C';
    } else {
        return 'D';
    }
}

$pdf->Ln(5); // Line break
$pdf->Cell(0, 10, 'B. Catatan Akademik', 0, 1, 'L');
$pdf->Ln(2); // Line break

// Menentukan isi catatan akademik
if (count($subjectsWithLowGrades) > 0) {
    $subjectsString = implode(', ', $subjectsWithLowGrades);
    $note = "Ananda perlu meningkatkan prestasi pada mata pelajaran: " . $subjectsString;
} else {
    $note = "Ananda telah menunjukkan prestasi baik, teruskan usaha yang baik!";
}

// Hitung dimensi
$pageWidth = $pdf->getPageWidth();
$margin = 20; // Total margin (kiri + kanan)
$cellWidth = $pageWidth - $margin;
$fontSize = 12; // Sesuaikan dengan ukuran font yang Anda gunakan
$pdf->SetFont('helvetica', '', $fontSize);
$cellHeight = $pdf->getStringHeight($cellWidth, $note) + 10; // Tambahkan padding

// Posisi awal
$x = $pdf->GetX();
$y = $pdf->GetY();

// Gambar background
$pdf->SetFillColor(255, 255, 153); // Warna kuning untuk background
$pdf->Rect($x, $y, $cellWidth, $cellHeight, 'F');

// Set ketebalan garis dan gambar border
$pdf->SetLineWidth(0.3); // Atur ketebalan garis (0.5 mm, bisa disesuaikan)
$pdf->SetDrawColor(0, 0, 0); // Warna hitam untuk border
$pdf->Rect($x, $y, $cellWidth, $cellHeight, 'D');

// Tulis teks
$pdf->SetXY($x, $y);
$pdf->MultiCell($cellWidth, $cellHeight, $note, 0, 'C', 0);

// Reset posisi dan ketebalan garis
$pdf->SetXY($x, $y + $cellHeight);
$pdf->SetLineWidth(0.1); // Kembalikan ke ketebalan default jika diperlukan

// Bersihkan output buffer dan kirim PDF
ob_end_clean();
$pdf->Output('rapor_siswa.pdf', 'I');

// Selesai
exit;
