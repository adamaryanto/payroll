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
    J.keterangan as nama_shift,
    A.upah as upahkaryawan, 
    A.potongan_telat, 
    A.potongan_istirahat, 
    A.potongan_lainnya
FROM tb_rkk_detail A 
LEFT JOIN ms_karyawan B ON A.id_karyawan = B.id_karyawan
LEFT JOIN tb_rkk C ON A.id_rkk = C.id_rkk
/* Mengambil data dari tabel karyawan (B) bukan tabel detail (A) */
LEFT JOIN ms_departmen D ON B.id_departmen = D.id_departmen
LEFT JOIN ms_sub_department BB ON B.id_sub_department = BB.id_sub_department 
LEFT JOIN tb_jadwal J ON A.id_jadwal = J.id_jadwal 
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
    <div class="border-b border-gray-100 py-4 px-5 bg-white flex justify-between items-center">
      <h3 class="text-xl font-bold text-indigo-600 m-0"><i class="fas fa-info-circle mr-2"></i> Daftar Rencana Kerja</h3>
      <div class="flex items-center gap-2">
        <?php if ($_SESSION['role'] == "owner") : ?>
          <?php if ($datastatusrkk == 1 || $datastatusrkk == 0) : ?>
            <a href="?page=rkk&aksi=accept&id=<?= $idrkk; ?>&iddetail=app" class="inline-flex items-center bg-emerald-600 hover:bg-emerald-700 text-white text-[14px] font-medium py-2 px-3 rounded shadow-sm transition-colors" onclick="return confirm('Approve Rencana Kerja ini?');">
              <i class="fas fa-check-circle mr-1.5"></i> Approve
            </a>
          <?php elseif ($datastatusrkk == 2) : ?>
            <a href="?page=rkk&aksi=accept&id=<?= $idrkk; ?>&iddetail=unapp" class="inline-flex items-center bg-rose-600 hover:bg-rose-700 text-white text-[14px] font-medium py-2 px-3 rounded shadow-sm transition-colors" onclick="return confirm('Batalkan Approve Rencana Kerja ini?');">
              <i class="fas fa-times-circle mr-1.5"></i> Un-Approve
            </a>
          <?php endif; ?>
        <?php endif; ?>

        <a href="?page=rkk&aksi=karyawan&id=<?= $idrkk; ?>" class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white text-[14px] font-medium py-2 px-3 rounded shadow-sm transition-colors">
          <i class="fas fa-user-plus mr-1.5"></i> Tetapkan Karyawan
        </a>
      </div>
    </div>

    <div class="p-5">
      <div class="row">
        <div class="col-md-3"><label>Tanggal:</label><input type="date" value="<?= $datatglrkk; ?>" readonly class="form-control" /></div>
        <div class="col-md-4"><label>Keterangan:</label><input type="text" value="<?= $dataketerangan; ?>" readonly class="form-control" /></div>
        <div class="col-md-2"><label>Jam Kerja:</label><input type="text" value="<?= $datajamkerja; ?> Jam" readonly class="form-control" /></div>
      </div>
    </div>

    <div class="p-0">
      <div class="table-responsive px-3 py-3">
        <table class="w-full text-left border-collapse" id="dataTables-example">
          <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
              <th class="py-2 px-2 text-[13px] font-bold text-gray-700 uppercase">No</th>
              <th class="py-2 px-2 text-[13px] font-bold text-gray-700 uppercase">Karyawan</th>
              <th class="py-2 px-2 text-[13px] font-bold text-gray-700 uppercase">Penempatan</th>
              <th class="py-2 px-2 text-[13px] font-bold text-gray-700 uppercase">Shift</th>
              <th class="py-2 px-2 text-[13px] font-bold text-gray-700 uppercase">Jam</th>
              <th class="py-2 px-2 text-[13px] font-bold text-gray-700 uppercase">Upah</th>
              <th class="py-2 px-2 text-[13px] font-bold text-gray-700 uppercase">Potongan</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            while ($data = $tampil->fetch_assoc()) : ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><b><?= $data['nama_karyawan'] ?></b><br><small><?= $data['no_absen'] ?></small></td>
                <td><?= $data['nama_departmen'] ?><br><small><?= $data['nama_sub_department'] ?></small></td>
                <td><span class="badge"><?= $data['nama_shift'] ?></span></td>
                <td><?= $data['jam_masuk'] ?> - <?= $data['jam_keluar'] ?></td>
                <td>Rp <?= number_format($data['upahkaryawan'], 0, ',', '.') ?></td>
                <td class="text-[12px] leading-tight">
                  <div class="text-amber-700">Telat: <?= number_format($data['potongan_telat'], 0, ',', '.') ?></div>
                  <div class="text-amber-700">Istirahat: <?= number_format($data['potongan_istirahat'], 0, ',', '.') ?></div>
                  <div class="text-amber-700">Lain: <?= number_format($data['potongan_lainnya'], 0, ',', '.') ?></div>
                </td>
                <td class="text-center">
                  <a href="?page=rkk&aksi=detail&id=<?= $data['id_rkk_detail']; ?>"
                    class="btn btn-info btn-xs"><i class="fas fa-eye"></i> Detail</a>

                  <a href="?page=rkk&aksi=hapusdetail&id=<?= $idrkk; ?>&iddetail=<?= $data['id_rkk_detail']; ?>"
                    class="btn btn-danger btn-xs" onclick="return confirm('Hapus?');">
                    <i class="fas fa-trash"></i>
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