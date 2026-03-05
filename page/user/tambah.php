<div class="row px-3 mt-4">
    <div class="col-md-8 mx-auto">
        <div class="card rounded-2xl shadow-sm border-0">
            
            <div class="card-header bg-white border-b border-gray-100 py-4 rounded-t-2xl">
                <h3 class="card-title text-xl font-bold text-gray-800 m-0">Tambah Data User</h3>
            </div>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="card-body p-6">
                    
                    <div class="mb-4">
                        <label class="form-label font-semibold text-gray-700">Username <span class="text-rose-500">*</span></label>
                        <input type="text" name="tnama" placeholder="Masukkan username" autocomplete="off" required 
                               class="form-control rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-100 transition-all px-4 py-2.5"/>
                    </div>

                    <div class="mb-4">
                        <label class="form-label font-semibold text-gray-700">Password <span class="text-rose-500">*</span></label>
                        <input type="password" name="tpassword" placeholder="Masukkan password" autocomplete="off" required 
                               class="form-control rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-100 transition-all px-4 py-2.5"/>
                    </div>

                    <div class="mb-5">
                        <label class="form-label font-semibold text-gray-700">Role <span class="text-rose-500">*</span></label>
                        <select name="trole" class="form-control select2 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-100 transition-all px-4 py-2.5" required>
                            <option value="" disabled selected>-- Pilih Role --</option>
                            <option value="owner">Owner</option>
                            <option value="Admin HRD">Admin HRD</option>
                            <option value="Kepala Gudang">Kepala Gudang</option>
                            <option value="Admin Master">Admin Master</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-100">
                        <div class="text-sm text-gray-500">
                            <span class="text-rose-500 font-bold text-lg leading-none">*</span> Wajib diisi
                        </div>
                        <button type="submit" name="simpan" value="Simpan" class="btn btn-primary bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-6 rounded-lg shadow-sm transition-colors border-0 flex items-center">
                            <i class="fas fa-save mr-2"></i> Simpan
                        </button>
                    </div>

                </div>
            </form>
        </div></div>
</div>

<?php
// Cek apakah tombol simpan ditekan
if (isset($_POST['simpan'])) {
    // Mengambil data dari form, menggunakan null coalescing (??) agar tidak error jika kosong
    $tnama     = $_POST['tnama'] ?? '';
    $trole     = $_POST['trole'] ?? '';
    $tpassword = $_POST['tpassword'] ?? '';
    
    // Proses Insert ke Database
    $sql = $koneksi->query("INSERT INTO ms_login (id_perusahaan, user_login, lg_password, role) VALUES ('1', '$tnama', '$tpassword', '$trole')");
    
    if ($sql) {
        echo '
        <script type="text/javascript">
            alert("Data Berhasil Tersimpan");
            window.location.href="?page=user";
        </script>
        ';
    } else {
        echo '
        <script type="text/javascript">
            alert("Gagal menyimpan data!");
        </script>
        ';
    }
}
?>