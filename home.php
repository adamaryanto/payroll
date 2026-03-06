   <?php
  $tampildetail=$koneksi->query("select COUNT(id_karyawan) as jmlkaryawan from ms_karyawan ");
  $datadetail=$tampildetail->fetch_assoc();
  $jmlkaryawan = $datadetail['jmlkaryawan'];

  // Rencana Upah Terbaru
  $rkk_q = $koneksi->query("SELECT A.*, (SELECT COUNT(id_rkk_detail) FROM tb_rkk_detail WHERE id_rkk = A.id_rkk) as jml, (SELECT SUM(upah) FROM tb_rkk_detail WHERE id_rkk = A.id_rkk) as ttl FROM tb_rkk A ORDER BY tgl_rkk DESC, id_rkk DESC LIMIT 1");
  $rkk_d = $rkk_q->fetch_assoc();
  if($rkk_d) {
      $rkk_terbaru = $rkk_d['ttl'] ?? 0;
      $rkk_jamkerja = $rkk_d['jam_kerja'] ?? '';
      $rkk_jmlkaryawan = $rkk_d['jml'] ?? 0;
      $rkk_id = $rkk_d['id_rkk'] ?? '';
      $rkk_status = $rkk_d['status_rkk'] ?? '';
      $rkk_tgl = $rkk_d['tgl_rkk'] ?? '';
  } else {
      $rkk_terbaru = 0; $rkk_jamkerja = ''; $rkk_jmlkaryawan = 0; $rkk_id = ''; $rkk_status = ''; $rkk_tgl = '';
  }

  // Realisasi Upah Terbaru
  $real_q = $koneksi->query("SELECT A.*, (SELECT COUNT(id_realisasi_detail) FROM tb_realisasi_detail WHERE id_realisasi = A.id_realisasi) as jml, (SELECT SUM(r_upah) FROM tb_realisasi_detail WHERE id_realisasi = A.id_realisasi) as ttl FROM tb_realisasi A ORDER BY tgl_realisasi DESC, id_realisasi DESC LIMIT 1");
  $real_d = $real_q->fetch_assoc();
  if($real_d) {
      $real_terbaru = $real_d['ttl'] ?? 0;
      $real_jamkerja = $real_d['jam_kerja'] ?? '';
      $real_jmlkaryawan = $real_d['jml'] ?? 0;
      $real_id = $real_d['id_realisasi'] ?? '';
      $real_status = $real_d['status_realisasi'] ?? '';
      $real_tgl = $real_d['tgl_realisasi'] ?? '';
  } else {
      $real_terbaru = 0; $real_jamkerja = ''; $real_jmlkaryawan = 0; $real_id = ''; $real_status = ''; $real_tgl = '';
  }
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
    <!-- Updated the grid from lg:grid-cols-4 to lg:grid-cols-3 so three cards fit evenly -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
   
      <!-- Widget 1: Total Karyawan -->
      <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex flex-col justify-between transition-all duration-200 hover:shadow-md md:col-span-2 lg:col-span-1 relative text-center">
          
          <div class="flex flex-col items-center justify-center mb-4">
              <div class="p-3 bg-brand-50 rounded-full text-brand-500 mb-3 inline-flex items-center justify-center">
                  <i class="fas fa-users text-xl"></i>
              </div>
              <div>
                  <h3 class="text-sm font-medium text-slate-500 mb-1">Total Karyawan</h3>
                  <div class="text-3xl font-bold text-slate-800 tracking-tight"><?php echo number_format($jmlkaryawan, 0, ',', '.'); ?> Orang</div>
              </div>
          </div>
          
          <a href="?page=karyawan" class="mt-auto px-4 py-2 bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100 hover:text-slate-800 rounded-lg text-sm font-medium transition-colors text-center">
              Lihat Anggota
          </a>
      </div>
      
      <!-- Widget 2: Rencana Upah Terbaru -->
      <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex flex-col justify-between transition-all duration-200 hover:shadow-md md:col-span-1 lg:col-span-1 relative text-center">
          
          <div class="flex flex-col items-center justify-center mb-4">
              <div class="p-3 bg-orange-50 rounded-full text-orange-500 mb-3 inline-flex items-center justify-center">
                  <i class="far fa-calendar-plus text-xl"></i>
              </div>
              <div>
                  <h3 class="text-sm font-medium text-slate-500 mb-1">Rencana Upah (<?php echo $rkk_tgl; ?>)</h3>
                  <div class="text-3xl font-bold text-slate-800 tracking-tight">Rp <?php echo number_format($rkk_terbaru, 0, ',', '.'); ?></div>
              </div>
          </div>
          
          <div class="flex flex-row space-x-6 mb-5 justify-center border-t border-b border-slate-100 py-3">
              <div class="flex-1">
                  <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Jam Kerja</div>
                  <div class="text-sm font-medium text-slate-700 mt-0.5"><?php echo htmlspecialchars($rkk_jamkerja); ?></div>
              </div>
              <div class="w-px bg-slate-100"></div>
              <div class="flex-1">
                  <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Total Pekerja</div>
                  <div class="text-sm font-medium text-slate-700 mt-0.5"><?php echo number_format($rkk_jmlkaryawan,0,',','.'); ?> Orang</div>
              </div>
          </div>
          
          <div class="flex flex-row gap-2 mt-auto justify-center">
              <!-- Outline styles for secondary actions -->
              <a href="excelrkk.php?id=<?php echo $rkk_id; ?>" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-50 hover:text-slate-800 transition-colors flex items-center justify-center flex-1">
                  <i class="fas fa-print mr-2"></i> Cetak
              </a>
              
              <?php if($rkk_status != "1") { ?>
              <!-- Subtle primary style for primary action -->
              <a href="?page=rkk&aksi=accept&id=<?php echo $rkk_id; ?>&iddetail=app" onclick="return confirm('Apakah Anda yakin ingin Approve Rencana Upah ini?');" class="px-4 py-2 bg-slate-800 text-white rounded-lg text-sm font-medium hover:bg-slate-700 transition-colors flex items-center justify-center flex-[1.5]">
                  <i class="fas fa-check-circle mr-2 opacity-80"></i> Approve
              </a>
              <?php } else { ?>
               <div class="px-4 py-2 bg-emerald-50 text-emerald-700 rounded-lg text-sm font-medium flex items-center justify-center flex-[1.5] cursor-default border border-emerald-100">
                  <i class="fas fa-check-circle mr-2"></i> Approved
              </div>
              <?php } ?>
              
              <a href="?page=rkk&aksi=kelola&id=<?php echo $rkk_id; ?>" class="px-4 py-2 text-slate-500 hover:text-brand-600 hover:bg-brand-50 rounded-lg text-sm font-medium transition-colors flex items-center justify-center flex-1">
                  Detail <i class="fas fa-chevron-right ml-1 text-[10px]"></i>
              </a>
          </div>
      </div>

      <!-- Widget 3: Realisasi Upah Terbaru -->
      <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex flex-col justify-between transition-all duration-200 hover:shadow-md md:col-span-1 lg:col-span-1 relative text-center">
          
          <div class="flex flex-col items-center justify-center mb-4">
              <div class="p-3 bg-emerald-50 rounded-full text-emerald-500 mb-3 inline-flex items-center justify-center">
                  <i class="fas fa-check-double text-xl"></i>
              </div>
              <div>
                  <h3 class="text-sm font-medium text-slate-500 mb-1">Realisasi Upah (<?php echo $real_tgl; ?>)</h3>
                  <div class="text-3xl font-bold text-slate-800 tracking-tight">Rp <?php echo number_format($real_terbaru, 0, ',', '.'); ?></div>
              </div>
          </div>
          
          <div class="flex flex-row space-x-6 mb-5 justify-center border-t border-b border-slate-100 py-3">
              <div class="flex-1">
                  <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Jam Kerja</div>
                  <div class="text-sm font-medium text-slate-700 mt-0.5"><?php echo htmlspecialchars($real_jamkerja); ?></div>
              </div>
              <div class="w-px bg-slate-100"></div>
              <div class="flex-1">
                  <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Total Pekerja</div>
                  <div class="text-sm font-medium text-slate-700 mt-0.5"><?php echo number_format($real_jmlkaryawan,0,',','.'); ?> Orang</div>
              </div>
          </div>
          
          <div class="flex flex-row gap-2 mt-auto justify-center">
              <a href="excelrealisasi.php?id=<?php echo $real_id; ?>" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-50 hover:text-slate-800 transition-colors flex items-center justify-center flex-1">
                  <i class="fas fa-file-invoice-dollar mr-2"></i> Payroll
              </a>
              
              <?php if($real_status != 'approve') { ?>
              <a href="?page=realisasi&aksi=accept&id=<?php echo $real_id; ?>" onclick="return confirm('Apakah Anda yakin ingin Approve Realisasi Upah ini?');" class="px-4 py-2 bg-slate-800 text-white rounded-lg text-sm font-medium hover:bg-slate-700 transition-colors flex items-center justify-center flex-[1.2]">
                  <i class="fas fa-check-circle mr-2 opacity-80"></i> Approve
              </a>
              <?php } else { ?>
               <a href="?page=realisasi&aksi=unapprove&id=<?php echo $real_id; ?>" onclick="return confirm('Apakah Anda yakin ingin Unapprove Realisasi Upah ini?');" class="px-4 py-2 bg-rose-50 text-rose-700 border border-rose-200 rounded-lg text-sm font-medium hover:bg-rose-100 transition-colors flex items-center justify-center flex-[1.2]">
                  <i class="fas fa-undo mr-2"></i> Unapprove
              </a>
              <?php } ?>
              
              <a href="?page=realisasi&aksi=kelola&id=<?php echo $real_id; ?>" class="px-4 py-2 text-slate-500 hover:text-brand-600 hover:bg-brand-50 rounded-lg text-sm font-medium transition-colors flex items-center justify-center flex-1">
                  Detail <i class="fas fa-chevron-right ml-1 text-[10px]"></i>
              </a>
          </div>
      </div>

    </div>
  </div><!-- /.container-fluid -->
</section>
<!-- /.content -->