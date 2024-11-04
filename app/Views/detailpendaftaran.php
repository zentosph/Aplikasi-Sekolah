<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Pendaftaran Sekolah</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form class="form-valide-with-icon" action="<?= base_url('home/aksi_pendaftaran') ?>" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <h6 class="text-label">Nama Lengkap Anak</h6>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                                        </div>
                                        <input type="text" class="form-control" id="val-username1" name="nama" placeholder="Masukkan Nama Lengkap Anak" value="<?= $siswa->nama_anak ?>" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h6 class="text-label">Tanggal Lahir</h6>
                                    <div class="input-group transparent-append">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-calendar"></i> </span>
                                        </div>
                                        <input type="date" class="form-control" id="val-password1" name="tanggal" value="<?= $siswa->tanggal_lahir ?>" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h6 class="text-label">Kartu Keluarga</h6>
                                    <div class="input-group">
                                        <a href="<?= base_url('Data Siswa/' . $siswa->kartu_keluarga) ?>" class="btn btn-secondary" target="_blank">Lihat Kartu Keluarga</a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h6 class="text-label">Akta Lahir</h6>
                                    <div class="input-group">
                                        <a href="<?= base_url('Data Siswa/' . $siswa->akta_lahir) ?>" class="btn btn-secondary" target="_blank">Lihat Akta Lahir</a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h6>Paket SPP</h6>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-envelope-open"></i> </span>
                                        </div>
                                        <select class="pilih form-control" tabindex="1" name="paket">
                                            <?php foreach ($paket as $key) { ?> 
                                                <option value="<?= $key->id_paket ?>"><?= $key->paket ?> - <?= $key->harga ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-check">
                                        <input id="checkbox1" class="form-check-input" type="checkbox">
                                        <label for="checkbox1" class="form-check-label">Check me out</label>
                                    </div>
                                </div>
                            </form>
                            <?php if ($siswa->status_pendaftaran === 'pending') { ?>
                            <a href="<?=base_url('home/diterima/'.$siswa->id_anak)?>">
                            <button  class="btn btn-secondary">Terima</button>
                            </a>
                            <a href="<?=base_url('home/ditolak/'.$siswa->id_anak)?>"></a>
                            <button  class="btn btn-danger">Tolak</button>
                            </a>
                            <?php } ?>
                            <button type="button" class="btn btn-light" onclick="history.back();">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
