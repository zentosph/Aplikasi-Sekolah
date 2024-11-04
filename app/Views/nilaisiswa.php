<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Nilai</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form class="form-valide-with-icon" action="<?= base_url('home/aksi_t_nilai') ?>" method="post">
                                <!-- Input Tahun Ajaran -->
                                <div class="form-group">
                                    <h6 class="text-label">Tahun Ajaran</h6>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="tahun_ajaran" placeholder="Masukkan Tahun Ajaran" required>
                                    </div>
                                </div>

                                <!-- Input Tipe Nilai -->
                                <div class="form-group">
                                    <h6 class="text-label">Tipe Nilai</h6>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-list"></i></span>
                                        </div>
                                        <select class="form-control" name="tipe_nilai" required>
                                            <option value="">Pilih Tipe Nilai</option>
                                            <option value="Harian">Harian</option>
                                            <option value="Ulangan">Ulangan</option>
                                            <option value="Ujian">Ujian</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Input Mapel -->
                                <div class="form-group">
                                    <h6 class="text-label">Mapel dan Nilai</h6>
                                    <div id="mapelNilaiContainer">
                                        <!-- Input Mapel dan Nilai pertama -->
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-book"></i></span>
                                            </div>
                                            <select class="form-control" name="mapel[]" required>
                                                <option value="">Pilih Mapel</option>
                                                <?php foreach ($mapel as $key): ?>
                                                    <option value="<?= $key->id_mapel ?>"><?= $key->mapel ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-graduation-cap"></i></span>
                                            </div>
                                            <input type="number" class="form-control" name="nilai[]" placeholder="Masukkan Nilai" required>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-danger" onclick="hapusInput(this)">Hapus</button>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id" value="<?= $anak->id_anak ?>">
                                    <!-- Button untuk menambah input Mapel dan Nilai -->
                                    <button type="button" class="btn btn-success" onclick="tambahMapelNilai()">Tambah</button>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="reset" class="btn btn-light">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Pre-generate the mapel options
    var mapelOptions = `<?php foreach ($mapel as $key): ?>
        <option value="<?= $key->id_mapel ?>"><?= $key->mapel ?></option>
    <?php endforeach; ?>`;

    function tambahMapelNilai() {
        // Element baru yang akan ditambahkan
        var newInput = `
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-book"></i></span>
                </div>
                <select class="form-control" name="mapel[]" required>
                    <option value="">Pilih Mapel</option>
                    ${mapelOptions}
                </select>
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-graduation-cap"></i></span>
                </div>
                <input type="number" class="form-control" name="nilai[]" placeholder="Masukkan Nilai" required>
                <div class="input-group-append">
                    <button type="button" class="btn btn-danger" onclick="hapusInput(this)">Hapus</button>
                </div>
            </div>
        `;
        // Menambahkan elemen baru ke dalam container
        document.getElementById('mapelNilaiContainer').insertAdjacentHTML('beforeend', newInput);
    }

    function hapusInput(button) {
        // Menghapus elemen input Mapel dan Nilai yang terkait
        button.parentElement.parentElement.remove();
    }
</script>
