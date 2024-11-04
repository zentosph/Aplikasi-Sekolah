<style>
    .tdcoy > td {
        color: black;
    }
    .attendance-container {
        display: flex;
        align-items: center;
        margin-left: 20px;
    }
    .attendance-checkbox {
        margin-right: 10px;
    }
    .attendance-reason {
        display: none;
        width: 150px;
    }
    .update-button {
        margin-left: 10px;
        padding: 5px 10px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    .update-button:hover {
        background-color: #0056b3;
    }
</style>

<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>ABSEN</h4>
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

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>Nama Siswa</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($kelas as $key) { ?> 
                                    <tr class="tdcoy">
                                        <td><?= $key->nama_anak ?></td>
                                        <td>
                                            <select class="class-select" data-id="<?= $key->id_anak ?>">
                                                <option value="">Pilih Kelas</option>
                                                <?php foreach ($kelazz as $kelas) { ?>
                                                    <option value="<?= $kelas->id_kelas ?>"><?= $kelas->nama_kelas ?></option>
                                                <?php } ?>
                                            </select>
                                            <button class="update-button" data-id="<?= $key->id_anak ?>">Update Kelas</button>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Nama Siswa</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Load jQuery first -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Load DataTables JS and CSS -->


<script>
    $(document).ready(function() {
        // Initialize DataTable


        // Handle button click for updating class
        $('.update-button').on('click', function() {
            var id_anak = $(this).data('id');
            var id_kelas = $(this).closest('tr').find('.class-select').val();
            console.log("Data yang dikirim: ", { id_anak: id_anak, id_kelas: id_kelas });

            // Check if id_kelas is selected
            if (!id_kelas) {
                alert("Silakan pilih kelas yang valid.");
                return;
            }

            // AJAX request to update class
            $.ajax({
                url: "<?= base_url('home/aksi_aturkelas') ?>", // Update this path with the correct controller method
                type: "POST",
                data: {
                    id_anak: id_anak,
                    id_kelas: id_kelas
                },
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        alert("Kelas berhasil diupdate!");
                    } else {
                        alert("Gagal mengupdate kelas.");
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Error:", textStatus, errorThrown);
                    alert("Terjadi kesalahan, coba lagi.");
                }
            });
        });
    });
</script>
