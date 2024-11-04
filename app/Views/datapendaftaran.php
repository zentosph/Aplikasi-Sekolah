<style>
    .tdcoy > td{
        color: black;
    }
</style>
<div class="content-body">

            <div class="container-fluid">
                <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">
                            <h4>Hi, welcome back!</h4>
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
                <!-- row -->


                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Basic Datatable</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="display" style="min-width: 845px">
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Paket</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php 
                                    $no=1;
                                    foreach ($siswa as $key) {

                                        $status_class = '';
                                        if ($key->status_pendaftaran == 'pending') {
                                            $status_class = 'btn-warning'; // Kelas untuk tombol kuning
                                        } elseif ($key->status_pendaftaran == 'diterima') {
                                            $status_class = 'btn-success'; // Kelas untuk tombol hijau
                                        } elseif ($key->status_pendaftaran == 'ditolak') {
                                            $status_class = 'btn-danger'; // Kelas untuk tombol merah
                                        }
                                    ?> 
                                            <tr class="tdcoy">
                                                <td><?= $key->nama_anak?></td>
                                                <td><?= $key->paket?></td>
                                                <td><span class="btn <?= $status_class ?> btn-sm"><?= ucfirst($key->status_pendaftaran) ?></span></td>
                                                <td>
                                                    <a href="<?=base_url('home/DetailPendaftaran/'.$key->id_anak)?>">
                                                        <button class="btn btn-info"><i class="fa fa-exclamation-circle"></i></button>
                                                    </a>
                                                </td>
                                            </tr>
                                    <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Paket</th>
                                                <th>Status</th>
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
    <script src="<?=base_url('vendor/global/global.min.js')?>"></script>
    <script src="<?=base_url('js/quixnav-init.js')?>"></script>
    <script src="<?=base_url('js/custom.min.js')?>"></script>
    <script src="<?=base_url('vendor/datatables/js/jquery.dataTables.min.js')?>"></script>
    <script src="<?=base_url('js/plugins-init/datatables.init.js')?>"></script>