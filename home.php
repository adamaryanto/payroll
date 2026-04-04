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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col h-full transition-all duration-300 hover:shadow-lg hover:-translate-y-1 group">
                <div class="flex flex-col items-center justify-center mb-6">
                    <div class="p-4 bg-brand-50 rounded-2xl text-brand-600 mb-4 transition-transform group-hover:scale-110 duration-300">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                    <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-2">Total Karyawan</h3>
                    <div class="text-4xl font-extrabold text-slate-800 tracking-tight">
                        <?php echo number_format($jmlkaryawan, 0, ',', '.'); ?> 
                        <span class="text-lg font-medium text-slate-400">Orang</span>
                    </div>
                </div>

                <div class="mt-auto pt-6 border-t border-slate-50">
                    <a href="?page=karyawan" class="w-full flex items-center justify-center px-4 py-3 bg-slate-50 text-slate-600 border border-slate-200 hover:bg-brand-600 hover:text-white hover:border-brand-600 rounded-xl text-sm font-bold transition-all duration-200">
                        <i class="fas fa-search mr-2 text-xs"></i> Kelola Data Karyawan
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col h-full transition-all duration-300 hover:shadow-lg hover:-translate-y-1 group">
                <div class="flex flex-col h-full">
                    <div class="flex flex-col items-center mb-6 text-center">
                        <div class="p-4 bg-orange-50 rounded-2xl text-orange-500 mb-4 transition-transform group-hover:scale-110 duration-300">
                            <i class="far fa-calendar-plus text-2xl"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-1">Rencana Upah</h3>
                        <p class="text-xs font-bold text-orange-600 bg-orange-50 px-2 py-0.5 rounded-full inline-block">
                            <?php echo date('d-m-Y', strtotime($rkk_tgl)); ?>
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div class="p-4 bg-rose-50/50 rounded-2xl border border-rose-100/50 flex flex-col items-center justify-center group/item hover:bg-rose-50 transition-colors">
                            <span class="text-[10px] uppercase font-bold text-rose-400 mb-1">Tanpa Mesin</span>
                            <div class="text-sm font-bold text-rose-700">
                                Rp <?php echo number_format($tampilan_tanpa_mesin, 0, ',', '.'); ?>
                            </div>
                        </div>

                        <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100/50 flex flex-col items-center justify-center group/item hover:bg-emerald-100/50 transition-colors">
                            <span class="text-[10px] uppercase font-bold text-emerald-600 mb-1">Dengan Mesin</span>
                            <div class="text-sm font-bold text-slate-800">
                                Rp <?php echo number_format($tampilan_dengan_mesin, 0, ',', '.'); ?>
                            </div>
                        </div>

                        <?php if ($total_hemat > 0): ?>
                        <div class="col-span-2 py-1 px-3 bg-emerald-500/10 rounded-lg flex items-center justify-center">
                             <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-tight">
                                <i class="fas fa-bolt mr-1"></i> Hemat: Rp <?php echo number_format($total_hemat, 0, ',', '.'); ?>
                             </span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 hover:border-slate-300 transition-colors">
                            <div class="text-[10px] font-bold text-slate-400 uppercase mb-1">Upah Pabrik</div>
                            <div class="text-xs font-bold text-slate-700">Rp <?php echo number_format($rkk_terbaru, 0, ',', '.'); ?></div>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 hover:border-slate-300 transition-colors">
                            <div class="text-[10px] font-bold text-slate-400 uppercase mb-1">Boneless Total</div>
                            <div class="text-xs font-bold text-slate-700">Rp <?php echo number_format($total_boneless_rencana, 0, ',', '.'); ?></div>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 hover:border-slate-300 transition-colors">
                            <div class="text-[10px] font-bold text-slate-400 uppercase mb-1">Total Pekerja</div>
                            <div class="text-xs font-bold text-slate-700"><?php echo number_format($rkk_jmlkaryawan, 0, ',', '.'); ?> Org</div>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 hover:border-slate-300 transition-colors">
                            <div class="text-[10px] font-bold text-slate-400 uppercase mb-1">Unit Mobil</div>
                            <div class="text-xs font-bold text-slate-700"><?php echo number_format($jumlah_mobil, 0, ',', '.'); ?> Mobil</div>
                        </div>
                    </div>

                    <div class="mt-auto space-y-3 pt-4 border-t border-slate-50">
                        <div class="flex gap-2">
                            <a href="excelrkk.php?id=<?php echo $rkk_id; ?>" class="flex-1 px-3 py-2.5 border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 transition-colors flex items-center justify-center">
                                <i class="fas fa-print mr-2"></i> Cetak
                            </a>

                            <?php if ($rkk_status == "0") {
                                if (strtolower($role) == 'owner') { ?>
                                    <a href="?page=rkk&aksi=accept&id=<?php echo $rkk_id; ?>&iddetail=app" onclick="return confirm('Approve?');" class="flex-[1.5] px-3 py-2.5 bg-slate-800 text-white rounded-xl text-xs font-bold hover:bg-slate-700 transition-colors flex items-center justify-center uppercase">
                                        <i class="fas fa-check-circle mr-2"></i> Approve
                                    </a>
                                <?php } else { ?>
                                    <a href="?page=rkk&aksi=accept&id=<?php echo $rkk_id; ?>&iddetail=pro" onclick="return confirm('Propose?');" class="flex-[1.5] px-3 py-2.5 bg-amber-500 text-white rounded-xl text-xs font-bold hover:bg-amber-600 transition-colors flex items-center justify-center uppercase">
                                        <i class="fas fa-paper-plane mr-2"></i> Propose
                                    </a>
                                <?php }
                            } elseif ($rkk_status == "1") { ?>
                                <a href="?page=rkk&aksi=accept&id=<?php echo $rkk_id; ?>&iddetail=unpro" class="flex-[1.5] px-3 py-2.5 bg-rose-500 text-white rounded-xl text-xs font-bold flex items-center justify-center uppercase">
                                    <i class="fas fa-undo mr-2"></i> Unpropose
                                </a>
                            <?php } else { ?>
                                <div class="flex-[1.5] px-3 py-2.5 bg-emerald-50 text-emerald-700 rounded-xl text-xs font-bold border border-emerald-100 flex items-center justify-center uppercase cursor-default">
                                    <i class="fas fa-check-double mr-2"></i> Approved
                                </div>
                            <?php } ?>
                        </div>

                        <div class="flex gap-2">
                            <a href="?page=rkk&aksi=kelola&id=<?php echo $rkk_id; ?>" class="flex-1 px-3 py-2.5 text-slate-600 hover:text-brand-600 hover:bg-brand-50 border border-slate-200 rounded-xl text-[10px] font-bold uppercase transition-colors flex items-center justify-center">
                                <i class="fas fa-list-ul mr-1.5"></i> Detail RKK
                            </a>

                            <?php if ($boneless_id) { ?>
                                <a href="?page=boneless&aksi=ubah&id=<?php echo $boneless_id; ?>&ref=home" class="flex-1 px-3 py-2.5 text-blue-600 hover:bg-blue-50 border border-blue-100 bg-blue-50 rounded-xl text-[10px] font-bold uppercase flex items-center justify-center">
                                    <i class="fas fa-edit mr-1.5"></i> Boneless
                                </a>
                            <?php } else { ?>
                                <a href="?page=boneless&aksi=tambah&tgl=<?php echo $rkk_tgl; ?>&ref=home" class="flex-1 px-3 py-2.5 text-slate-400 hover:bg-slate-50 border border-slate-200 rounded-xl text-[10px] font-bold uppercase flex items-center justify-center">
                                    <i class="fas fa-plus mr-1.5"></i> Boneless
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col h-full transition-all duration-300 hover:shadow-lg hover:-translate-y-1 group">
                <div class="flex flex-col items-center mb-6 text-center">
                    <div class="p-4 bg-emerald-50 rounded-2xl text-emerald-500 mb-4 transition-transform group-hover:scale-110 duration-300">
                        <i class="fas fa-check-double text-2xl"></i>
                    </div>
                    <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-1">Realisasi Upah</h3>
                    <p class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full inline-block">
                        <?php echo date('d-m-Y', strtotime($real_tgl)); ?>
                    </p>
                    <div class="mt-4 text-3xl font-extrabold text-slate-800 tracking-tight">
                        <span class="text-base font-bold text-slate-400 mr-1">Rp</span><?php echo number_format($real_terbaru, 0, ',', '.'); ?>
                    </div>
                </div>

                <div class="mb-6 p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between">
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Total Pekerja</div>
                        <div class="text-sm font-bold text-slate-700"><?php echo number_format($real_jmlkaryawan, 0, ',', '.'); ?> Orang</div>
                    </div>
                    <div class="h-8 w-8 bg-white rounded-full flex items-center justify-center shadow-sm">
                        <i class="fas fa-users text-slate-400 text-xs"></i>
                    </div>
                </div>

                <div class="mt-auto space-y-3 pt-4 border-t border-slate-50">
                    <div class="flex gap-2">
                        <a href="excelrealisasi.php?id=<?php echo $real_id; ?>" class="flex-1 px-3 py-2.5 border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 transition-colors flex items-center justify-center">
                            <i class="fas fa-file-invoice-dollar mr-2"></i> Payroll
                        </a>

                        <?php if ($real_status == 0) { ?>
                            <a href="?page=realisasi&aksi=accept&id=<?php echo $real_id; ?>&iddetail=app" onclick="return confirm('Approve?');" class="flex-[1.5] px-3 py-2.5 bg-slate-800 text-white rounded-xl text-xs font-bold hover:bg-slate-700 transition-colors flex items-center justify-center uppercase">
                                <i class="fas fa-check-circle mr-2"></i> Approve
                            </a>
                        <?php } elseif ($real_status == 2) {
                            if (strtolower($role) == 'owner' || strtolower($role) == 'admin master') { ?>
                                <a href="?page=realisasi&aksi=accept&id=<?php echo $real_id; ?>&iddetail=unapp" onclick="return confirm('Unapprove?');" class="flex-[1.5] px-3 py-2.5 bg-rose-50 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold hover:bg-rose-100 transition-colors flex items-center justify-center uppercase">
                                    <i class="fas fa-undo mr-2"></i> Unapprove
                                </a>
                            <?php } else { ?>
                                <div class="flex-[1.5] px-3 py-2.5 bg-emerald-50 text-emerald-700 rounded-xl text-xs font-bold border border-emerald-100 flex items-center justify-center uppercase cursor-default">
                                    <i class="fas fa-check-double mr-2"></i> Approved
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>

                    <a href="?page=realisasi&aksi=kelola&id=<?php echo $real_id; ?>" class="w-full px-3 py-2.5 text-slate-600 hover:text-brand-600 hover:bg-brand-50 border border-slate-200 rounded-xl text-[10px] font-bold uppercase transition-colors flex items-center justify-center">
                        <i class="fas fa-clipboard-list mr-1.5"></i> Detail Realisasi
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

        </div>
    </div>
</section>