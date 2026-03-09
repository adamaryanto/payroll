<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 mb-10">
    <div class="bg-white shadow-xl border border-gray-200 rounded-2xl overflow-hidden">
        
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
            <h3 class="text-2xl font-extrabold text-white m-0 tracking-tight flex items-center">
                <i class="fas fa-plus-circle mr-3"></i>
                Tambah Data Bagian
            </h3>
            <p class="text-slate-300 text-sm mt-1">Tambahkan departemen baru ke dalam sistem</p>
        </div>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="p-8">
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Nama Bagian <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-building text-gray-400"></i>
                        </div>
                        <input placeholder="Masukkan nama bagian..." autocomplete="off" type="text" name="tnama" required 
                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out font-medium"/>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                    <div class="text-xs text-gray-500 italic">
                        <span class="text-rose-500">*</span> Wajib diisi
                    </div>
                    <div class="flex gap-3">
                        <a href="?page=bagian" class="inline-flex items-center px-5 py-2.5 border border-gray-300 shadow-sm text-sm font-bold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition duration-150 ease-in-out">
                            Batal
                        </a>
                        <button type="submit" name="simpan" value="Simpan" class="inline-flex items-center px-6 py-2.5 border border-transparent text-sm font-bold rounded-xl shadow-md text-white bg-blue-600 hover:bg-blue-700 transition duration-150 ease-in-out transform hover:-translate-y-0.5">
                            <i class="fas fa-save mr-2"></i> Simpan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
if (isset($_POST['simpan'])) {
    $tnama = $_POST['tnama'] ?? '';
    
    // Gunakan prepared statement untuk keamanan lebih baik
    $stmt = $koneksi->prepare("INSERT INTO ms_departmen (nama_departmen) VALUES (?)");
    $stmt->bind_param("s", $tnama);
    
    if ($stmt->execute()) {
        echo '<script type="text/javascript">
                alert("Data Berhasil Tersimpan");
                window.location.href="?page=bagian";
              </script>';
    } else {
        echo '<script type="text/javascript">
                alert("Gagal menyimpan data!");
              </script>';
    }
}
?>