<?php
// 1. Ambil ID RKK
$idrkk = !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : (!empty($_POST['id_rkk_hidden']) ? intval($_POST['id_rkk_hidden']) : 0);

// 2. Logika Simpan
if (isset($_POST['simpan_karyawan'])) {
    $idkaryawan = $_POST['id_karyawan'];
    $id_dept    = $_POST['id_departmen'];
    $id_sub     = $_POST['id_sub_department'];
    $id_jadwal  = $_POST['id_jadwal']; 
    $upah       = $_POST['upah_manual'];

    if (!empty($idkaryawan) && !empty($id_jadwal) && $idrkk > 0) {
        $cek = $koneksi->query("SELECT id_karyawan FROM tb_rkk_detail WHERE id_rkk = '$idrkk' AND id_karyawan = '$idkaryawan'");

        if ($cek->num_rows == 0) {
            $idrkk_fix = intval($idrkk);
            $insert = $koneksi->query("INSERT INTO tb_rkk_detail 
                (id_rkk, id_karyawan, upah, id_departmen, id_sub_department, id_jadwal, status_rkk, 
                 potongan_telat, potongan_istirahat, potongan_lainnya, tgl_updt) 
                VALUES 
                ($idrkk_fix, '$idkaryawan', '$upah', '$id_dept', '$id_sub', '$id_jadwal', 'Hadir', 
                 '0', '0', '0', NOW())");

            if ($insert) {
                echo "<script>alert('Karyawan Berhasil Ditambahkan'); window.location.href = '?page=rkk&aksi=kelola&id=$idrkk';</script>";
                exit;
            }
        } else {
            echo "<script>alert('Karyawan ini sudah ada!');</script>";
        }
    }
}

// Ambil data master
$list_dept   = $koneksi->query("SELECT * FROM ms_departmen ORDER BY nama_departmen ASC");
$list_sub    = $koneksi->query("SELECT * FROM ms_sub_department ORDER BY nama_sub_department ASC");
$list_jadwal = $koneksi->query("SELECT * FROM tb_jadwal ORDER BY id_jadwal ASC");

// Upah global
$q_upah = $koneksi->query("SELECT * FROM ms_upah ORDER BY id_upah DESC LIMIT 1");
$global_upah = $q_upah->fetch_assoc();
$g_harian   = $global_upah['upah_harian'] ?? 0;
$g_mingguan = $global_upah['upah_mingguan'] ?? 0;
$g_bulanan  = $global_upah['upah_bulanan'] ?? 0;
?>

<div class="max-w-4xl mx-auto px-2 sm:px-6 lg:px-8 mt-4 sm:mt-10 mb-10">
    <div class="bg-white shadow-xl border border-gray-200 rounded-2xl overflow-hidden">
        
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-5 sm:px-8 py-5 sm:py-6">
            <h3 class="text-xl sm:text-2xl font-extrabold text-white m-0 tracking-tight flex items-center">
                <i class="fas fa-user-plus mr-3"></i>
                Tambah Karyawan ke RKK
            </h3>
            <p class="text-slate-300 text-xs sm:text-sm mt-1">Pilih karyawan dan tentukan shift kerja untuk rencana ini</p>
        </div>
        
        <form method="POST">
            <input type="hidden" name="id_rkk_hidden" value="<?= $idrkk; ?>">
            <div class="p-5 sm:p-8">
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pilih No. Absen / Nama Karyawan <span class="text-rose-500">*</span></label>
                    <select name="id_karyawan" id="search_karyawan" required
                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:ring-2 focus:ring-blue-500 transition outline-none font-medium text-sm sm:text-base">
                        <option value="">- Pilih Karyawan -</option>
                        <?php
                        $master = $koneksi->query("SELECT * FROM ms_karyawan WHERE status_karyawan = 'Aktif' ORDER BY nama_karyawan ASC");
                        while ($row = $master->fetch_assoc()) {
                            echo "<option value='" . $row['id_karyawan'] . "' 
                                    data-jk='" . $row['jenis_kelamin'] . "' 
                                    data-tgl='" . $row['tgl_aktif'] . "'
                                    data-golongan='" . $row['golongan'] . "'
                                    data-dept='" . ($row['id_departmen'] ?? '') . "'
                                    data-sub='" . ($row['id_sub_department'] ?? '') . "'>" . $row['no_absen'] . " | " . $row['nama_karyawan'] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mb-8">
                    <div>
                        <label class="block text-sm font-bold text-gray-600 mb-2">Jenis Kelamin</label>
                        <input type="text" id="jk" readonly class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-100 text-gray-500 cursor-not-allowed outline-none font-medium text-sm sm:text-base"/>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-600 mb-2">Tanggal Aktif</label>
                        <input type="text" id="tgl_aktif" readonly class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-100 text-gray-500 cursor-not-allowed outline-none font-medium text-sm sm:text-base"/>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Upah <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 font-bold text-sm">Rp</div>
                            <input type="number" name="upah_manual" id="upah_manual" required placeholder="0"
                                   class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl bg-white focus:ring-2 focus:ring-blue-500 outline-none font-bold text-blue-600 text-sm sm:text-base"/>
                        </div>
                    </div>
                </div>

                <div class="border-t border-dashed border-gray-200 my-8"></div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mb-8">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Shift Kerja <span class="text-rose-500">*</span></label>
                        <select name="id_jadwal" required class="block w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:ring-2 focus:ring-blue-500 outline-none text-sm sm:text-base">
                            <option value="">- Pilih Shift -</option>
                            <?php $list_jadwal->data_seek(0); while($j = $list_jadwal->fetch_assoc()) { ?>
                                <option value="<?= $j['id_jadwal']; ?>"><?= $j['keterangan']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Departemen <span class="text-rose-500">*</span></label>
                        <select name="id_departmen" id="id_departmen" required class="block w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:ring-2 focus:ring-blue-500 outline-none text-sm sm:text-base">
                            <option value="">- Pilih Dept -</option>
                            <?php $list_dept->data_seek(0); while ($d = $list_dept->fetch_assoc()) { ?>
                                <option value="<?= $d['id_departmen']; ?>"><?= $d['nama_departmen']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Sub Bagian <span class="text-rose-500">*</span></label>
                        <select name="id_sub_department" id="id_sub_department" required class="block w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:ring-2 focus:ring-blue-500 outline-none text-sm sm:text-base">
                            <option value="">- Pilih Sub -</option>
                            <?php $list_sub->data_seek(0); while ($s = $list_sub->fetch_assoc()) { ?>
                                <option value="<?= $s['id_sub_department']; ?>"><?= $s['nama_sub_department']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-100">
                    <div class="text-xs text-gray-500 italic order-2 sm:order-1 text-center sm:text-left">
                        <span class="text-rose-500">*</span> Pastikan data penempatan benar
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto order-1 sm:order-2">
                        <a href="?page=rkk&aksi=kelola&id=<?= $idrkk; ?>" class="flex justify-center items-center px-5 py-2.5 border border-gray-300 shadow-sm text-sm font-bold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit" name="simpan_karyawan" class="inline-flex items-center justify-center px-6 py-2.5 border border-transparent text-sm font-bold rounded-xl shadow-md text-white bg-blue-600 hover:bg-blue-700 transition transform hover:-translate-y-0.5">
                            <i class="fas fa-save mr-2"></i> Simpan ke Daftar
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const globalRates = { harian: <?= $g_harian ?>, mingguan: <?= $g_mingguan ?>, bulanan: <?= $g_bulanan ?> };

    document.getElementById('search_karyawan').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        
        if(this.value === "") {
            document.getElementById('jk').value = "";
            document.getElementById('tgl_aktif').value = "";
            document.getElementById('upah_manual').value = "";
            return;
        }

        var gol = selectedOption.getAttribute('data-golongan') || "";
        var finalWage = 0;

        if (gol.toLowerCase().includes("harian") || gol === "1") finalWage = globalRates.harian;
        else if (gol.toLowerCase().includes("mingguan")) finalWage = globalRates.mingguan;
        else if (gol.toLowerCase().includes("bulanan") || gol === "3") finalWage = globalRates.bulanan;

        document.getElementById('jk').value = selectedOption.getAttribute('data-jk');
        document.getElementById('tgl_aktif').value = selectedOption.getAttribute('data-tgl');
        document.getElementById('upah_manual').value = finalWage;
        document.getElementById('id_departmen').value = selectedOption.getAttribute('data-dept');
        document.getElementById('id_sub_department').value = selectedOption.getAttribute('data-sub');
    });
</script>