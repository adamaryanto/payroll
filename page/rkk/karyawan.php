<?php
// 1. Ambil ID RKK dari URL (GET) atau dari hidden field (POST)
$idrkk = !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : (!empty($_POST['id_rkk_hidden']) ? intval($_POST['id_rkk_hidden']) : 0);

// 2. Logika Simpan
if (isset($_POST['simpan_karyawan'])) {
    $idkaryawan = $_POST['id_karyawan'];
    $id_dept    = $_POST['id_departmen'];
    $id_sub     = $_POST['id_sub_department'];
    $id_jadwal  = $_POST['id_jadwal']; // Ini adalah ID dari tabel jadwal
    $upah       = $_POST['upah_manual'];

    if (!empty($idkaryawan) && !empty($id_jadwal) && $idrkk > 0) {
        // Cek duplikat
        $cek = $koneksi->query("SELECT id_karyawan FROM tb_rkk_detail WHERE id_rkk = '$idrkk' AND id_karyawan = '$idkaryawan'");

        if ($cek->num_rows == 0) {
            // Force int
            $idrkk_fix = intval($idrkk);
            
            // INSERT simplified: remove jam_masuk, jam_keluar, etc.
            $insert = $koneksi->query("INSERT INTO tb_rkk_detail 
                (id_rkk, id_karyawan, upah, id_departmen, id_sub_department, id_jadwal, status_rkk, 
                 potongan_telat, potongan_istirahat, potongan_lainnya, tgl_updt) 
                VALUES 
                ($idrkk_fix, '$idkaryawan', '$upah', '$id_dept', '$id_sub', '$id_jadwal', 'Hadir', 
                 '0', '0', '0', NOW())");

            if ($insert) {
                echo "<script>
                        alert('Karyawan Berhasil Ditambahkan');
                        window.location.href = '?page=rkk&aksi=kelola&id=$idrkk';
                      </script>";
                exit;
            } else {
                die("Error simpan: " . $koneksi->error);
            }
        } else {
            echo "<script>alert('Karyawan ini sudah ada!');</script>";
        }
    } else {
        echo "<script>alert('Lengkapi data karyawan dan pilih shift!');</script>";
    }
}

// Ambil data master
$list_dept   = $koneksi->query("SELECT * FROM ms_departmen ORDER BY nama_departmen ASC");
$list_sub    = $koneksi->query("SELECT * FROM ms_sub_department ORDER BY nama_sub_department ASC");
$list_jadwal = $koneksi->query("SELECT * FROM tb_jadwal ORDER BY id_jadwal ASC");

// Ambil upah global terbaru
$q_upah = $koneksi->query("SELECT * FROM ms_upah ORDER BY id_upah DESC LIMIT 1");
$global_upah = $q_upah->fetch_assoc();
$g_harian   = $global_upah['upah_harian'] ?? 0;
$g_mingguan = $global_upah['upah_mingguan'] ?? 0;
$g_bulanan  = $global_upah['upah_bulanan'] ?? 0;
?>

<style>
    .custom-card { border-radius: 12px; border: none; box-shadow: 0 8px 20px rgba(0,0,0,0.08); background: #fff; margin-bottom: 30px; }
    .custom-header { background: linear-gradient(45deg, #5F9EA0, #4d8284); color: white; padding: 15px 20px; border-radius: 12px 12px 0 0; }
    .form-section { padding: 25px; }
    .label-text { font-weight: bold; color: #555; margin-bottom: 8px; display: block; }
    .btn-submit { background-color: #5F9EA0; border: none; color: white; padding: 10px 25px; border-radius: 8px; font-weight: bold; width: 100%; }
</style>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel custom-card">
            <div class="panel-heading custom-header">
                <h3 style="margin:0; font-size: 18px;"><i class="fa fa-user-plus"></i> Tambah Karyawan ke RKK</h3>
            </div>
            <form method="POST">
                <input type="hidden" name="id_rkk_hidden" value="<?= $idrkk; ?>">
                <div class="panel-body form-section">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="label-text">Pilih No. Absen / Nama Karyawan</label>
                            <select name="id_karyawan" id="search_karyawan" class="form-control" required>
                                <option value="">- Pilih Karyawan -</option>
                                <?php
                                $master = $koneksi->query("SELECT * FROM ms_karyawan WHERE status_karyawan = 'Aktif' ORDER BY nama_karyawan ASC");
                                while ($row = $master->fetch_assoc()) {
                                    $dept_karyawan = isset($row['id_departmen']) ? $row['id_departmen'] : '';
                                    $sub_karyawan = isset($row['id_sub_department']) ? $row['id_sub_department'] : '';
                                    
                                    echo "<option value='" . $row['id_karyawan'] . "' 
                                            data-jk='" . $row['jenis_kelamin'] . "' 
                                            data-tgl='" . $row['tgl_aktif'] . "'
                                            data-golongan='" . $row['golongan'] . "'
                                            data-dept='" . $dept_karyawan . "'
                                            data-sub='" . $sub_karyawan . "'>" . $row['no_absen'] . " | " . $row['nama_karyawan'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label class="label-text">Jenis Kelamin</label>
                            <input type="text" id="jk" class="form-control" style="background:#f9f9f9" readonly>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="label-text">Tanggal Aktif</label>
                            <input type="text" id="tgl_aktif" class="form-control" style="background:#f9f9f9" readonly>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="label-text">Upah </label>
                            <input type="number" name="upah_manual" id="upah_manual" class="form-control" placeholder="Input Upah..." required>
                        </div>

                        <div class="col-md-12"><hr></div>

                        <div class="form-group col-md-4">
                            <label class="label-text">Pilih Shift Kerja</label>
                            <select name="id_jadwal" class="form-control" required>
                                <option value="">- Pilih Shift -</option>
                                <?php 
                                $list_jadwal->data_seek(0);
                                while($j = $list_jadwal->fetch_assoc()) { ?>
                                    <option value="<?= $j['id_jadwal']; ?>"><?= $j['keterangan']; ?> (<?= $j['jam_masuk']; ?> - <?= $j['jam_keluar']; ?>)</option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label class="label-text">Bagian (Departemen)</label>
                            <select name="id_departmen" id="id_departmen" class="form-control" required>
                                <option value="">- Pilih Departemen -</option>
                                <?php $list_dept->data_seek(0); while ($d = $list_dept->fetch_assoc()) { ?>
                                    <option value="<?= $d['id_departmen']; ?>"><?= $d['nama_departmen']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="label-text">Sub Bagian</label>
                            <select name="id_sub_department" id="id_sub_department" class="form-control" required>
                                <option value="">- Pilih Sub Bagian -</option>
                                <?php $list_sub->data_seek(0); while ($s = $list_sub->fetch_assoc()) { ?>
                                    <option value="<?= $s['id_sub_department']; ?>"><?= $s['nama_sub_department']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 20px;">
                        <div class="col-md-6">
                            <a href="?page=rkk&aksi=kelola&id=<?= $idrkk; ?>" class="btn btn-default btn-block">Batal</a>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" name="simpan_karyawan" class="btn btn-submit">Simpan ke Daftar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Variabel upah global dari PHP
    const globalRates = {
        harian: <?= $g_harian ?>,
        mingguan: <?= $g_mingguan ?>,
        bulanan: <?= $g_bulanan ?>
    };

    document.getElementById('search_karyawan').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        
        if(this.value === "") {
            document.getElementById('jk').value = "";
            document.getElementById('tgl_aktif').value = "";
            document.getElementById('upah_manual').value = "";
            document.getElementById('id_departmen').value = "";
            document.getElementById('id_sub_department').value = "";
            return;
        }

        // Ambil data-golongan dari option
        var gol = selectedOption.getAttribute('data-golongan') || "";
        var finalWage = 0;

        // Tentukan upah berdasarkan golongan karyawan
        if (gol.toLowerCase().includes("harian") || gol === "1") {
            finalWage = globalRates.harian;
        } else if (gol.toLowerCase().includes("mingguan")) {
            finalWage = globalRates.mingguan;
        } else if (gol.toLowerCase().includes("bulanan") || gol === "3") {
            finalWage = globalRates.bulanan;
        }

        document.getElementById('jk').value = selectedOption.getAttribute('data-jk');
        document.getElementById('tgl_aktif').value = selectedOption.getAttribute('data-tgl');
        document.getElementById('upah_manual').value = finalWage;
        document.getElementById('id_departmen').value = selectedOption.getAttribute('data-dept');
        document.getElementById('id_sub_department').value = selectedOption.getAttribute('data-sub');
    });
</script>
