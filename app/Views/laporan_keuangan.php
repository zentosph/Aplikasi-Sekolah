<style>
    .financial-report {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        font-size: 1em;
        text-align: left;
    }

    .financial-report th, .financial-report td {
        border: 1px solid #ddd;
        padding: 8px;
    }

    .financial-report th {
        background-color: #f2f2f2;
        color: #333;
    }

    .financial-report tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .financial-report tr:hover {
        background-color: #f1f1f1;
    }

    h2, h3 {
        color: #444;
        margin-top: 20px;
    }

    p {
        font-size: 1.1em;
    }
</style>

<h2>Laporan Keuangan</h2>
<p>Periode: <?php echo $startDate . ' - ' . $endDate; ?></p>

<h3>Pendapatan</h3>
<table class="financial-report">
    <thead>
        <tr>
            <th>Kategori</th>
            <th>Kuantitas</th>
            <th>Total Pendapatan</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if (!empty($laporan['pendapatan'])): 
            foreach ($laporan['pendapatan'] as $row): 
        ?>
            <tr>
                <td><?php echo htmlspecialchars($row->kategori, ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo $row->kuantitas; ?></td> <!-- Menampilkan kuantitas dari query -->
                <td><?php echo 'Rp ' . number_format($row->total_pendapatan, 0, ',', '.'); ?></td>
            </tr>
        <?php 
            endforeach;
        else: 
        ?>
        <tr>
            <td colspan="3">Tidak ada data pendapatan.</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<h3>Pengeluaran</h3>
<table class="financial-report">
    <thead>
        <tr>
            <th>Kategori</th>
            <th>Kuantitas</th>
            <th>Total Pengeluaran</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if (!empty($laporan['pengeluaran'])): 
            foreach ($laporan['pengeluaran'] as $row): 
        ?>
            <tr>
                <td><?php echo htmlspecialchars($row->kategori_pengeluaran, ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo $row->kuantitas; ?></td> <!-- Menampilkan kuantitas dari query -->
                <td><?php echo 'Rp ' . number_format($row->total_pengeluaran, 0, ',', '.'); ?></td>
            </tr>
        <?php 
            endforeach;
        else: 
        ?>
        <tr>
            <td colspan="3">Tidak ada data pengeluaran.</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>


<h3>Laba/Rugi</h3>
<?php
// Menghitung total pendapatan dan total pengeluaran
$totalPendapatan = array_sum(array_column($laporan['pendapatan'], 'total_pendapatan'));
$totalPengeluaran = array_sum(array_column($laporan['pengeluaran'], 'total_pengeluaran'));
$labaRugi = $totalPendapatan - $totalPengeluaran;
?>
<p>Total Pendapatan: <?php echo 'Rp ' . number_format($totalPendapatan, 0, ',', '.'); ?></p>
<p>Total Pengeluaran: <?php echo 'Rp ' . number_format($totalPengeluaran, 0, ',', '.'); ?></p>
<p>Laba/Rugi: <?php echo ($labaRugi < 0 ? 'Rp (' : 'Rp ') . number_format(abs($labaRugi), 0, ',', '.') . ($labaRugi < 0 ? ')' : ''); ?></p>
