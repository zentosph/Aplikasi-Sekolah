<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Pembayaran</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .payment-card {
            margin-top: 25px;
            padding: 20px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            width: 60%; /* Lebar diperbesar */
            margin-left: auto;
            margin-right: auto;
            max-height: 600px; /* Tinggi maksimum */
            overflow-y: auto; /* Scroll jika konten melebihi tinggi */
        }
        .total-price {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .table th, .table td {
            vertical-align: middle; /* Rata tengah secara vertikal */
            padding: 15px; /* Menambah padding */
        }
        h4 {
            margin-bottom: 20px; /* Spasi bawah pada judul */
        }

        .inputfile{
            margin-bottom: 10px;
        }

        .format{
            color: red;
        }
    </style>
</head>
<body>
<div class="content-body">
<div class="container">
    <h2 class="text-center mt-4">Pembayaran</h2>

    <div class="payment-card">
    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>
        <h4>Rincian Pembayaran</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Harga</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td><?=$spp->pembayaran_untuk?></td>
                    <td><?= number_format($spp->harga, 0, ',', '.') ?></td>
                    <td><?=$spp->tanggal_jatuh_tempo?></td>
                </tr>

                <tr>
                    <td colspan="1" class="text-right">Total:</td>
                    <td class="total-price"><?= number_format($spp->harga, 0, ',', '.') ?></td>
                </tr>
            </tbody>
        </table>
        <form action="<?=base_url('home/aksi_bayar/')?>" method="post" enctype="multipart/form-data">
            <label for="">Bukti Bayar <strong class="format">Format PDF</strong></label><br>
            <input type="file" name="buktibayar" class="inputfile" required>
        <button type="submit" class="btn btn-info btn-block">Bayar</button>
        <input type="hidden" value="<?=$spp->id_spp?>" name="id">
        </form>
    </div>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
