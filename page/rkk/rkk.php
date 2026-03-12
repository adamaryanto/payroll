<?php

// 1. hak Approve/Un-Approve
$is_authorized = (strtolower($_SESSION['role']) == "owner" || strtolower($_SESSION['role']) == "admin master");

// 2.hak Propose/Un-Propose (HRD & Admin Master)
$can_propose = (strtolower($_SESSION['role']) == "admin hr" || strtolower($_SESSION['role']) == "kepala pabrik");

$where_rkk = (strtolower($_SESSION['role']) == 'owner') ? " WHERE A.status_rkk > 0 " : "";
$tampil = $koneksi->query("SELECT A.*, (select count(id_rkk_detail) from tb_rkk_detail where id_rkk = A.id_rkk and status_rkk != 'Digantikan' ) as jml, (select sum(case when status_rkk = 'Digantikan' then 0 else upah end) from tb_rkk_detail where id_rkk = A.id_rkk ) as ttl from tb_rkk A $where_rkk");
if (strtolower($_SESSION['role']) != "owner") {
    $level_status =  "Hidden";
} else {
    $level_status = "";
}
if (strtolower($_SESSION['role']) == "owner") {
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

    /* Table Styling Default */
    .table thead th {
        background-color: #f8f9fa;
        color: #333;
        text-transform: uppercase;
        font-size: 13px; /* Diperbesar */
        letter-spacing: 0.5px;
        border-bottom: 2px solid #dee2e6 !important;
        vertical-align: middle;
    }

    .table tbody td {
        vertical-align: middle !important;
        font-size: 14px; /* Diperbesar */
    }

    .table-hover tbody tr:hover {
        background-color: rgba(95, 158, 160, 0.1) !important;
        transition: 0.3s;
    }

     /* 1. Reset wrapper agar tidak menggunakan float bawaan DataTables */
    .dataTables_wrapper {
        display: block !important;
    }

    /* 2. Memaksa area atas (Length & Filter) menjadi satu baris sejajar */
    .dataTables_wrapper::before,
    .dataTables_wrapper::after {
        display: none !important;
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

    h3{
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

    /* =========================================
       KHUSUS TAMPILAN MOBILE DIPERBAIKI DI SINI
       ========================================= */
    @media screen and (max-width: 768px) {
        .table-responsive {
            padding: 12px !important;
        }
        
        #dataTables-example_wrapper .row:first-child {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 15px;
        }
        .dataTables_filter, .dataTables_length {
            width: 100% !important;
            justify-content: flex-start !important;
        }
        .dataTables_filter input {
            width: 100% !important;
            max-width: 100% !important;
        }
        .dataTables_paginate {
            justify-content: center !important;
            flex-wrap: wrap;
        }

        .table-modern thead {
            display: none !important;
        }

        .table-modern tbody tr {
            display: block;
            margin-bottom: 1.5rem; /* Jarak antar kotak dilebarkan */
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px; /* Jarak padding ke dalam kotak dilebarkan */
            box-shadow: 0 2px 10px rgba(0,0,0,0.04);
            background-color: #fff;
        }

        .table-modern tbody td {
            display: flex;
            flex-direction: column; /* Label di atas, data di bawah (stacking) */
            align-items: flex-start;
            padding: 10px 0 !important; /* Jarak atas-bawah per baris dilebarkan */
            text-align: left !important;
            border: none !important;
            border-bottom: 1px dashed #e2e8f0 !important;
        }
        .table-modern tbody td:first-child {
            padding-top: 0 !important;
        }
        .table-modern tbody td:last-child {
            border-bottom: none !important;
            padding-bottom: 0 !important;
        }

        .table-modern tbody td:before {
            content: attr(data-label);
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            margin-bottom: 6px; /* Memberi jarak ke datanya */
            display: block;
            width: 100%;
        }

        /* Memperbesar Tombol Aksi di Mobile */
        .action-btn-group {
            width: 100%;
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 8px;
            padding-top: 5px;
            justify-content: flex-start;
        }
        .action-btn-group > a, .action-btn-group > div {
            flex: 0 0 auto;
        }
        .action-btn-group a {
            padding: 8px 12px !important;
            width: auto;
        }

        h3 {
            color: #2563eb !important;
        }
    }
    /* --- STYLING STEMPEL (STAMP) --- */
    .stamp {
        display: inline-block;
        padding: 5px 15px;
        border: 4px solid;
        border-radius: 10px;
        font-family: 'Courier New', Courier, monospace;
        font-weight: 900;
        text-transform: uppercase;
        font-size: 16px;
        transform: rotate(-15deg);
        opacity: 0.8;
        letter-spacing: 2px;
        user-select: none;
        margin: 10px;
    }
    .stamp-approved {
        color: #059669;
        border-color: #059669;
        box-shadow: 0 0 0 2px #059669;
    }
    .stamp-unapproved {
        color: #dc2626;
        border-color: #dc2626;
        box-shadow: 0 0 0 2px #dc2626;
    }
    @media screen and (max-width: 768px) {
        .stamp {
            transform: rotate(-12deg); /* Tetap miring sedikit di mobile */
            margin: 15px 0;
            font-size: 14px;
            padding: 4px 12px;
            display: inline-block;
            align-self: center; /* Diposisikan ke tengah kotak mobile */
        }
    }
</style>

<div class="container-fluid px-3 mt-4 mb-4">
    <div class="card border-0 shadow-sm rounded-xl overflow-hidden bg-white">

        <div class="border-b border-gray-200 py-4 px-4 md:px-5 flex flex-col md:flex-row justify-between items-start md:items-center bg-white gap-4">
            <div>
                <h3 class="text-xl font-bold text-indigo-600 m-0"><i class="fas fa-list-alt mr-2"></i>List Rencana Upah</h3>
            </div>
            <div class="w-full md:w-auto">
                <a href="?page=rkk&aksi=tambah" class="flex md:inline-flex justify-center items-center bg-indigo-600 hover:bg-indigo-700 text-white text-[15px] font-medium py-2.5 px-5 rounded shadow-sm transition-colors w-full md:w-auto">
                    <i class="fas fa-plus mr-1.5"></i> Tambah Data
                </a>
            </div>
        </div>

        <div class="p-0">
            <div class="table-responsive px-3 md:px-4 py-4">
                <table class="w-full text-left border-collapse table-modern" id="dataTables-example">
                    <thead class="bg-gray-100 border-b border-gray-300">
                        <tr>
                            <th class="py-3 px-2 text-sm font-bold text-gray-700 uppercase">No</th>
                            <th class="py-3 px-2 text-sm font-bold text-gray-700 uppercase">Tanggal</th>
                            <th class="py-3 px-2 text-sm font-bold text-gray-700 uppercase">Keterangan</th>
                            <th class="py-3 px-2 text-sm font-bold text-gray-700 uppercase text-center">Jumlah Karyawan</th>
                            <th class="py-3 px-2 text-sm font-bold text-gray-700 uppercase text-center">Aksi Data</th>
                            <th class="py-3 px-2 text-sm font-bold text-gray-700 uppercase text-center">Otorisasi</th>
                            <th class="py-3 px-2 text-sm font-bold text-gray-700 uppercase text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php
                        $no = 1;
                        while ($data = $tampil->fetch_assoc()) :
                            $status_color = ($data['status_rkk'] == "3") ? "bg-emerald-100 text-emerald-800" : "bg-amber-100 text-amber-800";
                        ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td data-label="No" class="py-3 px-2 text-center text-base md:text-[15px] font-bold md:font-normal"><?= $no++ ?></td>
                                <td data-label="Tanggal" class="py-3 px-2 text-base md:text-[15px] font-bold text-gray-900"><?= $data['tgl_rkk'] ?></td>
                                <td data-label="Keterangan" class="py-3 px-2 text-base md:text-[14px] text-gray-700"><?= $data['keterangan'] ?></td>
                                <td data-label="Jumlah Karyawan" class="py-3 px-2 md:text-center text-base md:text-[15px]">
                                    <span class="font-bold text-indigo-700"><?= $data['jml'] ?> Orang</span><br>
                                    <span class="text-emerald-600 font-semibold text-sm">Rp <?= number_format($data['ttl'] ?? 0, 0, ',', '.') ?></span>
                                </td>
                                
                                <td data-label="Aksi Data" class="py-3 px-2 align-middle">
                                    <div class="action-btn-group md:justify-center">
                                        <a href="?page=rkk&aksi=kelola&id=<?= $data['id_rkk']; ?>"
                                            class="flex items-center px-3 py-2 text-[13px] md:text-[12px] font-bold text-blue-700 bg-blue-50 hover:bg-blue-600 hover:text-white rounded border border-blue-300 transition-colors">
                                            <i class="fas fa-eye mr-1"></i> Detail
                                        </a>
                                        <a href="excelrkk.php?id=<?php echo $data['id_rkk']; ?>"
                                            class="flex items-center px-3 py-2 text-[13px] md:text-[12px] font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-600 hover:text-white rounded border border-emerald-300 transition-colors">
                                            <i class="fa fa-print mr-1"></i> Cetak
                                        </a>
                                        <?php if (strtolower($_SESSION['role']) == "owner") : ?>
                                            <a href="?page=rkk&aksi=karyawan&id=<?= $data['id_rkk']; ?>"
                                                class="flex items-center px-3 py-2 text-[13px] md:text-[12px] font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-600 hover:text-white rounded border border-indigo-300 transition-colors">
                                                <i class="fas fa-user-plus mr-1"></i> Tetapkan
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                
                                <td data-label="Otorisasi" class="py-3 px-2 align-middle">
                                    <div class="action-btn-group md:justify-center">
                                        <?php if ($data['status_rkk'] == '0' && $can_propose) : ?>
                                            <a href="?page=rkk&aksi=accept&id=<?= $data['id_rkk']; ?>&iddetail=pro"
                                                class="flex items-center px-3 py-2 text-[13px] md:text-[12px] font-bold text-amber-700 bg-amber-50 hover:bg-amber-600 hover:text-white rounded border border-amber-300 transition-colors"
                                                onclick="return confirm('Propose data ini?');"><i class="fas fa-paper-plane mr-1"></i> Propose</a>
                                        <?php endif; ?>

                                        <?php if ($data['status_rkk'] == '1' && $can_propose) : ?>
                                            <a href="?page=rkk&aksi=accept&id=<?= $data['id_rkk']; ?>&iddetail=unpro"
                                                class="flex items-center px-3 py-2 text-[13px] md:text-[12px] font-bold text-gray-700 bg-gray-50 hover:bg-gray-600 hover:text-white rounded border border-gray-300 transition-colors"
                                                onclick="return confirm('Tarik kembali data (Un-propose)?');"><i class="fas fa-undo mr-1"></i> Un-Propose</a>
                                        <?php endif; ?>

                                        <?php if (($data['status_rkk'] == '1' && $is_authorized) || ($data['status_rkk'] == '0' && strtolower($_SESSION['role']) == "owner")) : ?>
                                            <a href="?page=rkk&aksi=accept&id=<?= $data['id_rkk']; ?>&iddetail=app"
                                                class="flex items-center px-3 py-2 text-[13px] md:text-[12px] font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-600 hover:text-white rounded border border-emerald-300 transition-colors"
                                                onclick="return confirm('Approve data ini?');"><i class="fas fa-check mr-1"></i> Approve</a>
                                        <?php endif; ?>

                                        <?php if (($data['status_rkk'] == '2' || $data['status_rkk'] == '3') && $is_authorized) : ?>
                                            <a href="?page=rkk&aksi=accept&id=<?= $data['id_rkk']; ?>&iddetail=unapp"
                                                class="flex items-center px-3 py-2 text-[13px] md:text-[12px] font-bold text-rose-700 bg-rose-50 hover:bg-rose-600 hover:text-white rounded border border-rose-300 transition-colors"
                                                onclick="return confirm('Batalkan Approve data ini?');"><i class="fas fa-times mr-1"></i> Un-Approve</a>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <td data-label="Status" class="py-3 px-2 text-center align-middle">
                                    <?php if ($data['status_rkk'] >= 2) : ?>
                                        <div class="stamp stamp-approved">Approved</div>
                                    <?php else : ?>
                                        <div class="stamp stamp-unapproved">Unapproved</div>
                                    <?php endif; ?>
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
                search: "Cari Data:",
                searchPlaceholder: "Ketik pencarian...",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ s/d _END_ dari _TOTAL_ data",
                paginate: {
                    previous: "Prev",
                    next: "Next"
                }
            }
        });
        
        $('.dataTables_filter').addClass('mb-3');
        $('.dataTables_length').addClass('mb-3');
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