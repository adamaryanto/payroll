<?php
// karyawan.php - Responsive Version

$idrkk = $_GET['id'] ?? '';
?>

<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm rounded-xl overflow-hidden bg-white mb-4">
            <div class="bg-[#5F9EA0] py-3 px-5">
                <h3 class="text-lg font-bold text-white m-0">Data Karyawan</h3>
            </div>
            
            <div class="p-4">
                <div class="table-responsive">
                    <table class="w-full text-left border-collapse" id="dataTables-example">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th hidden="hidden">ID Karyawan</th>
                                <th class="py-2.5 px-3 text-[13px] font-bold text-gray-700 uppercase">No. Absen</th>
                                <th class="py-2.5 px-3 text-[13px] font-bold text-gray-700 uppercase">Nama</th>
                                <th class="py-2.5 px-3 text-[13px] font-bold text-gray-700 uppercase">Bagian</th>
                                <th class="py-2.5 px-3 text-[13px] font-bold text-gray-700 uppercase">Jenis Kelamin</th>
                                <th class="py-2.5 px-3 text-[13px] font-bold text-gray-700 uppercase">Tanggal Aktif</th>
                                <th class="py-2.5 px-3 text-[13px] font-bold text-gray-700 uppercase text-center w-24">Aksi</th>
                                <th hidden="hidden">Upah Harian</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php
                            $tampil = $koneksi->query("SELECT ms_karyawan.* , ms_departmen.nama_departmen FROM ms_karyawan LEFT JOIN ms_departmen on ms_karyawan.id_departmen = ms_departmen.id_departmen where ms_karyawan.status_karyawan = 'Aktif'");
                            while ($datakaryawan = $tampil->fetch_assoc()) :
                                $id = $datakaryawan['id_karyawan'];
                            ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td hidden="hidden"><input type="text" name="tidkaryawan[]" value="<?= $id ?>"/></td>
                                <td data-label="No. Absen" class="py-3 px-3 text-[15px] text-gray-700"><?= $datakaryawan['no_absen'] ?></td>
                                <td data-label="Nama" class="py-3 px-3 text-[15px] font-medium text-gray-900"><?= $datakaryawan['nama_karyawan'] ?></td>
                                <td data-label="Bagian" class="py-3 px-3 text-[15px] text-gray-700"><?= $datakaryawan['nama_departmen'] ?></td>
                                <td data-label="Jenis Kelamin" class="py-3 px-3 text-[15px] text-gray-700"><?= $datakaryawan['jenis_kelamin'] ?></td>
                                <td data-label="Tanggal Aktif" class="py-3 px-3 text-[15px] text-gray-700"><?= $datakaryawan['tgl_aktif'] ?></td>
                                <td data-label="Aksi" class="py-3 px-3 text-center">
                                    <a href="?page=realisasi&aksi=slip&id=<?= $id ?>" class="inline-flex items-center px-3 py-1.5 text-[13px] font-bold text-white bg-blue-600 hover:bg-blue-700 rounded shadow-sm transition-colors">
                                        <i class="fas fa-print mr-1.5"></i> Slip
                                    </a>
                                </td>
                                <td hidden="hidden"><input type="text" name="tupah[]" value="<?= $datakaryawan['upah_harian'] ?>"/></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #dataTables-example { width: 100% !important; border-collapse: collapse !important; }
    .dataTables_wrapper .dataTables_length select { border-radius: 4px; border: 1px solid #d1d5db; padding: 4px 8px; margin: 0 4px; outline: none; font-size: 14px; }
    .dataTables_wrapper .dataTables_filter input { border-radius: 4px !important; border: 1px solid #d1d5db !important; padding: 6px 10px !important; outline: none; font-size: 14px; transition: all 0.2s; }
    .dataTables_wrapper .dataTables_filter input:focus { border-color: #3b82f6 !important; box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2); }
    .dataTables_wrapper .dataTables_paginate { padding-top: 1rem !important; display: flex; justify-content: flex-end; gap: 4px; }
    .dataTables_wrapper .dataTables_paginate .paginate_button { background: white !important; border: 1px solid #d1d5db !important; border-radius: 4px !important; padding: 6px 12px !important; color: #374151 !important; font-size: 14px !important; cursor: pointer; }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled) { background: #f3f4f6 !important; color: #111827 !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #2563eb !important; border-color: #2563eb !important; color: white !important; font-weight: bold; }
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
        language: {
            search: "",
            searchPlaceholder: "Cari karyawan...",
            lengthMenu: "Tampil _MENU_",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_",
            paginate: { previous: "Prev", next: "Next" }
        }
    });
    $('.dataTables_filter').css('float', 'right').addClass('mb-3');
    $('.dataTables_length').css('float', 'left').addClass('mb-3');
});
</script>

<?php
if(isset($_POST['simpan'])) {
    $tidkaryawan = $_POST['tidkaryawan'];
    $tupah = $_POST['tupah'];
    if(!empty($_POST['ck'])){
        foreach ($_POST['ck'] as $cek) {
            $idkaryawan = $tidkaryawan[$cek];
            $upah = $tupah[$cek];
            $tampilcek = $koneksi->query("select COUNT(id_karyawan) as jml from tb_rkk_detail where id_rkk = '$idrkk' and id_karyawan = '$idkaryawan' ");
            $datacek = $tampilcek->fetch_assoc();
            if($datacek['jml'] != "1"){
                $koneksi->query("insert into tb_rkk_detail (id_rkk,id_karyawan,upah) values('$idrkk','$idkaryawan','$upah') ");
            }
        }
        echo '<script>alert("Data Tersimpan"); window.location.href="?page=rkk&aksi=kelola&id='.$idrkk.'";</script>';
    } else {
        echo '<script>alert("Tidak Ada Data Yang Dipilih");</script>';
    }
}
?>