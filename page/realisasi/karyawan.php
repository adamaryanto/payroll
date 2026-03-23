<?php
// 1. Ambil ID Realisasi
$idrealisasi = !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : (!empty($_POST['id_real_hidden']) ? intval($_POST['id_real_hidden']) : 0);

if ($idrealisasi <= 0) {
    echo "<script>alert('ID Realisasi tidak valid!'); window.location.href='?page=realisasi';</script>";
    exit;
}

// Check status realisasi
$cek_real = $koneksi->query("SELECT status_realisasi, id_rkk FROM tb_realisasi WHERE id_realisasi = '$idrealisasi'");
$data_real = $cek_real->fetch_assoc();
if ($data_real['status_realisasi'] >= 2) {
    echo "<script>alert('Tidak bisa menambah karyawan karena status Realisasi sudah Selesai/Approved!'); window.location.href='?page=realisasi&aksi=kelola&id=$idrealisasi';</script>";
    exit;
}
$idrkk_ref = $data_real['id_rkk'];

// 2. Logika Simpan
if (isset($_POST['simpan_karyawan'])) {
    $id_real_fix = intval($_POST['id_real_hidden']);
    $nama_manual = $koneksi->real_escape_string($_POST['nama_karyawan_manual']);
    $upah        = str_replace('.', '', $_POST['upah_manual']);
    $id_dept     = $_POST['id_departmen'];
    $id_sub      = $_POST['id_sub_department'];
    $id_jadwal   = $_POST['id_jadwal'];

    // Handle Tags untuk Dept & Sub (agar bisa input manual jika tidak ada di list)
    if (!empty($id_dept) && !is_numeric($id_dept)) {
        $name_dept = $koneksi->real_escape_string($id_dept);
        $koneksi->query("INSERT INTO ms_departmen (nama_departmen) VALUES ('$name_dept')");
        $id_dept = $koneksi->insert_id;
    }
    if (!empty($id_sub) && !is_numeric($id_sub)) {
        $name_sub = $koneksi->real_escape_string($id_sub);
        $dept_id_val = is_numeric($id_dept) ? $id_dept : 0;
        $koneksi->query("INSERT INTO ms_sub_department (nama_sub_department, id_departmen) VALUES ('$name_sub', '$dept_id_val')");
        $id_sub = $koneksi->insert_id;
    }

    if (!empty($nama_manual) && !empty($id_jadwal) && $id_real_fix > 0) {
        
        // Simpan ke detail
        // status_realisasi_detail = 1 (Manual/Saved)
        $query = "INSERT INTO tb_realisasi_detail 
                  (id_realisasi, id_rkk, id_karyawan, nama_karyawan_manual, r_upah, id_departmen, id_sub_department, id_jadwal, status_realisasi_detail, tgl_updt) 
                  VALUES 
                  ($id_real_fix, '$idrkk_ref', 0, '$nama_manual', '$upah', '$id_dept', '$id_sub', '$id_jadwal', 1, NOW())";

        if ($koneksi->query($query)) {
            echo "<script>alert('Karyawan Manual Berhasil Ditambahkan'); window.location.href = '?page=realisasi&aksi=kelola&id=$id_real_fix';</script>";
            exit;
        } else {
            echo "Error: " . $koneksi->error;
        }
    }
}

// 3. Ambil data master untuk dropdown
$list_dept   = $koneksi->query("SELECT * FROM ms_departmen ORDER BY nama_departmen ASC");
$list_sub    = $koneksi->query("SELECT * FROM ms_sub_department ORDER BY nama_sub_department ASC");
$list_jadwal = $koneksi->query("SELECT * FROM tb_jadwal ORDER BY id_jadwal ASC");

// Default Upah Global
$q_upah = $koneksi->query("SELECT upah_harian FROM ms_upah ORDER BY id_upah DESC LIMIT 1");
$global_upah = $q_upah->fetch_assoc();
$default_upah = $global_upah['upah_harian'] ?? 0;
?>

<!-- UI Form (Tailwind) -->

<div class="container-fluid px-3 mt-8 mb-10">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-6 md:px-10">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-white/10 rounded-2xl backdrop-blur-sm">
                        <i class="fas fa-user-plus text-white text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-white m-0">Tambah Karyawan Manual</h2>
                        <p class="text-blue-100 text-sm mt-1">Input karyawan yang tidak terdaftar di database utama</p>
                    </div>
                </div>
            </div>

            <form method="POST">
                <input type="hidden" name="id_real_hidden" value="<?= $idrealisasi; ?>">
                <div class="p-6 md:p-10">
                    
                    <!-- Nama & Upah -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap Karyawan <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_karyawan_manual" required autofocus
                                placeholder="Masukkan Nama Lengkap..."
                                class="block w-full px-4 py-3.5 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all font-medium text-gray-900 shadow-sm" />
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Upah Harian <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-400 font-bold group-focus-within:text-blue-600 transition-colors">Rp</span>
                                </div>
                                <input type="text" name="upah_manual" id="upah_manual" required 
                                    value="<?= number_format($default_upah, 0, ',', '.') ?>"
                                    class="block w-full pl-12 pr-4 py-3.5 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all font-black text-blue-700 shadow-sm" />
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-dashed border-gray-200 my-8"></div>

                    <!-- Penempatan -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Shift Kerja <span class="text-rose-500">*</span></label>
                            <select name="id_jadwal" required class="select2-karyawan block w-full px-4 py-3 border border-gray-300 rounded-2xl outline-none shadow-sm">
                                <option value="">- Pilih Shift -</option>
                                <?php while ($j = $list_jadwal->fetch_assoc()) : ?>
                                    <option value="<?= $j['id_jadwal']; ?>"><?= $j['keterangan'] ?> (<?= $j['jam_masuk'] ?> - <?= $j['jam_keluar'] ?>)</option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Departemen <span class="text-rose-500">*</span></label>
                            <select name="id_departmen" required data-tags="true" class="select2-karyawan block w-full px-4 py-3 border border-gray-300 rounded-2xl outline-none shadow-sm">
                                <option value="">- Pilih Dept -</option>
                                <?php while ($d = $list_dept->fetch_assoc()) : ?>
                                    <option value="<?= $d['id_departmen']; ?>"><?= $d['nama_departmen']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Sub Bagian <span class="text-rose-500">*</span></label>
                            <select name="id_sub_department" required data-tags="true" class="select2-karyawan block w-full px-4 py-3 border border-gray-300 rounded-2xl outline-none shadow-sm">
                                <option value="">- Pilih Sub -</option>
                                <?php while ($s = $list_sub->fetch_assoc()) : ?>
                                    <option value="<?= $s['id_sub_department']; ?>"><?= $s['nama_sub_department']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Footer Action -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-8 border-t border-gray-100">
                        <div class="text-xs text-gray-500 italic order-2 sm:order-1 flex items-center">
                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                            Karyawan ini akan langsung ditambahkan ke daftar realisasi.
                        </div>
                        <div class="flex gap-3 w-full sm:w-auto order-1 sm:order-2">
                            <a href="?page=realisasi&aksi=kelola&id=<?= $idrealisasi; ?>" 
                                class="flex-1 sm:flex-none justify-center items-center px-8 py-3.5 border border-gray-200 text-sm font-bold rounded-2xl text-gray-600 bg-gray-50 hover:bg-gray-100 hover:text-gray-800 transition-all text-center no-underline">
                                Batal
                            </a>
                            <button type="submit" name="simpan_karyawan" value="1" 
                                class="flex-1 sm:flex-none inline-flex items-center justify-center px-10 py-3.5 border border-transparent text-sm font-bold rounded-2xl shadow-lg shadow-blue-200 text-white bg-blue-600 hover:bg-blue-700 active:scale-95 transition-all transform">
                                <i class="fas fa-save mr-2"></i> Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.select2-karyawan').select2({
        width: '100%',
        dropdownAutoWidth: true,
        containerCssClass: 'modern-select2'
    });

    // Format Rupiah Input
    $('#upah_manual').on('keyup', function(){
        let value = $(this).val().replace(/[^0-9]/g, '');
        if (value !== '') {
            $(this).val(new Intl.NumberFormat('id-ID').format(value));
        } else {
            $(this).val('0');
        }
    });
});
</script>

<style>
/* Modern Select2 Styling */
.modern-select2.select2-container--default .select2-selection--single {
    height: 52px !important;
    padding: 10px 16px !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 16px !important;
    transition: all 0.2s !important;
}
.modern-select2.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: normal !important;
    font-size: 15px !important;
    font-weight: 500 !important;
    color: #1e293b !important;
}
.modern-select2.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 50px !important;
}
.select2-dropdown {
    border: 1px solid #e2e8f0 !important;
    border-radius: 16px !important;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
    overflow: hidden !important;
}
.select2-results__option {
    padding: 10px 16px !important;
    font-size: 14px !important;
}
.select2-results__option--highlighted {
    background-color: #2563eb !important;
}
</style>