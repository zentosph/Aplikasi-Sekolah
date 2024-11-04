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
                            <form class="form-valide-with-icon" action="<?=base_url('home/aksi_pendaftaran')?>" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <h6 class="text-label">Nama Orang Tua</h6>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-user-circle"></i> </span>
                                        </div>
                                        <input type="text" class="form-control" id="val-username1" name="nama_ortu" placeholder="Masukkan Nama Lengkap Orang Tua" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h6 class="text-label">Email</h6>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-envelope"></i> </span>
                                        </div>
                                        <input type="email" class="form-control" id="val-username1" name="email_ortu" placeholder="Masukkan Email" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h6 class="text-label">Nomor Whatsapp</h6>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-phone"></i> </span>
                                        </div>
                                        <input type="text" class="form-control" id="val-username1" name="no_wa" placeholder="Masukkan Nomor Whatsapp" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h6 class="text-label">Nama Lengkap Anak</h6>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-child"></i> </span>
                                        </div>
                                        <input type="text" class="form-control" id="val-username1" name="nama" placeholder="Masukkan Nama Lengkap Anak" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h6 class="text-label">Tanggal Lahir</h6>
                                    <div class="input-group transparent-append">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-calendar-alt"></i> </span>
                                        </div>
                                        <input type="date" class="form-control" id="val-password1" name="tanggal" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h6 class="text-label">Kartu Keluarga dalam Format PDF</h6>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-file-pdf"></i> </span>
                                        </div>
                                        <input type="file" class="form-control" id="val-username1" name="kartukeluarga" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h6>Akta Lahir dalam Format PDF</h6>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-file-alt"></i> </span>
                                        </div>
                                        <input type="file" class="form-control" id="val-username1" name="akta" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h6>Bukti Pembayaran Bank Permata<br> ke 111-222-333</h6>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-receipt"></i> </span>
                                        </div>
                                        <input type="file" class="form-control" id="val-username1" name="buktipembayaran" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h6>Paket SPP</h6>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-box"></i> </span>
                                        </div>
                                        <select class="pilih form-control" tabindex="1" name="paket">
                                            <?php foreach ($paket as $key) { ?>
                                                <option value="<?=$key->id_paket?>"><?=$key->paket?> - <?=$key->harga?></option>
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
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="submit" class="btn btn-light">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
