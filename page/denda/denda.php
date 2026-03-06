<?php
// Mengambil data denda saat ini
$tampil = $koneksi->query("SELECT * FROM tb_denda LIMIT 1");
$data = $tampil->fetch_assoc();
$dendamasuk = $data['denda_masuk'] ?? '';
$dendaistirahat = $data['denda_istirahat'] ?? '';

// Memproses saat tombol simpan ditekan (Dipindah ke atas agar lebih rapi)
if (isset($_POST['simpan'])) {
    $tdendamasuk = $_POST['tdendamasuk'] ?? '';
    $tdendaistirahat = $_POST['tdendaistirahat'] ?? '';
    
    // Proses Update ke Database
    $sql = $koneksi->query("UPDATE tb_denda SET denda_masuk = '$tdendamasuk', denda_istirahat = '$tdendaistirahat'");
    
    if ($sql) {
        echo '<script type="text/javascript">
                alert("Data Pengaturan Denda Berhasil Diperbarui");
                window.location.href="?page=denda";
              </script>';
    } else {
        echo '<script type="text/javascript">
                alert("Gagal memperbarui data denda!");
              </script>';
    }
}
?>

<div class="container-fluid px-2 mt-5 mb-5">
    <div class="max-w-3xl mx-auto">
        <div class="card border-0 shadow-sm rounded-xl overflow-hidden bg-white">
            
            <div class="border-b border-gray-100 py-5 px-6 bg-white">
                <h3 class="text-xl font-bold text-gray-800 m-0">Pengaturan Denda</h3>
                <p class="text-[14px] text-gray-500 mt-1 mb-0">Atur nominal potongan denda untuk keterlambatan dan waktu istirahat.</p>
            </div>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="p-6">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div>
                            <label class="block text-[14px] font-semibold text-gray-700 mb-2">
                                Denda Masuk <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <span class="text-gray-500 font-medium text-[15px]">Rp</span>
                                </div>
                                <input type="number" name="tdendamasuk" value="<?= htmlspecialchars($dendamasuk) ?>" required autocomplete="off"
                                       class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-[15px] text-gray-900 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors outline-none" 
                                       placeholder="0">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[14px] font-semibold text-gray-700 mb-2">
                                Denda Istirahat <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <span class="text-gray-500 font-medium text-[15px]">Rp</span>
                                </div>
                                <input type="number" name="tdendaistirahat" value="<?= htmlspecialchars($dendaistirahat) ?>" required autocomplete="off"
                                       class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-[15px] text-gray-900 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors outline-none" 
                                       placeholder="0">
                            </div>
                        </div>

                    </div>

                    <div class="flex items-center justify-between mt-8 pt-5 border-t border-gray-100">
                        <div class="text-[13px] text-gray-500 flex items-center">
                            <span class="text-rose-500 font-bold text-lg mr-1.5 leading-none">*</span> Wajib diisi
                        </div>
                        <button type="submit" name="simpan" class="inline-flex items-center justify-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-[15px] font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-4 focus:ring-indigo-100 border-0">
                            <i class="fas fa-save mr-2"></i> Simpan Pengaturan
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>
</div>