<?php
if (isset($_GET['id'])) {
  $idrkk = $_GET['id'];

  $tampildetail = $koneksi->query("select * from tb_rkk where id_rkk = '$idrkk' ");
  $datadetail = $tampildetail->fetch_assoc();
  $datatglrkk = $datadetail['tgl_rkk'];
  $dataketerangan = $datadetail['keterangan'];
  $datadetailrkk   = $datadetail['detail_rkk'];
  $datajamkerja   = $datadetail['jam_kerja'];
  $datastatusrkk   = $datadetail['status_rkk'];

  $tampil = $koneksi->query("SELECT 
    A.id_rkk_detail, 
    B.no_absen, 
    BB.nama_sub_department, 
    B.nama_karyawan, 
    D.nama_departmen, 
    C.tgl_rkk, 
    A.jam_masuk, 
    A.jam_keluar, 
    A.istirahat_keluar,
    A.istirahat_masuk, 
    A.status_rkk,
    B.OS_DHK,
    B.golongan,
    J.keterangan as nama_shift, /* Mengambil Nama Shift dari tb_jadwal */
    A.upah as upahkaryawan, 
    A.potongan_telat, 
    A.potongan_istirahat, 
    A.potongan_lainnya
FROM tb_rkk_detail A 
LEFT JOIN ms_karyawan B ON A.id_karyawan = B.id_karyawan
LEFT JOIN tb_rkk C ON A.id_rkk = C.id_rkk
LEFT JOIN ms_departmen D ON A.id_departmen = D.id_departmen
LEFT JOIN ms_sub_department BB ON A.id_sub_department = BB.id_sub_department
LEFT JOIN tb_jadwal J ON A.id_jadwal = J.id_jadwal /* Relasi lewat id_jadwal */
WHERE A.id_rkk = '$idrkk'
");
} else {
  $datatglrkk = "";
  $dataketerangan = "";
  $datadetailrkk   = "";
  $datajamkerja   = "";
  $datastatusrkk   = 3;
}

if ($datastatusrkk == 3) {
  $status = "Hidden";
} elseif ($datastatusrkk == 2) {
  if ($_SESSION['role'] != "owner") {
    $status = "Hidden";
  } else {
    $status = "";
  }
} elseif ($datastatusrkk == 1) {
  if ($_SESSION['role'] != "owner") {
    $status = "Hidden";
  } else {
    $status = "";
  }
} else {

  $status = "";
}

?>
<style>
  /* Container Utama */
  .custom-card {
    border-radius: 12px !important;
    border: none !important;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05) !important;
    overflow: hidden;
    margin-bottom: 25px;
    background: #fff;
  }

  /* Header Berwarna Toska */
  .custom-header {
    background: linear-gradient(45deg, #5F9EA0, #4d8284) !important;
    color: white !important;
    padding: 12px 20px !important;
    border: none !important;
  }

  /* Label Styling */
  .label-text {
    font-size: 12px;
    text-transform: uppercase;
    color: #666;
    font-weight: bold;
    margin-bottom: 5px;
    display: block;
  }

  /* Input Readonly Styling */
  .form-control[readonly] {
    background-color: #f8f9fa !important;
    border: 1px solid #ddd;
  }

  /* Tabel Header */
  #dataTables-example thead th {
    background-color: #f1f4f4 !important;
    color: #333;
    font-size: 13px;
    text-align: center;
    vertical-align: middle;
    border-bottom: 2px solid #5F9EA0 !important;
  }

  /* Badge Total di Bawah */
  .total-box {
    background-color: #fff9c4 !important;
    /* Kuning lembut */
    color: #5d4037 !important;
    height: 70px !important;
    font-size: 22px !important;
    border-radius: 10px !important;
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

  .btn-outline-secondary:hover {
    background-color: #5F9EA0 !important;
    color: #fff !important;
    transform: translateX(-3px);
    /* Efek geser sedikit ke kiri */
  }
</style>

<div class="row">
  <div class="col-md-12">
    <div class="panel custom-card">
      <div class="panel-heading custom-header">
        <h3 class="panel-title"><i class="fa fa-info-circle"></i> Detail Rencana Upah</h3>
      </div>

      <div class="panel-body" style="padding: 20px;">
        <div class="row">
          <div class="form-group col-md-3">
            <label class="label-text">Tanggal Pelaksanaan</label>
            <input type="date" value="<?php echo $datatglrkk; ?>" readonly class="form-control" />
          </div>
          <div class="form-group col-md-4">
            <label class="label-text">Keterangan Rencana Kerja</label>
            <input type="text" value="<?php echo $dataketerangan; ?>" readonly class="form-control" />
          </div>
          <div class="form-group col-md-2">
            <label class="label-text">Standar Jam Kerja</label>
            <div class="input-group align-items-center">
              <input type="text" value="<?php echo $datajamkerja; ?>" readonly class="form-control" />
              <span class="input-group-addon">Jam</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="panel custom-card">
      <div class="panel-heading" style="background: #fcfcfc; border-bottom: 1px solid #eee;">
        <div class="row">
          <div class="col-md-6">
            <h3 style="font-size: 16px; font-weight: bold; color: #5F9EA0;" class="mt-3 ms-2">
              <i class="fa fa-users"></i> List Karyawan Terdaftar
            </h3>
          </div>
          <div class="col-md-6 text-right">
            <div <?php echo $status; ?> style="display: inline-block;">
              <a href="?page=rkk&aksi=karyawan&id=<?php echo $idrkk; ?>" class="btn btn-info btn-sm">
                <i class="fa fa-plus"></i> Tambah Karyawan
              </a>
            </div>
            <a href="?page=rkk" class="btn btn-outline-secondary font-weight-bold mt-2"
              style="border-radius: 8px; padding: 10px 20px; border: 2px solid #5F9EA0; transition: 0.3s; color:#5F9EA0">
              <i class="fa fa-arrow-left"></i> &nbsp; Kembali
            </a>
          </div>
        </div>
      </div>

      <div class="panel-body">
        <div class="table-responsive">
          <table class="table table-hover table-bordered" id="dataTables-example">
            <thead>
              <tr>
                <th>No</th>
                <th>Biodata Karyawan</th>
                <th>Penempatan</th>
                <th>OS/DHK</th>
                <th>Gol</th>
                <th>Shift</th>
                <th>Jam Masuk/Pulang</th>
                <th>Istirahat Keluar/Masuk</th>
                <th>Upah (Rp)</th>
                <th>Potongan</th>
                <th <?php echo $status; ?>>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              $total = 0;
              while ($data = $tampil->fetch_assoc()) {
              ?>
                <tr>
                  <td class="text-center"><?php echo $no ?></td>
                  <td>
                    <b><?php echo $data['nama_karyawan'] ?></b><br>
                    <small class="text-muted">NIK: <?php echo $data['no_absen'] ?></small>
                  </td>
                  <td>
                    <?php echo $data['nama_departmen'] ?><br>
                    <small><?php echo $data['nama_sub_department'] ?></small>
                  </td>
                  <td class="text-center"><?php echo $data['OS_DHK'] ?></td>
                  <td class="text-center"><?php echo $data['golongan'] ?></td>
                  <td class="text-center">
                    <span class="label label-primary">
                      <?php echo ($data['nama_shift'] != "") ? $data['nama_shift'] : "Shift Tidak Set"; ?>
                    </span>
                  </td>
                  <td class="text-center">
                    <span class="label label-success"><?php echo $data['jam_masuk'] ?></span> -
                    <span class="label label-danger"><?php echo $data['jam_keluar'] ?></span>
                  </td>
                  <td class="text-center">
                    <span class="label label-success"><?php echo $data['istirahat_keluar'] ?></span> -
                    <span class="label label-danger"><?php echo $data['istirahat_masuk'] ?></span>
                  </td>
                  <td class="text-right">
                    <?php
                    echo number_format($data['upahkaryawan'], 0, ',', '.');
                    $total += $data['upahkaryawan'];
                    ?>
                  </td>
                  <td>
                    <small>Telat: <?php echo number_format($data['potongan_telat'], 0, ',', '.') ?></small><br>
                    <small>Istirahat: <?php echo number_format($data['potongan_istirahat'], 0, ',', '.') ?></small><br>
                    <small>Lain: <?php echo number_format($data['potongan_lainnya'], 0, ',', '.') ?></small>
                  </td>
                  <td <?php echo $status; ?> class="text-center">
                    <a href="?page=rkk&aksi=detail&id=<?php echo $data['id_rkk_detail']; ?>" class="btn btn-warning btn-xs" title="Detail">
                      <i class="fa fa-search"></i>
                    </a>
                    <a href="?page=rkk&aksi=hapusdetail&id=<?php echo $idrkk; ?>&iddetail=<?php echo $data['id_rkk_detail']; ?>"
                      class="btn btn-danger btn-xs" onclick="return confirm('Hapus data ini?');">
                      <i class="fa fa-trash"></i>
                    </a>
                  </td>
                </tr>
              <?php $no++;
              } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 col-md-offset-6">
        <div class="panel custom-card" style="border-left: 5px solid #5F9EA0 !important;">
          <div class="panel-body text-center">
            <label class="label-text">Total Estimasi Pengeluaran Upah</label>
            <input readonly class="form-control total-box font-weight-bold"
              value="<?php echo "Rp. " . number_format($total, 0, ',', '.') . " / " . ($no - 1) . " Org"; ?>" />
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('#dataTables-example').DataTable({
      "pageLength": 10, // Ubah ke 10 agar pagination terlihat bekerja
      "searching": true,
      "ordering": true,
      "dom": '<"row"<"col-sm-6"l><"col-sm-6"f>>rt<"row"<"col-sm-6"i><"col-sm-6 text-right"p>>',
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