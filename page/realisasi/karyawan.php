<?php
// 1. Ambil ID Realisasi
$idrealisasi = !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : (!empty($_POST['id_real_hidden']) ? intval($_POST['id_real_hidden']) : 0);

if ($idrealisasi <= 0) {
    echo "<script>alert('ID Realisasi tidak valid!'); window.location.href='?page=realisasi';</script>";
    exit;
}

// Check status realisasi
$cek_real = $koneksi->query("SELECT status_realisasi, id_rkk, tgl_realisasi FROM tb_realisasi WHERE id_realisasi = '$idrealisasi'");
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
    $id_os       = $_POST['id_os_dhk'] ?? 0;
    $id_gol      = $_POST['id_golongan'] ?? 0;
    $id_jadwal   = $_POST['id_jadwal'];
    $ra_masuk    = $_POST['ra_masuk'] ?: '00:00:00';
    $ra_keluar   = $_POST['ra_keluar'] ?: '00:00:00';

    // Handle Tags untuk Dept & Sub
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
        $tgl_ref = $data_real['tgl_realisasi'] ?? date('Y-m-d');

        // Ambil data jadwal untuk detail shift
        $q_j_detail = $koneksi->query("SELECT * FROM tb_jadwal WHERE id_jadwal = '$id_jadwal'");
        $d_j_detail = $q_j_detail->fetch_assoc();
        $s_masuk    = $d_j_detail['jam_masuk'] ?? '00:00:00';
        $s_keluar   = $d_j_detail['jam_keluar'] ?? '00:00:00';
        $ist_masuk  = $d_j_detail['istirahat_masuk'] ?? '00:00:00';
        $ist_keluar = $d_j_detail['istirahat_keluar'] ?? '00:00:00';

        // Tambahkan OS_DHK dan golongan ke query
        $query = "INSERT INTO tb_realisasi_detail 
            (id_realisasi, id_rkk, id_rkk_detail, id_karyawan, nama_karyawan_manual, 
             id_departmen, id_sub_department, r_upah, id_jadwal, status_realisasi_detail,
             r_potongan_telat, r_potongan_istirahat_awal, r_potongan_istirahat_telat, 
             r_potongan_lainnya, r_jam_masuk, r_jam_keluar, r_istirahat_masuk, 
             r_istirahat_keluar, r_status, r_update, ra_masuk, ra_keluar, 
             ra_istirahat_masuk, ra_istirahat_keluar, hasil_kerja, lembur, 
             tgl_realisasi_detail, r_potongan_pulang, r_potongan_tidak_lengkap,
             id_os_dhk, id_golongan) 
            VALUES 
            ($id_real_fix, '$idrkk_ref', 0, 0, '$nama_manual', 
             '$id_dept', '$id_sub', '$upah', '$id_jadwal', 1,
             0, 0, 0, 0, '$s_masuk', '$s_keluar', '$ist_masuk', '$ist_keluar', 'Hadir', 'manual', 
             '$ra_masuk', '$ra_keluar', '00:00:00', '00:00:00', '-', 0, 
             '$tgl_ref', 0, 0, '$id_os', '$id_gol')";

        if ($koneksi->query($query)) {
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Karyawan Manual Berhasil Ditambahkan',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = '?page=realisasi&aksi=karyawan&id=$id_real_fix';
                });
            </script>";
            exit;
        } else {
            echo "Error: " . $koneksi->error;
        }
    }
}

// 3. Ambil data master
$list_dept   = $koneksi->query("SELECT * FROM ms_departmen ORDER BY nama_departmen ASC");
$list_sub    = $koneksi->query("SELECT * FROM ms_sub_department ORDER BY nama_sub_department ASC");
$list_jadwal = $koneksi->query("SELECT * FROM tb_jadwal ORDER BY id_jadwal ASC");
$list_os     = $koneksi->query("SELECT * FROM ms_os_dhk ORDER BY OS_DHK ASC");
$list_gol    = $koneksi->query("SELECT * FROM ms_golongan ORDER BY golongan ASC");
$q_upah      = $koneksi->query("SELECT upah_harian FROM ms_upah ORDER BY id_upah DESC LIMIT 1");
$global_upah = $q_upah->fetch_assoc();
$default_upah = $global_upah['upah_harian'] ?? 0;
?>

<div class="container-fluid px-3 mt-8 mb-10">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-6 md:px-10">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-white/10 rounded-2xl backdrop-blur-sm">
                        <i class="fas fa-user-plus text-white text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-white m-0">Tambah Karyawan Manual</h2>
                        <p class="text-blue-100 text-sm mt-1">Lengkapi data untuk menghindari error tampilan</p>
                    </div>
                </div>
            </div>

            <form method="POST">
                <input type="hidden" name="id_real_hidden" value="<?= $idrealisasi; ?>">
                <input type="hidden" name="ra_masuk" value="">
                <input type="hidden" name="ra_keluar" value="">
                <div class="p-6 md:p-10">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap Karyawan <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_karyawan_manual" required autofocus class="block w-full px-4 py-3 border border-gray-300 rounded-2xl outline-none" placeholder="Nama Karyawan..." />
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Upah Harian <span class="text-rose-500">*</span></label>
                            <input type="text" name="upah_manual" value="<?= number_format($default_upah, 0, ',', '.') ?>" class="block w-full px-4 py-3 border border-gray-300 rounded-2xl outline-none font-bold text-blue-700" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Shift Kerja</label>
                            <select name="id_jadwal" required class="block w-full px-4 py-3 border border-gray-300 rounded-2xl outline-none">
                                <option value="">- Pilih Shift -</option>
                                <?php while ($j = $list_jadwal->fetch_assoc()) : ?>
                                    <option value="<?= $j['id_jadwal']; ?>"><?= $j['keterangan'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Departemen</label>
                            <select name="id_departmen" required class="block w-full px-4 py-3 border border-gray-300 rounded-2xl outline-none">
                                <option value="">- Pilih Dept -</option>
                                <?php while ($d = $list_dept->fetch_assoc()) : ?>
                                    <option value="<?= $d['id_departmen']; ?>"><?= $d['nama_departmen']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Sub Bagian</label>
                            <select name="id_sub_department" required class="block w-full px-4 py-3 border border-gray-300 rounded-2xl outline-none">
                                <option value="">- Pilih Sub -</option>
                                <?php while ($s = $list_sub->fetch_assoc()) : ?>
                                    <option value="<?= $s['id_sub_department']; ?>"><?= $s['nama_sub_department']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Penyedia OS/DHK <span class="text-rose-500">*</span></label>
                            <select name="id_os_dhk" required class="block w-full px-4 py-3 border border-gray-300 rounded-2xl outline-none">
                                <option value="">- Pilih OS/DHK -</option>
                                <?php while ($o = $list_os->fetch_assoc()) : ?>
                                    <option value="<?= $o['id_os_dhk']; ?>"><?= $o['OS_DHK']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Golongan <span class="text-rose-500">*</span></label>
                            <select name="id_golongan" required class="block w-full px-4 py-3 border border-gray-300 rounded-2xl outline-none">
                                <option value="">- Pilih Golongan -</option>
                                <?php while ($g = $list_gol->fetch_assoc()) : ?>
                                    <option value="<?= $g['id_golongan']; ?>"><?= $g['golongan']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-8 border-t border-gray-100">
                        <a href="?page=realisasi&aksi=kelola&id=<?= $idrealisasi; ?>" class="px-8 py-3 border border-gray-200 rounded-2xl text-gray-600 bg-gray-50 no-underline">Batal</a>
                        <button type="submit" name="simpan_karyawan" class="px-10 py-3 rounded-2xl text-white bg-blue-600 font-bold shadow-lg shadow-blue-200">Simpan</button>
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
        $('#upah_manual').on('keyup', function() {
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