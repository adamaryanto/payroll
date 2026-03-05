<?php
if (isset($_POST['simpan'])) {
    $nama = $_POST['tnama'];
    $noabsen = $_POST['tnoabsen'];
    $os = $_POST['tos'];
    $golongan = $_POST['tgolongan'];
    $jeniskelamin = $_POST['tjeniskelamin'];
    $agama = $_POST['tagama'];
    $tempatlahir = $_POST['ttempatlahir'];
    $tanggallahir = $_POST['ttanggallahir'];
    $statuskawin = $_POST['tstatuskawin'];
    $noktp = $_POST['tnoktp'];
    $bpjs = $_POST['tbpjs'];
    $alamatktp = $_POST['talamatktp'];
    $tanggalbergabung = $_POST['ttanggalbergabung'];
    $harian = $_POST['tharian'] ?: 0;
    $mingguan = $_POST['tmingguan'] ?: 0;
    $bulanan = $_POST['tbulanan'] ?: 0;
    
    $departmen = isset($_POST['tdepartmen']) ? $_POST['tdepartmen'] : 0;
    $jabatan = isset($_POST['tjabatan']) ? $_POST['tjabatan'] : 0;
    $subdept = isset($_POST['tsubdept']) ? $_POST['tsubdept'] : 0;
    $jadwal = isset($_POST['tjadwal']) ? $_POST['tjadwal'] : 0;

    $sql = $koneksi->query("INSERT INTO ms_karyawan (
        id_departmen, id_jabatan, no_absen, nama_karyawan, tempat_lahir, tgl_lahir, agama, 
        status_kawin, jenis_kelamin, no_ktp, alamat_ktp, alamat_tinggal, 
        status_karyawan, tgl_aktif, foto, no_bpjs, upah_harian, upah_mingguan, 
        upah_bulanan, id_jadwal, id_sub_department, OS_DHK, golongan
    ) VALUES (
        '$departmen', '$jabatan', '$noabsen', '$nama', '$tempatlahir', '$tanggallahir', '$agama',
        '$statuskawin', '$jeniskelamin', '$noktp', '$alamatktp', '',
        'Aktif', '$tanggalbergabung', '', '$bpjs', '$harian', '$mingguan',
        '$bulanan', '$jadwal', '$subdept', '$os', '$golongan'
    )");

    if ($sql) {
        echo "<script>alert('Data Berhasil Disimpan'); window.location='?page=karyawan';</script>";
    } else {
        echo "<script>alert('Gagal Simpan Data');</script>";
    }
}
?>

<div class="container-fluid px-4 mt-5 mb-5">
    <div class="card border-0 shadow-lg rounded-xl overflow-hidden">
        
        <div class="card-header bg-white border-b border-gray-100 py-5 px-6">
            <h3 class="text-2xl font-extrabold text-gray-800 tracking-tight m-0">Tambah Data Karyawan</h3>
            <p class="text-sm text-gray-500 mt-1">Lengkapi formulir di bawah ini untuk mendaftarkan karyawan baru.</p>
        </div>

        <form method="POST" enctype="multipart/form-data">
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
                                $q_absen = $koneksi->query("SELECT MAX(no_absen) as max_absen FROM ms_karyawan");
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
                                    echo "<option value='".$d['id_jabatan']."'>".$d['jabatan']."</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Jadwal</label>
                            <select name="tjadwal" class="w-full select2-manage px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none" data-placeholder="- Pilih Jadwal -" data-delete-route="jadwal">
                                <option value=""></option>
                                <option value="add_new" data-url="?page=jadwal&aksi=tambah" class="font-bold text-indigo-600">+ Tambah Jadwal Baru...</option>
                                <?php
                                $q_jadwal = $koneksi->query("SELECT * FROM tb_jadwal");
                                while($d = $q_jadwal->fetch_assoc()) {
                                    echo "<option value='".$d['id_jadwal']."'>".$d['nama_jadwal']."</option>";
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
                                    echo "<option value='".$d['nama_os_dhk']."'>".$d['nama_os_dhk']."</option>";
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
                                    echo "<option value='".$d['golongan']."'>".$d['golongan']."</option>";
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
                                    echo "<option value='".$d['agama']."'>".$d['agama']."</option>";
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
                            <select name="tstatuskawin" class="w-full select2-manage px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none" data-placeholder="- Pilih Status -" data-delete-route="statuskawin">
                                <option value=""></option>
                                <option value="add_new" data-url="?page=statuskawin&aksi=tambah" class="font-bold text-indigo-600">+ Tambah Status Baru...</option>
                                <?php
                                $q_status = $koneksi->query("SELECT * FROM ms_status_kawin");
                                while($d = $q_status->fetch_assoc()) {
                                    echo "<option value='".$d['status_kawin']."'>".$d['status_kawin']."</option>";
                                }
                                ?>
                            </select>
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
                            <label class="block text-sm font-semibold text-gray-700 mb-2">No. BPJS</label>
                            <input type="number" name="tbpjs" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Masukkan No. BPJS">
                        </div>
                        <div class="form-group md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap (KTP)</label>
                            <textarea name="talamatktp" rows="2" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Jl. Contoh No. 123..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <h4 class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-4 flex items-center">
                        <i class="fas fa-money-bill-wave mr-2 text-lg"></i> Informasi Penggajian
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Bergabung</label>
                            <input type="date" name="ttanggalbergabung" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Upah Harian</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-400 text-sm">Rp</span>
                                <input type="number" name="tharian" class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="0">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Upah Mingguan</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-400 text-sm">Rp</span>
                                <input type="number" name="tmingguan" class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="0">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Upah Bulanan</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-400 text-sm">Rp</span>
                                <input type="number" name="tbulanan" class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="0">
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