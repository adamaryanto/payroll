<?php


$tampil = $koneksi->query("SELECT A.*, (select count(id_rkk_detail) from tb_rkk_detail where id_rkk = A.id_rkk ) as jml, (select sum(upah) from tb_rkk_detail where id_rkk = A.id_rkk ) as ttl from tb_rkk A");
if ($_SESSION['level'] != "OWNER") {
    $level =  "Hidden";
} else {
    $level = "";
}
if ($_SESSION['level'] == "OWNER") {
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

    /* Container Utama */
    .dataTables_wrapper {
        width: 100%;
        margin-top: 10px;
    }

    /* Memperbaiki baris bawah (Info & Paginate) */
    .dataTables_wrapper .row:last-child {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        padding: 10px 0;
    }

    /* Info: Menampilkan halaman x dari y */
    .dataTables_wrapper .dataTables_info {
        padding-top: 0 !important;
        font-size: 13px;
        color: #666;
    }

    /* Paginate Wrapper: Paksa ke Kanan */
    .dataTables_wrapper .dataTables_paginate {
        display: flex !important;
        justify-content: flex-end !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    /* Styling Tombol Paginate (Kotak) */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 5px 12px !important;
        margin: 0 2px !important;
        border: 1px solid #ddd !important;
        border-radius: 4px !important;
        background: #fff !important;
        color: #337ab7 !important;
        cursor: pointer !important;
        text-decoration: none !important;
        display: inline-block !important;
        min-width: 35px;
        text-align: center;
    }

    /* Tombol Aktif (Halaman Sekarang) */
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background-color: #5F9EA0 !important;
        color: white !important;
        border-color: #5F9EA0 !important;
        font-weight: bold;
    }

    /* Efek Hover */
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background-color: #eee !important;
        border-color: #ccc !important;
        color: #23527c !important;
    }

    /* Sembunyikan garis/border default DataTables jika ada */
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        cursor: not-allowed !important;
        color: #ccc !important;
        background: #fafafa !important;
    }

</style>

<div class="row">
    <div class="col-md-12">
        <!-- Advanced Tables -->
        <div class="panel panel-primary custom-card">
            <div class="box-header with-border d-flex justify-content-between align-items-center"
                style="background-color:#5F9EA0; color:white; padding: 10px 15px; border-radius: 12px 12px 0 0;">

                <h3 class="box-title" style="margin: 0; font-size: 18px; font-weight: 600;">
                    <i class="fa fa-list-alt"></i> List Rencana Upah
                </h3>

                <a href="?page=rkk&aksi=tambah" class="btn btn-info"
                    style="border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border: none;">
                    <i class="fa fa-plus-circle"></i> &nbsp;
                    <span class="d-none d-md-inline">&nbsp;Tambah Data Rencana Upah</span>
                    <span class="d-inline d-md-none">&nbsp;Tambah</span>
                </a>
            </div>
        </div>

        <div class="table-responsive">

            <table class="table table-bordered table-striped" id="dataTables-example">
                <thead>
                    <tr class="text-center">
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Tanggal Input</th>
                        <th>Jam Kerja</th>
                        <th>Karyawan</th>
                        <th>Total Upah</th>
                        <th>Keterangan</th>
                        <th width="15%">Aksi Data</th>
                        <th width="15%">Otorisasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while ($data = $tampil->fetch_assoc()) {
                        // Logika Warna & Tombol
                        if ($data['status_rkk'] == "1") {
                            $bg = "#FFEBCD";
                            $pro = "hidden";
                            $app = "";
                            $unpro = "";
                            $unapp = "hidden";
                        } elseif ($data['status_rkk'] == "2") {
                            $bg = "#F0FFFF";
                            $pro = "hidden";
                            $app = "hidden";
                            $unpro = "hidden";
                            $unapp = "";
                        } elseif ($data['status_rkk'] == "3") {
                            $bg = "#98FB98";
                            $pro = "hidden";
                            $app = "hidden";
                            $unpro = "hidden";
                            $unapp = "hidden";
                        } else {
                            $bg = "transparent";
                            $pro = "";
                            $app = "hidden";
                            $unpro = "hidden";
                            $unapp = "hidden";
                        }
                    ?>
                        <tr style="background-color:<?php echo $bg ?>; color:black;">
                            <td class="text-center"><?php echo $no++; ?></td>
                            <td class="text-center"><strong><?php echo $data['tgl_rkk'] ?></strong></td>
                            <td class="text-center"><?php echo $data['detail_rkk'] ?></td>
                            <td class="text-center"><?php echo $data['jam_kerja'] ?> Jam</td>
                            <td class="text-center"><?php echo $data['jml'] ?> Org</td>
                            <td>Rp <?php echo number_format($data['ttl'] ?? 0, 0, ',', '.') ?></td>
                            <td><?php echo $data['keterangan'] ?></td>

                            <td class="text-center">
                                <a href="?page=rkk&aksi=kelola&id=<?php echo $data['id_rkk']; ?>" class="btn btn-warning btn-xs">
                                    <i class="fa fa-search"></i> <span class="d-none d-lg-inline"> Detail </span>
                                </a>
                                <a href="excelrkk.php?id=<?php echo $data['id_rkk']; ?>" class="btn btn-info btn-xs">
                                    <i class="fa fa-print"></i> <span class="d-none d-lg-inline">Cetak </span>
                                </a>
                            </td>

                            <td class="text-center">
                                <div <?php echo $hr ?>>
                                    <div <?php echo $pro ?>>
                                        <a href="?page=rkk&aksi=accept&id=<?php echo $data['id_rkk']; ?>&iddetail=pro"
                                            class="btn btn-danger btn-xs" onclick="return confirm('Propose data ini?');">Propose</a>
                                    </div>
                                    <div <?php echo $unpro ?>>
                                        <a href="?page=rkk&aksi=accept&id=<?php echo $data['id_rkk']; ?>&iddetail=unpro"
                                            class="btn btn-default btn-xs" onclick="return confirm('Batalkan Propose?');">UnPropose</a>
                                    </div>
                                </div>

                                <div <?php echo $level ?>>
                                    <div <?php echo $app ?>>
                                        <a href="?page=rkk&aksi=accept&id=<?php echo $data['id_rkk']; ?>&iddetail=app"
                                            class="btn btn-success btn-xs" onclick="return confirm('Approve data ini?');">Approve</a>
                                    </div>
                                    <div <?php echo $unapp ?>>
                                        <a href="?page=rkk&aksi=accept&id=<?php echo $data['id_rkk']; ?>&iddetail=unapp"
                                            class="btn btn-default btn-xs" onclick="return confirm('Batalkan Approve?');">Un-Approve</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    </div>
</div>
</div>
</div>

<script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            "pageLength": 10,
            "searching": true,
            "ordering": true,
            "dom": '<"row"<"col-sm-6"l><"col-sm-6 text-right pull-right"f>>rt<"row"<"col-sm-6"i><"col-sm-6 text-right"p>>',
            "language": {
                "search": "Cari Data:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Data tidak ditemukan",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data tersedia",
                "infoFiltered": "(disaring dari _MAX_ total data)",
                "paginate": {
                    "next": ">",
                    "previous": "<"
                }
            }
        });
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