   <?php
  $tampildetail=$koneksi->query("select COUNT(id_karyawan) as jmlkaryawan from ms_karyawan ");
  $datadetail=$tampildetail->fetch_assoc();
  $jmlkaryawan = $datadetail['jmlkaryawan'];
 ?>

<!-- Content Header (Page header) -->
<div class="content-header pt-6 pb-4 px-4">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-2xl font-bold text-slate-800 tracking-tight">Dashboard Overview</h1>
        <p class="text-sm text-slate-500 mt-1">Payroll & HR System Analytics</p>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right bg-transparent text-sm">
          <li class="breadcrumb-item"><a href="#" class="text-brand-600 hover:text-brand-800 transition-colors">Home</a></li>
          <li class="breadcrumb-item active text-slate-500">Dashboard</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content px-4 pb-6">
  <div class="container-fluid">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
   
      <!-- Widget 1: Total Karyawan -->
      <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg border border-slate-100 p-6 flex flex-row items-center justify-between transition-all duration-300 transform hover:-translate-y-1 group relative overflow-hidden">
          <!-- decorative gradient blob -->
          <div class="absolute -right-6 -top-6 w-24 h-24 bg-brand-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
          
          <div class="relative z-10">
              <p class="text-xs font-bold text-slate-400 mb-1 uppercase tracking-wider">Total Karyawan</p>
              <h4 class="text-4xl font-extrabold text-slate-800 group-hover:text-brand-600 transition-colors"><?php echo $jmlkaryawan ; ?></h4>
          </div>
          <div class="relative z-10 w-16 h-16 rounded-2xl bg-gradient-to-br from-brand-100 to-brand-50 flex items-center justify-center text-brand-600 group-hover:from-brand-500 group-hover:to-brand-600 group-hover:text-white group-hover:shadow-md transition-all duration-300">
              <i class="fas fa-users text-2xl"></i>
          </div>
          
          <!-- explicit hit area taking whole card -->
          <a href="?page=karyawan" class="absolute inset-0 z-20"><span class="sr-only">View Employees</span></a>
      </div>
      
      <!-- Widget Placeholder 1 -->
      <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-row items-center justify-between opacity-60">
          <div>
              <p class="text-xs font-bold text-slate-400 mb-1 uppercase tracking-wider">Pending RKK</p>
              <h4 class="text-4xl font-extrabold text-slate-300">--</h4>
          </div>
          <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-300">
              <i class="fas fa-clipboard-check text-2xl"></i>
          </div>
      </div>

    </div>
  </div><!-- /.container-fluid -->
</section>
<!-- /.content -->