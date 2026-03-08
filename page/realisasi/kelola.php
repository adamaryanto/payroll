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
        $status = "hidden";
    } else {
        $status = "";
    }
} else {
    $status = "";
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
?>

<style>
    .card-clean {
        background: #fff;
        border: 1px solid #E0E4E8;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.03);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .card-header-clean {
        background-color: #2C3E50;
        color: #ffffff;
        padding: 12px 15px;
        font-weight: 600;
        font-size: 15px;
    }

    .section-title {
        color: #34495E;
        border-left: 4px solid #3498DB;
        padding-left: 8px;
        font-weight: bold;
        margin-bottom: 10px;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-group {
        margin-bottom: 10px;
    }

    .form-control-clean {
        border-radius: 4px;
        border: 1px solid #CCD1D1;
        padding: 6px 10px;
        background-color: #FAFAFA;
        width: 100%;
        font-size: 13px;
    }

    .form-control-clean:focus {
        border-color: #3498DB;
        box-shadow: none;
        background-color: #FFFFFF;
    }

    .total-box {
        background-color: #EAF2F8;
        border: 1px solid #AED6F1;
        color: #1A5276;
        height: 45px;
        font-size: 18px;
        font-weight: bold;
        text-align: right;
        border-radius: 6px;
        padding-right: 15px;
    }

    /* Styling Dasar Table */
    #dataTables-example {
        border-collapse: separate !important;
        border-spacing: 0 !important;
        width: 100%;
    }

    .table-clean tbody td {
        vertical-align: middle;
        font-size: 12px;
        color: #000;
        font-weight: bold;
        border-top: 1px solid #E0E4E8;
        padding: 6px;
    }

    .bg-red-custom {
        background-color: #e74c3c !important;
        color: #fff !important;
    }

    .bg-yellow-custom {
        background-color: #f1c40f !important;
        color: #000 !important;
    }

    /* Form Filter & Length Menu DataTables */
    .dataTables_wrapper .dataTables_length select {
        border-radius: 6px;
        border: 1px solid #e5e7eb;
        padding: 2px 6px;
        margin: 0 5px;
    }

    .dataTables_wrapper .dataTables_filter input {
        border-radius: 6px !important;
        border: 1px solid #e5e7eb !important;
        padding: 6px 10px !important;
        outline: none;
        transition: all 0.2s;
    }

    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #3498DB !important;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    }

    /* PAGINATION STYLING DataTables */
    .dataTables_wrapper .dataTables_paginate {
        padding-top: 1rem !important;
        padding-bottom: 1rem !important;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 2px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border: 1px solid #e5e7eb !important;
        background: white !important;
        border-radius: 6px !important;
        padding: 4px 10px !important;
        color: #4b5563 !important;
        font-weight: 500 !important;
        font-size: 12px;
        cursor: pointer;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #3498DB !important;
        border-color: #3498DB !important;
        color: white !important;
    }

    /* RESPONSIVE TABLE "STACKED" VIEW (Mobile View) - DIPERBAIKI */
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

        #dataTables-example tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 10px;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        /* Flexbox agar data & label proporsional */
        #dataTables-example thead {
            display: none !important;
        }

        #dataTables-example tbody td {
            display: flex;
            align-items: flex-start;
            text-align: left !important;
            padding: 8px 10px !important;
            border: none !important;
            border-bottom: 1px solid #f3f4f6 !important;
            width: 100% !important;
            font-size: 13px;
        }

        #dataTables-example tbody td:last-child {
            border-bottom: none !important;
            padding-top: 12px !important;
            justify-content: flex-start !important;
        }

        #dataTables-example tbody td:before {
            content: attr(data-label);
            font-weight: 700;
            color: #4b5563;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.05em;
            text-align: left;
            flex-basis: 35%;
            min-width: 120px;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .flex-action {
            display: flex;
            justify-content: flex-start;
            width: 100%;
            gap: 10px;
        }

        .card-header-clean {
            font-size: 14px;
            padding: 10px 15px;
        }

        .section-title {
            font-size: 13px;
            margin-top: 15px;
        }

        .panel-body {
            padding: 10px !important;
        }

        .total-box {
            font-size: 15px;
            height: 40px;
            text-align: left;
            padding-left: 10px;
        }
    }
</style>

<div class="container-fluid" style="padding: 15px 0;">
    <div class="row">
        <div class="col-md-12">
            <div class="card-clean">
                <div class="card-header-clean">
                    <i class="fa fa-list-alt mr-2"></i> Daftar Realisasi Upah
                </div>

                <form method="POST" enctype="multipart/form-data">
                    <div class="panel-body" style="padding: 15px;">

                        <div class="section-title">Rencana Upah</div>
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold text-muted" style="margin-bottom: 2px;">Tanggal</label>
                                <input readonly type="date" value="<?php echo $datatglrkk; ?>" class="form-control form-control-clean" />
                            </div>
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold text-muted" style="margin-bottom: 2px;">Keterangan</label>
                                <input readonly type="text" value="<?php echo $dataketeranganrkk; ?>" class="form-control form-control-clean" />
                            </div>
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold text-muted" style="margin-bottom: 2px;">Jam Kerja</label>
                                <input readonly type="number" value="<?php echo $datajamkerjarkk; ?>" class="form-control form-control-clean" />
                            </div>
                        </div>

                        <hr style="border-top: 1px dashed #D5D8DC; margin: 15px 0;">

                        <div class="section-title">Realisasi Upah</div>
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold text-muted" style="margin-bottom: 2px;">Tanggal</label>
                                <input readonly type="date" name="ttgl1" value="<?php echo $datatglrealisasi; ?>" class="form-control form-control-clean" />
                            </div>
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold text-muted" style="margin-bottom: 2px;">Keterangan</label>
                                <input type="text" name="tketerangan" value="<?php echo $dataketerangan; ?>" placeholder="Masukkan Keterangan Realisasi..." class="form-control form-control-clean" autocomplete="off" />
                            </div>
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold text-muted" style="margin-bottom: 2px;">Jam Kerja</label>
                                <input readonly type="number" name="tjamkerja" value="<?php echo $datajamkerja; ?>" class="form-control form-control-clean" />
                            </div>
                        </div>

                        <div class="form-group" <?php echo $status; ?> style="margin-bottom: 20px;">
                            <button type="submit" name="simpan" value="Simpan" class="btn btn-primary btn-sm" style="background-color: #2C3E50; border-color: #2C3E50;">
                                <i class="fa fa-save"></i> Simpan Ket.
                            </button>
                            <button type="submit" name="cleanup" value="Cleanup" class="btn btn-warning btn-sm" onclick="return confirm('Tarik data dari record mesin? Data realisasi manual akan tertimpa.')">
                                <i class="fa fa-refresh"></i> Tarik Data Realisasi
                            </button>
                            <a href="?page=realisasi" class="btn btn-default btn-sm" style="border: 1px solid #ccc;">
                                <i class="fa fa-arrow-left"></i> Kembali
                            </a>
                        </div>

                        <div class="section-title">List Karyawan</div>
                        <div class="table-responsive">
                            <table class="table table-hover table-clean align-middle mb-0" id="dataTables-example">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle">No</th>
                                        <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle">NIK</th>
                                        <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle">Nama Karyawan</th>
                                        <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle">Departemen</th>
                                        <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle">Sub Bagian</th>
                                        <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle">OS/DHK</th>
                                        <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle">Golongan</th>
                                        <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle">Jam Masuk</th>
                                        <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle">Jam Pulang</th>
                                        <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle">Istirahat Keluar</th>
                                        <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle">Istirahat Masuk</th>
                                        <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle">Absen Masuk</th>
                                        <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle">Absen Pulang</th>
                                        <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle">Absen Istirahat Keluar</th>
                                        <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle">Absen Istirahat Masuk</th>
                                        <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle text-right">Upah</th>
                                        <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle text-right">Lembur</th>
                                        <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle text-right">Potongan Telat</th>
                                        <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle text-right">Potongan Istirahat</th>
                                        <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle text-right">Potongan Lain</th>
                                        <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle">Hasil</th>
                                        <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $total = 0;

                                    while ($data = $tampil->fetch_assoc()) {
                                        $upah = $data['upahkaryawan'];
                                        $total += $upah;

                                        // Check if both check-in and check-out are missing
                                        $isFullMissing = (empty($data['r_jam_masuk']) || $data['r_jam_masuk'] == '00:00:00') &&
                                            (empty($data['r_jam_keluar']) || $data['r_jam_keluar'] == '00:00:00');

                                        $rowClass = $isFullMissing ? 'bg-red-custom' : '';
                                    ?>
                                        <tr>
                                            <td data-label="No"><?php echo $no; ?></td>
                                            <td data-label="NIK"><?php echo $data['no_absen']; ?></td>
                                            <td data-label="Nama Karyawan">
                                                <strong><?php echo $data['nama_karyawan']; ?></strong>
                                                <?php if (!empty($data['menggantikan'])) : ?>
                                                    <div class="text-xs text-blue-600 font-bold italic">
                                                        <i class="fas fa-exchange-alt mr-1"></i> (Menggantikan <?php echo $data['menggantikan']; ?>)
                                                    </div>
                                                <?php endif; ?>
                                                <?php if (!empty($data['digantikan_oleh'])) : ?>
                                                    <div class="text-xs text-red-600 font-bold italic line-through">
                                                        <i class="fas fa-user-times mr-1"></i> (Digantikan oleh <?php echo $data['digantikan_oleh']; ?>)
                                                    </div>
                                                <?php endif; ?>

                                                <div class="mt-1">
                                                    <?php if ($data['status_rkk'] == 'Hadir') : ?>
                                                        <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded-full">Hadir</span>
                                                    <?php elseif ($data['status_rkk'] == 'Tidak Hadir') : ?>
                                                        <span class="bg-rose-100 text-rose-800 text-[10px] font-bold px-2 py-0.5 rounded-full">Tidak Hadir</span>
                                                    <?php elseif ($data['status_rkk'] == 'Digantikan') : ?>
                                                        <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2 py-0.5 rounded-full">Digantikan</span>
                                                    <?php elseif ($data['status_rkk'] == 'Pengganti') : ?>
                                                        <span class="bg-indigo-100 text-indigo-800 text-[10px] font-bold px-2 py-0.5 rounded-full">Pengganti</span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td data-label="Departemen"><?php echo $data['nama_departmen']; ?></td>
                                            <td data-label="Sub Bagian"><?php echo $data['nama_sub_department']; ?></td>
                                            <td data-label="OS/DHK"><?php echo $data['OS_DHK']; ?></td>
                                            <td data-label="Gol"><?php echo $data['golongan']; ?></td>
                                            <td data-label="Rec. Masuk" class="<?php echo (empty($data['r_jam_masuk']) || $data['r_jam_masuk'] == '00:00:00') ? 'bg-red-custom' : ''; ?>"><?php echo $data['r_jam_masuk']; ?></td>
                                            <td data-label="Rec. Pulang" class="<?php echo (empty($data['r_jam_keluar']) || $data['r_jam_keluar'] == '00:00:00') ? 'bg-red-custom' : ''; ?>"><?php echo $data['r_jam_keluar']; ?></td>
                                            <td data-label="Rec. Ist. K" class="<?php echo (empty($data['r_istirahat_keluar']) || $data['r_istirahat_keluar'] == '00:00:00') ? 'bg-red-custom' : ''; ?>"><?php echo $data['r_istirahat_keluar']; ?></td>
                                            <td data-label="Rec. Ist. M" class="<?php echo (empty($data['r_istirahat_masuk']) || $data['r_istirahat_masuk'] == '00:00:00') ? 'bg-red-custom' : ''; ?>"><?php echo $data['r_istirahat_masuk']; ?></td>
                                            
                                            <td data-label="Real. Masuk" class="<?php echo (empty($data['ra_masuk']) || $data['ra_masuk'] == '00:00:00' || $data['r_potongan_telat'] > 0) ? 'bg-red-custom' : ''; ?>"><?php echo $data['ra_masuk']; ?></td>
                                            <td data-label="Real. Pulang" class="<?php echo (empty($data['ra_keluar']) || $data['ra_keluar'] == '00:00:00') ? 'bg-red-custom' : ($data['r_potongan_lainnya'] > 0 ? 'bg-yellow-custom' : ''); ?>"><?php echo $data['ra_keluar']; ?></td>
                                            <td data-label="Real. Ist. K" class="<?php echo (empty($data['ra_istirahat_keluar']) || $data['ra_istirahat_keluar'] == '00:00:00') ? 'bg-red-custom' : ''; ?>"><?php echo $data['ra_istirahat_keluar']; ?></td>
                                            <td data-label="Real. Ist. M" class="<?php echo (empty($data['ra_istirahat_masuk']) || $data['ra_istirahat_masuk'] == '00:00:00' || $data['r_potongan_istirahat'] > 0) ? 'bg-red-custom' : ''; ?>"><?php echo $data['ra_istirahat_masuk']; ?></td>

                                            <td data-label="Upah" class="text-right">
                                                <?php echo number_format($upah, 0, ',', '.'); ?>
                                            </td>
                                            <td data-label="Lembur" class="text-right">
                                                <?php echo number_format($data['lembur'], 0, ',', '.'); ?>
                                            </td>
                                            <td data-label="Pot. Telat" class="text-right <?php echo ($data['r_potongan_telat'] > 0) ? 'bg-red-custom' : ''; ?>"><?php echo number_format($data['r_potongan_telat'], 0, ',', '.'); ?></td>
                                            <td data-label="Pot. Istirahat" class="text-right <?php echo ($data['r_potongan_istirahat'] > 0) ? 'bg-red-custom' : ''; ?>"><?php echo number_format($data['r_potongan_istirahat'], 0, ',', '.'); ?></td>
                                            <td data-label="Pot. Lain" class="text-right <?php echo ($data['r_potongan_lainnya'] > 0) ? 'bg-yellow-custom' : ''; ?>"><?php echo number_format($data['r_potongan_lainnya'], 0, ',', '.'); ?></td>
                                            <td data-label="Hasil"><?php echo $data['hasil_kerja']; ?></td>
                                            <td data-label="Aksi">
                                                <div class="flex-action">
                                                    <a href="?page=realisasi&aksi=detail&id=<?php echo $data['id_realisasi_detail']; ?>"
                                                        class="btn btn-xs btn-info" style="background-color: #3498DB; border:none; border-radius:4px; padding:4px 10px;">
                                                        <i class="fa fa-eye"></i> Detail
                                                    </a>
                                                </div>
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

                        <div class="row" style="margin-top: 20px;">
                            <div class="col-md-6 hidden-xs"></div>
                            <div class="col-md-6 col-xs-12 text-right" style="text-align: left !important;">
                                <label class="font-weight-bold text-muted" style="font-size: 13px; margin-bottom:5px; display:block;">
                                    Total Realisasi Upah (<?php echo $jml_karyawan; ?> Karyawan)
                                </label>
                                <input readonly type="text" value="<?php echo "Rp " . number_format($total, 0, ',', '.'); ?>" class="form-control total-box" />
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Detect mobile: disable scrollX on small screens so stacked cards work
        var isMobile = window.innerWidth <= 768;

        $('#dataTables-example').DataTable({
            pageLength: 10,
            responsive: false,
            scrollX: !isMobile, // Only enable horizontal scroll on desktop
            autoWidth: !isMobile,
            language: {
                search: "",
                searchPlaceholder: "Cari data...",
                lengthMenu: "Tampil _MENU_",
                info: "Menampilkan _START_ sd _END_ dari _TOTAL_ data",
                paginate: {
                    previous: "Prev",
                    next: "Next"
                }
            }
        });

        $('.dataTables_filter').addClass('mb-2');
    });
</script>