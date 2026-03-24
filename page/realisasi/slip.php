<?php
$id = $_GET['id'] ?? '';
$ttgl1 = date("Y-m-d");

// Ambil ID Realisasi dari detail terakhir untuk navigasi kembali
$q_realisasi = $koneksi->query("SELECT id_realisasi FROM tb_realisasi_detail WHERE id_karyawan = '$id' ORDER BY id_realisasi_detail DESC LIMIT 1");
$d_realisasi = $q_realisasi->fetch_assoc();
$idRealisasi = $d_realisasi ? $d_realisasi['id_realisasi'] : 0;
?>

<div class="container-fluid px-3 md:px-6 mt-6 mb-10">
    <div class="max-w-5xl mx-auto">
        <div class="bg-white shadow-xl rounded-3xl border border-gray-100 overflow-hidden">
            
            <div class="bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-700 px-6 md:px-10 py-6 md:py-8">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-white shadow-inner">
                            <i class="fas fa-file-invoice-dollar text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-white m-0 tracking-tight">Cetak Slip Gaji</h3>
                            <p class="text-blue-100 text-sm font-medium opacity-90 mt-1">Pilih karyawan dan rentang tanggal untuk mengunduh slip gaji</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 md:p-10">
                <form method="POST" class="space-y-6">
                    <div class="bg-blue-50/40 border border-blue-100/60 p-6 md:p-8 rounded-2xl">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                            <div class="md:col-span-6 lg:col-span-4">
                                <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Pilih Karyawan <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <select name="id_karyawan_post" class="select2-slip w-full" data-placeholder="- Cari Karyawan -" required>
                                        <option value=""></option>
                                        <?php
                                        $q_all_kar = $koneksi->query("SELECT id_karyawan, no_absen, nama_karyawan FROM ms_karyawan WHERE status_karyawan = 'Aktif' ORDER BY nama_karyawan ASC");
                                        while($kar = $q_all_kar->fetch_assoc()) {
                                            $selected = ($kar['id_karyawan'] == $id) ? 'selected' : '';
                                            echo "<option value='{$kar['id_karyawan']}' {$selected}>{$kar['no_absen']} - {$kar['nama_karyawan']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="md:col-span-6 lg:col-span-4">
                                <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Dari Tanggal <span class="text-rose-500">*</span></label>
                                <div class="relative group">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-blue-500 pointer-events-none group-focus-within:text-blue-600 transition-colors">
                                        <i class="fas fa-calendar-alt text-lg"></i>
                                    </span>
                                    <input type="date" name="ttgl1" required value="<?= $ttgl1 ?>" 
                                        class="w-full pl-12 pr-4 py-3.5 bg-white border border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none text-gray-700 font-bold shadow-sm">
                                </div>
                            </div>
                            <div class="md:col-span-6 lg:col-span-4">
                                <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Sampai Tanggal <span class="text-rose-500">*</span></label>
                                <div class="relative group">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-blue-500 pointer-events-none group-focus-within:text-blue-600 transition-colors">
                                        <i class="fas fa-calendar-alt text-lg"></i>
                                    </span>
                                    <input type="date" name="ttgl2" required value="<?= $ttgl1 ?>" 
                                        class="w-full pl-12 pr-4 py-3.5 bg-white border border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none text-gray-700 font-bold shadow-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row justify-end items-center gap-4 pt-6 border-t border-gray-100">
                        <a href="?page=realisasi&aksi=kelola&id=<?= $idRealisasi ?>" class="w-full md:w-auto px-8 py-3.5 border-2 border-gray-200 hover:border-gray-300 rounded-2xl text-gray-600 hover:text-gray-800 font-bold bg-white text-center transition-all">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                        <button type="submit" name="simpan" value="Download Excel" class="w-full md:w-auto px-10 py-3.5 rounded-2xl text-white bg-blue-600 hover:bg-blue-700 font-bold shadow-lg shadow-blue-600/30 transform hover:-translate-y-0.5 transition-all flex items-center justify-center">
                            <i class="fas fa-file-excel mr-2 text-lg"></i> Download Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling modern untuk select2 mengikuti input date */
    .modern-select2-container .select2-selection--single {
        height: 54px !important;
        padding: 12px 16px !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 1rem !important; /* rounded-2xl */
        background-color: white !important;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
        transition: all 0.2s !important;
        display: flex !important;
        align-items: center !important;
    }
    
    .modern-select2-container.select2-container--focus .select2-selection--single {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2) !important;
    }

    .modern-select2-container .select2-selection__arrow {
        height: 52px !important;
        right: 12px !important;
    }

    .modern-select2-container .select2-selection__rendered {
        color: #374151 !important;
        font-weight: 700 !important;
        padding-left: 0 !important;
    }

    .modern-select2-container .select2-search__field {
        border-radius: 8px !important;
        padding: 8px 12px !important;
        border: 1px solid #e5e7eb !important;
        outline: none !important;
    }
    
    .modern-select2-container .select2-search__field:focus {
        border-color: #3b82f6 !important;
    }

    .modern-select2-container .select2-results__option {
        padding: 10px 16px !important;
        font-weight: 500 !important;
    }

    .modern-select2-container .select2-results__option--highlighted[aria-selected] {
        background-color: #eff6ff !important;
        color: #1d4ed8 !important;
        font-weight: 700 !important;
    }
</style>

<script>
$(document).ready(function() {
    $('.select2-slip').select2({
        width: '100%',
        dropdownAutoWidth: true,
        containerCssClass: 'modern-select2-container',
        dropdownCssClass: 'modern-select2-container'
    });
});
</script>

<?php
$ttgl11 = $_POST['ttgl1'] ?? '';
$ttgl22 = $_POST['ttgl2'] ?? '';
$id_karyawan_post = $_POST['id_karyawan_post'] ?? '';
$simpan = $_POST['simpan'] ?? '';

if($simpan) {
?>
    <script type="text/javascript">
        window.location.href="slip.php?id=<?= $id_karyawan_post ?>&ttgl1=<?= $ttgl11 ?>&ttgl2=<?= $ttgl22 ?>";
    </script>
<?php
}
?>