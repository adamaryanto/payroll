<?php

// 1. hak Approve/Un-Approve
$is_authorized = ($_SESSION['role'] == "owner" || $_SESSION['role'] == "admin master");

// 2.hak Propose/Un-Propose (HRD & Admin Master)
$can_propose = ($_SESSION['role'] == "admin" || $_SESSION['role'] == "kepala gudang");
$tampil = $koneksi->query("SELECT A.*, (select count(id_rkk_detail) from tb_rkk_detail where id_rkk = A.id_rkk ) as jml, (select sum(upah) from tb_rkk_detail where id_rkk = A.id_rkk ) as ttl from tb_rkk A");
if ($_SESSION['role'] != "owner") {
    $level_status =  "Hidden";
} else {
    $level_status = "";
}
if ($_SESSION['role'] == "owner") {
    $hr =  "Hidden";
} else {
    $hr = "";
}

?>
<style>
    /* Card Styling */
    .panel-primary {
        border: none;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .box-header {
        padding: 15px 20px !important;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    /* Table Styling */
    .table thead th {
        background-color: #f8f9fa;
        color: #333;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 1px;
        border-bottom: 2px solid #dee2e6 !important;
        vertical-align: middle;
    }

    .table tbody td {
        vertical-align: middle !important;
        font-size: 13px;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(95, 158, 160, 0.1) !important;
        transition: 0.3s;
    }

    /* Custom Badges untuk Status */
    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: bold;
        display: inline-block;
    }

    .bg-propose {
        background-color: #FFEBCD;
        color: #856404;
        border: 1px solid #ffeeba;
    }

    .bg-accept {
        background-color: #98FB98;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .bg-reject {
        background-color: #F0FFFF;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }

    /* Button Styling */
    .btn {
        border-radius: 4px;
        font-weight: 600;
        font-size: 12px;
        transition: 0.2s;
    }

    .btn-info {
        background-color: #5bc0de;
        border: none;
    }

    .btn-info:hover {
        background-color: #31b0d5;
        transform: translateY(-1px);
    }

    .btn-warning {
        color: #fff !important;
    }

    /* Utility */
    .m-b-10 {
        margin-bottom: 10px;
    }

    .p-20 {
        padding: 20px;
    }

    /* 1. Reset wrapper agar tidak menggunakan float bawaan DataTables */
    .dataTables_wrapper {
        display: block !important;
    }

    /* 2. Memaksa area atas (Length & Filter) menjadi satu baris sejajar */
    .dataTables_wrapper::before,
    .dataTables_wrapper::after {
        display: none !important;
        /* Hapus clearfix bawaan yang mengganggu */
    }

    /* 3. Membuat container fleksibel untuk Length (kiri) dan Filter (kanan) */
    #dataTables-example_wrapper .row:first-child {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        margin-bottom: 20px !important;
        width: 100% !important;
    }

    /* 4. Styling Tampil _MENU_ (Kiri) */
    .dataTables_length {
        display: flex !important;
        align-items: center !important;
    }

    .dataTables_length label {
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        margin: 0 !important;
    }

    .dataTables_length select {
        padding: 5px 10px !important;
        border: 1px solid #e0e6ed !important;
        border-radius: 8px !important;
    }

    /* 5. Styling Cari: (Kanan) */
    .dataTables_filter {
        text-align: right !important;
        display: flex !important;
        justify-content: flex-end !important;
    }

    .dataTables_filter label {
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        margin: 0 !important;
    }

    .dataTables_filter input {
        padding: 6px 12px !important;
        border: 1px solid #e0e6ed !important;
        border-radius: 8px !important;
        width: 200px !important;
    }

    /* --- STYLING PAGINATE (PREV/NEXT) --- */
    .dataTables_wrapper .dataTables_paginate {
        display: flex !important;
        justify-content: flex-end !important;
        align-items: center !important;
        gap: 4px !important;
        padding-top: 15px !important;
    }

    .dataTables_paginate .paginate_button {
        border: 1px solid #e2e8f0 !important;
        background: white !important;
        border-radius: 6px !important;
        padding: 5px 12px !important;
        color: #475569 !important;
        font-weight: 500 !important;
        cursor: pointer !important;
        transition: all 0.2s !important;
    }

    .dataTables_paginate .paginate_button:hover {
        background: #f8fafc !important;
        color: #2563eb !important;
        border-color: #cbd5e1 !important;
    }

    h3 {
        color: #2563eb !important;
    }

    .dataTables_paginate .paginate_button.current {
        background: #2563eb !important;
        border-color: #2563eb !important;
        color: white !important;
    }

    .dataTables_paginate .paginate_button.disabled {
        background: #f1f5f9 !important;
        color: #94a3b8 !important;
        cursor: not-allowed !important;
    }

    /* --- STYLING INFO --- */
    .dataTables_wrapper .dataTables_info {
        padding-top: 20px !important;
        color: #64748b !important;
        font-size: 13px !important;
    }

    @media screen and (max-width: 768px) {
        .table-responsive {
            padding: 12px !important;
        }

        .table-modern thead {
            display: none !important;
        }

        .table-modern tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 10px;
        }

        .table-modern tbody td {
            display: flex;
            align-items: flex-start;
            padding: 8px 10px !important;
            border: none !important;
            border-bottom: 1px solid #f3f4f6 !important;
        }

        .table-modern tbody td:before {
            content: attr(data-label);
            font-weight: 700;
            color: #4b5563;
            text-transform: uppercase;
            font-size: 11px;
            min-width: 120px;
            margin-right: 15px;
        }

        h3 {
            color: #2563eb !important;
        }
    }
</style>

<div class="container-fluid px-2 mt-4 mb-4">
    <div class="card border-0 shadow-sm rounded-xl overflow-hidden bg-white">

        <div class="border-b border-gray-100 py-4 px-5 flex justify-between items-center bg-white">
            <div>
                <h3 class="text-xl font-bold text-indigo-600 m-0"><i class="fas fa-list-alt mr-2"></i>List Rencana Upah</h3>
            </div>
            <div>
                <a href="?page=rkk&aksi=tambah" class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white text-[15px] font-medium py-2 px-4 rounded shadow-sm transition-colors">
                    <i class="fas fa-plus mr-1.5"></i> Tambah Data
                </a>
            </div>
        </div>

        <div class="p-0">
            <div class="table-responsive px-3 py-3">
                <table class="w-full text-left border-collapse" id="dataTables-example">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="py-2 px-2 text-[13px] font-bold text-gray-700 uppercase text-center">No</th>
                            <th class="py-2 px-2 text-[13px] font-bold text-gray-700 uppercase">Tanggal</th>
                            <th class="py-2 px-2 text-[13px] font-bold text-gray-700 uppercase">Jam</th>
                            <th class="py-2 px-2 text-[13px] font-bold text-gray-700 uppercase text-center">Karyawan</th>
                            <th class="py-2 px-2 text-[13px] font-bold text-gray-700 uppercase text-right">Total Upah</th>
                            <th class="py-2 px-2 text-[13px] font-bold text-gray-700 uppercase">Keterangan</th>
                            <th class="py-2 px-2 text-[13px] font-bold text-gray-700 uppercase text-center">Aksi</th>

                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php
                        $no = 1;
                        while ($data = $tampil->fetch_assoc()) :
                            $status_color = ($data['status_rkk'] == "3") ? "bg-emerald-100 text-emerald-800" : "bg-amber-100 text-amber-800";
                        ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-2.5 px-2 text-center text-[15px]"><?= $no++ ?></td>
                                <td class="py-2.5 px-2 text-[15px] font-medium"><?= $data['tgl_rkk'] ?></td>
                                <td class="py-2.5 px-2 text-[15px]"><?= $data['jam_kerja'] ?> Jam</td>
                                <td class="py-2.5 px-2 text-center text-[15px]"><?= $data['jml'] ?></td>
                                <td class="py-2.5 px-2 text-right text-[15px] font-bold">Rp <?= number_format($data['ttl'] ?? 0, 0, ',', '.') ?></td>
                                <td class="py-2.5 px-2 text-[14px] text-gray-600"><?= $data['keterangan'] ?></td>
                                <td class="py-2.5 px-2 align-middle text-center">
                                    <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                        <a href="?page=rkk&aksi=kelola&id=<?= $data['id_rkk']; ?>"
                                            class="px-2 py-1 text-[12px] font-bold text-blue-600 bg-blue-50 hover:bg-blue-600 hover:text-white rounded border border-blue-200">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                        <?php if ($_SESSION['role'] == "owner") : ?>
                                            <a href="?page=rkk&aksi=karyawan&id=<?= $data['id_rkk']; ?>"
                                                class="px-2 py-1 text-[12px] font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-600 hover:text-white rounded border border-indigo-200">
                                                <i class="fas fa-user-plus"></i> Tetapkan</a>
                                        <?php endif; ?>

                                        <?php if ($data['status_rkk'] == '0' && $can_propose) : ?>
                                            <a href="?page=rkk&aksi=accept&id=<?= $data['id_rkk']; ?>&iddetail=pro"
                                                class="px-2 py-1 text-[12px] font-bold text-amber-600 bg-amber-50 hover:bg-amber-600 hover:text-white rounded border border-amber-200"
                                                onclick="return confirm('Propose data ini?');"><i class="fas fa-paper-plane"></i> Propose</a>
                                        <?php endif; ?>

                                        <?php if ($data['status_rkk'] == '1' && $can_propose) : ?>
                                            <a href="?page=rkk&aksi=accept&id=<?= $data['id_rkk']; ?>&iddetail=unpro"
                                                class="px-2 py-1 text-[12px] font-bold text-gray-600 bg-gray-50 hover:bg-gray-600 hover:text-white rounded border border-gray-200"
                                                onclick="return confirm('Tarik kembali data (Un-propose)?');"><i class="fas fa-undo"></i> Un-Propose</a>
                                        <?php endif; ?>

                                        <?php if (($data['status_rkk'] == '1' && $is_authorized) || ($data['status_rkk'] == '0' && $_SESSION['role'] == "owner")) : ?>
                                            <a href="?page=rkk&aksi=accept&id=<?= $data['id_rkk']; ?>&iddetail=app"
                                                class="px-2 py-1 text-[12px] font-bold text-emerald-600 bg-emerald-50 hover:bg-emerald-600 hover:text-white rounded border border-emerald-200"
                                                onclick="return confirm('Approve data ini?');"><i class="fas fa-check"></i> Approve</a>
                                        <?php endif; ?>

                                        <?php if ($data['status_rkk'] == '2' && $is_authorized) : ?>
                                            <a href="?page=rkk&aksi=accept&id=<?= $data['id_rkk']; ?>&iddetail=unapp"
                                                class="px-2 py-1 text-[12px] font-bold text-rose-600 bg-rose-50 hover:bg-rose-600 hover:text-white rounded border border-rose-200"
                                                onclick="return confirm('Batalkan Approve data ini?');"><i class="fas fa-times"></i> Un-Approve</a>
                                        <?php endif; ?>

                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            pageLength: 25,
            autoWidth: false,
            responsive: false,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],
            language: {
                search: "Cari:",
                searchPlaceholder: "Cari data...",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ s/d _END_ dari _TOTAL_ data",
                paginate: {
                    previous: "Prev",
                    next: "Next"
                }
            }
        });
        $('.dataTables_filter').css('float', 'right').addClass('mb-3');
        $('.dataTables_length').css('float', 'left').addClass('mb-3');
    });
</script>

<?php

$ttgl1 = @$_POST['ttgl1'];
$ttgl2 = @$_POST['ttgl2'];

$simpan = @$_POST['simpan'];
$print = @$_POST['print'];
$excel = @$_POST['excel'];

if ($simpan) {
?><script type="text/javascript">
        window.location.href = "?page=cuti&ttgl1=<?php echo $ttgl1; ?>&ttgl2=<?php echo $ttgl2; ?>";
    </script>
<?php
}
if ($print) {
?><script type="text/javascript">
        window.location.href = "laporanpendapatan.php?ttgl1=<?php echo $ttgl1; ?>&ttgl2=<?php echo $ttgl2; ?>";
    </script>
<?php
}
if ($excel) {
?><script type="text/javascript">
        window.location.href = "excelpendapatan.php?ttgl1=<?php echo $ttgl1; ?>&ttgl2=<?php echo $ttgl2; ?>";
    </script>
<?php
}
?>