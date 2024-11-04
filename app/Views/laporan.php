<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Laporan Keuangan</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form class="form-valide-with-icon" action="<?= base_url('home/laporanKeuangan') ?>" method="post" target="blank">
                                <div class="form-group">
                                    <h6 class="text-label">Tanggal Mulai</h6>
                                    <div class="input-group transparent-append">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-calendar-alt"></i> </span>
                                        </div>
                                        <input type="date" class="form-control" name="start_date" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h6 class="text-label">Tanggal Akhir</h6>
                                    <div class="input-group transparent-append">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-calendar-alt"></i> </span>
                                        </div>
                                        <input type="date" class="form-control" name="end_date" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="reset" class="btn btn-light">Bersihkan</button>
                                </div>
                                <div class="form-group">
                                    
                                <button type="submit" class="btn btn-primary">Tampilkan Laporan</button>
                                    <button type="button" class="btn btn-info" onclick="exportToExcel()">Print Excel</button>
                                    <button type="button" class="btn btn-danger" onclick="exportToPDF()">Print PDF</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function exportToExcel() {
        // Mendapatkan nilai tanggal dari input form
        var startDate = document.querySelector('input[name="start_date"]').value;
        var endDate = document.querySelector('input[name="end_date"]').value;
        
        // Memastikan tanggal tidak kosong
        if (!startDate || !endDate) {
            alert('Silakan isi tanggal mulai dan tanggal akhir.');
            return;
        }
        
        // Menggunakan window.open untuk membuka URL di tab baru
        window.open('<?= base_url('home/laporanexcel') ?>?start_date=' + startDate + '&end_date=' + endDate, '_blank');
    }

    function exportToPDF() {
        // Mendapatkan nilai tanggal dari input form
        var startDate = document.querySelector('input[name="start_date"]').value;
        var endDate = document.querySelector('input[name="end_date"]').value;

        // Memastikan tanggal tidak kosong
        if (!startDate || !endDate) {
            alert('Silakan isi tanggal mulai dan tanggal akhir.');
            return;
        }

        // Menggunakan window.open untuk membuka URL di tab baru
        window.open('<?= base_url('home/laporanpdf') ?>?start_date=' + startDate + '&end_date=' + endDate, '_blank');
    }
</script>


