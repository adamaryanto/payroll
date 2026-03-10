<?php
if (isset($_GET['id'])) {
    $idrealisasi = $_GET['id'];

    $tampildetail = $koneksi->query("SELECT * FROM tb_realisasi WHERE id_realisasi = '$idrealisasi'");
    $datadetail = $tampildetail->fetch_assoc();

    $datatglrealisasi    = $datadetail['tgl_realisasi'];
    $dataketerangan      = $datadetail['keterangan'];
    $datadetailrealisasi = $datadetail['detail_realisasi'];
    $datajamkerja        = $datadetail['jam_kerja'];
    $datastatusrealisasi = $datadetail['status_realisasi'];
    $idrkk               = $datadetail['id_rkk'];

    $tampil = $koneksi->query("SELECT 
        A.*, 
        A.r_upah as upahkaryawan, 
        B.no_absen, 
        BB.nama_sub_department, 
        B.nama_karyawan, 
        D.nama_departmen, 
        C.tgl_realisasi, 
        B.OS_DHK,
        B.golongan,
        RD.status_rkk,
        (SELECT K3.nama_karyawan 
         FROM tb_rkk_update U1 
         JOIN tb_rkk_update U2 ON U1.id_rkk_detail = U2.id_rkk_detail 
         JOIN ms_karyawan K3 ON U2.id_karyawan = K3.id_karyawan 
         JOIN tb_rkk_detail RD2 ON U1.id_rkk_detail = RD2.id_rkk_detail
         WHERE U1.id_karyawan = A.id_karyawan 
         AND U1.status = 'Pengganti' 
         AND U2.status = 'Digantikan' 
         AND RD2.id_rkk = A.id_rkk
         LIMIT 1) as menggantikan,
        (SELECT K4.nama_karyawan 
         FROM tb_rkk_update U3 
         JOIN tb_rkk_update U4 ON U3.id_rkk_detail = U4.id_rkk_detail 
         JOIN ms_karyawan K4 ON U4.id_karyawan = K4.id_karyawan 
         WHERE U3.id_rkk_detail = A.id_rkk_detail 
         AND U3.status = 'Digantikan' 
         AND U4.status = 'Pengganti' 
         LIMIT 1) as digantikan_oleh
        FROM tb_realisasi_detail A 
        LEFT JOIN ms_karyawan B ON A.id_karyawan = B.id_karyawan
        LEFT JOIN tb_realisasi C ON A.id_realisasi = C.id_realisasi
        LEFT JOIN ms_departmen D ON B.id_departmen = D.id_departmen
        LEFT JOIN ms_sub_department BB ON B.id_sub_department = BB.id_sub_department
        LEFT JOIN tb_rkk_detail RD ON A.id_rkk_detail = RD.id_rkk_detail
        WHERE A.id_realisasi = '$idrealisasi'
    ");

    $tampilrkk = $koneksi->query("SELECT * FROM tb_rkk WHERE id_rkk = '$idrkk'");
    $datarkk = $tampilrkk->fetch_assoc();
    $datatglrkk = $datarkk['tgl_rkk'];
    $dataketeranganrkk = $datarkk['keterangan'];
    $datajamkerjarkk = $datarkk['jam_kerja'];
} else {
    $datatglrealisasi    = "";
    $dataketerangan      = "";
    $datadetailrealisasi = "";
    $datajamkerja        = "";
    $datastatusrealisasi = 'pending';
}

if ($datastatusrealisasi == 'approve') {
    if ($_SESSION['role'] != "owner") {
        $status_hidden = "hidden";
    } else {
        $status_hidden = "";
    }
} else {
    $status_hidden = "";
}

$simpan = @$_POST['simpan'];
if ($simpan) {
    $tketerangan = @$_POST['tketerangan'];
    $sql = $koneksi->query("UPDATE tb_realisasi SET keterangan = '$tketerangan' WHERE id_realisasi = '$idrealisasi'");
    if ($sql) {
        echo '<script type="text/javascript">
                alert("Data Tersimpan");
                window.location.href="?page=realisasi&aksi=kelola&id=' . $idrealisasi . '";
              </script>';
    }
}

$cleanup = @$_POST['cleanup'];
if ($cleanup) {
    // Run synchronization logic
    include 'fix_realisasi.php';
    $count = syncRealisasiData($koneksi, $idrealisasi);
    echo '<script type="text/javascript">
            alert("Berhasil menarik ' . $count . ' data dari record mesin.");
            window.location.href="?page=realisasi&aksi=kelola&id=' . $idrealisasi . '";
          </script>';
}

if (!function_exists('rupiah')) {
    function rupiah($angka) {
        return "Rp " . number_format($angka, 0, ',', '.');
    }
}
?>

<div class="container-fluid px-3 md:px-6 mt-6 mb-10">
    <div class="bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden">
        
        <!-- Premium Modern Header -->
        <div class="bg-gradient-to-r from-slate-800 via-slate-700 to-slate-800 px-6 md:px-8 py-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-white/10 backdrop-blur-md rounded-xl flex items-center justify-center text-white border border-white/20 shadow-inner">
                        <i class="fas fa-tasks text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl md:text-2xl font-bold text-white m-0 tracking-tight">Kelola Realisasi Upah</h3>
                        <p class="text-slate-300 text-sm font-medium opacity-90 mt-1">Laporan Realisasi & Kehadiran Karyawan</p>
                    </div>
                </div>
                <div class="flex items-center w-full md:w-auto mt-2 md:mt-0">
                    <a href="?page=realisasi" class="w-full md:w-auto inline-flex justify-center items-center bg-white/10 hover:bg-white/20 text-white text-sm font-bold py-2.5 px-5 rounded-xl border border-white/20 transition-all backdrop-blur-sm">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="p-4 md:p-8">
            <form method="POST" enctype="multipart/form-data">
                <!-- Summary Section Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    
                    <!-- Rencana Information Card -->
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                            <i class="fas fa-calendar-check text-6xl"></i>
                        </div>
                        <h4 class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-4 flex items-center">
                            <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span> Rencana Upah (RKK)
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="flex flex-col">
                                <span class="text-[11px] font-bold text-slate-400 uppercase">Tanggal</span>
                                <span class="text-sm font-bold text-slate-700 mt-1"><?= date('d M Y', strtotime($datatglrkk)) ?></span>
                            </div>
                            <div class="flex flex-col md:col-span-2">
                                <span class="text-[11px] font-bold text-slate-400 uppercase">Keterangan</span>
                                <span class="text-sm font-bold text-slate-700 mt-1 truncate"><?= $dataketeranganrkk ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[11px] font-bold text-slate-400 uppercase">Jam Kerja</span>
                                <span class="text-sm font-bold text-slate-700 mt-1"><?= $datajamkerjarkk ?> Jam</span>
                            </div>
                        </div>
                    </div>

                    <!-- Realisasi Information Card -->
                    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6 relative overflow-hidden">
                        <h4 class="text-sm font-bold text-blue-600 uppercase tracking-widest mb-4 flex items-center">
                            <span class="w-2 h-2 bg-blue-600 rounded-full mr-2"></span> Dashboard Realisasi
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-end">
                            <div class="md:col-span-4">
                                <label class="block text-[11px] font-bold text-slate-400 uppercase mb-1">Tanggal Realisasi</label>
                                <input readonly type="date" value="<?= $datatglrealisasi ?>" class="w-full px-4 py-2 bg-white border border-blue-200 rounded-xl text-sm font-bold text-slate-700 outline-none">
                            </div>
                            <div class="md:col-span-8">
                                <label class="block text-[11px] font-bold text-slate-400 uppercase mb-1">Ket. Realisasi</label>
                                <input type="text" name="tketerangan" value="<?= $dataketerangan ?>" placeholder="Masukkan Keterangan..." class="w-full px-4 py-2 bg-white border border-blue-200 rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Button Group -->
                <div class="flex flex-wrap items-center gap-3 mb-10 pb-6 border-b border-slate-100 <?= $status_hidden ?>">
                    <button type="submit" name="simpan" value="Simpan" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-100 transition-all transform hover:-translate-y-0.5">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                    <button type="submit" name="cleanup" value="Cleanup" onclick="return confirm('Tarik data dari record mesin? Data realisasi manual akan tertimpa.')" class="inline-flex items-center px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-amber-100 transition-all transform hover:-translate-y-0.5">
                        <i class="fas fa-sync-alt mr-2"></i> Tarik Data Mesin
                    </button>
                </div>

                <!-- Employee Table List -->
                <div class="mb-6">
                    <h4 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-users mr-2 text-blue-500"></i> List Karyawan Realisasi
                    </h4>
                </div>

                <div class="table-responsive">
                    <table class="w-full text-left border-collapse table-modern" id="dataTables-example">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="py-4 px-3 text-[10px] font-extrabold text-slate-500 uppercase tracking-widest text-center">No</th>
                                <th class="py-4 px-3 text-[10px] font-extrabold text-slate-500 uppercase tracking-widest">Detail Karyawan</th>
                                <th class="py-4 px-3 text-[10px] font-extrabold text-slate-500 uppercase tracking-widest">Dept / Sub</th>
                                <th class="py-4 px-3 text-[10px] font-extrabold text-slate-500 uppercase tracking-widest">OS/DHK/Gol</th>
                                <th class="py-4 px-3 text-[10px] font-extrabold text-slate-500 uppercase tracking-widest">Record Mesin</th>
                                <th class="py-4 px-3 text-[10px] font-extrabold text-slate-500 uppercase tracking-widest">Realization Time</th>
                                <th class="py-4 px-3 text-[10px] font-extrabold text-slate-500 uppercase tracking-widest text-right">Potongan</th>
                                <th class="py-4 px-3 text-[10px] font-extrabold text-slate-500 uppercase tracking-widest text-right">Upah & Lembur</th>
                                <th class="py-4 px-3 text-[10px] font-extrabold text-slate-500 uppercase tracking-widest text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php
                            $no = 1;
                            $total_upah = 0;

                            while ($data = $tampil->fetch_assoc()) {
                                $upah = $data['upahkaryawan'];
                                $total_upah += $upah;

                                $isFullMissing = (empty($data['r_jam_masuk']) || $data['r_jam_masuk'] == '00:00:00') &&
                                                (empty($data['r_jam_keluar']) || $data['r_jam_keluar'] == '00:00:00');
                                ?>
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td data-label="No" class="py-4 px-3 text-sm font-bold text-slate-400 text-center"><?= $no ?></td>
                                    <td data-label="Detail Karyawan" class="py-4 px-3">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-900 leading-tight"><?= $data['nama_karyawan'] ?></span>
                                            <span class="text-[11px] font-medium text-slate-500">NIK: <?= $data['no_absen'] ?></span>
                                            
                                            <?php if (!empty($data['menggantikan'])) : ?>
                                                <span class="text-[10px] text-blue-600 font-bold mt-1 bg-blue-50 px-2 py-0.5 rounded border border-blue-100 inline-block w-max italic italic">
                                                    <i class="fas fa-exchange-alt mr-1"></i> (Pengganti <?= $data['menggantikan'] ?>)
                                                </span>
                                            <?php endif; ?>
                                            
                                            <div class="mt-1 flex gap-1">
                                                <?php if ($data['status_rkk'] == 'Hadir') : ?>
                                                    <span class="bg-emerald-100 text-emerald-800 text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider">Hadir</span>
                                                <?php elseif ($data['status_rkk'] == 'Tidak Hadir') : ?>
                                                    <span class="bg-rose-100 text-rose-800 text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider">Mangkir</span>
                                                <?php elseif ($data['status_rkk'] == 'Digantikan') : ?>
                                                    <span class="bg-blue-100 text-blue-800 text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider">Digantikan</span>
                                                <?php elseif ($data['status_rkk'] == 'Pengganti') : ?>
                                                    <span class="bg-indigo-100 text-indigo-800 text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider">Pengganti</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Dept / Sub" class="py-4 px-3">
                                        <div class="flex flex-col">
                                            <span class="text-[13px] font-bold text-slate-700"><?= $data['nama_departmen'] ?></span>
                                            <span class="text-[11px] font-medium text-slate-400"><?= $data['nama_sub_department'] ?></span>
                                        </div>
                                    </td>
                                    <td data-label="OS/DHK/Gol" class="py-4 px-3">
                                        <div class="flex items-center gap-1">
                                            <span class="px-2 py-1 bg-slate-100 rounded text-[10px] font-bold text-slate-600"><?= $data['OS_DHK'] ?></span>
                                            <span class="px-2 py-1 bg-blue-50 rounded text-[10px] font-bold text-blue-600">G<?= $data['golongan'] ?></span>
                                        </div>
                                    </td>
                                    <td data-label="Record Mesin" class="py-4 px-3">
                                        <div class="flex flex-col space-y-1">
                                            <div class="flex items-center justify-between gap-3 text-[11px]">
                                                <span class="text-slate-400 font-bold">M/P:</span>
                                                <span class="font-bold <?= ($isFullMissing) ? 'text-rose-600' : 'text-slate-700' ?>">
                                                    <?= $data['r_jam_masuk'] ?> - <?= $data['r_jam_keluar'] ?>
                                                </span>
                                            </div>
                                            <div class="flex items-center justify-between gap-3 text-[11px]">
                                                <span class="text-slate-400 font-bold">I. K/M:</span>
                                                <span class="font-bold text-slate-700"><?= $data['r_istirahat_keluar'] ?> - <?= $data['r_istirahat_masuk'] ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Realization Time" class="py-4 px-3">
                                        <div class="flex flex-col space-y-1">
                                            <div class="flex items-center justify-between gap-3 text-[11px]">
                                                <span class="text-slate-400 font-bold uppercase">Masuk:</span>
                                                <span class="font-black <?= (empty($data['ra_masuk']) || $data['ra_masuk'] == '00:00:00' || $data['r_potongan_telat'] > 0) ? 'text-rose-600' : 'text-emerald-600' ?>">
                                                    <?= $data['ra_masuk'] ?>
                                                </span>
                                            </div>
                                            <div class="flex items-center justify-between gap-3 text-[11px]">
                                                <span class="text-slate-400 font-bold uppercase">Pulang:</span>
                                                <span class="font-black <?= ($data['r_potongan_lainnya'] > 0) ? 'text-amber-500' : 'text-emerald-600' ?>">
                                                    <?= $data['ra_keluar'] ?>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Potongan" class="py-4 px-3 text-right">
                                        <div class="flex flex-col text-[11px] font-bold">
                                            <?php if($data['r_potongan_telat'] > 0): ?>
                                                <span class="text-rose-600">Telat: <?= rupiah($data['r_potongan_telat']) ?></span>
                                            <?php endif; ?>
                                            <?php if($data['r_potongan_istirahat'] > 0): ?>
                                                <span class="text-rose-600 italic">Ist: <?= rupiah($data['r_potongan_istirahat']) ?></span>
                                            <?php endif; ?>
                                            <?php if($data['r_potongan_lainnya'] > 0): ?>
                                                <span class="text-amber-600">Lain: <?= rupiah($data['r_potongan_lainnya']) ?></span>
                                            <?php endif; ?>
                                            <?php if($data['r_potongan_telat'] == 0 && $data['r_potongan_istirahat'] == 0 && $data['r_potongan_lainnya'] == 0): ?>
                                                <span class="text-slate-300">-</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td data-label="Upah & Lembur" class="py-4 px-3 text-right">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-extrabold text-blue-700"><?= rupiah($upah) ?></span>
                                            <?php if($data['lembur'] > 0): ?>
                                                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded w-max ml-auto mt-0.5">+ Lembur: <?= rupiah($data['lembur']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td data-label="Aksi" class="py-4 px-3 text-center">
                                        <a href="?page=realisasi&aksi=detail&id=<?= $data['id_realisasi_detail'] ?>"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-blue-100">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                                $no++;
                            }
                            $jml_karyawan = $no - 1;
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Footer Total Card -->
                <div class="flex justify-end mt-8">
                    <div class="w-full md:w-auto min-w-[300px] bg-slate-800 rounded-2xl p-6 shadow-xl shadow-slate-200 border-b-4 border-blue-500">
                        <span class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 block">Estimasi Total Realisasi (<?= $jml_karyawan ?> Orang)</span>
                        <h2 class="text-2xl md:text-3xl font-black text-white m-0 tracking-tight leading-none"><?= rupiah($total_upah) ?></h2>
                        <div class="mt-3 flex items-center text-[10px] font-bold text-blue-300 uppercase italic">
                            <i class="fas fa-info-circle mr-1.5"></i> Estimasi upah pokok harian
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Styling khusus table-modern untuk Realisasi Kelola */
    @media screen and (max-width: 768px) {
        .table-modern thead { display: none !important; }
        .table-modern tbody tr {
            display: block;
            margin-bottom: 1.25rem;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 16px;
            background: #fff;
            box-shadow: 0 4px 10px -2px rgba(0, 0, 0, 0.05);
        }
        .table-modern tbody td {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 10px 0 !important;
            border: none !important;
            border-bottom: 1px dashed #f1f5f9 !important;
            text-align: right !important;
        }
        .table-modern tbody td:last-child { 
            border-bottom: none !important; 
            padding-top: 15px !important;
            justify-content: center;
        }
        .table-modern tbody td:before {
            content: attr(data-label);
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.8px;
            text-align: left;
            margin-top: 3px;
        }
        
        .table-modern tbody td[data-label="Aksi"] {
            background-color: #f8fafc;
            border-radius: 12px;
            margin-top: 8px;
            padding: 10px !important;
        }
        .table-modern tbody td[data-label="Aksi"] a {
            width: 100%;
            height: 40px;
            border-radius: 10px;
        }
    }

    /* Custom DataTables Refinement */
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #e2e8f0 !important;
        border-radius: 10px !important;
        padding: 8px 14px !important;
        outline: none !important;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1) !important;
    }
    
    .dataTables_paginate .paginate_button {
        padding: 5px 12px !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        border: 1px solid #e2e8f0 !important;
        background: white !important;
        margin: 0 2px !important;
        transition: all 0.2s ease;
    }
    .dataTables_paginate .paginate_button.current {
        background: #2563eb !important;
        color: white !important;
        border-color: #2563eb !important;
    }
    .dataTables_paginate .paginate_button:hover:not(.current):not(.disabled) {
        background: #f8fafc !important;
        border-color: #cbd5e1 !important;
        color: #2563eb !important;
    }
    .dataTables_info {
        font-size: 13px !important;
        font-weight: 500 !important;
        color: #64748b !important;
    }
</style>

<script>
    $(document).ready(function() {
        var table = $('#dataTables-example').DataTable({
            "pageLength": 10,
            "autoWidth": false,
            "language": {
                "search": "",
                "searchPlaceholder": "Cari Karyawan...",
                "lengthMenu": "Tampil _MENU_",
                "info": "Menampilkan _START_ sd _END_ dari _TOTAL_ Karyawan",
                "paginate": {
                    "previous": "<i class='fas fa-chevron-left'></i>",
                    "next": "<i class='fas fa-chevron-right'></i>"
                }
            },
            "dom": '<"flex flex-col md:flex-row justify-between items-center mb-6 gap-4"f>t<"flex flex-col md:flex-row justify-between items-center mt-6 gap-4"ip>'
        });
        
        // Add custom search icon inside input container if possible or style wrapper
        $('.dataTables_filter label').addClass('relative flex items-center w-full md:w-auto');
        $('.dataTables_filter input').addClass('w-full md:w-64');
    });
</script>