<?php
session_start();

include "koneksi.php";


if ($_SESSION['iduser'] != "" && $_SESSION['nama'] != "") {
  $uid = $_SESSION['iduser'];
  $nama =  $_SESSION['nama'];
  $pass =  $_SESSION['passuser'];


?>
  <!DOCTYPE html>
  <html>

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>PAYROLL</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Font Awesome 5 (AdminLTE 3 default) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- Theme style AdminLTE 3.2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script type="text/javascript" src="chart.js/Chart.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        corePlugins: {
          preflight: false, // Prevent Tailwind from completely breaking AdminLTE/Bootstrap basics
        },
        theme: {
          extend: {
            fontFamily: {
              sans: ['Inter', 'sans-serif']
            },
            colors: {
              brand: {
                50: '#eff6ff',
                100: '#dbeafe',
                500: '#3b82f6',
                600: '#2563eb',
                900: '#1e3a8a',
              },
              sidebar: '#ffffff',
              sidebarhover: '#f8fafc'
            }
          }
        }
      }
    </script>

    <!-- Select2 -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">
  <link rel="stylesheet" href="css/modern.css">
    <style>
      /* Override AdminLTE styles using Tailwind-like clean styles */
      body {
        font-family: 'Inter', sans-serif !important;
        background-color: #f1f5f9 !important;
      }

      .main-header.navbar {
        background-color: white !important;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        border-bottom: 1px solid #e2e8f0;
        padding: 0 1rem;
      }

      .navbar-light .navbar-nav .nav-link {
        color: #475569 !important;
      }

      .brand-link {
        background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%) !important;
        color: white !important;
        font-weight: 700 !important;
        letter-spacing: 0.5px;
        border-bottom: 0 !important;
      }

      .brand-link:hover {
        color: #f8fafc !important;
      }

      /* Sidebar Styling */
      .main-sidebar {
        background-color: #ffffff !important;
        box-shadow: 1px 0 10px rgba(0, 0, 0, 0.05);
        border-right: 1px solid #e2e8f0;
      }

      .nav-sidebar>.nav-header {
        background-color: #ffffff !important;
        color: #94a3b8 !important;
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 15px 15px 10px 15px;
      }

      .nav-sidebar .nav-item>.nav-link {
        color: #475569 !important;
        font-weight: 500;
        transition: all 0.2s ease;
        border-radius: 0.5rem;
        margin-bottom: 2px;
      }

      .nav-sidebar .nav-item:hover>.nav-link,
      .nav-sidebar .nav-item>.nav-link.active {
        background-color: #eff6ff !important;
        color: #2563eb !important;
      }

      .nav-sidebar .nav-item>.nav-link>i {
        color: #64748b !important;
        margin-right: 8px;
        transition: all 0.2s ease;
      }

      .nav-sidebar .nav-item:hover>.nav-link>i,
      .nav-sidebar .nav-item>.nav-link.active>i {
        color: #2563eb !important;
      }

      /* Treeview Styling */
      .nav-treeview {
        background-color: #f8fafc !important;
        padding-left: 10px;
        border-radius: 0.5rem;
      }

      .nav-treeview>.nav-item>.nav-link {
        color: #64748b !important;
        padding: 8px 15px 8px 25px;
        transition: all 0.2s;
      }

      .nav-treeview>.nav-item>.nav-link:hover {
        color: #2563eb !important;
        background-color: transparent !important;
        transform: translateX(4px);
      }

      .nav-treeview>.nav-item>.nav-link>i {
        color: #94a3b8 !important;
      }

      .nav-treeview>.nav-item>.nav-link:hover>i {
        color: #2563eb !important;
      }

      .main-sidebar {
        overflow-x: hidden !important;
      }

      /* Content Block */
      .content-wrapper {
        background-color: #f1f5f9 !important;
      }
    </style>
  </head>

  <body class="hold-transition sidebar-mini layout-fixed">

    <div class="wrapper">

      <!-- Navbar AdminLTE 3 -->
      <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
          </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
          <!-- User Account Dropdown -->
          <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
              <img src="images/iconhr.jpeg" class="user-image img-circle elevation-1" alt="User Image">
              <span class="d-none d-md-inline"><?php echo $_SESSION['nama']; ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="border:none; border-radius:0.5rem; box-shadow:0 10px 15px -3px rgba(0, 0, 0, 0.1);">
              <!-- User image -->
              <li class="user-header bg-primary" style="background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%) !important;">
                <img src="images/iconhr.jpeg" class="img-circle elevation-2" alt="User Image">
                <p>
                  <?php echo $_SESSION['nama']; ?> - Administrator
                  <small>Admin Account</small>
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer bg-light flex justify-between p-3">
                <a href="?page=user&aksi=ubahpass" class="btn btn-default btn-flat rounded">Change Password</a>
                <a href="logout.php" class="btn btn-default btn-flat rounded">Sign out</a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
      <!-- /.navbar -->

      <!-- Main Sidebar Container AdminLTE 3 -->
      <aside class="main-sidebar sidebar-light-primary elevation-4">
        <!-- Brand Logo -->
        <a href="?page=" class="brand-link">
          <img src="images/iconhr.jpeg" alt="Admin Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
          <span class="brand-text font-weight-light"><b>ADMIN</b></span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">

          <!-- Sidebar Menu -->
          <nav class="mt-3">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
              <li class="nav-header">MENU ADMIN</li>

              <li class="nav-item">
                <a href="?page=" class="nav-link">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>Dashboard</p>
                </a>
              </li>

              <?php if ($_SESSION['role'] != 'Owner') : ?>
              <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-cogs"></i>
                  <p>
                    MASTER
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item"><a href="?page=karyawan" class="nav-link"><i class="fas fa-users nav-icon"></i>
                      <p>Karyawan</p>
                    </a></li>
                  <li class="nav-item"><a href="?page=bagian" class="nav-link"><i class="far fa-building nav-icon"></i>
                      <p>Bagian</p>
                    </a></li>

                  <li class="nav-item"><a href="?page=user" class="nav-link"><i class="fas fa-user-shield nav-icon"></i>
                      <p>User</p>
                    </a></li>
                </ul>
              </li>
              <?php endif; ?>

              <li class="nav-item"><a href="?page=rkk" class="nav-link"><i class="nav-icon far fa-calendar-plus"></i>
                  <p>Rencana Upah</p>
                </a></li>
              <li class="nav-item"><a href="?page=realisasi" class="nav-link"><i class="nav-icon fas fa-check-double"></i>
                  <p>Realisasi Upah</p>
                </a></li>
              <li class="nav-item"><a href="?page=realisasi&aksi=karyawan" class="nav-link"><i class="nav-icon fas fa-file-invoice-dollar"></i>
                  <p>Cetak Slip</p>
                </a></li>

              <?php if ($_SESSION['role'] != 'Owner') : ?>
              <li class="nav-item"><a href="?page=jadwal" class="nav-link"><i class="nav-icon far fa-clock"></i>
                  <p>Jadwal</p>
                </a></li>

              <li class="nav-item"><a href="?page=generate&aksi=tarik" class="nav-link"><i class="nav-icon fas fa-download"></i>
                  <p>Tarik Data</p>
                </a></li>
              <li class="nav-item"><a href="?page=denda" class="nav-link"><i class="nav-icon fas fa-exclamation-triangle"></i>
                  <p>Denda</p>
                </a></li>
              <?php endif; ?>

              <?php if ($_SESSION['role'] != 'Owner') : ?>
              <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-clipboard-list"></i>
                  <p>
                    SIA
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item"><a href="?page=siac" class="nav-link"><i class="far fa-star nav-icon"></i>
                      <p>Data Karyawan SIA</p>
                    </a></li>
                  <li class="nav-item"><a href="?page=sakit" class="nav-link"><i class="fas fa-briefcase-medical nav-icon"></i>
                      <p>Sakit</p>
                    </a></li>
                  <li class="nav-item"><a href="?page=ijin" class="nav-link"><i class="fas fa-envelope-open-text nav-icon"></i>
                      <p>Ijin</p>
                    </a></li>
                  <li class="nav-item"><a href="?page=cuti" class="nav-link"><i class="fas fa-plane-departure nav-icon"></i>
                      <p>Cuti</p>
                    </a></li>
                  <li class="nav-item"><a href="?page=alfa" class="nav-link"><i class="fas fa-times-circle nav-icon"></i>
                      <p>Alfa</p>
                    </a></li>
                </ul>
              </li>

              <li class="nav-item"><a href="?page=mesin" class="nav-link"><i class="nav-icon fas fa-cog"></i>
                  <p>Setting Device</p>
                </a></li>
              <?php endif; ?>
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
          </nav>
          <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">

          <div id="page-wrapper">
            <div id="page-inner">
              <div class="row">
                <div class="col-md-12">

                  <?php

                  $page = @$_GET['page'];
                  $aksi = @$_GET['aksi'];

                  if ($page == 'grup') {
                    if ($aksi == "") {
                      include "page/grup/grup.php";
                    } elseif ($aksi == "hapus") {
                      include "page/grup/hapus.php";
                    }
                  } else if ($page == 'karyawan') {
                    if ($aksi == "") {
                      include "page/karyawan/karyawan.php";
                    } elseif ($aksi == "hapus") {
                      include "page/karyawan/hapus.php";
                    } elseif ($aksi == "tambah") {
                      include "page/karyawan/tambah.php";
                    } elseif ($aksi == "ubah") {
                      include "page/karyawan/ubah.php";
                    } elseif ($aksi == "view") {
                      include "page/karyawan/view.php";
                    } elseif ($aksi == "shift") {
                      include "page/karyawan/shift.php";
                    } elseif ($aksi == "upah") {
                      include "page/karyawan/upah.php";
                    }
                  } else if ($page == 'bagian') {
                    if ($aksi == "") {
                      include "page/bagian/bagian.php";
                    } elseif ($aksi == "hapus") {
                      include "page/bagian/hapus.php";
                    } elseif ($aksi == "tambah") {
                      include "page/bagian/tambah.php";
                    } elseif ($aksi == "ubah") {
                      include "page/bagian/ubah.php";
                    }
                  } else if ($page == 'jadwal') {
                    if ($aksi == "") {
                      include "page/jadwal/jadwal.php";
                    } elseif ($aksi == "hapus") {
                      include "page/jadwal/hapus.php";
                    } elseif ($aksi == "tambah") {
                      include "page/jadwal/tambah.php";
                    } elseif ($aksi == "ubah") {
                      include "page/jadwal/ubah.php";
                    }
                  } else if ($page == 'subbagian') {
                    if ($aksi == "hapus") {
                      include "page/subbagian/hapus.php";
                    } elseif ($aksi == "tambah") {
                      include "page/subbagian/tambah.php";
                    } elseif ($aksi == "ubah") {
                      include "page/subbagian/ubah.php";
                    }
                  } else if ($page == 'jabatan') {
                    if ($aksi == "hapus") {
                      include "page/jabatan/hapus.php";
                    } elseif ($aksi == "tambah") {
                      include "page/jabatan/tambah.php";
                    } elseif ($aksi == "ubah") {
                      include "page/jabatan/ubah.php";
                    }
                  } else if ($page == 'agama') {
                    if ($aksi == "hapus") {
                      include "page/agama/hapus.php";
                    } elseif ($aksi == "tambah") {
                      include "page/agama/tambah.php";
                    } elseif ($aksi == "ubah") {
                      include "page/agama/ubah.php";
                    }
                  } else if ($page == 'golongan') {
                    if ($aksi == "hapus") {
                      include "page/golongan/hapus.php";
                    } elseif ($aksi == "tambah") {
                      include "page/golongan/tambah.php";
                    } elseif ($aksi == "ubah") {
                      include "page/golongan/ubah.php";
                    }
                  } else if ($page == 'statuskawin') {
                    if ($aksi == "hapus") {
                      include "page/statuskawin/hapus.php";
                    } elseif ($aksi == "tambah") {
                      include "page/statuskawin/tambah.php";
                    } elseif ($aksi == "ubah") {
                      include "page/statuskawin/ubah.php";
                    }
                  } else if ($page == 'os_dhk') {
                    if ($aksi == "hapus") {
                      include "page/os_dhk/hapus.php";
                    } elseif ($aksi == "tambah") {
                      include "page/os_dhk/tambah.php";
                    } elseif ($aksi == "ubah") {
                      include "page/os_dhk/ubah.php";
                    }
                  } else if ($page == 'user') {
                    if ($aksi == "") {
                      include "page/user/user.php";
                    } elseif ($aksi == "hapus") {
                      include "page/user/hapus.php";
                    } elseif ($aksi == "tambah") {
                      include "page/user/tambah.php";
                    } elseif ($aksi == "ubah") {
                      include "page/user/ubah.php";
                    } elseif ($aksi == "ubahpass") {
                      include "page/user/ubahpass.php";
                    }
                  } else if ($page == 'sakit') {
                    if ($aksi == "") {
                      include "page/sakit/sakit.php";
                    } elseif ($aksi == "hapus") {
                      include "page/sakit/hapus.php";
                    } elseif ($aksi == "tambah") {
                      include "page/sakit/tambah.php";
                    } elseif ($aksi == "cari") {
                      include "page/sakit/karyawan.php";
                    }
                  } else if ($page == 'cuti') {
                    if ($aksi == "") {
                      include "page/cuti/cuti.php";
                    } elseif ($aksi == "hapus") {
                      include "page/cuti/hapus.php";
                    } elseif ($aksi == "tambah") {
                      include "page/cuti/tambah.php";
                    } elseif ($aksi == "cari") {
                      include "page/cuti/karyawan.php";
                    }
                  } else if ($page == 'alfa') {
                    if ($aksi == "") {
                      include "page/alfa/alfa.php";
                    } elseif ($aksi == "hapus") {
                      include "page/alfa/hapus.php";
                    } elseif ($aksi == "tambah") {
                      include "page/alfa/tambah.php";
                    } elseif ($aksi == "cari") {
                      include "page/alfa/karyawan.php";
                    }
                  } else if ($page == 'ijin') {
                    if ($aksi == "") {
                      include "page/ijin/ijin.php";
                    } elseif ($aksi == "hapus") {
                      include "page/ijin/hapus.php";
                    } elseif ($aksi == "tambah") {
                      include "page/ijin/tambah.php";
                    } elseif ($aksi == "cari") {
                      include "page/ijin/karyawan.php";
                    }

                  } else if ($page == 'denda') {
                    if ($aksi == "") {
                      include "page/denda/denda.php";
                    }
                  } else if ($page == 'mesin') {
                    if ($aksi == "") {
                      include "page/mesin/mesin.php";
                    }
                  } else if ($page == 'generate') {
                    if ($aksi == "") {
                      include "page/generate/generate.php";
                    } elseif ($aksi == "tarik") {
                      include "page/generate/tarik-data.php";
                    }
                  } else if ($page == 'rkk') {
                    if ($aksi == "") {
                      include "page/rkk/rkk.php";
                    } elseif ($aksi == "hapus") {
                      include "page/rkk/hapus.php";
                    } elseif ($aksi == "tambah") {
                      include "page/rkk/tambah.php";
                    } elseif ($aksi == "ubah") {
                      include "page/rkk/ubah.php";
                    } elseif ($aksi == "karyawan") {
                      include "page/rkk/karyawan.php";
                    } elseif ($aksi == "update") {
                      include "page/rkk/karyawanupdate.php";
                    } elseif ($aksi == "history") {
                      include "page/rkk/history.php";
                    } elseif ($aksi == "payroll") {
                      include "page/rkk/payroll.php";
                    } elseif ($aksi == "payrollrkk") {
                      include "page/rkk/payrollrkk.php";
                    } elseif ($aksi == "kelola") {
                      include "page/rkk/kelola.php";
                    } elseif ($aksi == "hapusdetail") {
                      include "page/rkk/hapus.php";
                    } elseif ($aksi == "detail") {
                      include "page/rkk/detail.php";
                    } elseif ($aksi == "accept") {
                      include "page/rkk/app.php";
                    }
                  } else if ($page == 'siac') {
                    if ($aksi == "") {
                      include "page/siac/karyawan.php";
                    } elseif ($aksi == "sakit") {
                      include "page/siac/sakit.php";
                    } elseif ($aksi == "ijin") {
                      include "page/siac/ijin.php";
                    } elseif ($aksi == "alfa") {
                      include "page/siac/alfa.php";
                    } elseif ($aksi == "cuti") {
                      include "page/siac/cuti.php";
                    }
                  } elseif ($page == "") {
                    include "home.php";

                  } else if ($page == 'dailyactivity') {
                    if ($aksi == "") {
                      include "page/dailyactivity/karyawan.php";
                    } elseif ($aksi == "tambah") {
                      include "page/dailyactivity/tambah.php";
                    } elseif ($aksi == "list") {
                      include "page/dailyactivity/listdaily.php";
                    }
                  } else if ($page == 'dailyactivityvendor') {
                    if ($aksi == "") {
                      include "page/vendor/vendor.php";
                    } elseif ($aksi == "tambah") {
                      include "page/vendor/tambah.php";
                    }
                  } else if ($page == 'report') {
                    if ($aksi == "") {
                      include "page/report/report.php";
                    } elseif ($aksi == "daily") {
                      include "page/report/Daily.php";
                    }

                  } else if ($page == 'realisasi') {
                    if ($aksi == "") {
                      include "page/realisasi/realisasi.php";
                    } elseif ($aksi == "rkk") {
                      include "page/realisasi/rkk.php";
                    } elseif ($aksi == "tambah") {
                      include "page/realisasi/tambah.php";
                    } elseif ($aksi == "kelola") {
                      include "page/realisasi/kelola.php";
                    } elseif ($aksi == "accept") {
                      include "page/realisasi/app.php";
                    } elseif ($aksi == "detail") {
                      include "page/realisasi/detail.php";
                    } elseif ($aksi == "karyawan") {
                      include "page/realisasi/karyawan.php";
                    } elseif ($aksi == "slip") {
                      include "page/realisasi/slip.php";
                    }
                  }


                  ?>

                </div>
              </div>


            </div>

        </section>

        </section>




        <!-- jQuery (Minimal load for inner pages) -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
        <!-- AdminLTE 3 App -->
        <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

        <!-- The inner pages might still rely on Old Bootstrap DataTables, we keep the JS locally if possible, but load newer DT via CDN if needed. For now keeping original scripts but omitting old AdminLTE 2. -->
        <!-- jQuery UI 1.11.4 -->
        <script src="assets/bower_components/jquery-ui/jquery-ui.min.js"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
          $.widget.bridge('uibutton', $.ui.button);
        </script>
        <!-- Bootstrap 3.3.7 -->

        <script src="assets/dataTables/jquery.dataTables.js"></script>
        <script src="assets/dataTables/dataTables.bootstrap.js"></script>
        <!-- Select2 -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <script>
    $(document).ready(function() {
      $('#dataTables-example').dataTable();

      // Global Select2 Initialization with Quick Delete
      $('.select2-manage').each(function() {
        const $select = $(this);
        const placeholder = $select.data('placeholder') || '- Pilih -';
        const deleteUrl = $select.data('delete-route');

        $select.select2({
          theme: 'bootstrap4',
          placeholder: placeholder,
          allowClear: true,
          templateResult: formatState,
          templateSelection: formatSelection
        });

        function formatState(state) {
          if (!state.id || state.id === 'add_new' || !deleteUrl) return state.text;
          
          const $state = $(
            '<div class="flex justify-between items-center w-full group">' +
            '<span class="text-gray-700">' + state.text + '</span>' +
            '<button type="button" class="btn-delete-item ml-2 h-6 w-6 flex items-center justify-center rounded-full text-gray-400 hover:text-rose-600 hover:bg-rose-50 transition-all duration-200 opacity-0 group-hover:opacity-100" data-id="' + state.id + '">' +
            '<i class="fas fa-times text-[10px]"></i>' +
            '</button>' +
            '</div>'
          );

          $state.find('.btn-delete-item').on('mousedown', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const $btn = $(this);
            const itemId = $(state.element).data('id') || state.id; // Use data-id or fallback to value
            const itemText = state.text;

            if (confirm('Hapus "' + itemText + '" dari master data?')) {
              $.post('page/ajax/hapus_master.php', {
                id: itemId,
                route: deleteUrl
              }, function(res) {
                if (res.success) {
                  $select.find('option[data-id="' + itemId + '"], option[value="' + itemId + '"]').remove();
                  $select.trigger('change');
                  // To force the results list to refresh, we briefly close/open or rebuild
                  const currentOpen = $select.data('select2').isOpen();
                  if (currentOpen) {
                    $select.select2('close').select2('open');
                  }
                } else {
                  alert('Gagal menghapus: ' + (res.message || 'Data mungkin sedang digunakan.'));
                }
              }, 'json');
            }
          });

          return $state;
        }

        function formatSelection(state) {
          return state.text;
        }
      });

    });
  </script>

  </body>


  <style type="text/css">
    /* Removed manual background-color here as it's governed by Tailwind now */
  </style>

  </html>
<?php

} else {
  header("location:login.php");
}


?>