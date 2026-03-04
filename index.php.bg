<?php
session_start();
 
include "koneksi.php";


     if($_SESSION['iduser']!="" && $_SESSION['nama']!="" ){
     $uid= $_SESSION['iduser'] ;
      $nama=  $_SESSION['nama'] ;
      $pass=  $_SESSION['passuser'] ;


?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>PAYROLL</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="assets/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="assets/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="assets/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="assets/dist/css/skins/_all-skins.min.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="assets/bower_components/morris.js/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="assets/bower_components/jvectormap/jquery-jvectormap.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="assets/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script type="text/javascript" src="js/jquery.min.js">
  

</script>

 
  <link rel="stylesheet" href="assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script type="text/javascript" src="chart.js/Chart.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
   <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <![endif]-->

  <!-- Google Font -->
 


</head>
<body class="hold-transition  sidebar-mini">

<div class="wrapper"  >

  <header class="main-header"  style="background-color: #5F9EA0; color:white; ">
    <!-- Logo -->
    <a href="?page=" class="logo" style="font-color:white; ">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span style="color:white; " class="logo-mini" ><b>ADM</b></span>
      <!-- logo for regular state and mobile devices -->
      <span style="color:white; " class="logo-lg" ><b ><?php echo "ADMIN"; ?></b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" >
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle"  style="color:white; " data-toggle="push-menu" role="button">
        <span style="color:white; " class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu" style="background-color: #5F9EA0; color:white;">
        <ul class="nav navbar-nav" style="background-color: #5F9EA0; color:white;">
          <!-- Messages: style can be found in dropdown.less-->
          
          <!-- Notifications: style can be found in dropdown.less -->
          
          <!-- Tasks: style can be found in dropdown.less -->
          <li class="dropdown tasks-menu">
           
            <ul class="dropdown-menu">
             
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  
                  <!-- end task item -->
                  
                  <!-- end task item -->
                  
                  <!-- end task item -->
                 
                  <!-- end task item -->
                </ul>
              </li>
             
            </ul>
          </li>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu" style="background-color: #5F9EA0; color:white;" >
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="images/iconhr.jpeg" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $_SESSION['nama']; ?></span>
            </a>
            <ul class="dropdown-menu" >
              <!-- User image -->
              <li class="user-header">
                <img src="images/iconhr.jpeg" class="img-circle" alt="User Image">

                <p>
                  <?php echo $_SESSION['nama']; ?> - Administrator
                  <small> Admin Account </small>
                </p>
              </li>
              <!-- Menu Body -->
              
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
               <a href="?page=user&aksi=ubahpass" class="btn btn-default btn-flat">Change Password</a>
                </div>
                <div class="pull-right">
                  <a href="logout.php" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          
        </ul>
      </div>
    </nav>
  </header>

  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar" style="background-color: white; color:black;">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
    
      <!-- search form -->
   
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->


      <ul class="sidebar-menu" data-widget="tree" >
       <li class="header">Menu Admin</li>
        <li><a href="?page="><i style="background-color: white; color:black;" class="fa fa-dashboard"></i> <span style="background-color: white; color:black;">Dashboard</span></a></li> 

        <li class="treeview">
          <a href="#">
            <i class="fa fa-cog"></i> <span style="background-color: white; color:black;">MASTER</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
          <li><a href="?page=karyawan"><i style="background-color: white; color:black;" class="fa fa-users"></i> <span style="background-color: white; color:black;">Karyawan</span></a></li> 
  <li><a href="?page=bagian"><i style="background-color: white; color:black;" class="fa fa-building"></i> <span style="background-color: white; color:black;">Bagian</span></a></li> 
   <li><a href="?page=subbagian"><i style="background-color: white; color:black;" class="fa fa-university"></i> <span style="background-color: white; color:black;">Sub Bagian</span></a></li> 
    <li><a href="?page=jasa"><i style="background-color: white; color:black;" class="fa fa-university"></i> <span style="background-color: white; color:black;">Jasa</span></a></li> 

  <li><a href="?page=user"><i style="background-color: white; color:black;" class="fa fa-users"></i> <span style="background-color: white; color:black;">User</span></a></li>    
          
          </ul>
        </li>
        
 
                                

<li class="treeview">
          <a href="#">
            <i class="fa fa-cog"></i> <span style="background-color: white; color:black;">SIA</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
              <li><a href="?page=siac"><i style="background-color: white; color:black;" class="fa fa-star"></i> <span style="background-color: white; color:black;">Data Karyawan SIA</span></a></li>       
              <li><a href="?page=sakit"><i style="background-color: white; color:black;" class="fa fa-paper-plane-o"></i> <span style="background-color: white; color:black;">Sakit</span></a></li> 
               <li><a href="?page=ijin"><i style="background-color: white; color:black;" class="fa fa-safari"></i> <span style="background-color: white; color:black;">Ijin</span></a></li> 
                <li><a href="?page=cuti"><i style="background-color: white; color:black;" class="fa fa-edit"></i> <span style="background-color: white; color:black;">Cuti</span></a></li> 
                 <li><a href="?page=alfa"><i style="background-color: white; color:black;" class="fa fa-arrows"></i> <span style="background-color: white; color:black;">Alfa</span></a></li> 
          
          </ul>
        </li>
 <li><a href="?page=jadwal"><i style="background-color: white; color:black;" class="fa fa-clock-o"></i> <span style="background-color: white; color:black;">Jadwal</span></a></li> 
 <li><a href="?page=absen"><i style="background-color: white; color:black;" class="fa fa-users"></i> <span style="background-color: white; color:black;">Absensi Karyawan</span></a></li> 
  <li><a href="?page=payroll"><i style="background-color: white; color:black;" class="fa fa-money"></i> <span style="background-color: white; color:black;">Payroll</span></a></li> 

   <!--<li><a href="?page=generate"><i style="background-color: white; color:black;" class="fa fa-cogs"></i> <span style="background-color: white; color:black;">Generate Absen</span></a></li> -->

<li><a href="?page=generate&aksi=tarik"><i style="background-color: white; color:black;" class="fa fa-cogs"></i> <span style="background-color: white; color:black;">Tarik Data</span></a></li> 

  <li><a href="?page=denda"><i style="background-color: white; color:black;" class="fa fa-user-secret"></i> <span style="background-color: white; color:black;">Denda</span></a></li> 
  <li class="treeview">
          <a href="#">
            <i class="fa fa-calendar-plus-o"></i> <span style="background-color: white; color:black;">Productivity</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
                   
              <li><a href="?page=rkk"><i style="background-color: white; color:black;" class="fa fa-calendar-plus-o"></i> <span style="background-color: white; color:black;">Rencana Upah</span></a></li> 
               <li><a href="?page=realisasi"><i style="background-color: white; color:black;" class="fa fa-calendar-plus-o"></i> <span style="background-color: white; color:black;">Realisasi Upah</span></a></li> 
                <li><a href="?page=realisasi&aksi=karyawan"><i style="background-color: white; color:black;" class="fa fa-book"></i> <span style="background-color: white; color:black;">Cetak Slip</span></a></li> 
             <!--  <li><a href="?page=dailyactivity"><i style="background-color: white; color:black;" class="fa fa-pencil"></i> <span style="background-color: white; color:black;">Create Daily Activity</span></a></li> 
              <li><a href="?page=dailyactivity&aksi=list"><i style="background-color: white; color:black;" class="fa fa-file"></i> <span style="background-color: white; color:black;">List Daily Activity</span></a></li> 
               <li><a href="?page=dailyactivityvendor"><i style="background-color: white; color:black;" class="fa fa-user-secret"></i> <span style="background-color: white; color:black;">Activity Vendor</span></a></li> 
          -->
          </ul>
        </li>
         <!--
         <li class="treeview">
          <a href="#">
            <i class="fa fa-file-text-o"></i> <span style="background-color: white; color:black;">Report</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
                   
              <li><a href="?page=report&aksi=daily"><i style="background-color: white; color:black;" class="fa fa-calendar-plus-o"></i> <span style="background-color: white; color:black;">Daily Report</span></a></li> 
              
          
          </ul>
        </li>
      -->
        
                                
<li><a href="?page=mesin"><i style="background-color: white; color:black;" class="fa fa-cog"></i> <span style="background-color: white; color:black;">Setting Device</span></a></li> 
 <!--
         <li class="treeview">
          <a href="#">
            <i class="fa fa-edit"></i> <span style="background-color: white; color:black;">Request Order</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
                   
           
        <li><a href="?page=rovisa"><i style="background-color: white; color:black;" class="fa fa-cc-visa"></i> <span style="background-color: white; color:black;">RO Visa</span></a></li> 
                                 <li><a href="?page=rolegal"><i style="background-color: white; color:black;" class="fa fa-file-archive-o"></i> <span style="background-color: white; color:black;">RO Legal</span></a></li> 
          
          </ul>
        </li>
-->

 <!--
           <li class="treeview">
          <a href="#">
            <i class="fa fa-arrows"></i> <span style="background-color: white; color:black;">Kelola FO</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
                   
              <li><a href="?page=pevisa&aksi=fovisa"><i style="background-color: white; color:black;" class="fa fa-cog"></i> <span style="background-color: white; color:black;">Kelola FO Visa</span></a></li> 
                                 <li><a href="?page=pelegal&aksi=folegal"><i style="background-color: white; color:black;" class="fa fa-paper-plane-o"></i> <span style="background-color: white; color:black;">Kelola FO Legal</span></a></li> 
          
          </ul>
        </li>

    


         <li class="treeview">
          <a href="#">
            <i class="fa  fa-user-secret"></i> <span style="background-color: white; color:black;">Proyek FO Operator
            </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
                   
              <li><a href="?page=billingvisa&aksi=pevisa"><i style="background-color: white; color:black;" class="fa fa-cog"></i> <span style="background-color: white; color:black;">Proyek FO Visa</span></a></li> 
                                 <li><a href="?page=billinglegal&aksi=pelegal"><i style="background-color: white; color:black;" class="fa fa-cogs"></i> <span style="background-color: white; color:black;">Proyek FO Legal</span></a></li> 
          
          </ul>
        </li>


 <li class="treeview">
          <a href="#">
            <i class="fa fa-dollar"></i> <span style="background-color: white; color:black;">Sales Invoice</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
                   
              <li><a href="?page=salesinvoicevisa&aksi=fovisa"><i style="background-color: white; color:black;" class="fa fa-cog"></i> <span style="background-color: white; color:black;">Invoice Visa</span></a></li> 
                                 <li><a href="?page=salesinvoicelegal&aksi=folegal"><i style="background-color: white; color:black;" class="fa fa-cog"></i> <span style="background-color: white; color:black;">Invoice Legal</span></a></li> 
          
          </ul>
        </li>

        -->

       
                   
             
      

      
  <!--     
         <li class="treeview">
          <a href="#">
            <i class="fa fa-book"></i> <span style="background-color: white; color:black;">PRODUK</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="?page=produk"><i style="background-color: white; color:black;" class="fa fa-cube"></i> <span style="background-color: white; color:black;">Data Produk</span></a></li>
 <li><a href="?page=produk&aksi=datahpp"><i style="background-color: white; color:black;" class="fa fa-money"></i> <span style="background-color: white; color:black;">Hpp Produk</span></a></li> 
  <li><a href="?page=produk&aksi=datappn"><i style="background-color: white; color:black;" class="fa fa-gear"></i> <span style="background-color: white; color:black;">PPN Produk</span></a></li> 
  <li><a href="?page=produk&aksi=datapph"><i style="background-color: white; color:black;" class="fa fa-gear"></i> <span style="background-color: white; color:black;">PPH Produk</span></a></li> 
  <li><a href="?page=produk&aksi=datadiskon"><i style="background-color: white; color:black;" class="fa fa-money"></i> <span style="background-color: white; color:black;">Diskon</span></a></li> 

          
          </ul>
        </li>

      

        <li class="treeview">
          <a href="#">
            <i class="fa fa-book"></i> <span style="background-color: white; color:black;">Tagihan</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
                   
              <li><a href="?page=tagihan&aksi=piutang"><i style="background-color: white; color:black;" class="fa fa-plus-square"></i> <span style="background-color: white; color:black;">Data Piutang</span></a></li> 
              <li><a href="?page=tagihan&aksi=hutang"><i style="background-color: white; color:black;" class="fa fa-minus-square"></i> <span style="background-color: white; color:black;">Data Hutang</span></a></li>      
                
                                
          
          </ul>
        </li>

       

 <li class="treeview">
          <a href="#">
            <i class="fa fa-book"></i> <span style="background-color: white; color:black;">MASTER</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             <li><a href="?page=customer"><i style="background-color: white; color:black;" class="fa fa-users"></i> <span style="background-color: white; color:black;">Customer</span></a></li>
              <li><a href="?page=user"><i style="background-color: white; color:black;" class="fa fa-user"></i> <span style="background-color: white; color:black;">User</span></a></li>
              <li><a href="?page=department"><i style="background-color: white; color:black;" class="fa fa-building"></i> <span style="background-color: white; color:black;">Department</span></a></li>
                  
          </ul>
        </li>

           -->
        </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      
      <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                     
                     <?php

                      $page = @$_GET['page'];
                      $aksi = @$_GET['aksi'];

                    if ($page == 'grup'){
                        if ($aksi == ""){
                             include "page/grup/grup.php";
                                 }elseif ($aksi == "hapus"){
                                    include "page/grup/hapus.php";
                                }
                                        

                     }else if ($page == 'karyawan'){
                        if ($aksi == ""){
                             include "page/karyawan/karyawan.php";
                                 }elseif ($aksi == "hapus"){
                                    include "page/karyawan/hapus.php";
                                }elseif ($aksi == "tambah"){
                                    include "page/karyawan/tambah.php";
                                }elseif ($aksi == "ubah"){
                                    include "page/karyawan/ubah.php";
                                }elseif ($aksi == "view"){
                                    include "page/karyawan/view.php";
                                }elseif ($aksi == "shift"){
                                    include "page/karyawan/shift.php";
                                }elseif ($aksi == "upah"){
                                    include "page/karyawan/upah.php";
                                }

                                        

                     }else if ($page == 'bagian'){
                        if ($aksi == ""){
                             include "page/bagian/bagian.php";
                                 }elseif ($aksi == "hapus"){
                                    include "page/bagian/hapus.php";
                                }elseif ($aksi == "tambah"){
                                    include "page/bagian/tambah.php";
                                }elseif ($aksi == "ubah"){
                                    include "page/bagian/ubah.php";
                                }
                                        

                     }else if ($page == 'jadwal'){
                        if ($aksi == ""){
                             include "page/jadwal/jadwal.php";
                                 }elseif ($aksi == "hapus"){
                                    include "page/jadwal/hapus.php";
                                }elseif ($aksi == "tambah"){
                                    include "page/jadwal/tambah.php";
                                }elseif ($aksi == "ubah"){
                                    include "page/jadwal/ubah.php";
                                }
                                        

                     }else if ($page == 'user'){
                        if ($aksi == ""){
                             include "page/user/user.php";
                                 }elseif ($aksi == "hapus"){
                                    include "page/user/hapus.php";
                                }elseif ($aksi == "tambah"){
                                    include "page/user/tambah.php";
                                }elseif ($aksi == "ubah"){
                                    include "page/user/ubah.php";
                                }elseif ($aksi == "ubahpass"){
                                    include "page/user/ubahpass.php";
                                }
                                        

                     }else if ($page == 'sakit'){
                        if ($aksi == ""){
                             include "page/sakit/sakit.php";
                                 }elseif ($aksi == "hapus"){
                                    include "page/sakit/hapus.php";
                                }elseif ($aksi == "tambah"){
                                    include "page/sakit/tambah.php";
                                }elseif ($aksi == "cari"){
                                    include "page/sakit/karyawan.php";
                                }
                                        

                     }else if ($page == 'cuti'){
                        if ($aksi == ""){
                             include "page/cuti/cuti.php";
                                 }elseif ($aksi == "hapus"){
                                    include "page/cuti/hapus.php";
                                }elseif ($aksi == "tambah"){
                                    include "page/cuti/tambah.php";
                                }elseif ($aksi == "cari"){
                                    include "page/cuti/karyawan.php";
                                }
                                        

                     }else if ($page == 'alfa'){
                        if ($aksi == ""){
                             include "page/alfa/alfa.php";
                                 }elseif ($aksi == "hapus"){
                                    include "page/alfa/hapus.php";
                                }elseif ($aksi == "tambah"){
                                    include "page/alfa/tambah.php";
                                }elseif ($aksi == "cari"){
                                    include "page/alfa/karyawan.php";
                                }
                                        

                     }else if ($page == 'ijin'){
                        if ($aksi == ""){
                             include "page/ijin/ijin.php";
                                 }elseif ($aksi == "hapus"){
                                    include "page/ijin/hapus.php";
                                }elseif ($aksi == "tambah"){
                                    include "page/ijin/tambah.php";
                                }elseif ($aksi == "cari"){
                                    include "page/ijin/karyawan.php";
                                }
                                        

                     }else if ($page == 'payroll'){
                        if ($aksi == ""){
                             include "page/payroll/payroll.php";
                                 }elseif ($aksi == "hapus"){
                                    include "page/payroll/hapus.php";
                                }elseif ($aksi == "tambah"){
                                    include "page/payroll/tambah.php";
                                }elseif ($aksi == "cari"){
                                    include "page/payroll/karyawan.php";
                                }
                                        

                     }else if ($page == 'denda'){
                        if ($aksi == ""){
                             include "page/denda/denda.php";
                                 }
                                        

                     }else if ($page == 'mesin'){
                        if ($aksi == ""){
                             include "page/mesin/mesin.php";
                                 }
                                        

                     }else if ($page == 'generate'){
                        if ($aksi == ""){
                             include "page/generate/generate.php";
                                 }elseif ($aksi == "tarik"){
                                    include "page/generate/tarik-data.php";
                                }
                                  
                                        

                     }else if ($page == 'rkk'){
                        if ($aksi == ""){
                             include "page/rkk/rkk.php";
                                 }elseif ($aksi == "hapus"){
                                    include "page/rkk/hapus.php";
                                }elseif ($aksi == "tambah"){
                                    include "page/rkk/tambah.php";
                                }elseif ($aksi == "ubah"){
                                    include "page/rkk/ubah.php";
                                }elseif ($aksi == "karyawan"){
                                    include "page/rkk/karyawan.php";
                                }elseif ($aksi == "update"){
                                    include "page/rkk/karyawanupdate.php";
                                }elseif ($aksi == "history"){
                                    include "page/rkk/history.php";
                                }elseif ($aksi == "payroll"){
                                    include "page/rkk/payroll.php";
                                }elseif ($aksi == "payrollrkk"){
                                    include "page/rkk/payrollrkk.php";
                                }elseif ($aksi == "kelola"){
                                    include "page/rkk/kelola.php";
                                }elseif ($aksi == "hapusdetail"){
                                    include "page/rkk/hapus.php";
                                }elseif ($aksi == "detail"){
                                    include "page/rkk/detail.php";
                                }elseif ($aksi == "accept"){
                                    include "page/rkk/app.php";
                                }
                                        

                     }else if ($page == 'siac'){
                        if ($aksi == ""){
                             include "page/siac/karyawan.php";
                                 }elseif ($aksi == "sakit"){
                                    include "page/siac/sakit.php";
                                }elseif ($aksi == "ijin"){
                                    include "page/siac/ijin.php";
                                }elseif ($aksi == "alfa"){
                                    include "page/siac/alfa.php";
                                }elseif ($aksi == "cuti"){
                                    include "page/siac/cuti.php";
                                }
                                        

                     }else if ($page == 'subbagian'){
                        if ($aksi == ""){
                             include "page/subbagian/subbagian.php";
                                 }elseif ($aksi == "hapus"){
                                    include "page/subbagian/hapus.php";
                                }elseif ($aksi == "tambah"){
                                    include "page/subbagian/tambah.php";
                                }elseif ($aksi == "ubah"){
                                    include "page/subbagian/ubah.php";
                                }
                                        

                     }elseif ($page=="") {
                    include "home.php";
                    
                     
                     }else if ($page == 'jasa'){
                        if ($aksi == ""){
                             include "page/jasa/jasa.php";
                                 }elseif ($aksi == "hapus"){
                                    include "page/jasa/hapus.php";
                                }elseif ($aksi == "tambah"){
                                    include "page/jasa/tambah.php";
                                }elseif ($aksi == "ubah"){
                                    include "page/jasa/ubah.php";
                                }
                                        

                     }else if ($page == 'dailyactivity'){
                        if ($aksi == ""){
                             include "page/dailyactivity/karyawan.php";
                                 
                                }elseif ($aksi == "tambah"){
                                    include "page/dailyactivity/tambah.php";
                                }elseif ($aksi == "list"){
                                    include "page/dailyactivity/listdaily.php";
                                }
                                        

                     }else if ($page == 'dailyactivityvendor'){
                        if ($aksi == ""){
                             include "page/vendor/vendor.php";
                                 }elseif ($aksi == "tambah"){
                                    include "page/vendor/tambah.php";
                                }
                                        

                     }else if ($page == 'report'){
                        if ($aksi == ""){
                             include "page/report/report.php";
                                 }elseif ($aksi == "daily"){
                                    include "page/report/Daily.php";
                                }
                                        

                     }else if ($page == 'absen'){
                        if ($aksi == ""){
                             include "page/absen/absen.php";
                                 }elseif ($aksi == "cari"){
                                    include "page/absen/karyawan.php";
                                }
                                        

                     }else if ($page == 'realisasi'){
                        if ($aksi == ""){
                             include "page/realisasi/realisasi.php";
                                 }elseif ($aksi == "rkk"){
                                    include "page/realisasi/rkk.php";
                                }elseif ($aksi == "tambah"){
                                    include "page/realisasi/tambah.php";
                                }elseif ($aksi == "kelola"){
                                    include "page/realisasi/kelola.php";
                                }elseif ($aksi == "accept"){
                                    include "page/realisasi/app.php";
                                }elseif ($aksi == "detail"){
                                    include "page/realisasi/detail.php";
                                }elseif ($aksi == "karyawan"){
                                    include "page/realisasi/karyawan.php";
                                }elseif ($aksi == "slip"){
                                    include "page/realisasi/slip.php";
                                }
                                        

                     }

                     
                     ?>

                    </div>
                </div>
            
            
          </div>
          
        </section>
  
    </section>


  
 
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="assets/bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="assets/bower_components/raphael/raphael.min.js"></script>
<script src="assets/bower_components/morris.js/morris.min.js"></script>
<!-- Sparkline -->
<script src="assets/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="assets/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="assets/bower_components/moment/min/moment.min.js"></script>
<script src="assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="assets/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="assets/dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="assets/dist/js/demo.js"></script>
<script src="assets/dataTables/jquery.dataTables.js"></script>
    <script src="assets/dataTables/dataTables.bootstrap.js"></script>
        <script>
            $(document).ready(function () {
                $('#dataTables-example').dataTable();
            });
    </script>

</body>


<style type="text/css">
  .main-sidebar{
    background-color: #00FFFF ;
  }


</style>

</html>
<?php
    
    }else{
        header("location:login.php");
    }


?>