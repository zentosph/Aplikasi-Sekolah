<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Kirim Tugas</h4>
                    </div>
                    <div class="card-body">
                        <form id="tugasForm" method="post" action="<?= base_url('home/aksi_tugas') ?>" enctype="multipart/form-data">
                        <?php if (session()->get('level') == 1) { ?>
                            <div class="form-group">
                                <label for="kirimKe">Kirim Informasi Ke:</label>
                                <select id="kirimKe" name="kirim_ke" class="form-control" onchange="toggleClassSelection()">
                                    <option value="semua">Semua Kelas</option>
                                    <option value="per_kelas">Per Kelas</option>
                                </select>
                            </div>

                            <div id="kelasSelection" class="form-group" style="display:none;">
                                <label for="kelas">Pilih Kelas:</label>
                                <select id="kelas" name="id_kelas" class="form-control">
                                    <?php foreach ($kelas as $k): ?>
                                        <option value="<?= $k->id_kelas ?>"><?= $k->nama_kelas ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php } elseif (session()->get('level') == 2) { ?>
                            <input type="hidden" name="id_kelas" value="<?= $kelas->id_kelas?>" />
                        <?php } ?>
                        
                        <div class="basic-form">
                            <h6 class="text-label">Mengenai</h6>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                                </div>
                                <textarea name="mengenai" placeholder="Masukkan deskripsi Tugas" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="basic-form">
                            <h6 class="text-label">Deskripsi</h6>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                                </div>
                                <textarea name="deskripsi" placeholder="Masukkan deskripsi Tugas" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="basic-form">
                            <h6>Tugas dalam Bentuk PDF</h6>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-id-card"></i></span>
                                </div>
                                <input type="file" class="form-control" name="tugas" required>
                            </div>
                        </div>

                        <div class="basic-form">
                            <h6>Terakhir Kumpul</h6>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-id-card"></i></span>
                                </div>
                                <input type="date" class="form-control" name="tanggal" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Submit</button>
                        <button type="button" class="btn btn-light" style="margin-top: 10px;" onclick="history.back();">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleClassSelection() {
    const select = document.getElementById("kirimKe");
    const kelasSelection = document.getElementById("kelasSelection");
    if (select.value === "per_kelas") {
        kelasSelection.style.display = "block";
    } else {
        kelasSelection.style.display = "none";
    }
}
</script>
