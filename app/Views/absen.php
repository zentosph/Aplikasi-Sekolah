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
                                        <th>Absen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    foreach ($kelas as $key) {
                                        $absenStatus = null;
                                        foreach ($absen as $record) {
                                            if ($record->id_anak == $key->id_anak) {
                                                $absenStatus = $record->status_absen;
                                                break;
                                            }
                                        }
                                    ?> 
                                    <tr class="tdcoy">
                                        <td><?= $key->nama_anak ?></td>
                                        <td>
                                            <div class="attendance-container">
                                                <input type="checkbox" class="attendance-checkbox" data-id="<?= $key->id_anak ?>" 
                                                <?= $absenStatus === 'hadir' ? 'checked' : '' ?> />
                                                
                                                <input type="text" class="attendance-reason" data-id="<?= $key->id_anak ?>" value="<?=$key->alasan?>"
                                                placeholder="Alasan" style="display: <?= $absenStatus === 'tidak hadir' ? 'block' : 'none' ?>;" />
                                            </div>
                                        </td>

                                    </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Nama Siswa</th>
                                        <th>Absen</th>
                                    
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?=base_url('vendor/global/global.min.js')?>"></script>
<script src="<?=base_url('js/quixnav-init.js')?>"></script>
<script src="<?=base_url('js/custom.min.js')?>"></script>
<script src="<?=base_url('vendor/datatables/js/jquery.dataTables.min.js')?>"></script>
<script src="<?=base_url('js/plugins-init/datatables.init.js')?>"></script>

<script>
$(document).ready(function() {
    // Loop melalui setiap checkbox dan periksa status kehadiran
    $('.attendance-checkbox').each(function() {
        var isChecked = $(this).is(':checked');
        var reasonInput = $(this).siblings('.attendance-reason');

        // Jika checkbox tidak dicentang, tampilkan input alasan
        if (!isChecked) {
            reasonInput.show(); // Tampilkan input alasan jika checkbox tidak dicentang
        }
    });

    // Ketika checkbox berubah
    $('.attendance-checkbox').change(function() {
        var id_anak = $(this).data('id'); 
        var isChecked = $(this).is(':checked');
        var status = isChecked ? 'hadir' : 'tidak hadir';
        var alasan = $(this).siblings('.attendance-reason').val();

        // Menyembunyikan atau menampilkan input alasan
        if (isChecked) {
            $(this).siblings('.attendance-reason').hide(); 
        } else {
            $(this).siblings('.attendance-reason').show().focus();
        }

        // Log data yang dikirim
        console.log('Data yang dikirim:', {
            id_anak: id_anak,
            status: status,
            alasan: alasan
        });

        // AJAX untuk menambahkan kehadiran
        $.ajax({
            url: '<?= base_url('home/addAttendanceOrReason') ?>',
            type: 'POST',
            data: {
                id_anak: id_anak,
                status: status,
                alasan: alasan
            },
            success: function(response) {
                console.log('Response:', response); // Tampilkan seluruh data response di console
                try {
                    var res = JSON.parse(response);
                    if (res.status === 'error') {
                        alert(res.message);
                    } else {
                        console.log('Parsed Response:', res);
                    }
                } catch (e) {
                    console.error('Parsing error:', e);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                console.log('Response Text:', xhr.responseText);
            }
        });
    });

    // Tangani perubahan pada input alasan
    $('.attendance-reason').on('input', function() {
        var id_anak = $(this).data('id'); 
        var alasan = $(this).val(); // Ambil nilai alasan dari input
        var status = $(this).siblings('.attendance-checkbox').is(':checked') ? 'hadir' : 'tidak hadir';

        // AJAX untuk memperbarui alasan kehadiran
        $.ajax({
            url: '<?= base_url('home/addAttendanceOrReason') ?>',
            type: 'POST',
            data: {
                id_anak: id_anak,
                status: status,
                alasan: alasan
            },
            success: function(response) {
                console.log('Response:', response); // Tampilkan seluruh data response di console
                try {
                    var res = JSON.parse(response);
                    if (res.status === 'error') {
                        alert(res.message);
                    } else {
                        console.log('Parsed Response:', res);
                    }
                } catch (e) {
                    console.error('Parsing error:', e);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                console.log('Response Text:', xhr.responseText);
            }
        });
    });
});



</script>

