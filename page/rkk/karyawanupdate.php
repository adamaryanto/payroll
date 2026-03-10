<?php
if (isset($_GET['id'])) {
    $idrkkdetail = $_GET['id'];
    $tampildetail = $koneksi->query("select * from tb_rkk_detail where id_rkk_detail = '$idrkkdetail' ");
    $datadetail = $tampildetail->fetch_assoc();
    $idrkk = $datadetail['id_rkk'];
    $idrkkkaryawan = $datadetail['id_karyawan'];
    $orig_upah = $datadetail['upah'];
    $orig_jadwal = $datadetail['id_jadwal'];
} else {
    $idrkkdetail = "";
    $idrkk = "";
    $idrkkkaryawan = "";
}
?>

<div class="container-fluid px-2 mt-4 mb-4">
    <div class="card border-0 shadow-sm rounded-xl overflow-hidden bg-white">
        <div class="border-b border-gray-100 py-4 px-5 flex justify-between items-center bg-white">
            <div>
                <h3 class="text-xl font-bold text-indigo-600 m-0"><i class="fas fa-sync-alt mr-2"></i>Pilih Karyawan Pengganti</h3>
                <small class="text-gray-500">Menggantikan karyawan pada RKK ID: <?= $idrkk; ?></small>
            </div>
            <div>
                <a href="?page=rkk&aksi=kelola&id=<?= $idrkk; ?>" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded transition-colors">
                    Kembali
                </a>
            </div>
        </div>

        <form method="POST">
            <div class="p-0">
                <div class="table-responsive px-4 py-4">
                    <table class="w-full text-left border-collapse" id="dataTables-example">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="py-2 px-2 text-[13px] font-bold text-gray-700 uppercase text-center">Pilih</th>
                                <th class="py-2 px-2 text-[13px] font-bold text-gray-700 uppercase">No. Absen</th>
                                <th class="py-2 px-2 text-[13px] font-bold text-gray-700 uppercase">Nama Karyawan</th>
                                <th class="py-2 px-2 text-[13px] font-bold text-gray-700 uppercase">Departemen</th>
                                <th class="py-2 px-2 text-[13px] font-bold text-gray-700 uppercase">Bagian</th>
                                <th class="py-2 px-2 text-[13px] font-bold text-gray-700 uppercase">Status Saat Ini</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php
                            $no = 0;
                            $tampil = $koneksi->query("SELECT ms_karyawan.*, ms_departmen.nama_departmen, ms_sub_department.nama_sub_department 
                                FROM ms_karyawan 
                                LEFT JOIN ms_departmen ON ms_karyawan.id_departmen = ms_departmen.id_departmen 
                                LEFT JOIN ms_sub_department ON ms_karyawan.id_sub_department = ms_sub_department.id_sub_department
                                WHERE ms_karyawan.status_karyawan = 'Aktif' 
                                ORDER BY ms_karyawan.nama_karyawan ASC");

                            while ($datakaryawan = $tampil->fetch_assoc()) {
                                $id_k = $datakaryawan['id_karyawan'];
                                // Cek apakah sudah ada di RKK ini
                                $cek_rkk = $koneksi->query("SELECT status_rkk FROM tb_rkk_detail WHERE id_rkk = '$idrkk' AND id_karyawan = '$id_k' LIMIT 1");
                                $rkk_status = $cek_rkk->num_rows > 0 ? $cek_rkk->fetch_assoc()['status_rkk'] : 'Tersedia';

                                $is_disabled = ($rkk_status != 'Tersedia');
                            ?>
                                <tr class="<?= $is_disabled ? 'bg-gray-50 opacity-60' : 'hover:bg-gray-50' ?> transition-colors text-[14px]">
                                    <td class="py-2 px-2 text-center">
                                        <?php if (!$is_disabled): ?>
                                            <input type="radio" name="id_pengganti" value="<?= $id_k; ?>" class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-gray-300" required>
                                        <?php else: ?>
                                            <i class="fas fa-lock text-gray-400"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-2 px-2"><?= $datakaryawan['no_absen'] ?></td>
                                    <td class="py-2 px-2 font-medium text-gray-900"><?= $datakaryawan['nama_karyawan'] ?></td>
                                    <td class="py-2 px-2"><?= $datakaryawan['nama_departmen'] ?></td>
                                    <td class="py-2 px-2"><?= $datakaryawan['nama_sub_department'] ?></td>
                                    <td class="py-2 px-2">
                                        <?php if ($rkk_status == 'Tersedia'): ?>
                                            <span class="bg-emerald-100 text-emerald-800 text-[11px] font-bold px-2 py-0.5 rounded-full">Tersedia</span>
                                        <?php else: ?>
                                            <span class="bg-gray-200 text-gray-600 text-[11px] font-bold px-2 py-0.5 rounded-full"><?= $rkk_status ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php $no++;
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="px-5 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <input type="submit" name="simpan" value="Konfirmasi Penggantian" class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-bold rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer">
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            pageLength: 25,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],
            language: {
                search: "Cari Karyawan:",
                lengthMenu: "Tampilkan _MENU_",
                info: "Menampilkan _START_ s/d _END_ dari _TOTAL_ data",
                paginate: {
                    previous: "Prev",
                    next: "Next"
                }
            }
        });
    });
</script>

<?php
if (isset($_POST['simpan'])) {
    // Cek apakah radio button 'id_pengganti' sudah dipilih
    if (isset($_POST['id_pengganti'])) {
        $idkaryawan_pengganti = $_POST['id_pengganti'];

        // Ambil data departemen & sub-dept karyawan pengganti langsung dari DB agar aman
        $q_karyawan = $koneksi->query("SELECT id_departmen, id_sub_department FROM ms_karyawan WHERE id_karyawan = '$idkaryawan_pengganti'");
        $data_k = $q_karyawan->fetch_assoc();

        $iddept_pengganti = $data_k['id_departmen'];
        $idsub_pengganti  = $data_k['id_sub_department'];

        // 1. Masukkan karyawan pengganti ke tb_rkk_detail
        $q_insert = "INSERT INTO tb_rkk_detail 
            (id_rkk, id_karyawan, upah, id_departmen, id_sub_department, id_jadwal, status_rkk, 
             potongan_telat, potongan_istirahat, potongan_lainnya, tgl_updt) 
            VALUES 
            ('$idrkk', '$idkaryawan_pengganti', '$orig_upah', '$iddept_pengganti', '$idsub_pengganti', '$orig_jadwal', 'Pengganti', 
             '0', '0', '0', NOW())";

        if ($koneksi->query($q_insert)) {
            // 2. Update status karyawan lama
            $koneksi->query("UPDATE tb_rkk_detail SET status_rkk = 'Digantikan' WHERE id_rkk_detail = '$idrkkdetail'");

            // 3. Catat di tabel histori update
            $koneksi->query("INSERT INTO tb_rkk_update (id_rkk_detail, id_karyawan, status) VALUES ('$idrkkdetail', '$idkaryawan_pengganti', 'Pengganti')");
            $koneksi->query("INSERT INTO tb_rkk_update (id_rkk_detail, id_karyawan, status) VALUES ('$idrkkdetail', '$idrkkkaryawan', 'Digantikan')");

            echo "<script>alert('Berhasil mengganti karyawan.'); window.location.href='?page=rkk&aksi=kelola&id=$idrkk';</script>";
        } else {
            echo "<script>alert('Gagal menyimpan: " . $koneksi->error . "');</script>";
        }
    } else {
        echo "<script>alert('Silakan pilih satu karyawan pengganti!');</script>";
    }
}
?>