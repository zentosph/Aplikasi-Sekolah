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
    .btn-red {
        background-color: red;
        color: white;
    }

    .btn-green {
        background-color: green;
        color: white;
    }
</style>

<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
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
                                
                    <h4>Nama Siswa: <?= $anak->nama_anak ?></h4>
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nilai</th>
                                        <th>Tipe Nilai</th>
                                        <th>Mata Pelajaran</th>
                                    </tr>
                                </thead>
                                <tbody>
    <?php 
    $no = 1;
    foreach ($mapel as $key) {
        // Determine the button class based on the nilai value
        $buttonClass = ($key->nilai < 75) ? 'btn btn-danger' : 'btn btn-success';
    ?> 
    <tr class="tdcoy">
        <td><?= $no++ ?></td>
        <td>
            <button class="<?= $buttonClass ?>">
                <?= $key->nilai ?>
            </button>
        </td>
        <td><?= $key->tipe_nilai ?></td>
        <td><?= $key->mapel ?></td>
    </tr>
    <?php } ?>
</tbody>

                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Nilai</th>
                                        <th>Tipe Nilai</th>
                                        <th>Mata Pelajaran</th>
                                    
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


