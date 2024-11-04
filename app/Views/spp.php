<style>
    .tdcoy > td {
        color: black;
    }
</style>
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">



                    <h4>Hi, welcome back!</h4>
                    <span class="ml-1">Datatable</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Datatable</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Basic Datatable</h4>
                    </div>
                    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="filterStatus">Filter by Status:</label>
                            <select id="filterStatus" class="form-control">
                                <option value="all">Semua</option>
                                <option value="lunas">Lunas</option>
                                <option value="belum_lunas" selected>Belum Lunas</option> <!-- Set default to Belum Lunas -->
                            </select>
                        </div>
                        <div class="table-responsive">
                            <table class="table" id="paymentTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Perihal</th>
                                        <th>Jatuh Tempo</th>
                                        <th>Harga</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;

                                    // Tampilkan pembayaran buku, seragam, dan tahunan
                                   // Tampilkan semua pembayaran (buku, seragam, tahunan, dan SPP)
$tipe_pembayaran_array = [
    'pembayaran_buku',
    'pembayaran_seragam',
    'pembayaran_tahunan'
];

foreach ($tipe_pembayaran_array as $tipe_pembayaran) {
    if (!empty($$tipe_pembayaran)) {
        foreach ($$tipe_pembayaran as $item) {
            // Tambahkan class tdcoy ke elemen tr
            echo "<tr class='tdcoy' data-status='" . ($item->status == 'lunas' ? 'lunas' : 'belum_lunas') . "'>"; 
            echo "<td>{$no}</td>";
            echo "<td>{$item->pembayaran_untuk}</td>";
            echo "<td>{$item->tanggal_jatuh_tempo}</td>";
            echo "<td>{$item->harga}</td>";
            echo "<td>{$item->status}</td>";

            // Menampilkan tombol sesuai dengan status
            if ($item->status == 'lunas') {
                echo "<td><button class='btn btn-success' disabled>&#10004;</button></td>"; // Tombol centang untuk yang lunas
            } elseif ($item->status == 'check') {
                echo "<td><button class='btn btn-warning' disabled>Check</button></td>"; // Tombol centang untuk yang lunas
            }else {
                echo "<td><a href='" . base_url('home/bayar/' . $item->id_spp) . "'><button class='btn btn-info'>Bayar</button></a></td>"; // Tombol bayar untuk yang belum lunas
            }

            echo "</tr>";
            $no++; // Increment nomor
        }
    }
}

// Tampilkan SPP jika semua pembayaran sebelumnya sudah lunas
if ($semua_lunas) {
    if (!empty($pembayaran_spp)) {
        foreach ($pembayaran_spp as $item) {
            echo "<tr class='tdcoy' data-status='" . ($item->status == 'lunas' ? 'lunas' : 'belum_lunas') . "'>"; 
            echo "<td>{$no}</td>";
            echo "<td>{$item->pembayaran_untuk}</td>";
            echo "<td>{$item->tanggal_jatuh_tempo}</td>";
            echo "<td>{$item->harga}</td>";
            echo "<td>{$item->status}</td>";

            // Menampilkan tombol sesuai dengan status untuk SPP
            if ($item->status == 'lunas') {
                echo "<td><button class='btn btn-success' disabled>&#10004;</button></td>"; // Tombol centang untuk yang lunas
            } else {
                echo "<td><a href='" . base_url('home/bayar/' . $item->id_spp) . "'><button class='btn btn-info'>Bayar</button></a></td>"; // Tombol bayar untuk yang belum lunas
            }

            echo "</tr>";
            $no++; // Increment nomor
        }
    }
}

// Jika tidak semua lunas, tampilkan pesan
if (!$semua_lunas) {
    echo "<tr><td colspan='5'>Harap selesaikan pembayaran buku, seragam, dan tahunan terlebih dahulu.</td></tr>";
}

                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('vendor/global/global.min.js') ?>"></script>
<script src="<?= base_url('js/quixnav-init.js') ?>"></script>
<script src="<?= base_url('js/custom.min.js') ?>"></script>

<!-- Datatable -->
<script src="<?= base_url('vendor/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('js/plugins-init/datatables.init.js') ?>"></script>

<script>
    // Function to filter the table based on selected status
    function filterTable() {
        var filterValue = document.getElementById('filterStatus').value;
        var rows = document.querySelectorAll('#paymentTable tbody tr');

        rows.forEach(function(row) {
            var status = row.getAttribute('data-status');
            if (filterValue === 'all') {
                row.style.display = ''; // Show all
            } else {
                row.style.display = (status === filterValue) ? '' : 'none'; // Show based on status
            }
        });
    }

    // Initial filter on page load
    document.addEventListener('DOMContentLoaded', function() {
        filterTable(); // Call filterTable function on page load
    });

    // Add event listener to filter dropdown
    document.getElementById('filterStatus').addEventListener('change', filterTable);
</script>
