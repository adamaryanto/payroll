<?php
// 1. Ambil ID RKK dari URL
$idrkk = isset($_GET['id']) ? $_GET['id'] : "";

// 2. Logika Simpan
if (isset($_POST['simpan_karyawan'])) {
    $idkaryawan = $_POST['id_karyawan'];
    $id_dept    = $_POST['id_departmen'];
    $id_sub     = $_POST['id_sub_department'];
    $id_jadwal  = $_POST['id_jadwal']; // Ini adalah ID dari tabel jadwal
    $upah       = $_POST['upah_manual'];

    if (!empty($idkaryawan) && !empty($id_jadwal)) {
        // Ambil data detail jam dari tabel jadwal
        $ambil_jadwal = $koneksi->query("SELECT * FROM tb_jadwal WHERE id_jadwal = '$id_jadwal'");
        $dj = $ambil_jadwal->fetch_assoc();
        
        $nama_shift = $dj['shift']; // Inilah variabel 'shift' yang akan disimpan ke tb_rkk_detail
        $j_masuk    = $dj['jam_masuk'];
        $j_keluar   = $dj['jam_keluar'];
        $i_masuk    = $dj['istirahat_masuk'];
        $i_keluar   = $dj['istirahat_keluar'];

        // Cek duplikat
        $cek = $koneksi->query("SELECT id_karyawan FROM tb_rkk_detail WHERE id_rkk = '$idrkk' AND id_karyawan = '$idkaryawan'");

        if ($cek->num_rows == 0) {
            // INSERT dengan menyertakan tgl_updt menggunakan NOW() agar tidak error
            $insert = $koneksi->query("INSERT INTO tb_rkk_detail 
                (id_rkk, id_karyawan, upah, id_departmen, id_sub_department, id_jadwal, shift, status_rkk, 
                 jam_masuk, jam_keluar, istirahat_masuk, istirahat_keluar, 
                 potongan_telat, potongan_istirahat, potongan_lainnya, tgl_updt) 
                VALUES 
                ('$idrkk', '$idkaryawan', '$upah', '$id_dept', '$id_sub', '$id_jadwal', '$nama_shift', 'Hadir', 
                 '$j_masuk', '$j_keluar', '$i_masuk', '$i_keluar', 
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
                <div class="panel-body form-section">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="label-text">Cari No. Absen / Nama Karyawan</label>
                            <input list="karyawan_list" id="search_karyawan" class="form-control" placeholder="Ketik NIK atau Nama..." autocomplete="off">
                            <datalist id="karyawan_list">
                                <?php
                                $master = $koneksi->query("SELECT * FROM ms_karyawan WHERE status_karyawan = 'Aktif'");
                                while ($row = $master->fetch_assoc()) {
                                    // Simpan upah di attribute data-upah
                                    echo "<option value='" . $row['no_absen'] . " | " . $row['nama_karyawan'] . "' data-id='" . $row['id_karyawan'] . "' data-jk='" . $row['jenis_kelamin'] . "' data-tgl='" . $row['tgl_aktif'] . "' data-upah='" . $row['upah_harian'] . "'>";
                                }
                                ?>
                            </datalist>
                            <input type="hidden" name="id_karyawan" id="id_karyawan">
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
                            <label class="label-text">Upah Harian (Manual)</label>
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
                            <select name="id_departmen" class="form-control" required>
                                <option value="">- Pilih Departemen -</option>
                                <?php $list_dept->data_seek(0); while ($d = $list_dept->fetch_assoc()) { ?>
                                    <option value="<?= $d['id_departmen']; ?>"><?= $d['nama_departmen']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="label-text">Sub Bagian</label>
                            <select name="id_sub_department" class="form-control" required>
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
    document.getElementById('search_karyawan').addEventListener('input', function() {
        var val = this.value;
        var opts = document.getElementById('karyawan_list').childNodes;
        for (var i = 0; i < opts.length; i++) {
            if (opts[i].value === val) {
                document.getElementById('id_karyawan').value = opts[i].getAttribute('data-id');
                document.getElementById('jk').value = opts[i].getAttribute('data-jk');
                document.getElementById('tgl_aktif').value = opts[i].getAttribute('data-tgl');
                
                // Mengisi value awal upah dari data master, tapi masih bisa kamu ganti manual
                document.getElementById('upah_manual').value = opts[i].getAttribute('data-upah');
                break;
            }
        }
    });
</script>