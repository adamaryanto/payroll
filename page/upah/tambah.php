<?php
if (isset($_POST['simpan'])) {
    $tharian = str_replace('.', '', $_POST['tharian'] ?? 0);
    $tmingguan = str_replace('.', '', $_POST['tmingguan'] ?? 0);
    $tbulanan = str_replace('.', '', $_POST['tbulanan'] ?? 0);

    $sql = $koneksi->query("INSERT INTO ms_upah (upah_harian, upah_mingguan, upah_bulanan) VALUES ('$tharian', '$tmingguan', '$tbulanan')");
    if ($sql) {
        echo "<!DOCTYPE html>
        <html>
        <head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data Tersimpan',
                    confirmButtonColor: '#2563eb',
                    confirmButtonText: 'Selesai'
                }).then((result) => {
                    window.location.href='?page=upah';
                });
            </script>
        </body>
        </html>";
        exit;
    } else {
        echo "<!DOCTYPE html>
        <html>
        <head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal menyimpan data upah!',
                    confirmButtonColor: '#2563eb',
                    confirmButtonText: 'Ok'
                });
            </script>
        </body>
        </html>";
    }
}
?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 mb-10">
    <div class="bg-white shadow-xl border border-gray-200 rounded-2xl overflow-hidden">
        
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
            <h3 class="text-2xl font-extrabold text-white m-0 tracking-tight flex items-center">
                <i class="fas fa-plus-circle mr-3"></i>
                Tambah Data Upah
            </h3>
            <p class="text-blue-200 text-sm mt-1">Buat Data Upah baru</p>
        </div>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="p-8">
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Upah Harian <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-gray-400 text-sm font-semibold">Rp</span>
                        <input type="text" name="tharian" id="tharian" required placeholder="0" class="input-rupiah block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl bg-gray-50 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm font-medium"/>
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Upah Mingguan <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-gray-400 text-sm font-semibold">Rp</span>
                        <input type="text" name="tmingguan" id="tmingguan" required placeholder="0" class="input-rupiah block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl bg-gray-50 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm font-medium"/>
                    </div>
                </div>
                <div class="mb-8">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Upah Bulanan <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-gray-400 text-sm font-semibold">Rp</span>
                        <input type="text" name="tbulanan" id="tbulanan" required placeholder="0" class="input-rupiah block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl bg-gray-50 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm font-medium"/>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                    <div class="text-xs text-gray-500 italic"><span class="text-red-500">*</span> Wajib diisi</div>
                    <div class="flex gap-3">
                        <a href="?page=upah" class="inline-flex items-center px-5 py-2.5 border border-gray-300 shadow-sm text-sm font-bold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition">Batal</a>
                        <button type="submit" name="simpan" value="Simpan" class="inline-flex items-center px-6 py-2.5 border border-transparent text-sm font-bold rounded-xl shadow-md text-white bg-blue-600 hover:bg-blue-700 transition transform hover:-translate-y-0.5">
                            <i class="fas fa-save mr-2"></i> Simpan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('.input-rupiah').forEach(input => {
    input.addEventListener('keyup', function(e) {
        let val = this.value.replace(/\D/g, '');
        this.value = val ? new Intl.NumberFormat('id-ID').format(val) : '';
    });
});
</script>
