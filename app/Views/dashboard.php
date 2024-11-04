<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <!-- Card untuk Total Siswa, Siswa Baru, Kelas Aktif -->
    
            <div class="col-md-4">
    <div class="card bg-secondary mb-3">
        <div class="card-header text-white">Total Siswa</div>
        <div class="card-body text-white">
            <h5 class="card-title text-white" id="totalSiswa">0</h5>
            <p class="card-text text-white">Jumlah siswa yang terdaftar.</p>
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="card bg-info mb-3">
        <div class="card-header text-white">Siswa Baru</div>
        <div class="card-body text-white">
            <h5 class="card-title text-white" id="siswaBaru"><?= $siswaBaru; ?></h5> <!-- Menampilkan siswa baru -->
            <p class="card-text text-white">Siswa baru terdaftar tahun ini.</p>
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="card bg-dark mb-3">
        <div class="card-header text-white">Kelas Aktif</div>
        <div class="card-body text-white">
            <h5 class="card-title text-white" id="totalKelas"><?= $totalKelas; ?></h5> <!-- Menampilkan total kelas -->
            <p class="card-text text-white">Jumlah kelas aktif saat ini.</p>
        </div>
    </div>
</div>

        </div>
        <?php if (session()->get('level') == 1) { ?>
        <!-- Dropdown filter bulan dan tahun -->
        <label for="bulan">Bulan:</label>
        <select id="bulan">
            <option value="all">Semua</option>
            <?php
                $bulanArray = [
                    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                    '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                    '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
                    '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                ];
                foreach ($bulanArray as $value => $namaBulan) {
                    echo "<option value=\"$value\">$namaBulan</option>";
                }
            ?>
        </select>

        <label for="tahun">Tahun:</label>
        <select id="tahun">
            <option value="all">Semua</option>
            <?php
                $tahunMulai = 2010;
                $tahunSekarang = date("Y");
                for ($tahun = $tahunMulai; $tahun <= $tahunSekarang; $tahun++) {
                    echo "<option value=\"$tahun\">$tahun</option>";
                }
            ?>
        </select>

        <!-- Canvas untuk Chart -->
        <canvas id="myChart" width="400" height="200"></canvas>
        <?php } ?>
    </div>
</div>

<!-- Script untuk animasi CountUp dan Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/countup.js/2.0.7/countUp.min.js"></script>

<script>
   const totalSiswa = <?= $totalAnak; ?>;

// Fungsi untuk animasi count-up menggunakan Anime.js
// Total Siswa
anime({
    targets: '#totalSiswa',
    innerHTML: [0, <?= $totalAnak; ?>],
    easing: 'easeOutExpo',
    duration: 2000,
    round: 1
});

// Siswa Baru
anime({
    targets: '#siswaBaru',
    innerHTML: [0, <?= $siswaBaru; ?>],
    easing: 'easeOutExpo',
    duration: 2000,
    round: 1
});

// Total Kelas
anime({
    targets: '#totalKelas',
    innerHTML: [0, <?= $totalKelas; ?>],
    easing: 'easeOutExpo',
    duration: 2000,
    round: 1
});




    // Data dari PHP untuk Chart
    const laporanKeuangan = <?= json_encode($laporan); ?>;

    // Fungsi untuk filter data berdasarkan bulan dan tahun
    function filterData(bulan, tahun) {
        return laporanKeuangan.filter(data => {
            const date = new Date(data.tanggal);
            const month = ('0' + (date.getMonth() + 1)).slice(-2);
            const year = date.getFullYear().toString();
            return (bulan === 'all' || bulan === month) && (tahun === 'all' || tahun === year);
        });
    }

    // Fungsi untuk memperbarui chart
    function updateChart() {
        const bulan = document.getElementById('bulan').value;
        const tahun = document.getElementById('tahun').value;
        const filteredData = filterData(bulan, tahun);

        const labels = filteredData.map(data => data.tanggal);
        const pendapatan = filteredData.map(data => data.total_pendapatan);
        const pengeluaran = filteredData.map(data => data.total_pengeluaran);

        // Hapus chart lama jika ada
        if (window.myChart && typeof window.myChart.destroy === 'function') {
            window.myChart.destroy();
        }

        // Inisialisasi chart baru
        const ctx = document.getElementById('myChart').getContext('2d');
        window.myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Pendapatan',
                        data: pendapatan,
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Pengeluaran',
                        data: pengeluaran,
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Event listener untuk dropdown
    document.getElementById('bulan').addEventListener('change', updateChart);
    document.getElementById('tahun').addEventListener('change', updateChart);

    // Inisialisasi chart pertama kali
    updateChart();
</script>

</body>
</html>
