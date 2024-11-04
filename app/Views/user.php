<style>
    .tdcoy > td{
        color: black;
    }
</style>
<div class="content-body">
<?php
$activePage = basename($_SERVER['REQUEST_URI']);

?>
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
                            <a href="<?=base_url('home/Tambah_User/')?>"><button class="btn btn-info">Tambah</button></a>
                                <div class="table-responsive">
                                    <table id="example" class="display" style="min-width: 845px">
                                        <thead>
                                            <tr>
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php 
                                    $no=1;
                                    foreach ($user as $key) {
                                    ?> 
                                            <tr class="tdcoy">
                                                <td><?= $key->nama?></td>
                                                <td><?= $key->email?></td>

                                                <td>
                                                <?php if ($activePage === 'User') { ?>
                                                    <a href="<?=base_url('home/resetpassword/'.$key->id_user)?>">
                                                        <button class="btn btn-info"><i class="fa fa-refresh"></i></button>
                                                    </a>
                                                    <a href="<?=base_url('home/sduser/'.$key->id_user)?>">
                                                        <button class="btn btn-info"><i class="fa fa-trash"></i></button>
                                                    </a>
                                                    <a href="<?=base_url('home/euser/'.$key->id_user)?>">
                                                        <button class="btn btn-info"><i class="fa fa-edit"></i></button>
                                                    </a>
                                                    <a href="<?=base_url('home/undo_user/'.$key->id_user)?>">
                                                        <button class="btn btn-info"><i class="fa fa-arrow-left"></i></button>
                                                    </a>
                                                <?php } ?>
                                                <?php if ($activePage === 'RecycleUser') { ?>
                                                    <a href="<?=base_url('home/rsuser/'.$key->id_user)?>">
                                                        <button class="btn btn-info"><i class="fa fa-exclamation-circle"></i></button>
                                                    </a>
                                                <?php } ?>
                                                </td>
                                            </tr>
                                    <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Email</th>
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