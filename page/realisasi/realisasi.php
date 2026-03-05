<?php
// Query utama
$tampil = $koneksi->query("SELECT A.*, 
    (select count(id_realisasi_detail) from tb_realisasi_detail where id_realisasi = A.id_realisasi ) as jml, 
    (select sum(r_upah) from tb_realisasi_detail where id_realisasi = A.id_realisasi ) as ttl, 
    (select sum(r_potongan_telat) from tb_realisasi_detail where id_realisasi = A.id_realisasi ) as pottelat,
    (select sum(r_potongan_istirahat) from tb_realisasi_detail where id_realisasi = A.id_realisasi ) as potistirahat, 
    (select sum(r_potongan_lainnya) from tb_realisasi_detail where id_realisasi = A.id_realisasi ) as potlainnya
    from tb_realisasi A");

// Logika Akses Owner
$level_status = ($_SESSION['role'] != "owner") ? "hidden" : "";
?>

<div class="container-fluid px-2 mt-4 mb-4">
    <div class="card border-0 shadow-sm rounded-xl overflow-hidden bg-white">
        
        <div class="border-b border-gray-100 py-4 px-5 flex justify-between items-center bg-white">
            <div>
                <h3 class="text-xl font-bold text-gray-800 m-0">List Realisasi Upah</h3>
            </div>
            <div>
                <a href="?page=realisasi&aksi=rkk" class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white text-[15px] font-medium py-2 px-4 rounded shadow-sm transition-colors">
                    <i class="fas fa-plus mr-1.5"></i> Tambah Data
                </a>
            </div>
        </div>
        
        <div class="p-0">
            <div class="table-responsive px-3 py-3">
                <table class="w-full text-left border-collapse" id="dataTables-example">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="py-2 px-2 text-[13px] font-bold text-gray-700 uppercase align-middle text-center w-8">No</th>
                            <th class="py-2 px-2 text-[13px] font-bold text-gray-700 uppercase align-middle">Tanggal</th>
                            <th class="py-2 px-2 text-[13px] font-bold text-gray-700 uppercase align-middle">Tgl Input</th>
                            <th class="py-2 px-2 text-[13px] font-bold text-gray-700 uppercase align-middle text-center">Jam</th>
                            <th class="py-2 px-2 text-[13px] font-bold text-gray-700 uppercase align-middle text-center">Jml</th>
                            <th class="py-2 px-2 text-[13px] font-bold text-gray-700 uppercase align-middle text-right">Total Upah</th>
                            <th class="py-2 px-2 text-[13px] font-bold text-gray-700 uppercase align-middle text-right" title="Potongan Telat">Pot. Tlt</th>
                            <th class="py-2 px-2 text-[13px] font-bold text-gray-700 uppercase align-middle text-right" title="Potongan Istirahat">Pot. Ist</th>
                            <th class="py-2 px-2 text-[13px] font-bold text-gray-700 uppercase align-middle text-right" title="Potongan Lainnya">Pot. Lain</th>
                            <th class="py-2 px-2 text-[13px] font-bold text-gray-700 uppercase align-middle">Ket</th>
                            <th class="py-2 px-2 text-[13px] font-bold text-gray-700 uppercase align-middle text-center">Status</th>
                            <th class="py-2 px-2 text-[13px] font-bold text-gray-700 uppercase align-middle text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php
                        $no = 1;
                        while ($data = $tampil->fetch_assoc()) :
                            if ($data['status_realisasi'] == "1") {
                                $app = "hidden";
                                $print = "";
                                $row_class = "bg-slate-50/40"; 
                                $status_badge = '<span class="px-2 py-1 rounded bg-emerald-100 text-emerald-800 text-[13px] font-bold tracking-wide">ACC</span>';
                            } else {
                                $app = "";
                                $print = "hidden";
                                $row_class = "hover:bg-gray-50 transition-colors";
                                $status_badge = '<span class="px-2 py-1 rounded bg-amber-100 text-amber-800 text-[13px] font-bold tracking-wide">PEND</span>';
                            }
                        ?>
                        <tr class="<?= $row_class ?>">
                            <td class="py-2.5 px-2 text-center text-[15px] text-gray-700 align-middle"><?= $no ?></td>
                            <td class="py-2.5 px-2 text-[15px] font-medium text-gray-900 align-middle whitespace-nowrap"><?= $data['tgl_realisasi'] ?></td>
                            <td class="py-2.5 px-2 text-[15px] text-gray-700 align-middle whitespace-nowrap"><?= $data['detail_realisasi'] ?></td>
                            <td class="py-2.5 px-2 text-center text-[15px] font-bold text-indigo-600 align-middle"><?= $data['jam_kerja'] ?></td>
                            <td class="py-2.5 px-2 text-center text-[15px] text-gray-700 align-middle"><?= $data['jml'] ?></td>
                            
                            <td class="py-2.5 px-2 text-right text-[15px] font-bold text-gray-900 align-middle whitespace-nowrap">
                                <?= number_format($data['ttl'] ?? 0, 0, ',', '.') ?>
                            </td>
                            <td class="py-2.5 px-2 text-right text-[15px] font-medium text-rose-600 align-middle whitespace-nowrap">
                                <?= number_format($data['pottelat'] ?? 0, 0, ',', '.') ?>
                            </td>
                            <td class="py-2.5 px-2 text-right text-[15px] font-medium text-rose-600 align-middle whitespace-nowrap">
                                <?= number_format($data['potistirahat'] ?? 0, 0, ',', '.') ?>
                            </td>
                            <td class="py-2.5 px-2 text-right text-[15px] font-medium text-rose-600 align-middle whitespace-nowrap">
                                <?= number_format($data['potlainnya'] ?? 0, 0, ',', '.') ?>
                            </td>
                            
                            <td class="py-2.5 px-2 align-middle">
                                <div class="text-[14px] text-gray-700 max-w-[150px] truncate" title="<?= htmlspecialchars($data['keterangan']) ?>">
                                    <?= htmlspecialchars($data['keterangan']) ?>
                                </div>
                            </td>
                            
                            <td class="py-2.5 px-2 align-middle text-center">
                                <?= $status_badge ?>
                            </td>

                            <td class="py-2.5 px-2 align-middle text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="?page=realisasi&aksi=kelola&id=<?= $data['id_realisasi'];?>" 
                                       class="px-2.5 py-1.5 text-[14px] font-bold text-blue-600 bg-blue-50 hover:bg-blue-600 hover:text-white rounded border border-blue-200 transition-colors" title="Detail">
                                       <i class="fas fa-eye"></i>
                                    </a>

                                    <div class="<?= $level_status ?> <?= $app ?>">
                                        <a href="?page=realisasi&aksi=accept&id=<?= $data['id_realisasi'];?>"
                                           class="px-2.5 py-1.5 text-[14px] font-bold text-emerald-600 bg-emerald-50 hover:bg-emerald-600 hover:text-white rounded border border-emerald-200 transition-colors"
                                           onclick="return confirm('Apakah Anda yakin ingin Approve data ini?');" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </a>
                                    </div>  

                                    <div class="<?= $print ?>">
                                        <a href="excelrealisasi.php?id=<?= $data['id_realisasi'];?>"
                                           class="px-2.5 py-1.5 text-[14px] font-bold text-purple-600 bg-purple-50 hover:bg-purple-600 hover:text-white rounded border border-purple-200 transition-colors" title="Download Payroll">
                                            <i class="fas fa-file-excel"></i>
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php $no++; endwhile; ?>
                    </tbody>   
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Mengamankan agar font DataTables juga ikut lebih besar dan proporsional */
    #dataTables-example { width: 100% !important; border-collapse: collapse !important; }
    .dataTables_wrapper .dataTables_length select { border-radius: 4px; border: 1px solid #d1d5db; padding: 4px 8px; margin: 0 4px; outline: none; font-size: 14px; }
    .dataTables_wrapper .dataTables_filter input { border-radius: 4px !important; border: 1px solid #d1d5db !important; padding: 6px 10px !important; outline: none; font-size: 14px; transition: all 0.2s; }
    .dataTables_wrapper .dataTables_filter input:focus { border-color: #4f46e5 !important; box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2); }
    .dataTables_wrapper .dataTables_paginate { padding-top: 1rem !important; display: flex; justify-content: flex-end; gap: 4px; }
    .dataTables_wrapper .dataTables_paginate .paginate_button { background: white !important; border: 1px solid #d1d5db !important; border-radius: 4px !important; padding: 6px 12px !important; color: #374151 !important; font-size: 14px !important; cursor: pointer; }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled) { background: #f3f4f6 !important; color: #111827 !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #4f46e5 !important; border-color: #4f46e5 !important; color: white !important; font-weight: bold; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled { opacity: 0.5; cursor: not-allowed; }
    .dataTables_wrapper .dataTables_info { padding-top: 1.1rem !important; font-size: 14px; color: #4b5563; }
    .dataTables_wrapper::after { content: ""; clear: both; display: table; }
</style>

<script>
$(document).ready(function() {
    $('#dataTables-example').DataTable({
        pageLength: 25,
        autoWidth: false, // Penting agar tidak scroll samping
        responsive: false, 
        language: {
            search: "",
            searchPlaceholder: "Cari data...",
            lengthMenu: "Tampilkan _MENU_",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_",
            paginate: { previous: "Prev", next: "Next" } // Kembali ke teks agar mudah ditekan
        }
    });
    $('.dataTables_filter').css('float', 'right').addClass('mb-3');
    $('.dataTables_length').css('float', 'left').addClass('mb-3');
});
</script>

<?php
// Script Redirect
$ttgl1 = $_POST['ttgl1'] ?? '';
$ttgl2 = $_POST['ttgl2'] ?? '';

if(isset($_POST['simpan'])){
    echo '<script>window.location.href="?page=cuti&ttgl1='.$ttgl1.'&ttgl2='.$ttgl2.'";</script>';
}
if(isset($_POST['print'])){
    echo '<script>window.location.href="laporanpendapatan.php?ttgl1='.$ttgl1.'&ttgl2='.$ttgl2.'";</script>';
}
if(isset($_POST['excel'])){
    echo '<script>window.location.href="excelpendapatan.php?ttgl1='.$ttgl1.'&ttgl2='.$ttgl2.'";</script>';
}
?>