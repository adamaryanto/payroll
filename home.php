<?php
$tampildetail = $koneksi->query("select COUNT(id_karyawan) as jmlkaryawan from ms_karyawan ");
$datadetail = $tampildetail->fetch_assoc();
$jmlkaryawan = $datadetail['jmlkaryawan'];

// Rencana Upah Terbaru
$where_rkk = (strtolower($role) == 'owner' || strtolower($role) == 'admin master') ? "" : " WHERE status_rkk > 0 ";
$rkk_q = $koneksi->query("SELECT A.*, (SELECT COUNT(id_rkk_detail) FROM tb_rkk_detail WHERE id_rkk = A.id_rkk AND status_rkk != 'Digantikan') as jml, (SELECT SUM(upah) FROM tb_rkk_detail WHERE id_rkk = A.id_rkk AND status_rkk != 'Digantikan') as ttl FROM tb_rkk A $where_rkk ORDER BY tgl_rkk DESC, id_rkk DESC LIMIT 1");
$rkk_d = $rkk_q->fetch_assoc();
if ($rkk_d) {
    $rkk_terbaru = $rkk_d['ttl'] ?? 0;
    $rkk_jmlkaryawan = $rkk_d['jml'] ?? 0;
    $rkk_id = $rkk_d['id_rkk'];
    $rkk_tgl = $rkk_d['tgl_rkk'];
    $rkk_status = $rkk_d['status_rkk'];

    // Ambil ID Boneless utama
    $bnl_q = $koneksi->query("SELECT A.id_boneless, A.jumlah_mobil, B.biaya_mobil 
        FROM tb_boneless A 
        LEFT JOIN tb_biayamobil B ON A.id_biayamobil = B.id_biayamobil 
        WHERE A.id_rkk = '$rkk_id' LIMIT 1");
    $bnl_d = $bnl_q->fetch_assoc();
    $boneless_id = $bnl_d['id_boneless'] ?? '';
    $jumlah_mobil = $bnl_d['jumlah_mobil'] ?? 0;
    $biaya_mobil = $bnl_d['biaya_mobil'] ?? 0;

    // Inisialisasi variabel penampung
    $total_boneless_plus = 0;
    $total_boneless_minus = 0;
    $total_boneless_item = 0;
    $total_boneless_rencana = 0;

    if ($boneless_id) {
        $query_detail = $koneksi->query("SELECT 
            SUM(total) as total_akhir,
            SUM(CASE WHEN jenis = 'plus' THEN total ELSE 0 END) as total_plus,
            SUM(CASE WHEN jenis = 'minus' THEN total ELSE 0 END) as total_minus
            FROM tb_boneless_detail 
            WHERE id_boneless = '$boneless_id'");

        $data_detail = $query_detail->fetch_assoc();
        $total_boneless_item = $data_detail['total_akhir'] ?? 0;
        $total_boneless_plus = $data_detail['total_plus'] ?? 0;
        $total_boneless_minus = $data_detail['total_minus'] ?? 0;

        // Total Rencana Boneless (Mobil + Plus - Minus)
        $total_boneless_rencana = ($jumlah_mobil * $biaya_mobil) + $total_boneless_plus - $total_boneless_minus;

        // Tanpa Mesin = Nilai Base + Biaya Plus (Beban manual)
        $tampilan_tanpa_mesin = $rkk_terbaru + $total_boneless_plus;

        // Dengan Mesin = Nilai Base - Biaya Minus (Efisiensi mesin)
        $tampilan_dengan_mesin = $rkk_terbaru - $total_boneless_minus;

        // Total Penghematan
        $total_hemat = $tampilan_tanpa_mesin - $tampilan_dengan_mesin;
    }
} else {
    $rkk_terbaru = 0;
    $rkk_jamkerja = '';
    $rkk_jmlkaryawan = 0;
    $rkk_id = '';
    $rkk_status = '';
    $rkk_tgl = '';
    $boneless_id = '';
    $jumlah_mobil = 0;
    $biaya_mobil = 0;
    $total_boneless_item = 0;
    $total_boneless_rencana = 0;
    $total_boneless_plus = 0;
    $total_boneless_minus = 0;
}

// Realisasi Upah Terbaru
$where_real = (strtolower($role) == 'owner' || strtolower($role) == 'admin master') ? "" : " WHERE status_realisasi > 0 ";
$real_q = $koneksi->query("SELECT A.*, (SELECT COUNT(id_realisasi_detail) FROM tb_realisasi_detail WHERE id_realisasi = A.id_realisasi) as jml, (SELECT SUM(r_upah) FROM tb_realisasi_detail WHERE id_realisasi = A.id_realisasi) as ttl FROM tb_realisasi A $where_real ORDER BY tgl_realisasi DESC, id_realisasi DESC LIMIT 1");
$real_d = $real_q->fetch_assoc();
if ($real_d) {
    $real_terbaru = $real_d['ttl'] ?? 0;
    $real_jamkerja = $real_d['jam_kerja'] ?? '';
    $real_jmlkaryawan = $real_d['jml'] ?? 0;
    $real_id = $real_d['id_realisasi'] ?? '';
    $real_status = $real_d['status_realisasi'] ?? '';
    $real_tgl = $real_d['tgl_realisasi'] ?? '';
} else {
    $real_terbaru = 0;
    $real_jamkerja = '';
    $real_jmlkaryawan = 0;
    $real_id = '';
    $real_status = '';
    $real_tgl = '';
}
?>

<div class="content-header pt-6 pb-4 px-4">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-2xl font-bold text-slate-800 tracking-tight">Dashboard Overview</h1>
                <p class="text-sm text-slate-500 mt-1">Payroll & HR System Analytics</p>
            </div>
            <div class="col-sm-6 mt-3 sm:mt-0">
                <ol class="breadcrumb float-sm-right bg-transparent text-sm p-0 m-0">
                    <li class="breadcrumb-item"><a href="#" class="text-brand-600 hover:text-brand-800 transition-colors">Home</a></li>
                    <li class="breadcrumb-item active text-slate-500">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content px-4 pb-6">
    <div class="container-fluid">

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex flex-col justify-between transition-all duration-200 hover:shadow-md relative text-center">

                <div class="flex flex-col items-center justify-center mb-4">
                    <div class="p-3 bg-brand-50 rounded-full text-brand-500 mb-3 inline-flex items-center justify-center">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-slate-500 mb-1">Total Karyawan</h3>
                        <div class="text-3xl font-bold text-slate-800 tracking-tight"><?php echo number_format($jmlkaryawan, 0, ',', '.'); ?> Orang</div>
                    </div>
                </div>

                <a href="?page=karyawan" class="mt-auto px-4 py-2.5 sm:py-2 bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100 hover:text-slate-800 rounded-lg text-sm font-medium transition-colors text-center">
                    Lihat Karyawan
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex flex-col justify-between transition-all duration-200 hover:shadow-md text-center relative">

                <div class="flex flex-col items-center justify-center mb-4">
                    <div class="p-3 bg-orange-50 rounded-full text-orange-500 mb-3 inline-flex items-center justify-center">
                        <i class="far fa-calendar-plus text-xl"></i>
                    </div>
                    <div class="text-center w-full">
                        <h3 class="text-sm font-medium text-slate-500 mb-4">Rencana Upah (<?php echo date('d-m-Y', strtotime($rkk_tgl)); ?>)</h3>

                        <!-- Comparison Section -->
                        <div class="grid grid-cols-1 gap-3 mb-5">
                            <div class="p-3 bg-rose-50 rounded-xl border border-rose-100">
                                <span class="text-[10px] uppercase font-bold text-rose-400 block mb-1">Tanpa Mesin Boneless</span>
                                <div class="text-xl font-bold text-rose-700">
                                    Rp <?php echo number_format($tampilan_tanpa_mesin, 0, ',', '.'); ?>
                                </div>
                            </div>

                            <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100 relative overflow-hidden">
                                <span class="text-[10px] uppercase font-bold text-emerald-500 block mb-1">Dengan Mesin Boneless</span>
                                <div class="text-2xl font-bold text-slate-800 tracking-tight">
                                    Rp <?php echo number_format($tampilan_dengan_mesin, 0, ',', '.'); ?>
                                </div>
                                <?php if ($total_hemat > 0): ?>
                                    <div class="text-[11px] font-semibold text-emerald-600 mt-1">
                                        <i class="fas fa-magic mr-1"></i> Hemat: Rp <?php echo number_format($total_hemat, 0, ',', '.'); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Breakdown Section -->
                        <div class="grid grid-cols-2 gap-2 mb-5 px-1">
                            <div class="p-2.5 bg-slate-50 rounded-lg border border-slate-100">
                                <span class="text-[9px] uppercase font-bold text-slate-400 block mb-1">Upah Pabrik</span>
                                <div class="text-sm font-bold text-slate-700">
                                    Rp <?php echo number_format($rkk_terbaru, 0, ',', '.'); ?>
                                </div>
                            </div>
                            <div class="p-2.5 bg-blue-50/50 rounded-lg border border-blue-100">
                                <span class="text-[9px] uppercase font-bold text-blue-500 block mb-1">Rencana Boneless</span>
                                <div class="text-sm font-bold text-blue-600">
                                    Rp <?php echo number_format($total_boneless_rencana, 0, ',', '.'); ?>
                                </div>
                                <span class="text-[8px] text-blue-400 mt-0.5 block font-medium">(Item + Biaya Mobil)</span>
                            </div>
                        </div>

                        <div class="flex flex-row justify-center border-t border-b border-slate-100 py-3 mb-5">
                            <div class="flex-1">
                                <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Info Tambahan</div>
                                <div class="text-sm font-medium text-slate-700 mt-0.5">
                                    <i class="fas fa-users mr-1.5 text-slate-400"></i> <?php echo number_format($rkk_jmlkaryawan, 0, ',', '.'); ?> Orang
                                    <span class="mx-2 text-slate-200">|</span>
                                    <i class="fas fa-car mr-1.5 text-slate-400"></i> <?php echo $jumlah_mobil; ?> Mobil
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-row gap-2 mt-auto">
                        <a href="excelrkk.php?id=<?php echo $rkk_id; ?>" class="px-2 py-2 border border-slate-200 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-50 transition-colors flex items-center justify-center flex-1">
                            <i class="fas fa-print mr-2"></i> Cetak
                        </a>

                        <?php if ($rkk_status == "0") {
                            if (strtolower($role) == 'owner') { ?>
                                <a href="?page=rkk&aksi=accept&id=<?php echo $rkk_id; ?>&iddetail=app" onclick="return confirm('Approve?');" class="px-2 py-2 bg-slate-800 text-white rounded-lg text-sm font-bold hover:bg-slate-700 transition-colors flex items-center justify-center flex-[1.5] uppercase">
                                    <i class="fas fa-check-circle mr-2"></i> Approve
                                </a>
                            <?php } else { ?>
                                <a href="?page=rkk&aksi=accept&id=<?php echo $rkk_id; ?>&iddetail=pro" onclick="return confirm('Propose?');" class="px-2 py-2 bg-amber-500 text-white rounded-lg text-sm font-bold hover:bg-amber-600 transition-colors flex items-center justify-center flex-[1.5] uppercase">
                                    <i class="fas fa-paper-plane mr-2"></i> Propose
                                </a>
                            <?php }
                        } elseif ($rkk_status == "1") { ?>
                            <a href="?page=rkk&aksi=accept&id=<?php echo $rkk_id; ?>&iddetail=unpro" class="px-2 py-2 bg-rose-500 text-white rounded-lg text-sm font-bold flex-[1.5] uppercase">
                                <i class="fas fa-undo mr-2"></i> Unpropose
                            </a>
                        <?php } else { ?>
                            <div class="px-2 py-2 bg-emerald-50 text-emerald-700 rounded-lg text-sm font-bold border border-emerald-100 flex-[1.5] uppercase cursor-default flex items-center justify-center">
                                <i class="fas fa-check-double mr-2"></i> Approved
                            </div>
                        <?php } ?>
                    </div>

                    <div class="flex flex-row gap-2 mt-2">
                        <a href="?page=rkk&aksi=kelola&id=<?php echo $rkk_id; ?>" class="px-3 py-2 text-slate-600 hover:text-brand-600 hover:bg-brand-50 border border-slate-200 rounded-lg text-xs font-semibold transition-colors flex items-center justify-center flex-1">
                            <i class="fas fa-list-ul mr-1.5 text-[10px]"></i> Detail RKK
                        </a>

                        <?php if ($boneless_id) { ?>
                            <a href="?page=boneless&aksi=ubah&id=<?php echo $boneless_id; ?>&ref=home" class="px-3 py-2 text-blue-600 hover:bg-blue-50 border border-blue-100 bg-blue-50 rounded-lg text-xs font-semibold flex items-center justify-center flex-1">
                                <i class="fas fa-edit mr-1.5 text-[10px]"></i> Show Boneless
                            </a>
                        <?php } else { ?>
                            <a href="?page=boneless&aksi=tambah&tgl=<?php echo $rkk_tgl; ?>&ref=home" class="px-3 py-2 text-slate-400 hover:bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold flex items-center justify-center flex-1">
                                <i class="fas fa-plus mr-1.5 text-[10px]"></i> Add Boneless
                            </a>
                        <?php } ?>
                    </div>
                </div>

            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex flex-col justify-between transition-all duration-200 hover:shadow-md relative text-center">

                <div class="flex flex-col items-center justify-center mb-4">
                    <div class="p-3 bg-emerald-50 rounded-full text-emerald-500 mb-3 inline-flex items-center justify-center">
                        <i class="fas fa-check-double text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-slate-500 mb-1">Realisasi Upah (<?php echo date('d-m-Y', strtotime($real_tgl)); ?>)</h3>
                        <div class="text-3xl font-bold text-slate-800 tracking-tight">Rp <?php echo number_format($real_terbaru, 0, ',', '.'); ?></div>
                    </div>
                </div>

                <div class="flex flex-row space-x-2 sm:space-x-6 mb-5 justify-center border-t border-b border-slate-100 py-3">
                    <div class="flex-1">
                        <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Total Pekerja</div>
                        <div class="text-sm font-medium text-slate-700 mt-0.5"><?php echo number_format($real_jmlkaryawan, 0, ',', '.'); ?> Orang</div>
                    </div>
                </div>

                <div class="flex flex-row gap-1.5 sm:gap-2 mt-auto justify-center">
                    <a href="excelrealisasi.php?id=<?php echo $real_id; ?>" class="px-1.5 py-2 sm:px-4 border border-slate-200 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-50 hover:text-slate-800 transition-colors flex items-center justify-center flex-1 tracking-tight sm:tracking-normal">
                        <i class="fas fa-file-invoice-dollar mr-1 sm:mr-2"></i> Payroll
                    </a>

                    <?php if ($real_status == 0) { ?>
                        <a href="?page=realisasi&aksi=accept&id=<?php echo $real_id; ?>&iddetail=app" onclick="return confirm('Apakah Anda yakin ingin Approve Realisasi Upah ini?');" class="px-1.5 py-2 sm:px-4 bg-slate-800 text-white rounded-lg text-sm font-bold hover:bg-slate-700 transition-colors flex items-center justify-center flex-[1.2] shadow-sm uppercase tracking-tight sm:tracking-normal">
                            <i class="fas fa-check-circle mr-1 sm:mr-2"></i> Approve
                        </a>
                        <?php } elseif ($real_status == 2) {
                        if (strtolower($role) == 'owner' || strtolower($role) == 'admin master') { ?>
                            <a href="?page=realisasi&aksi=accept&id=<?php echo $real_id; ?>&iddetail=unapp" onclick="return confirm('Apakah Anda yakin ingin Unapprove Realisasi Upah ini?');" class="px-1.5 py-2 sm:px-4 bg-rose-50 text-rose-700 border border-rose-200 rounded-lg text-sm font-bold hover:bg-rose-100 transition-colors flex items-center justify-center flex-[1.2] uppercase tracking-tighter shadow-sm">
                                <i class="fas fa-undo mr-1 sm:mr-2"></i> Unapprove
                            </a>
                        <?php } else { ?>
                            <div class="px-1.5 py-2 sm:px-4 bg-emerald-50 text-emerald-700 rounded-lg text-[13px] sm:text-sm font-bold flex items-center justify-center flex-[1.2] cursor-default border border-emerald-100 uppercase tracking-tighter">
                                <i class="fas fa-check-double mr-1 sm:mr-2"></i> Approved
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>

                <div class="flex flex-row gap-2 mt-1 justify-center">
                    <a href="?page=realisasi&aksi=kelola&id=<?php echo $real_id; ?>" class="px-4 py-2 text-slate-600 hover:text-brand-600 hover:bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold transition-colors flex items-center justify-center flex-1">
                        <i class="fas fa-clipboard-list mr-1.5 text-[10px]"></i> Detail Realisasi
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>