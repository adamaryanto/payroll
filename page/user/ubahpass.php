<?php
$idu = $_SESSION['iduser'];
// Note: Keeping the original query logic but improving the UI
$tampil = $koneksi->query("SELECT A.*, nama_department FROM tb_user A LEFT JOIN tb_department B ON A.id_department = B.id_department WHERE A.id_user = '$idu'");
$data = $tampil->fetch_assoc();
$nama = $data['nama'];
$fullname = $data['fullname'];
?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 mb-10">
    <div class="bg-white shadow-xl border border-gray-200 rounded-2xl overflow-hidden">
        
        <div class="bg-gradient-to-r from-slate-700 to-slate-800 px-8 py-6">
            <h3 class="text-2xl font-extrabold text-white m-0 tracking-tight flex items-center">
                <i class="fas fa-key mr-3"></i>
                Ubah Password User
            </h3>
            <p class="text-slate-300 text-sm mt-1">Perbarui kata sandi akun Anda untuk keamanan</p>
        </div>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="p-8">
                <!-- Username (Read Onlyish) -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Username
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input type="text" name="tnama" value="<?php echo $nama; ?>" required 
                               class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl leading-5 bg-gray-50 text-gray-500 sm:text-sm font-medium" readonly/>
                    </div>
                </div>

                <!-- Fullname -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Nama Lengkap
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-id-card text-gray-400"></i>
                        </div>
                        <input type="text" name="tfullname" value="<?php echo $fullname; ?>" required 
                               class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl leading-5 bg-gray-50 text-gray-500 sm:text-sm font-medium" readonly/>
                    </div>
                </div>

                <!-- New Password Input -->
                <div class="mb-8">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Password Baru <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input placeholder="Masukkan password baru..." autocomplete="off" type="password" name="tpass" required 
                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:border-slate-500 sm:text-sm transition duration-150 ease-in-out font-medium"/>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                    <div class="text-xs text-gray-500 italic">
                        <span class="text-rose-500">*</span> Wajib diisi
                    </div>
                    <div class="flex gap-3">
                        <a onclick="history.back();" href="#" class="inline-flex items-center px-5 py-2.5 border border-gray-300 shadow-sm text-sm font-bold rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition duration-150 ease-in-out">
                            Batal
                        </a>
                        <button type="submit" name="simpan" class="inline-flex items-center px-6 py-2.5 border border-transparent text-sm font-bold rounded-xl shadow-md text-white bg-slate-700 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition duration-150 ease-in-out transform hover:-translate-y-0.5">
                            <i class="fas fa-shield-alt mr-2"></i> Update Password
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
$tpass = @$_POST['tpass'];
$simpan = @$_POST['simpan'];
if ($simpan) {
    $sql = $koneksi->query("UPDATE tb_user SET lg_password='$tpass' WHERE id_user = '$idu'");
    if ($sql) {
        echo "<script>alert('Password Berhasil Diperbarui'); window.location.href='?page=user';</script>";
    }
}
?>