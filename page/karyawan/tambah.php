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
                            <label class="block text-sm font-semibold text-gray-700 mb-2">OS / DHK <span class="text-red-500">*</span></label>
                            <select name="tos" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none" required>
                                <option value="OS">OS</option>
                                <option value="DHK">DHK</option>
                                <option value="-">-</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Golongan</label>
                            <select name="tgolongan" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none">
                                <option value="Harian">Harian</option>
                                <option value="Mingguan">Mingguan</option>
                                <option value="Bulanan">Bulanan</option>
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
                            <select name="tagama" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none">
                                <option value="Islam">Islam</option>
                                <option value="Kristen Katolik">Kristen Katolik</option>
                                <option value="Kristen Protestan">Kristen Protestan</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Budha">Budha</option>
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
                                <option value="Belum Kawin">Belum Kawin</option>
                                <option value="Kawin">Kawin</option>
                                <option value="Janda">Janda</option>
                                <option value="Duda">Duda</option>
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
                            <label class="block text-sm font-semibold text-gray-700 mb-2">No. SIM</label>
                            <input type="text" name="tnosim" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Masukkan No. SIM">
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">No. NPWP</label>
                            <input type="number" name="tnpwp" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Masukkan No. NPWP">
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
                    <?php if(isset($_GET['id'])): ?>
                    <button type="submit" name="update" value="Update" class="px-8 py-2.5 bg-emerald-600 text-white rounded-lg font-bold hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-all transform hover:-translate-y-0.5">
                        <i class="fas fa-edit mr-2"></i> Update Data
                    </button>
                    <?php endif; ?>
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