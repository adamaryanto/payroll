<?php
// Ambil data untuk ditampilkan di form
$tampil = $koneksi->query("SELECT * FROM ms_upah LIMIT 1");
$data = $tampil->fetch_assoc();

if (isset($_POST['simpan'])) {
    // 1. Bersihkan format Rupiah (ubah 1.000.000 jadi 1000000)
    $tharian   = str_replace('.', '', $_POST['tharian'] ?? 0);
    $tmingguan = str_replace('.', '', $_POST['tmingguan'] ?? 0);
    $tbulanan  = str_replace('.', '', $_POST['tbulanan'] ?? 0);
    
    // 2. Proses Update ke Database
    // Pastikan nama kolom di database sesuai (upah_harian, upah_mingguan, upah_bulanan)
    $sql = $koneksi->query("UPDATE ms_upah SET 
        upah_harian = '$tharian', 
        upah_mingguan = '$tmingguan', 
        upah_bulanan = '$tbulanan'
    ");
    
    if ($sql) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Data Upah Berhasil Diperbarui',
                confirmButtonColor: '#2563eb',
                confirmButtonText: 'Selesai'
            }).then((result) => {
                window.location.href='?page=upah';
            });
        </script>";
        exit;
    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Gagal memperbarui data!',
                confirmButtonColor: '#2563eb'
            });
        </script>";
    }
}
?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 mb-10">
    <div class="bg-white shadow-xl border border-gray-200 rounded-2xl overflow-hidden">
        
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
            <h3 class="text-2xl font-extrabold text-white m-0 tracking-tight flex items-center">
                <i class="fas fa-wallet mr-3"></i> Pengaturan Upah
            </h3>
            <p class="text-slate-300 text-sm mt-1">Atur nominal upah standar harian, mingguan, dan bulanan</p>
        </div>
        
        <form method="POST">
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Upah Harian <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><span class="text-gray-400 font-bold">Rp</span></div>
                            <input type="text" name="tharian" value="<?= number_format($data['upah_harian'] ?? 0, 0, ',', '.') ?>" required 
                                   class="input-rupiah block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl bg-gray-50 font-medium focus:ring-2 focus:ring-blue-500"/>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Upah Mingguan <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><span class="text-gray-400 font-bold">Rp</span></div>
                            <input type="text" name="tmingguan" value="<?= number_format($data['upah_mingguan'] ?? 0, 0, ',', '.') ?>" required 
                                   class="input-rupiah block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl bg-gray-50 font-medium focus:ring-2 focus:ring-blue-500"/>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Upah Bulanan <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><span class="text-gray-400 font-bold">Rp</span></div>
                            <input type="text" name="tbulanan" value="<?= number_format($data['upah_bulanan'] ?? 0, 0, ',', '.') ?>" required 
                                   class="input-rupiah block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl bg-gray-50 font-medium focus:ring-2 focus:ring-blue-500"/>
                        </div>
                    </div>

                </div>

                <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                    <div class="text-xs text-gray-500 italic"><span class="text-rose-500">*</span> Wajib diisi</div>
                    <button type="submit" name="simpan" class="inline-flex items-center px-6 py-2.5 border border-transparent text-sm font-bold rounded-xl shadow-md text-white bg-blue-600 hover:bg-blue-700 transition duration-150 ease-in-out transform hover:-translate-y-0.5">
                        <i class="fas fa-save mr-2"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Auto format Rupiah
document.querySelectorAll('.input-rupiah').forEach(input => {
    input.addEventListener('keyup', function(e) {
        let val = this.value.replace(/\D/g, '');
        this.value = val ? new Intl.NumberFormat('id-ID').format(val) : '';
    });
});
</script>