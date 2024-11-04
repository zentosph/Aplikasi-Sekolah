<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Status SPP</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form class="form-valide-with-icon" action="<?= base_url('home/aksi_lunas') ?>" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <h6 class="text-label">Nama Anak</h6>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                                        </div>
                                        <input type="text" class="form-control" id="val-username1" name="nama" placeholder="Masukkan Nama Lengkap Anak" value="<?= $siswa->nama_anak ?>" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h6 class="text-label">Tanggal Jatuh Tempo</h6>
                                    <div class="input-group transparent-append">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-calendar"></i> </span>
                                        </div>
                                        <input type="date" class="form-control" id="val-password1" name="tanggal" value="<?= $siswa->tanggal_jatuh_tempo ?>" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h6 class="text-label">Bukti Pembayaran</h6>
                                    <div class="input-group">
                                        <a href="<?= base_url('SPP/' . $siswa->bukti_pembayaran) ?>" class="btn btn-secondary" target="_blank">Lihat Kartu Keluarga</a>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-check">
                                        <input id="checkbox1" class="form-check-input" type="checkbox">
                                        <label for="checkbox1" class="form-check-label">Check me out</label>
                                    </div>
                                </div>
                            </form>
                            
                            <a href="<?=base_url('home/aksi_lunas/'.$siswa->id_spp)?>">
                            <button  class="btn btn-success">Lunas</button>
                            </a>
                            <a href="<?=base_url('home/aksi_ditolak/'.$siswa->id_spp)?>"></a>
                            <button  class="btn btn-danger">Tolak</button>
                            </a>
                       
                            <button type="button" class="btn btn-light" onclick="history.back();">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
