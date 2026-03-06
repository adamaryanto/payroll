<?php
// Logika Redirect Form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ttgl1 = $_POST['ttgl1'] ?? '';
    $ttgl2 = $_POST['ttgl2'] ?? '';

    if (isset($_POST['simpan'])) {
        echo "<script>window.location.href='?page=cuti&ttgl1=$ttgl1&ttgl2=$ttgl2';</script>";
        exit;
    } elseif (isset($_POST['print'])) {
        echo "<script>window.location.href='laporanpendapatan.php?ttgl1=$ttgl1&ttgl2=$ttgl2';</script>";
        exit;
    } elseif (isset($_POST['excel'])) {
        echo "<script>window.location.href='excelpendapatan.php?ttgl1=$ttgl1&ttgl2=$ttgl2';</script>";
        exit;
    }
}

// Query Data
$query = "SELECT A.*, 
            (SELECT COUNT(id_rkk_detail) FROM tb_rkk_detail WHERE id_rkk = A.id_rkk) as jml, 
            (SELECT SUM(upah) FROM tb_rkk_detail WHERE id_rkk = A.id_rkk) as ttl 
          FROM tb_rkk A 
          WHERE status_rkk = 2";
$tampil = $koneksi->query($query);
?>

<div class="container-fluid px-2 mt-4 mb-4">
    <div class="card border-0 shadow-sm rounded-xl overflow-hidden bg-white">
        
        <div class="border-b border-gray-100 py-4 px-5 flex justify-between items-center bg-white">
            <div>
                <h3 class="text-xl font-bold text-gray-800 m-0">Pilih Rencana Upah untuk Direalisasikan</h3>
                <p class="text-[13px] text-gray-500 mt-1 mb-0">Pilih data RKK yang sudah disetujui untuk membuat Realisasi Upah baru.</p>
            </div>
            <div>
                <a href="?page=realisasi" class="inline-flex items-center bg-gray-600 hover:bg-gray-700 text-white text-[15px] font-medium py-2 px-4 rounded shadow-sm transition-colors">
                    <i class="fas fa-arrow-left mr-1.5"></i> Kembali
                </a>
            </div>
        </div>
        
        <div class="p-0">
            <div class="table-responsive px-3 py-3">
                <table class="w-full text-left border-collapse" id="dataTables-example">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="py-2.5 px-3 text-[13px] font-bold text-gray-700 uppercase align-middle text-center w-10">No</th>
                            <th class="py-2.5 px-3 text-[13px] font-bold text-gray-700 uppercase align-middle">Tanggal</th>
                            <th class="py-2.5 px-3 text-[13px] font-bold text-gray-700 uppercase align-middle">Tgl Input</th>
                            <th class="py-2.5 px-3 text-[13px] font-bold text-gray-700 uppercase align-middle text-center">Jam Kerja</th>
                            <th class="py-2.5 px-3 text-[13px] font-bold text-gray-700 uppercase align-middle text-center">Jml Karyawan</th>
                            <th class="py-2.5 px-3 text-[13px] font-bold text-gray-700 uppercase align-middle text-right">Total Upah</th>
                            <th class="py-2.5 px-3 text-[13px] font-bold text-gray-700 uppercase align-middle">Keterangan</th>
                            <th class="py-2.5 px-3 text-[13px] font-bold text-gray-700 uppercase align-middle text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php
                        $no = 1;
                        while ($data = $tampil->fetch_assoc()) :
                        ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td data-label="No" class="py-3 px-3 text-center text-[15px] text-gray-700 align-middle"><?= $no++ ?></td>
                            <td data-label="Tanggal" class="py-3 px-3 text-[15px] font-medium text-gray-900 align-middle whitespace-nowrap"><?= htmlspecialchars($data['tgl_rkk']) ?></td>
                            <td data-label="Tgl Input" class="py-3 px-3 text-[15px] text-gray-700 align-middle whitespace-nowrap"><?= htmlspecialchars($data['detail_rkk']) ?></td>
                            <td data-label="Jam Kerja" class="py-3 px-3 text-center text-[15px] font-bold text-indigo-600 align-middle"><?= htmlspecialchars($data['jam_kerja']) ?></td>
                            <td data-label="Jml Karyawan" class="py-3 px-3 text-center align-middle">
                                <span class="px-2 py-1 rounded bg-blue-100 text-blue-800 text-[13px] font-bold"><?= $data['jml'] ?> Orang</span>
                            </td>
                            <td data-label="Total Upah" class="py-3 px-3 text-right text-[15px] font-bold text-gray-900 align-middle whitespace-nowrap">
                                Rp <?= number_format($data['ttl'] ?? 0, 0, ',', '.') ?>
                            </td>
                            <td data-label="Keterangan" class="py-3 px-3 align-middle">
                                <div class="text-[14px] text-gray-700 md:max-w-[200px] md:truncate" title="<?= htmlspecialchars($data['keterangan']) ?>">
                                    <?= htmlspecialchars($data['keterangan']) ?>
                                </div>
                            </td>
                            <td data-label="Aksi" class="py-3 px-3 text-center align-middle">
                                <a href="?page=realisasi&aksi=tambah&id=<?= $data['id_rkk']; ?>" 
                                   class="inline-flex items-center px-3 py-1.5 text-[13px] font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded shadow-sm transition-colors">
                                    Realisasi <i class="fas fa-chevron-right ml-1.5 text-[11px]"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
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

    /* RESPONSIVE TABLE "STACKED" VIEW (Mobile View) */
    @media screen and (max-width: 768px) {
        .table-responsive { 
            border: none !important; 
            overflow-x: visible !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        #dataTables-example {
            width: 100% !important;
            margin: 0 !important;
        }
        #dataTables-example thead { display: none !important; }
        #dataTables-example tbody tr {
            display: block;
            margin-bottom: 1.5rem;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 12px;
            background: #fff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        #dataTables-example tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-align: right !important;
            padding: 12px 4px !important;
            border: none !important;
            border-bottom: 1px solid #f3f4f6 !important;
            width: 100% !important;
            font-size: 14px;
        }
        #dataTables-example tbody td:last-child { 
            border-bottom: none !important; 
            margin-top: 10px; 
            justify-content: center !important; 
            display: flex !important; 
        }
        #dataTables-example tbody td:before {
            content: attr(data-label);
            font-weight: 700;
            color: #4b5563;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.05em;
            text-align: left;
            margin-right: 15px;
            flex-shrink: 0;
        }
    }
</style>

<script>
$(document).ready(function() {
    $('#dataTables-example').DataTable({
        pageLength: 25,
        autoWidth: false,
        responsive: false,
        ordering: true,
        language: {
            search: "",
            searchPlaceholder: "Cari data...",
            lengthMenu: "Tampilkan _MENU_",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_",
            paginate: { previous: "Prev", next: "Next" }
        }
    });
    $('.dataTables_filter').css('float', 'right').addClass('mb-3');
    $('.dataTables_length').css('float', 'left').addClass('mb-3');
});
</script>