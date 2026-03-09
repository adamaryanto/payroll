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

    /* DataTables Styling Update untuk Mobile */
    .dataTables_wrapper {
        display: block !important;
    }

    .dataTables_wrapper::before,
    .dataTables_wrapper::after {
        display: none !important;
    }

    /* Flexbox pembungkus Search & Length */
    #dataTables-example_wrapper .row:first-child {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        flex-wrap: wrap !important;
        gap: 15px;
        margin-bottom: 20px !important;
        width: 100% !important;
    }

    .dataTables_length, .dataTables_filter {
        display: flex !important;
        align-items: center !important;
    }
    
    .dataTables_filter {
        justify-content: flex-end !important;
    }

    .dataTables_length select, .dataTables_filter input {
        padding: 8px 12px !important;
        border: 1px solid #e0e6ed !important;
        border-radius: 8px !important;
        font-size: 14px !important;
    }

    .dataTables_filter input {
        width: 100%;
        max-width: 250px;
    }

    /* Pagination & Info */
    .dataTables_wrapper .dataTables_paginate {
        display: flex !important;
        justify-content: center !important;
        flex-wrap: wrap !important;
        gap: 6px !important;
        padding-top: 15px !important;
    }

    .dataTables_paginate .paginate_button {
        border: 1px solid #e2e8f0 !important;
        background: white !important;
        border-radius: 6px !important;
        padding: 8px 14px !important;
        color: #475569 !important;
        font-weight: 500 !important;
        cursor: pointer !important;
        font-size: 14px;
    }

    .dataTables_paginate .paginate_button.current {
        background: #2563eb !important;
        border-color: #2563eb !important;
        color: white !important;
    }

    .dataTables_wrapper .dataTables_info {
        padding-top: 15px !important;
        color: #64748b !important;
        font-size: 14px !important;
        text-align: center;
    }

    /* Responsive Mobile View Khusus Tabel */
    @media screen and (max-width: 768px) {
        #dataTables-example_wrapper .row:first-child {
            flex-direction: column !important;
            align-items: flex-start !important;
        }
        .dataTables_length, .dataTables_filter {
            width: 100% !important;
            justify-content: flex-start !important;
        }
        .dataTables_filter input {
            width: 100% !important;
            max-width: 100%;
        }

        .table-modern thead {
            display: none !important;
        }

        .table-modern tbody tr {
            display: block;
            margin-bottom: 1.5rem;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            padding: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .table-modern tbody td {
            display: flex;
            flex-direction: column;
            padding: 10px 0 !important;
            border: none !important;
            border-bottom: 1px solid #f1f5f9 !important;
            font-size: 15px; /* Teks lebih besar untuk orang tua */
            text-align: left !important;
        }

        .table-modern tbody td:last-child {
            border-bottom: none !important;
        }

        .table-modern tbody td:before {
            content: attr(data-label);
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            font-size: 12px;
            margin-bottom: 6px; /* Jarak antara label dan isi */
        }

        .btn-action-group {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 10px;
            width: 100%;
            margin-top: 5px;
        }

        .btn-action-group a {
            flex: 1 1 auto;
            text-align: center;
            justify-content: center;
            padding: 10px !important; /* Area sentuh jari lebih besar */
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
                            <th class="py-3 px-2 text-sm font-bold text-gray-700 uppercase text-center">No</th>
                            <th class="py-3 px-2 text-sm font-bold text-gray-700 uppercase">Tanggal</th>
                            <th class="py-3 px-2 text-sm font-bold text-gray-700 uppercase">Keterangan</th>
                            <th class="py-3 px-2 text-sm font-bold text-gray-700 uppercase text-center">Jumlah Karyawan</th>
                            <th class="py-3 px-2 text-sm font-bold text-gray-700 uppercase text-center">Aksi Data</th>
                            <th class="py-3 px-2 text-sm font-bold text-gray-700 uppercase text-center">Otorisasi</th>
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
                                    <div class="btn-action-group md:justify-center">
                                        <a href="?page=rkk&aksi=kelola&id=<?= $data['id_rkk']; ?>"
                                            class="flex items-center px-3 py-2 text-[13px] md:text-[12px] font-bold text-blue-700 bg-blue-50 hover:bg-blue-600 hover:text-white rounded border border-blue-300 transition-colors">
                                            <i class="fas fa-eye mr-1"></i> Detail
                                        </a>
                                        <a href="excelrkk.php?id=<?php echo $data['id_rkk']; ?>"
                                            class="flex items-center px-3 py-2 text-[13px] md:text-[12px] font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-600 hover:text-white rounded border border-emerald-300 transition-colors">
                                            <i class="fa fa-print mr-1"></i> Cetak
                                        </a>
                                        <?php if ($_SESSION['role'] == "owner") : ?>
                                            <a href="?page=rkk&aksi=karyawan&id=<?= $data['id_rkk']; ?>"
                                                class="flex items-center px-3 py-2 text-[13px] md:text-[12px] font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-600 hover:text-white rounded border border-indigo-300 transition-colors">
                                                <i class="fas fa-user-plus mr-1"></i> Tetapkan
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                
                                <td data-label="Otorisasi" class="py-3 px-2 align-middle">
                                    <div class="btn-action-group md:justify-center">
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

                                        <?php if (($data['status_rkk'] == '1' && $is_authorized) || ($data['status_rkk'] == '0' && $_SESSION['role'] == "owner")) : ?>
                                            <a href="?page=rkk&aksi=accept&id=<?= $data['id_rkk']; ?>&iddetail=app"
                                                class="flex items-center px-3 py-2 text-[13px] md:text-[12px] font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-600 hover:text-white rounded border border-emerald-300 transition-colors"
                                                onclick="return confirm('Approve data ini?');"><i class="fas fa-check mr-1"></i> Approve</a>
                                        <?php endif; ?>

                                        <?php if ($data['status_rkk'] == '2' && $is_authorized) : ?>
                                            <a href="?page=rkk&aksi=accept&id=<?= $data['id_rkk']; ?>&iddetail=unapp"
                                                class="flex items-center px-3 py-2 text-[13px] md:text-[12px] font-bold text-rose-700 bg-rose-50 hover:bg-rose-600 hover:text-white rounded border border-rose-300 transition-colors"
                                                onclick="return confirm('Batalkan Approve data ini?');"><i class="fas fa-times mr-1"></i> Un-Approve</a>
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
                search: "Cari Data:",
                searchPlaceholder: "Ketik pencarian...",
                lengthMenu: "Tampilkan _MENU_ baris",
                info: "Menampilkan _START_ s/d _END_ dari _TOTAL_ data",
                paginate: {
                    previous: "« Prev",
                    next: "Next »"
                }
            }
        });
        
        // Dihapus style .css('float') yang bisa mengganggu flexbox responsif
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