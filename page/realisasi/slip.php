<?php
$id = $_GET['id'] ?? '';
$ttgl1 = date("Y-m-d");

// Ambil Nama Karyawan untuk Header
$q_karyawan = $koneksi->query("SELECT A.nama_karyawan, B.nama_departmen 
                             FROM ms_karyawan A 
                             LEFT JOIN ms_departmen B ON A.id_departmen = B.id_departmen 
                             WHERE A.id_karyawan = '$id'");
$d_karyawan = $q_karyawan->fetch_assoc();
$namaKaryawan = $d_karyawan ? $d_karyawan['nama_karyawan'] : 'Karyawan';
$deptKaryawan = $d_karyawan ? $d_karyawan['nama_departmen'] : '-';

// Ambil ID Realisasi dari detail terakhir untuk navigasi kembali
$q_realisasi = $koneksi->query("SELECT id_realisasi FROM tb_realisasi_detail WHERE id_karyawan = '$id' ORDER BY id_realisasi_detail DESC LIMIT 1");
$d_realisasi = $q_realisasi->fetch_assoc();
$idRealisasi = $d_realisasi ? $d_realisasi['id_realisasi'] : 0;

if (!function_exists('rupiah')) {
    function rupiah($angka) {
        return "Rp " . number_format($angka, 0, ',', '.');
    }
}
?>

<div class="container-fluid px-3 md:px-6 mt-6 mb-10">
    <div class="bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden">
        
        <div class="bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-700 px-6 md:px-8 py-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center text-white border border-white/30 shadow-inner">
                        <i class="fas fa-file-invoice-dollar text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl md:text-2xl font-bold text-white m-0 tracking-tight">Preview Slip Gaji</h3>
                        <p class="text-blue-100 text-sm font-medium opacity-90 mt-1"><?= $namaKaryawan ?> • <span class="bg-white/20 px-2 py-0.5 rounded text-[11px] uppercase tracking-wider"><?= $deptKaryawan ?></span></p>
                    </div>
                </div>
                <div class="flex items-center w-full md:w-auto mt-2 md:mt-0">
                    <a href="?page=realisasi&aksi=kelola&id=<?= $idRealisasi ?>" class="w-full md:w-auto inline-flex justify-center items-center bg-white/10 hover:bg-white/20 text-white text-sm font-bold py-2.5 px-5 rounded-xl border border-white/20 transition-all backdrop-blur-sm">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="p-4 md:p-8">
            <form method="GET" class="bg-blue-50/50 border border-blue-100 p-5 rounded-2xl mb-8">
                <input type="hidden" name="page" value="realisasi">
                <input type="hidden" name="aksi" value="slip">
                
                <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-end">
                    <div class="md:col-span-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Pilih Karyawan</label>
                        <select name="id" class="select2-manage w-full" data-placeholder="- Pilih Karyawan -" required>
                            <option value=""></option>
                            <?php
                            $q_all_kar = $koneksi->query("SELECT id_karyawan, no_absen, nama_karyawan FROM ms_karyawan WHERE status_karyawan = 'Aktif' ORDER BY nama_karyawan ASC");
                            while($kar = $q_all_kar->fetch_assoc()) {
                                $selected = ($kar['id_karyawan'] == $id) ? 'selected' : '';
                                echo "<option value='{$kar['id_karyawan']}' {$selected}>{$kar['no_absen']} - {$kar['nama_karyawan']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Dari Tanggal</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 pointer-events-none">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            <input type="date" name="ttgl1" required value="<?= $_GET['ttgl1'] ?? $ttgl1 ?>" 
                                class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none text-gray-700 font-medium">
                        </div>
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Sampai Tanggal</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 pointer-events-none">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            <input type="date" name="ttgl2" required value="<?= $_GET['ttgl2'] ?? $ttgl1 ?>" 
                                class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none text-gray-700 font-medium">
                        </div>
                    </div>
                    <div class="md:col-span-2 mt-2 md:mt-0">
                        <button type="submit" name="cari" value="Search" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-md transition-all transform hover:-translate-y-0.5 flex items-center justify-center space-x-2">
                            <i class="fas fa-search shadow-sm"></i>
                            <span>Cari</span>
                        </button>
                    </div>
                </div>
            </form>

            <?php
            if (isset($_GET['cari']) && $id) {
                $ttgl11 = $_GET['ttgl1'];
                $ttgl22 = $_GET['ttgl2'];

                // Ambil denda global
                $q_denda = $koneksi->query("SELECT * FROM tb_denda LIMIT 1");
                $d_denda = $q_denda->fetch_assoc();
                $globalDendaMasuk = $d_denda['denda_masuk'] ?? 0;
                $globalDendaIstirahatKeluar = $d_denda['denda_istirahat_keluar'] ?? 0;
                $globalDendaIstirahatMasuk = $d_denda['denda_istirahat_masuk'] ?? 0;
                $globalDendaPulang = $d_denda['denda_pulang'] ?? 0;
                $globalDendaTidakLengkap = $d_denda['denda_tidak_lengkap'] ?? 0;

                $sql = "SELECT r.*, j.jam_masuk, j.jam_keluar, j.istirahat_masuk, j.istirahat_keluar, rd.status_rkk
                        FROM tb_realisasi_detail r
                        LEFT JOIN tb_jadwal j ON r.id_jadwal = j.id_jadwal
                        LEFT JOIN tb_rkk_detail rd ON r.id_rkk_detail = rd.id_rkk_detail
                        WHERE r.id_karyawan = '$id'
                        AND r.tgl_realisasi_detail BETWEEN '$ttgl11' AND '$ttgl22'
                        ORDER BY r.tgl_realisasi_detail ASC";
                $result = $koneksi->query($sql);
                ?>

                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6 pt-4 border-t border-gray-100">
                    <div>
                        <h4 class="text-lg font-bold text-gray-800 m-0">Hasil Pencarian</h4>
                        <p class="text-gray-500 text-xs mt-1">Menampilkan data slip gaji periode <?= date('d/m/Y', strtotime($ttgl11)) ?> - <?= date('d/m/Y', strtotime($ttgl22)) ?></p>
                    </div>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <div class="w-full md:w-auto">
                            <a target="_blank" href="slip.php?id=<?= $id ?>&ttgl1=<?= $ttgl11 ?>&ttgl2=<?= $ttgl22 ?>" 
                               class="w-full md:w-auto flex justify-center items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-md transition-all transform hover:-translate-y-0.5">
                                <i class="fas fa-file-excel mr-2"></i> Download Excel
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="table-responsive">
                    <table class="w-full text-left border-collapse table-modern" id="slipTable">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="py-4 px-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest">Tanggal</th>
                                <th class="py-4 px-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest">Upah Pokok</th>
                                <th class="py-4 px-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest">Masuk/Pulang</th>
                                <th class="py-4 px-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest">Pot. Telat</th>
                                <th class="py-4 px-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest">Pot. Istirahat</th>
                                <th class="py-4 px-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest">Pot. Pulang</th>
                                <th class="py-4 px-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest">Pot. Tidak Absen</th>
                                <th class="py-4 px-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest">Pot. Lain</th>
                                <th class="py-4 px-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest">Lembur</th>
                                <th class="py-4 px-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest text-right">Total Net</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                                <?php
                                if ($result && $result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        // Logika Pelanggaran Dinamis (Sync with realisasi/kelola.php)
                                        $isLate = (!empty($row['ra_masuk']) && $row['ra_masuk'] != '00:00:00' && !empty($row['jam_masuk']) && $row['jam_masuk'] != '00:00:00' && strtotime($row['ra_masuk']) > strtotime($row['jam_masuk']));
                                        $isLateBreak = (!empty($row['ra_istirahat_masuk']) && $row['ra_istirahat_masuk'] != '00:00:00' && !empty($row['istirahat_masuk']) && $row['istirahat_masuk'] != '00:00:00' && strtotime($row['ra_istirahat_masuk']) > strtotime($row['istirahat_masuk']));
                                        $isEarlyOut = (!empty($row['ra_keluar']) && $row['ra_keluar'] != '00:00:00' && !empty($row['jam_keluar']) && $row['jam_keluar'] != '00:00:00' && strtotime($row['ra_keluar']) < strtotime($row['jam_keluar']));
                                        
                                        // Incomplete Logs
                                        $hasIncompleteMain = (
                                            (!empty($row['ra_masuk']) && $row['ra_masuk'] != '00:00:00' && (empty($row['ra_keluar']) || $row['ra_keluar'] == '00:00:00')) ||
                                            ((empty($row['ra_masuk']) || $row['ra_masuk'] == '00:00:00') && !empty($row['ra_keluar']) && $row['ra_keluar'] != '00:00:00')
                                        );
                                        $isRestExpected = (!empty($row['istirahat_keluar']) && $row['istirahat_keluar'] != '00:00:00');
                                        $hasIncompleteBreak = ($isRestExpected && (
                                            (empty($row['ra_istirahat_keluar']) || $row['ra_istirahat_keluar'] == '00:00:00') ||
                                            (empty($row['ra_istirahat_masuk']) || $row['ra_istirahat_masuk'] == '00:00:00')
                                        ));

                                        $isEarlyBreak = (!empty($row['ra_istirahat_keluar']) && $row['ra_istirahat_keluar'] != '00:00:00' && !empty($row['istirahat_keluar']) && $row['istirahat_keluar'] != '00:00:00' && strtotime($row['ra_istirahat_keluar']) < strtotime($row['istirahat_keluar']));

                                        // Skip Denda if wage is 0 or status is "Digantikan"
                                        if ($row['r_upah'] == 0 || $row['status_rkk'] == 'Digantikan') {
                                            $potTelatValue = 0;
                                            $potIstirahatValue = 0;
                                            $potPulangValue = 0;
                                            $potTidakLengkapValue = 0;
                                        } else {
                                            $potTelatValue = $isLate ? $globalDendaMasuk : 0;
                                            $potIstirahatValue = ($isEarlyBreak ? $globalDendaIstirahatKeluar : 0) + ($isLateBreak ? $globalDendaIstirahatMasuk : 0);
                                            $potPulangValue = $isEarlyOut ? $globalDendaPulang : 0;
                                            $potTidakLengkapValue = ($hasIncompleteMain || $hasIncompleteBreak) ? $globalDendaTidakLengkap : 0;
                                        }

                                        $total = ($row['r_upah'] + $row['lembur']) - ($potTelatValue + $potIstirahatValue + $potPulangValue + $potTidakLengkapValue + $row['r_potongan_lainnya']);
                                        ?>
                                        <tr class="hover:bg-blue-50/30 transition-colors">
                                            <td data-label="Tanggal" class="py-4 px-3 text-sm font-bold text-gray-900"><?= date('d/m/Y', strtotime($row['tgl_realisasi_detail'])) ?></td>
                                            <td data-label="Upah Pokok" class="py-4 px-3 text-sm font-medium text-gray-700"><?= rupiah($row['r_upah']) ?></td>
                                            <td data-label="Jam Kerja" class="py-4 px-3 text-sm text-gray-600">
                                                <span class="bg-gray-100 px-2 py-1 rounded text-xs font-bold"><?= $row['ra_masuk'] ?> - <?= $row['ra_keluar'] ?></span>
                                            </td>
                                            <td data-label="Pot. Telat" class="py-4 px-3 text-sm font-bold text-rose-600"><?= rupiah($potTelatValue) ?></td>
                                            <td data-label="Pot. Istirahat" class="py-4 px-3 text-sm font-bold text-rose-600"><?= rupiah($potIstirahatValue) ?></td>
                                            <td data-label="Pot. Pulang" class="py-4 px-3 text-sm font-bold text-rose-600"><?= rupiah($potPulangValue) ?></td>
                                            <td data-label="Pot. Tidak Absen" class="py-4 px-3 text-sm font-bold text-rose-600"><?= rupiah($potTidakLengkapValue) ?></td>
                                            <td data-label="Pot. Lain" class="py-4 px-3 text-sm font-bold text-orange-600"><?= rupiah($row['r_potongan_lainnya']) ?></td>
                                            <td data-label="Lembur" class="py-4 px-3 text-sm font-bold text-emerald-600"><?= rupiah($row['lembur']) ?></td>
                                            <td data-label="Total Net" class="py-4 px-3 text-[15px] font-extrabold text-blue-700 text-right"><?= rupiah($total) ?></td>
                                        </tr>
                                        <?php
                                    }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="8" class="py-12 text-center">
                                        <div class="flex flex-col items-center justify-center opacity-40">
                                            <i class="fas fa-folder-open text-5xl mb-3"></i>
                                            <p class="text-gray-500 font-medium italic">Tidak ada data ditemukan untuk rentang tanggal tersebut.</p>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php } else { ?>
                <div class="py-16 text-center border-2 border-dashed border-gray-100 rounded-3xl">
                    <div class="flex flex-col items-center justify-center">
                        <div class="w-16 h-16 bg-blue-50 text-blue-300 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-search text-2xl"></i>
                        </div>
                        <h5 class="text-gray-400 font-bold mb-1">Cari Data Slip</h5>
                        <p class="text-gray-400 text-sm italic">Pilih rentang tanggal di atas untuk melihat preview slip gaji</p>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<style>
    /* Styling khusus table-modern untuk slip */
    @media screen and (max-width: 768px) {
        .table-modern thead { display: none !important; }
        .table-modern tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        .table-modern tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0 !important;
            border: none !important;
            border-bottom: 1px dashed #f1f5f9 !important;
            text-align: right !important;
        }
        .table-modern tbody td:last-child { 
            border-bottom: none !important; 
            padding-top: 12px !important;
            margin-top: 5px;
            background: #f8fafc;
            border-radius: 8px;
            padding-left: 10px !important;
            padding-right: 10px !important;
        }
        .table-modern tbody td:before {
            content: attr(data-label);
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
            text-align: left;
        }
    }

    /* Styling Pagination & Info */
    .dataTables_info {
        color: #64748b !important;
        font-size: 13px !important;
        font-weight: 500 !important;
    }

    .dataTables_paginate {
        display: flex !important;
        gap: 4px !important;
        align-items: center !important;
    }

    .dataTables_paginate .paginate_button {
        padding: 5px 12px !important;
        border-radius: 8px !important;
        border: 1px solid #e2e8f0 !important;
        background: white !important;
        color: #475569 !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        transition: all 0.2s ease !important;
        cursor: pointer !important;
        outline: none !important;
        margin: 0 !important;
    }

    .dataTables_paginate .paginate_button:hover:not(.disabled):not(.current) {
        background: #f8fafc !important;
        color: #2563eb !important;
        border-color: #cbd5e1 !important;
        text-decoration: none !important;
    }

    .dataTables_paginate .paginate_button.current {
        background: #2563eb !important;
        color: white !important;
        border-color: #2563eb !important;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.15) !important;
    }

    .dataTables_paginate .paginate_button.disabled {
        color: #94a3b8 !important;
        background: #f1f5f9 !important;
        border-color: #e2e8f0 !important;
        cursor: not-allowed !important;
    }

    .dataTables_paginate .paginate_button.previous,
    .dataTables_paginate .paginate_button.next {
        background: #f8fafc !important;
        font-weight: 700 !important;
        border-color: #e2e8f0 !important;
    }
</style>

<script>
    $(document).ready(function() {
        if ($('#slipTable').length && $('#slipTable').find('tbody tr td[colspan]').length === 0) {
            // Kita gunakan standard DataTable tapi minimalis
            $('#slipTable').DataTable({
                "pageLength": 10,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "search": "Cari Baris:",
                    "lengthMenu": "_MENU_",
                    "info": "Menampilkan _START_ - _END_ dari _TOTAL_ data"
                },
                "dom": '<"flex flex-col md:flex-row justify-between items-center mb-4 gap-3"f>t<"flex flex-col md:flex-row justify-between items-center mt-4 gap-3"ip>'
            });
            
            // Perbaiki gaya input DataTables agar matching
            $('.dataTables_filter input').addClass('w-full md:w-auto px-3 py-1.5 border border-gray-200 rounded-lg outline-none focus:ring-2 focus:ring-blue-500');
            $('.dataTables_filter label').addClass('w-full md:w-auto flex flex-col md:flex-row md:items-center gap-2');
        }
    });
</script>