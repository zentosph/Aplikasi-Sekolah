<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit User</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form class="form-valide-with-icon" action="<?=base_url('home/aksi_euser')?>" method="post">
                                <div class="form-group">
                                    <h6 class="text-label">Nama</h6>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-user-circle"></i> </span>
                                        </div>
                                        <input type="text" class="form-control" id="val-nama" name="nama" placeholder="Masukkan Nama Lengkap" value="<?= $user->nama ?>" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h6 class="text-label">Email</h6>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-envelope"></i> </span>
                                        </div>
                                        <input type="email" class="form-control" id="val-email" name="email" placeholder="Masukkan Email" value="<?= $user->email ?>" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h6 class="text-label">Nomor Whatsapp</h6>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-phone"></i> </span>
                                        </div>
                                        <input type="text" class="form-control" id="val-no-wa" name="no_wa" placeholder="Masukkan Nomor Whatsapp" value="<?= $user->no_wa ?>" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h6 class="text-label">Level Pengguna</h6>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-users-cog"></i> </span>
                                        </div>
                                        <select class="form-control" name="id_level" required>
                                            <option value="1" <?= $user->id_level == 1 ? 'selected' : '' ?>>Admin</option>
                                            <option value="2" <?= $user->id_level == 2 ? 'selected' : '' ?>>Guru</option>
                                            <option value="3" <?= $user->id_level == 3 ? 'selected' : '' ?>>Siswa</option>
                                            <!-- Tambahkan opsi lainnya sesuai kebutuhan -->
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" value="<?=$user->id_user?>" name="id">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <button type="button" class="btn btn-light" onclick="window.history.back()">Batal</button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
