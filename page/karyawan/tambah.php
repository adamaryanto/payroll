<?php
if (isset($_POST['simpan'])) {
    // ---------------------------------------------------------
    // Inline Master Data Creation Logic
    // ---------------------------------------------------------
    
    // Function to handle inline insert and return new ID
    function getOrInsertMaster($koneksi, $postKey, $table, $column, $extraData = []) {
        // Ambil dari input baru jika ada (dikirim via JavaScript)
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
        
        // Ambil nilai reguler. Jika 'add_new' (berarti gagal isi prompt), kembalikan 0 agar tidak error DB.
        $selectedVal = isset($_POST[$postKey]) ? $koneksi->real_escape_string($_POST[$postKey]) : 0;
        return ($selectedVal === 'add_new') ? 0 : $selectedVal;
    }

    $departmen = getOrInsertMaster($koneksi, 'tdepartmen', 'ms_departmen', 'nama_departmen', ['id_perusahaan' => '1']);
    $subdept = getOrInsertMaster($koneksi, 'tsubdept', 'ms_sub_department', 'nama_sub_department', ['id_perusahaan' => '1']);
    $jabatan = getOrInsertMaster($koneksi, 'tjabatan', 'ms_jabatan', 'jabatan', ['id_perusahaan' => '1']);
    $os = getOrInsertMaster($koneksi, 'tos', 'ms_os_dhk', 'nama_os_dhk');
    $golongan = getOrInsertMaster($koneksi, 'tgolongan', 'ms_golongan', 'golongan');
    $agama = getOrInsertMaster($koneksi, 'tagama', 'ms_agama', 'agama');
    $statuskawin = isset($_POST['tstatuskawin']) ? $koneksi->real_escape_string($_POST['tstatuskawin']) : '';
    
    // ---------------------------------------------------------

    $nama = $koneksi->real_escape_string($_POST['tnama']);
    $noabsen = $koneksi->real_escape_string($_POST['tnoabsen']);
    $jeniskelamin = $koneksi->real_escape_string($_POST['tjeniskelamin']);
    $tempatlahir = $koneksi->real_escape_string($_POST['ttempatlahir']);
    $tanggallahir = $koneksi->real_escape_string($_POST['ttanggallahir']);
    $noktp = $koneksi->real_escape_string($_POST['tnoktp']);
    $alamatktp = isset($_POST['talamatktp']) ? $koneksi->real_escape_string($_POST['talamatktp']) : '';
    $alamattinggal = isset($_POST['talamattinggal']) ? $koneksi->real_escape_string($_POST['talamattinggal']) : '';
    $tanggalbergabung = isset($_POST['ttanggalbergabung']) && $_POST['ttanggalbergabung'] !== '' ? $koneksi->real_escape_string($_POST['ttanggalbergabung']) : date('Y-m-d');
    $sql = $koneksi->query("INSERT INTO ms_karyawan (
        id_departmen, id_jabatan, no_absen, nama_karyawan, tempat_lahir, tgl_lahir, agama, 
        status_kawin, jenis_kelamin, no_ktp, alamat_ktp, alamat_tinggal, 
        status_karyawan, tgl_aktif, foto, id_sub_department, OS_DHK, golongan, id_jadwal
    ) VALUES (
        '$departmen', '$jabatan', '$noabsen', '$nama', '$tempatlahir', '$tanggallahir', '$agama',
        '$statuskawin', '$jeniskelamin', '$noktp', '$alamatktp', '$alamattinggal',
        'Aktif', '$tanggalbergabung', '', '$subdept', '$os', '$golongan', '0'
    )");

    $new_id = $koneksi->insert_id;

    if ($sql) {
        echo "<script>alert('Data Berhasil Disimpan'); window.location='?page=karyawan';</script>";
    } else {
        echo "<script>alert('Gagal Simpan Data: " . $koneksi->error . "');</script>";
    }
}
?>

<div class="container-fluid px-4 mt-5 mb-5">
    <div class="card border-0 shadow-lg rounded-xl overflow-hidden">
        
        <div class="card-header bg-white border-b border-gray-100 py-5 px-6">
            <h3 class="text-2xl font-extrabold text-gray-800 tracking-tight m-0">Tambah Data Karyawan</h3>
            <p class="text-sm text-gray-500 mt-1">Lengkapi formulir di bawah ini untuk mendaftarkan karyawan baru.</p>
        </div>

        <form method="POST" enctype="multipart/form-data" id="formTambahKaryawan">
            <div class="card-body p-6 bg-gray-50/30">
                
                <div class="mb-8">
                    <h4 class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-4 flex items-center">
                        <i class="fas fa-user-circle mr-2 text-lg"></i> Informasi Pribadi & Jabatan
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        
                        <input type="hidden" name="tid">

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input autocomplete="off" type="text" name="tnama" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none" placeholder="Masukkan nama lengkap">
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">No. Absen</label>
                            <?php
                                $q_absen = $koneksi->query("SELECT MAX(CAST(no_absen AS UNSIGNED)) as max_absen FROM ms_karyawan");
                                $dt_absen = $q_absen->fetch_assoc();
                                $new_absen = (int)$dt_absen['max_absen'] + 1;
                            ?>
                            <input type="text" name="tnoabsen" value="<?= $new_absen; ?>" readonly class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-gray-100 text-gray-500 font-bold cursor-not-allowed">
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Departemen</label>
                            <select name="tdepartmen" class="w-full select2-manage px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none" data-placeholder="- Pilih Departemen -" data-delete-route="bagian">
                                <option value=""></option>
                                <option value="add_new" data-url="?page=bagian&aksi=tambah" class="font-bold text-indigo-600">+ Tambah Departemen Baru...</option>
                                <?php
                                $q_dept = $koneksi->query("SELECT * FROM ms_departmen");
                                while($d = $q_dept->fetch_assoc()) {
                                    echo "<option value='".$d['id_departmen']."'>".$d['nama_departmen']."</option>";
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
                                    echo "<option value='".$d['id_sub_department']."'>".$d['nama_sub_department']."</option>";
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
                                    // Sudah diperbaiki: value menggunakan id_jabatan
                                    echo "<option value='".$d['id_jabatan']."' data-id='".$d['id_jabatan']."'>".$d['jabatan']."</option>";
                                }
                                ?>
                            </select>
                        </div>



                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">OS / DHK <span class="text-red-500">*</span></label>
                            <select name="tos" class="w-full select2-manage px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none" data-placeholder="- Pilih -" data-delete-route="os_dhk" required>
                                <option value=""></option>
                                <option value="add_new" data-url="?page=os_dhk&aksi=tambah" class="font-bold text-indigo-600">+ Tambah Data Baru...</option>
                                <?php
                                $q_os = $koneksi->query("SELECT * FROM ms_os_dhk");
                                while($d = $q_os->fetch_assoc()) {
                                    // Sudah diperbaiki: Menggunakan id_os_dhk
                                    echo "<option value='".$d['id_os_dhk']."' data-id='".$d['id_os_dhk']."'>".$d['nama_os_dhk']."</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Golongan</label>
                            <select name="tgolongan" id="selectGolongan" class="w-full select2-manage px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none" data-placeholder="- Pilih -" data-delete-route="golongan">
                                <option value=""></option>
                                <option value="add_new" data-url="?page=golongan&aksi=tambah" class="font-bold text-indigo-600">+ Tambah Golongan Baru...</option>
                                <?php
                                $q_gol = $koneksi->query("SELECT g.*, COALESCE(u.upah_harian, 0) as upah_harian, COALESCE(u.upah_mingguan, 0) as upah_mingguan, COALESCE(u.upah_bulanan, 0) as upah_bulanan FROM ms_golongan g LEFT JOIN ms_upah u ON g.id_golongan = u.id_golongan");
                                while($d = $q_gol->fetch_assoc()) {
                                    echo "<option value='".$d['id_golongan']."' data-harian='".$d['upah_harian']."' data-mingguan='".$d['upah_mingguan']."' data-bulanan='".$d['upah_bulanan']."'>".$d['golongan']."</option>";
                                }
                                ?>
                            </select>
                        </div>

                        

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kelamin</label>
                            <select name="tjeniskelamin" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none">
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
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
                                    // Sudah diperbaiki: menggunakan id_agama
                                    echo "<option value='".$d['id_agama']."' data-id='".$d['id_agama']."'>".$d['agama']."</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tempat Lahir</label>
                            <input type="text" name="ttempatlahir" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Contoh: Jakarta">
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Lahir</label>
                            <input type="date" name="ttanggallahir" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Status Kawin</label>
                            <select name="tstatuskawin" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none">
                                <option value="">- Pilih Status -</option>
                                <option value="Kawin">Kawin</option>
                                <option value="Belum Kawin">Belum Kawin</option>
                                <option value="Janda">Janda</option>
                                <option value="Duda">Duda</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Bergabung <span class="text-red-500">*</span></label>
                            <input type="date" name="ttanggalbergabung" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <h4 class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-4 flex items-center">
                        <i class="fas fa-file-alt mr-2 text-lg"></i> Dokumen & Domisili
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">No. KTP</label>
                            <input type="text" name="tnoktp" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="16 Digit No. KTP">
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap (KTP)</label>
                            <textarea name="talamatktp" rows="2" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Jl. Contoh No. 123..."></textarea>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap (Tinggal)</label>
                            <textarea name="talamattinggal" rows="2" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Jl. Contoh No. 123..."></textarea>
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
                    <button type="submit" name="simpan" value="Simpan" class="px-8 py-2.5 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-0.5">
                        <i class="fas fa-save mr-2"></i> Simpan Data
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
    // Auto-fill upah saat golongan berubah
    $('#selectGolongan').on('change', function() {
        var selected = $(this).find('option:selected');
        var val = $(this).val();
        
        if (val && val !== 'add_new' && val !== '') {
            $('#upahHarian').val(selected.data('harian') || 0);
            $('#upahMingguan').val(selected.data('mingguan') || 0);
            $('#upahBulanan').val(selected.data('bulanan') || 0);
        } else if (val !== 'add_new') {
            $('#upahHarian').val('');
            $('#upahMingguan').val('');
            $('#upahBulanan').val('');
        }
    });

    $('.select2-manage').on('change', function() {
        var $select = $(this);
        var selectName = $select.attr('name');
        var selectedVal = $select.val();

        if (selectedVal === 'add_new') {
            var label = $select.closest('.form-group').find('label').text().replace('*', '').trim();
            var newData = prompt("Masukkan " + label + " Baru:");

            if (newData && newData.trim() !== "") {
                $('input[name="new_' + selectName + '"]').remove();
                $('<input>').attr({
                    type: 'hidden',
                    name: 'new_' + selectName,
                    value: newData.trim()
                }).appendTo('#formTambahKaryawan');
                var newOption = new Option(newData.trim(), newData.trim(), true, true);
                $select.append(newOption).trigger('change');
            } else {
                $select.val('').trigger('change');
            }
        }
    });
});
</script>