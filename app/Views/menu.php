<style>
    .nav-label{
        color: white;
    }
</style>
<div class="nav-header">
            <a href="index.html" class="brand-logo">
                <img class="logo-abbr" src="<?=base_url('images/'.$setting->menu)?>" alt="">
                <img class="logo-compact" src="<?=base_url('images/logo-text.png')?>" alt="">
                <img class="brand-title" src="<?=base_url('images/logo-text.png')?>" alt="">
            </a>

            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Header start
        ***********************************-->
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            <div class="search_bar dropdown">
                                <span class="search_icon p-3 c-pointer" data-toggle="dropdown">
                                    <i class="mdi mdi-magnify"></i>
                                </span>
                                <div class="dropdown-menu p-0 m-0">
                                    <form>
                                        <input class="form-control" type="search" placeholder="Search" aria-label="Search">
                                    </form>
                                </div>
                            </div>
                        </div>

                        <ul class="navbar-nav header-right">

                            <li class="nav-item dropdown header-profile">
                            <?php 
if(session()->get('id')){ 
?>
                            
 <span class="namauser"><?=session()->get('nama')?></span>
    <a class="nav-link" href="" role="button" data-toggle="dropdown">
        <i class="mdi mdi-account"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-right">
        <a href="./app-profile.html" class="dropdown-item">
            <i class="icon-user"></i>
            <span class="ml-2">Profile</span>
        </a>
        <a href="./email-inbox.html" class="dropdown-item">
            <i class="icon-envelope-open"></i>
            <span class="ml-2">Inbox</span>
        </a>
        <a href="<?=base_url('home/logout')?>" class="dropdown-item">
            <i class="icon-key"></i>
            <span class="ml-2">Logout</span>
        </a>
    </div>
<?php 
} else { 
?>
    <a class="nav-link" href="<?=base_url('home/login')?>" role="button">
        <h6>Login</h6>
    </a>
<?php 
} 
?>

                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <div class="quixnav">
            <div class="quixnav-scroll">
                <ul class="metismenu" id="menu">
                    
                    <li class="nav-label first">Main Menu</li>
                    <li><a href="<?=base_url('home')?>" aria-expanded="false"><i class="fa fa-home"></i><span
                                class="nav-text">Dashboard</span></a></li>
                    
                    <li><a href="<?=base_url('home/pendaftaran')?>" aria-expanded="false"><i class="fa fa-user-plus"></i><span class="nav-text">Pendaftaran</span></a></li>
                    
                    <?php if (session()->get('level') == 1 || session()->get('level') == 3) { ?>
                    <li><a href="<?=base_url('home/spp')?>" aria-expanded="false"><i class="fa fa-credit-card"></i><span class="nav-text">Bayar SPP</span></a></li>
                    <?php } ?>

                    <?php if ($menu->apps == 1) { ?>
                    <li class="nav-label">Apps</li>
                    
                    
                    <li><a href="<?=base_url('home/Tugas')?>" aria-expanded="false"><i class="fa fa-tasks"></i><span class="nav-text">Pemberian Tugas</span></a></li>
                   

                    

                    <li><a href="<?=base_url('home/Penilaian')?>" aria-expanded="false"><i class="fa fa-graduation-cap"></i><span class="nav-text">Penilaian</span></a></li>
            

              
                    
           

                   
                    <li><a href="<?=base_url('home/Guru')?>" aria-expanded="false"><i class="fa fa-chalkboard-teacher"></i><span class="nav-text">Guru</span></a></li>
                    
                    <li><a href="<?=base_url('home/Kelas')?>" aria-expanded="false"><i class="fa fa-building"></i><span class="nav-text">Kelas</span></a></li>
                    <?php } ?>

                    <?php if ($menu->data == 1) { ?>
                    <li class="nav-label">Data</li>
                 
                    <li><a href="<?=base_url('home/DataPendaftaran')?>" aria-expanded="false"><i class="fa fa-list-alt"></i><span class="nav-text">Data Pendaftaran</span></a></li>
            

                    <li><a href="<?=base_url('home/StatusSPP')?>" aria-expanded="false"><i class="fa fa-money"></i><span class="nav-text">Pembayaran SPP</span></a></li>
             
                    
                   
                   
               
                    <li><a href="<?=base_url('home/aturkelas')?>" aria-expanded="false"><i class="fa fa-building"></i><span class="nav-text">Anak</span></a></li>
                    <li><a href="<?=base_url('home/laporan')?>" aria-expanded="false"><i class="fa fa-book"></i><span class="nav-text">laporan</span></a></li>
                    <li><a href="<?=base_url('home/User')?>" aria-expanded="false"><i class="fa fa-user"></i><span class="nav-text">User</span></a></li>
                    <?php } ?>
                    
                    <?php if ($menu->recyclebin == 1) { ?>

                    <li class="nav-label">Recycle Bin</li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                                class="icon icon-single-copy-06"></i><span class="nav-text">Recycle Bin</span></a>
                        <ul aria-expanded="false">
                            <li><a href="<?=base_url('home/RecycleUser')?>">User</a></li>
                            <li><a href="<?=base_url('home/Recycleuserlog')?>">Log</a></li>
                        </ul>
                    </li>
                    <?php } ?>

                    <?php if ($menu->website == 1) { ?>
                    <li class="nav-label">Website</li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                                class="icon icon-single-copy-06"></i><span class="nav-text">Website</span></a>
                        <ul aria-expanded="false">
                            <li><a href="<?=base_url('home/loguser')?>">Log Activity</a></li>
                            <li><a href="<?=base_url('home/setting')?>">Setting</a></li>
                            <li><a href="<?=base_url('home/Menu')?>">Menu</a></li>
                        </ul>
                    </li>
                    <?php } ?>  
                </ul>
            </div>


        </div>