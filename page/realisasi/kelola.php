<?php
// Catatan: Pastikan session_start(); sudah dipanggil di file utama Anda

if(isset($_GET['id'])){
    $idrealisasi = $_GET['id'];

    $tampildetail = $koneksi->query("SELECT * FROM tb_realisasi WHERE id_realisasi = '$idrealisasi'");
    $datadetail = $tampildetail->fetch_assoc();
    
    $datatglrealisasi    = $datadetail['tgl_realisasi'];
    $dataketerangan      = $datadetail['keterangan'];
    $datadetailrealisasi = $datadetail['detail_realisasi'];
    $datajamkerja        = $datadetail['jam_kerja'];
    $datastatusrealisasi = $datadetail['status_realisasi'];
    $idrkk               = $datadetail['id_rkk'];

    $tampil = $koneksi->query("SELECT A.id_realisasi_detail, B.no_absen , BB.nama_sub_department ,B.nama_karyawan , D.nama_departmen , C.tgl_realisasi ,A.r_jam_masuk , A.r_jam_keluar , A.r_istirahat_keluar,
        A.r_istirahat_masuk ,A.ra_masuk , A.ra_keluar , A.ra_istirahat_keluar,B.OS_DHK,B.golongan,
        A.ra_istirahat_masuk , 
        A.r_upah as upahkaryawan, A.r_potongan_telat, A.r_potongan_istirahat, A.r_potongan_lainnya, A.hasil_kerja
        FROM tb_realisasi_detail A 
        LEFT JOIN ms_karyawan B on A.id_karyawan = B.id_karyawan
        LEFT JOIN tb_realisasi C ON A.id_realisasi = C.id_realisasi
        LEFT JOIN tb_rkk_detail E ON A.id_rkk_detail = E.id_rkk_detail
        LEFT JOIN ms_departmen D on E.id_departmen = D.id_departmen
        LEFT JOIN ms_sub_department BB on E.id_sub_department = BB.id_sub_department
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
    $datajamkerja        = "";
    $datastatusrealisasi = 'pending';
}

if($datastatusrealisasi == 'approve'){
    if($_SESSION['role'] != "owner"){ 
        $status = "hidden";
    } else {
        $status = "";
    }
} else {
    $status = "";
}

$simpan = @$_POST['simpan'];
if($simpan) {
    $tketerangan = @$_POST['tketerangan'];
    $sql = $koneksi->query("UPDATE tb_realisasi SET keterangan = '$tketerangan' WHERE id_realisasi = '$idrealisasi'");
    if($sql) {
        echo '<script type="text/javascript">
                alert("Data Tersimpan");
                window.location.href="?page=realisasi&aksi=kelola&id='.$idrealisasi.'";
              </script>';
    }
}
?>

<style>
    /* Styling Card & Header (Formal Clean) */
    .card-clean {
        background: #fff;
        border: 1px solid #E0E4E8;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.03);
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
        color: #4D5656;
        border-top: 1px solid #E0E4E8;
        padding: 6px; 
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
            flex-basis: 35%; /* Label otomatis mengambil 35% lebar layar */
            min-width: 120px; /* Batas minimal agar teks label tidak hancur */
            margin-right: 15px; /* Jarak pas agar label & data tidak terlalu dekat */
            flex-shrink: 0; /* Mencegah label menyusut */
        }

        /* Penyesuaian khusus form & detail di atas table */
        .flex-action { 
            display: flex; 
            justify-content: flex-start; 
            width: 100%; 
            gap: 10px;
        }
        .card-header-clean { font-size: 14px; padding: 10px 15px; }
        .section-title { font-size: 13px; margin-top: 15px; }
        .panel-body { padding: 10px !important; }
        .total-box { font-size: 15px; height: 40px; text-align: left; padding-left: 10px;} 
    }
</style>

<div class="container-fluid" style="padding: 15px 0;">
    <div class="row">
        <div class="col-md-12">
            <div class="card-clean">
                <div class="card-header-clean">
                    <i class="fa fa-list-alt"></i> Detail Realisasi Upah
                </div>
                
                <form method="POST" enctype="multipart/form-data">
                    <div class="panel-body" style="padding: 15px;"> 
                        
                        <div class="section-title">Rencana Upah</div>
                        <div class="row" style="margin-bottom: 10px;"> 
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold text-muted" style="margin-bottom: 2px;">Tanggal</label>
                                <input readonly type="date" value="<?php echo $datatglrkk; ?>" class="form-control form-control-clean"/>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold text-muted" style="margin-bottom: 2px;">Keterangan</label>
                                <input readonly type="text" value="<?php echo $dataketeranganrkk; ?>" class="form-control form-control-clean"/>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold text-muted" style="margin-bottom: 2px;">Jam Kerja</label>
                                <input readonly type="number" value="<?php echo $datajamkerjarkk; ?>" class="form-control form-control-clean"/>
                            </div>
                        </div>

                        <hr style="border-top: 1px dashed #D5D8DC; margin: 15px 0;"> 
                        
                        <div class="section-title">Realisasi Upah</div>
                        <div class="row" style="margin-bottom: 10px;"> 
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold text-muted" style="margin-bottom: 2px;">Tanggal</label>
                                <input readonly type="date" name="ttgl1" value="<?php echo $datatglrealisasi; ?>" class="form-control form-control-clean"/>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold text-muted" style="margin-bottom: 2px;">Keterangan</label>
                                <input type="text" name="tketerangan" value="<?php echo $dataketerangan; ?>" placeholder="Masukkan Keterangan Realisasi..." class="form-control form-control-clean" autocomplete="off"/>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold text-muted" style="margin-bottom: 2px;">Jam Kerja</label>
                                <input readonly type="number" name="tjamkerja" value="<?php echo $datajamkerja; ?>" class="form-control form-control-clean"/>
                            </div>
                        </div>

                        <div class="form-group" <?php echo $status;?> style="margin-bottom: 20px;">
                            <button type="submit" name="simpan" value="Simpan" class="btn btn-primary btn-sm" style="background-color: #2C3E50; border-color: #2C3E50;">
                                <i class="fa fa-save"></i> Simpan Ket.
                            </button>
                            <a href="?page=realisasi" class="btn btn-default btn-sm" style="border: 1px solid #ccc;">
                                <i class="fa fa-arrow-left"></i> Kembali
                            </a>
                        </div> 

                        <div class="section-title">List Karyawan</div>
                        <div class="table-responsive">
                            <table class="table table-hover table-clean align-middle mb-0" id="dataTables-example">
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $total = 0;

                                    while ($data = $tampil->fetch_assoc()) {
                                        $upah = $data['upahkaryawan'];
                                        $total += $upah;
                                    ?>
                                    <tr>
                                        <td data-label="No"><?php echo $no; ?></td>
                                        <td data-label="NIK" class="font-weight-bold"><?php echo $data['no_absen']; ?></td>
                                        <td data-label="Nama Karyawan"><strong><?php echo $data['nama_karyawan']; ?></strong></td>
                                        <td data-label="Departemen"><?php echo $data['nama_departmen']; ?></td>
                                        <td data-label="Sub Bagian"><?php echo $data['nama_sub_department']; ?></td>
                                        <td data-label="OS/DHK"><?php echo $data['OS_DHK']; ?></td>
                                        <td data-label="Golongan"><?php echo $data['golongan']; ?></td>
                                        <td data-label="Jam Masuk"><?php echo $data['r_jam_masuk']; ?></td>
                                        <td data-label="Jam Pulang"><?php echo $data['r_jam_keluar']; ?></td>
                                        <td data-label="Istirahat (Kel)"><?php echo $data['r_istirahat_keluar']; ?></td>
                                        <td data-label="Istirahat (Msk)"><?php echo $data['r_istirahat_masuk']; ?></td>
                                        <td data-label="Upah (Rp)" style="color:#27AE60; font-weight:bold;">
                                            <?php echo number_format($upah, 0, ',', '.'); ?>
                                        </td>
                                        <td data-label="Pot. Telat" class="text-danger"><?php echo number_format($data['r_potongan_telat'], 0, ',', '.'); ?></td>
                                        <td data-label="Pot. Istirahat" class="text-danger"><?php echo number_format($data['r_potongan_istirahat'], 0, ',', '.'); ?></td>
                                        <td data-label="Pot. Lainnya" class="text-danger"><?php echo number_format($data['r_potongan_lainnya'], 0, ',', '.'); ?></td>
                                        <td data-label="Hasil Kerja"><?php echo $data['hasil_kerja']; ?></td>
                                        <td data-label="Aksi">
                                            <div class="flex-action">
                                                <a href="?page=realisasi&aksi=detail&id=<?php echo $data['id_realisasi_detail'];?>" 
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
                                <input readonly type="text" value="<?php echo "Rp " . number_format($total, 0, ',', '.'); ?>" class="form-control total-box"/>
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