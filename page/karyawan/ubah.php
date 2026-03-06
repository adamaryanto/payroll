<?php
if(isset($_GET['id'])){
    $idu = $_GET['id'];
    $tampil=$koneksi->query("SELECT * FROM ms_karyawan WHERE id_karyawan = '$idu'");
    $data=$tampil->fetch_assoc();
    
    $idkaryawan = $data['id_karyawan'];
    $iddepartmen = $data['id_departmen'];
    $idjabatan = $data['id_jabatan'];
    $idsubdept = $data['id_sub_department'];
    $idjadwal = $data['id_jadwal'];
    $noabsen = $data['no_absen'];
    $nama = $data['nama_karyawan'];
    $tempatlahir = $data['tempat_lahir'];
    $tgllahir = $data['tgl_lahir'];
    $agama = $data['agama'];
    $statuskawin = $data['status_kawin'];
    $jeniskelamin = $data['jenis_kelamin'];
    $noktp = $data['no_ktp'];
    $nobpjs = $data['no_bpjs'];
    $os = $data['OS_DHK'];
    $golongan = $data['golongan'];
    $alamatktp = $data['alamat_ktp'];
    $alamattinggal = $data['alamat_tinggal'];
    $statuskaryawan = $data['status_karyawan'];
    $tglaktif = $data['tgl_aktif'];
    $tglnonaktif = $data['tgl_nonaktif'];
    $harian = $data['upah_harian'];
    $mingguan = $data['upah_mingguan'];
    $bulanan = $data['upah_bulanan'];
}

if (isset($_POST['update'])) {
    $tid = $_POST['tid'];
    $tnama = $_POST['tnama'];
    $tnoabsen = $_POST['tnoabsen'];
    $tos = $_POST['tos'];
    $tgolongan = $_POST['tgolongan'];
    $tjeniskelamin = $_POST['tjeniskelamin'];
    $tagama = $_POST['tagama'];
    $ttempatlahir = $_POST['ttempatlahir'];
    $ttanggallahir = $_POST['ttanggallahir'];
    $tstatuskawin = $_POST['tstatuskawin'];
    $tnoktp = $_POST['tnoktp'];
    $tbpjs = $_POST['tbpjs'];
    $talamatktp = $_POST['talamatktp'];
    $talamattinggal = $_POST['talamattinggal'];
    $ttanggalbergabung = $_POST['ttanggalbergabung'];
    $tharian = $_POST['tharian'] ?: 0;
    $tmingguan = $_POST['tmingguan'] ?: 0;
    $tbulanan = $_POST['tbulanan'] ?: 0;
    // Function to handle inline insert and return new ID
    if (!function_exists('getOrInsertMaster')) {
        function getOrInsertMaster($koneksi, $postKey, $table, $column, $extraData = []) {
            $newValue = isset($_POST['new_' . $postKey]) ? trim($koneksi->real_escape_string($_POST['new_' . $postKey])) : '';
            
            if ($newValue !== '') {
                // Check if exists
                $check = $koneksi->query("SELECT * FROM $table WHERE $column = '$newValue'");
                if ($check && $check->num_rows > 0) {
                    $existing = $check->fetch_assoc();
                    $pkResult = $koneksi->query("SHOW KEYS FROM $table WHERE Key_name = 'PRIMARY'");
                    $pk = $pkResult->fetch_assoc()['Column_name'];
                    return $existing[$pk];
                }
                
                // Insert new
                $cols = [$column];
                $vals = ["'$newValue'"];
                foreach ($extraData as $c => $v) {
                    $cols[] = $c;
                    $vals[] = "'$v'";
                }
                
                $koneksi->query("INSERT INTO $table (" . implode(',', $cols) . ") VALUES (" . implode(',', $vals) . ")");
                return $koneksi->insert_id;
            }
            
            $selectedVal = isset($_POST[$postKey]) ? $koneksi->real_escape_string($_POST[$postKey]) : 0;
            return ($selectedVal === 'add_new') ? 0 : $selectedVal;
        }
    }

    $tdepartmen = getOrInsertMaster($koneksi, 'tdepartmen', 'ms_departmen', 'nama_departmen', ['id_perusahaan' => '1']);
    $tsubdept = getOrInsertMaster($koneksi, 'tsubdept', 'ms_sub_department', 'nama_sub_department', ['id_perusahaan' => '1']);
    $tjabatan = getOrInsertMaster($koneksi, 'tjabatan', 'ms_jabatan', 'jabatan', ['id_perusahaan' => '1']);
    $tjadwal = getOrInsertMaster($koneksi, 'tjadwal', 'tb_jadwal', 'keterangan', ['jam_masuk' => '08:00:00', 'jam_keluar' => '17:00:00', 'istirahat_masuk' => '12:00:00', 'istirahat_keluar' => '13:00:00']);

    $sql = $koneksi->query("UPDATE ms_karyawan SET 
        id_departmen = '$tdepartmen',
        id_jabatan = '$tjabatan',
        id_sub_department = '$tsubdept',
        id_jadwal = '$tjadwal',
        nama_karyawan = '$tnama',
        tempat_lahir = '$ttempatlahir',
        tgl_lahir = '$ttanggallahir',
        agama = '$tagama',
        status_kawin = '$tstatuskawin',
        jenis_kelamin = '$tjeniskelamin',
        no_ktp = '$tnoktp',
        no_bpjs = '$tbpjs',
        alamat_ktp = '$talamatktp',
        alamat_tinggal = '$talamattinggal',
        tgl_aktif = '$ttanggalbergabung',
        OS_DHK = '$tos',
        golongan = '$tgolongan',
        upah_harian = '$tharian',
        upah_mingguan = '$tmingguan',
        upah_bulanan = '$tbulanan'
        WHERE id_karyawan = '$tid'
    ");

    if ($sql) {
        echo "<script>alert('Data Berhasil Diperbarui'); window.location='?page=karyawan';</script>";
    } else {
        echo "<script>alert('Gagal Perbarui Data');</script>";
    }
}
?>

<div class="container-fluid px-4 mt-5 mb-5">
    <div class="card border-0 shadow-lg rounded-xl overflow-hidden">
        
        <div class="card-header bg-white border-b border-gray-100 py-5 px-6">
            <h3 class="text-2xl font-extrabold text-gray-800 tracking-tight m-0">Ubah Data Karyawan</h3>
            <p class="text-sm text-gray-500 mt-1">Perbarui informasi karyawan di bawah ini.</p>
        </div>

        <form method="POST" id="formUbahKaryawan" enctype="multipart/form-data">
            <div class="card-body p-6 bg-gray-50/30">
                
                <!-- Section 1: Personal & Position -->
                <div class="mb-8">
                    <h4 class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-4 flex items-center">
                        <i class="fas fa-user-circle mr-2 text-lg"></i> Informasi Pribadi & Jabatan
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        
                        <input type="hidden" name="tid" value="<?= $idkaryawan; ?>">

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input autocomplete="off" type="text" name="tnama" value="<?= $nama; ?>" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none" placeholder="Masukkan nama lengkap">
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">No. Absen</label>
                            <input type="text" name="tnoabsen" value="<?= $noabsen; ?>" readonly class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-gray-100 text-gray-500 font-bold cursor-not-allowed">
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Departemen</label>
                            <select name="tdepartmen" class="w-full select2-manage px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none" data-placeholder="- Pilih Departemen -" data-delete-route="bagian">
                                <option value=""></option>
                                <option value="add_new" data-url="?page=bagian&aksi=tambah" class="font-bold text-indigo-600">+ Tambah Departemen Baru...</option>
                                <?php
                                $q_dept = $koneksi->query("SELECT * FROM ms_departmen");
                                while($d = $q_dept->fetch_assoc()) {
                                    $sel = ($d['id_departmen'] == $iddepartmen) ? 'selected' : '';
                                    echo "<option value='".$d['id_departmen']."' $sel>".$d['nama_departmen']."</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Sub Departemen</label>
                            <select name="tsubdept" class="w-full select2-manage px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none" data-placeholder="- Pilih Sub Bagian -" data-delete-route="subbagian">
                                <option value=""></option>
                                <option value="add_new" data-url="?page=subbagian&aksi=tambah" class="font-bold text-indigo-600">+ Tambah Sub Bagian Baru...</option>
                                <?php
                                $q_sub = $koneksi->query("SELECT * FROM ms_sub_department");
                                while($d = $q_sub->fetch_assoc()) {
                                    $sel = ($d['id_sub_department'] == $idsubdept) ? 'selected' : '';
                                    echo "<option value='".$d['id_sub_department']."' $sel>".$d['nama_sub_department']."</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Jabatan</label>
                            <select name="tjabatan" class="w-full select2-manage px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none" data-placeholder="- Pilih Jabatan -" data-delete-route="jabatan">
                                <option value=""></option>
                                <option value="add_new" data-url="?page=jabatan&aksi=tambah" class="font-bold text-indigo-600">+ Tambah Jabatan Baru...</option>
                                <?php
                                $q_jab = $koneksi->query("SELECT * FROM ms_jabatan");
                                while($d = $q_jab->fetch_assoc()) {
                                    $sel = ($d['id_jabatan'] == $idjabatan) ? 'selected' : '';
                                    echo "<option value='".$d['id_jabatan']."' $sel>".$d['jabatan']."</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Jadwal / Shift</label>
                            <select name="tjadwal" class="w-full select2-manage px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none" data-placeholder="- Pilih Jadwal -" data-delete-route="jadwal">
                                <option value=""></option>
                                <option value="add_new" data-url="?page=jadwal&aksi=tambah" class="font-bold text-indigo-600">+ Tambah Jadwal Baru...</option>
                                <?php
                                $q_jad = $koneksi->query("SELECT * FROM tb_jadwal");
                                while($d = $q_jad->fetch_assoc()) {
                                    $sel = ($d['id_jadwal'] == $idjadwal) ? 'selected' : '';
                                    echo "<option value='".$d['id_jadwal']."' $sel>".$d['nama_jadwal']."</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">OS / DHK <span class="text-red-500">*</span></label>
                            <select name="tos" class="w-full select2-manage px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none" required data-placeholder="- Pilih -" data-delete-route="os_dhk">
                                <option value=""></option>
                                <option value="add_new" data-url="?page=os_dhk&aksi=tambah" class="font-bold text-indigo-600">+ Tambah Data Baru...</option>
                                <?php
                                $q_os = $koneksi->query("SELECT * FROM ms_os_dhk");
                                while($d = $q_os->fetch_assoc()) {
                                    $sel = ($d['nama_os_dhk'] == $os) ? 'selected' : '';
                                    echo "<option value='".$d['nama_os_dhk']."' $sel>".$d['nama_os_dhk']."</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Golongan</label>
                            <select name="tgolongan" class="w-full select2-manage px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none" data-placeholder="- Pilih -" data-delete-route="golongan">
                                <option value=""></option>
                                <option value="add_new" data-url="?page=golongan&aksi=tambah" class="font-bold text-indigo-600">+ Tambah Golongan Baru...</option>
                                <?php
                                $q_gol = $koneksi->query("SELECT * FROM ms_golongan");
                                while($d = $q_gol->fetch_assoc()) {
                                    $sel = ($d['golongan'] == $golongan) ? 'selected' : '';
                                    echo "<option value='".$d['golongan']."' $sel>".$d['golongan']."</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kelamin</label>
                            <select name="tjeniskelamin" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none">
                                <option value="Laki-laki" <?= ($jeniskelamin == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                                <option value="Perempuan" <?= ($jeniskelamin == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Agama</label>
                            <select name="tagama" class="w-full select2-manage px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none" data-placeholder="- Pilih Agama -" data-delete-route="agama">
                                <option value=""></option>
                                <option value="add_new" data-url="?page=agama&aksi=tambah" class="font-bold text-indigo-600">+ Tambah Agama Baru...</option>
                                <?php
                                $q_agama = $koneksi->query("SELECT * FROM ms_agama");
                                while($d = $q_agama->fetch_assoc()) {
                                    $sel = ($d['agama'] == $agama) ? 'selected' : '';
                                    echo "<option value='".$d['agama']."' $sel>".$d['agama']."</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tempat Lahir</label>
                            <input type="text" name="ttempatlahir" value="<?= $tempatlahir; ?>" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Contoh: Jakarta">
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Lahir</label>
                            <input type="date" name="ttanggallahir" value="<?= $tgllahir; ?>" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Status Kawin</label>
                            <select name="tstatuskawin" class="w-full select2-manage px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none" data-placeholder="- Pilih Status -" data-delete-route="statuskawin">
                                <option value=""></option>
                                <option value="add_new" data-url="?page=statuskawin&aksi=tambah" class="font-bold text-indigo-600">+ Tambah Status Baru...</option>
                                <?php
                                $q_status = $koneksi->query("SELECT * FROM ms_status_kawin");
                                while($d = $q_status->fetch_assoc()) {
                                    $sel = ($d['status_kawin'] == $statuskawin) ? 'selected' : '';
                                    echo "<option value='".$d['status_kawin']."' $sel>".$d['status_kawin']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Documents & Domisile -->
                <div class="mb-8">
                    <h4 class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-4 flex items-center">
                        <i class="fas fa-file-alt mr-2 text-lg"></i> Dokumen & Domisili
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">No. KTP</label>
                            <input type="text" name="tnoktp" value="<?= $noktp; ?>" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="16 Digit No. KTP">
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">No. BPJS</label>
                            <input type="number" name="tbpjs" value="<?= $nobpjs; ?>" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Masukkan No. BPJS">
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap (KTP)</label>
                            <textarea name="talamatktp" rows="2" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Jl. Contoh No. 123..."><?= $alamatktp; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Tinggal</label>
                            <textarea name="talamattinggal" rows="2" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Jl. Contoh No. 123..."><?= $alamattinggal; ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Payroll Info -->
                <div class="mb-8">
                    <h4 class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-4 flex items-center">
                        <i class="fas fa-money-bill-wave mr-2 text-lg"></i> Informasi Penggajian
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Bergabung</label>
                            <input type="date" name="ttanggalbergabung" value="<?= $tglaktif; ?>" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Upah Harian</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-400 text-sm">Rp</span>
                                <input type="number" name="tharian" value="<?= $harian; ?>" class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="0">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Upah Mingguan</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-400 text-sm">Rp</span>
                                <input type="number" name="tmingguan" value="<?= $mingguan; ?>" class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="0">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Upah Bulanan</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-400 text-sm">Rp</span>
                                <input type="number" name="tbulanan" value="<?= $bulanan; ?>" class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="0">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card-footer bg-gray-50 px-8 py-5 flex items-center justify-between border-t border-gray-200">
                <p class="text-xs text-gray-400 italic">Tanda <span class="text-red-500 font-bold">*</span> wajib diisi.</p>
                <div class="flex gap-3">
                    <a href="?page=karyawan" class="px-6 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-bold hover:bg-gray-100 transition-all no-underline">
                        Batal
                    </a>
                    <button type="submit" name="update" value="Update" class="px-8 py-2.5 bg-emerald-600 text-white rounded-lg font-bold hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-all transform hover:-translate-y-0.5">
                        <i class="fas fa-save mr-2"></i> Perbarui Data
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    /* Menghilangkan panah pada input number agar lebih bersih */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    $('.select2-manage').on('change', function() {
        var $select = $(this);
        var selectName = $select.attr('name');
        var selectedVal = $select.val();

        if (selectedVal === 'add_new') {
            // Mengambil label text (Misal: "Sub Departemen")
            var label = $select.closest('.form-group').find('label').text().replace('*', '').trim();
            
            // Tampilkan prompt
            var newData = prompt("Masukkan " + label + " Baru:");

            if (newData && newData.trim() !== "") {
                // Hapus input hidden lama jika user mencoba input berkali-kali
                $('input[name="new_' + selectName + '"]').remove();
                
                // Tambahkan input hidden baru ke form
                $('<input>').attr({
                    type: 'hidden',
                    name: 'new_' + selectName,
                    value: newData.trim()
                }).appendTo('#formUbahKaryawan'); // ID Form

                // Tambahkan opsi secara visual agar tidak "disabled" atau blank
                var newOption = new Option(newData.trim(), newData.trim(), true, true);
                $select.append(newOption).trigger('change');
            } else {
                // Jika batal, reset pilihan
                $select.val('').trigger('change');
            }
        }
    });
});
</script>
