<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Pendaftaran Pengguna</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form class="form-valide-with-icon" action="<?=base_url('home/aksi_t_user')?>" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <h6 class="text-label">Nama</h6>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-user-circle"></i> </span>
                                        </div>
                                        <input type="text" class="form-control" name="nama" placeholder="Masukkan Nama Lengkap" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h6 class="text-label">Nomor Whatsapp</h6>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-phone"></i> </span>
                                        </div>
                                        <input type="text" class="form-control" name="no_wa" placeholder="Masukkan Nomor Whatsapp" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h6 class="text-label">Email</h6>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-envelope"></i> </span>
                                        </div>
                                        <input type="email" class="form-control" name="email" placeholder="Masukkan Email" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h6 class="text-label">Level</h6>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-layer-group"></i> </span>
                                        </div>
                                        <select class="form-control" name="level" id="level-select" required>
                                            <option value="">Pilih Level</option>
                                            <?php foreach ($levels as $level) { ?>
                                                <option value="<?=$level->id_level?>"><?=$level->level?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Input tambahan untuk guru -->
                                <div id="guru-fields" style="display: none;">
                                    <div class="form-group">
                                        <h6 class="text-label">Gaji Bulanan</h6>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"> <i class="fa fa-money-bill"></i> </span>
                                            </div>
                                            <input type="number" class="form-control" name="gaji_bulanan" placeholder="Masukkan Gaji Bulanan">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <h6 class="text-label">Tunjangan</h6>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"> <i class="fa fa-gift"></i> </span>
                                            </div>
                                            <input type="number" class="form-control" name="tunjangan" placeholder="Masukkan Tunjangan">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <h6 class="text-label">Tanggal Mulai Kerja</h6>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"> <i class="fa fa-calendar"></i> </span>
                                            </div>
                                            <input type="date" class="form-control" name="tanggal_mulai_kerja">
                                        </div>
                                    </div>
                                </div>

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
    // JavaScript untuk menampilkan atau menyembunyikan input tambahan berdasarkan level yang dipilih
    document.getElementById('level-select').addEventListener('change', function() {
    var levelValue = this.value;
    var guruFields = document.getElementById('guru-fields');
    var inputs = guruFields.querySelectorAll('input');

    if (levelValue == 2) { // Jika level 2 (guru) dipilih
        guruFields.style.display = 'block';
        inputs.forEach(input => input.required = true);
    } else {
        guruFields.style.display = 'none';
        inputs.forEach(input => input.required = false);
    }
});

</script>
