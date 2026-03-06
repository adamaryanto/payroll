<?php
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);

    // Query Detail Realisasi
    $queryDetail = "SELECT A.*, B.keterangan as keterangan_realisasi, B.tgl_realisasi, B.detail_realisasi, B.jam_kerja, 
                           C.keterangan as shift, BB.no_absen, BC.nama_sub_department, BB.nama_karyawan, 
                           BD.nama_departmen, BB.jenis_kelamin
                    FROM tb_realisasi_detail A 
                    LEFT JOIN tb_realisasi B ON A.id_realisasi = B.id_realisasi
                    LEFT JOIN tb_jadwal C ON A.id_jadwal = C.id_jadwal
                    LEFT JOIN ms_karyawan BB ON A.id_karyawan = BB.id_karyawan
                    LEFT JOIN tb_rkk_detail RD ON A.id_rkk_detail = RD.id_rkk_detail
                    LEFT JOIN ms_departmen BD ON RD.id_departmen = BD.id_departmen
                    LEFT JOIN ms_sub_department BC ON RD.id_sub_department = BC.id_sub_department
                    WHERE A.id_realisasi_detail = '$id'";
    
    $tampildetail = $koneksi->query($queryDetail);
    $datadetail = $tampildetail->fetch_assoc();

    // Mapping Data Detail
    $dataidrealisasi = $datadetail['id_realisasi'];
    $datatglrealisasi = $datadetail['tgl_realisasi'];
    $dataidrkkk = $datadetail['id_rkk_detail'];
    $status_realisasi_detail = $datadetail['status_realisasi_detail'];
    $datanoabsen = $datadetail['no_absen'];

    // Query Detail RKK
    $queryRKK = "SELECT A.*, B.keterangan as keterangan_rkk, B.tgl_rkk, C.keterangan as shift_rkk
                 FROM tb_rkk_detail A 
                 LEFT JOIN tb_rkk B ON A.id_rkk = B.id_rkk
                 LEFT JOIN tb_jadwal C ON A.id_jadwal = C.id_jadwal
                 WHERE A.id_rkk_detail = '$dataidrkkk'";
    
    $tampildetailrkk = $koneksi->query($queryRKK);
    $datadetailrkk = $tampildetailrkk->fetch_assoc();

    // Query Absensi dari Log Mesin (tb_record)
    $uid = $datanoabsen;
    $ttgl = $datatglrealisasi;
    $queryAbsen = "SELECT 
        (SELECT TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE userid = '$uid' AND tgl = '$ttgl' AND status = 0 ORDER BY detail_waktu ASC LIMIT 1) AS absen_masuk,
        (SELECT TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE userid = '$uid' AND tgl = '$ttgl' AND status = 2 ORDER BY detail_waktu ASC LIMIT 1) AS istirahat_keluar,
        (SELECT TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE userid = '$uid' AND tgl = '$ttgl' AND status = 3 ORDER BY detail_waktu ASC LIMIT 1) AS istirahat_masuk,
        (SELECT TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE userid = '$uid' AND tgl = '$ttgl' AND status = 1 ORDER BY detail_waktu DESC LIMIT 1) AS absen_keluar";
    
    $datadetailabsen = $koneksi->query($queryAbsen)->fetch_assoc();

    // Query Pengaturan Denda Global
    $queryDenda = $koneksi->query("SELECT * FROM tb_denda LIMIT 1");
    $dataDenda = $queryDenda->fetch_assoc();
    $globalDendaMasuk = $dataDenda['denda_masuk'] ?? 0;
    $globalDendaIstirahat = $dataDenda['denda_istirahat'] ?? 0;

    // Logika Perhitungan Potongan Otomatis
    $jamMasukAbsensi = strtotime($datadetailabsen['absen_masuk'] ?? '');
    $jamMasukRKK     = strtotime($datadetailrkk['jam_masuk'] ?? '');
    $hasilpotongantelat = ($jamMasukAbsensi > $jamMasukRKK && $jamMasukAbsensi && $jamMasukRKK) ? $globalDendaMasuk : 0;

    $jamIstirahatMasukAbsensi = strtotime($datadetailabsen['istirahat_masuk'] ?? '');
    $jamIstirahatRKK          = strtotime($datadetailrkk['istirahat_masuk'] ?? '');
    $hasilpotonganistirahat   = ($jamIstirahatMasukAbsensi > $jamIstirahatRKK && $jamIstirahatMasukAbsensi && $jamIstirahatRKK) ? $globalDendaIstirahat : 0;

    // Tentukan data mana yang ditampilkan (Manual vs Auto)
    if($status_realisasi_detail == 0){
        $hasilabsenmasuk = $datadetailabsen['absen_masuk'];
        $hasilabsenkeluar = $datadetailabsen['absen_keluar'];
        $hasilabsenistirahatmasuk = $datadetailabsen['istirahat_masuk'];
        $hasilabsenistirahatkeluar = $datadetailabsen['istirahat_keluar'];
    } else {
        $hasilabsenmasuk = $datadetail['ra_masuk'];
        $hasilabsenkeluar = $datadetail['ra_keluar'];
        $hasilabsenistirahatmasuk = $datadetail['ra_istirahat_masuk'];
        $hasilabsenistirahatkeluar = $datadetail['ra_istirahat_keluar'];
        $hasilpotongantelat = $datadetail['r_potongan_telat'];
        $hasilpotonganistirahat = $datadetail['r_potongan_istirahat'];
    }
}
?>

<style>
    .section-divider { border-left: 4px solid #5F9EA0; padding-left: 15px; margin: 20px 0; color: #5F9EA0; font-weight: bold; }
    .card-custom { border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; background: #fff; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    label { font-size: 0.85rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; }
    @media (max-width: 768px) { .col-md-2, .col-md-3, .col-md-4 { margin-bottom: 15px; } }
</style>

<div class="row">
    <div class="col-md-12">
        <form method="POST" enctype="multipart/form-data">
            <div class="card-custom" style="border-top: 5px solid #5F9EA0;">
                <h3 style="margin-top:0;">Detail Realisasi Upah</h3>
                <hr>

                <div class="section-divider">RENCANA KERJA (RKK)</div>
                <div class="row">
                    <div class="form-group col-md-3">
                        <label>Tanggal RKK</label>
                        <input type="text" readonly value="<?= $datadetailrkk['tgl_rkk'] ?>" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Keterangan RKK</label>
                        <input type="text" readonly value="<?= $datadetailrkk['keterangan_rkk'] ?>" class="form-control">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Shift RKK</label>
                        <input type="text" readonly value="<?= $datadetailrkk['shift_rkk'] ?>" class="form-control">
                    </div>
                </div>

                <div class="section-divider">DATA KARYAWAN</div>
                <div class="row">
                    <div class="form-group col-md-2">
                        <label>No. Absen</label>
                        <input type="text" readonly value="<?= $datadetail['no_absen'] ?>" class="form-control">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Nama Karyawan</label>
                        <input type="text" readonly value="<?= $datadetail['nama_karyawan'] ?>" class="form-control">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Bagian</label>
                        <input type="text" readonly value="<?= $datadetail['nama_departmen'] ?>" class="form-control">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Sub Bagian</label>
                        <input type="text" readonly value="<?= $datadetail['nama_sub_department'] ?>" class="form-control">
                    </div>
                </div>

                <div class="section-divider text-danger">INPUT REALISASI ABSENSI & UPAH</div>
                <div class="row">
                    <div class="form-group col-md-3">
                        <label>Shift Realisasi</label>
                        <select class="form-control" name="tshift" required>
                            <option value="<?= $datadetail['id_jadwal'] ?>"><?= $datadetail['shift'] ?></option>
                            <?php 
                            $sqlJadwal = $koneksi->query("SELECT * FROM tb_jadwal");
                            while ($j = $sqlJadwal->fetch_assoc()) {
                                echo "<option value='".$j['id_jadwal']."'>".$j['keterangan']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label>Absen Masuk</label>
                        <input type="time" name="tjammasuk" value="<?= $hasilabsenmasuk ?>" class="form-control" readonly>
                    </div>
                    <div class="form-group col-md-2">
                        <label>Absen Keluar</label>
                        <input type="time" name="tjamkeluar" value="<?= $hasilabsenkeluar ?>" class="form-control" readonly>
                    </div>
                    <div class="form-group col-md-2">
                        <label>Istirahat Masuk</label>
                        <input type="time" name="tistirahatmasuk" value="<?= $hasilabsenistirahatmasuk ?>" class="form-control" readonly>
                    </div>
                    <div class="form-group col-md-2">
                        <label>Istirahat Keluar</label>
                        <input type="time" name="tistirahatkeluar" value="<?= $hasilabsenistirahatkeluar ?>" class="form-control" readonly>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="form-group col-md-3">
                        <label>Upah (Pokok)</label>
                        <input type="number" name="tupah" value="<?= $datadetail['r_upah'] ?>" class="form-control" required>
                    </div>
                    <div class="form-group col-md-2">
                        <label>Pot. Telat</label>
                        <input type="number" name="tpottelat" value="<?= $globalDendaMasuk ?>" class="form-control" readonly>
                    </div>
                    <div class="form-group col-md-2">
                        <label>Pot. Istirahat</label>
                        <input type="number" name="tpotistirahat" value="<?= $globalDendaIstirahat ?>" class="form-control" readonly>
                    </div>
                    <div class="form-group col-md-2">
                        <label>Pot. Lainnya</label>
                        <input type="number" name="tpotlainnya" value="<?= $datadetail['r_potongan_lainnya'] ?>" class="form-control" required>
                    </div>
                    <div class="form-group col-md-2">
                        <label>Lembur</label>
                        <input type="number" name="tlembur" value="<?= $datadetail['lembur'] ?>" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-12">
                        <label>Hasil Kerja / Keterangan Detail</label>
                        <textarea name="thasilkerja" class="form-control" rows="3"><?= $datadetail['hasil_kerja'] ?></textarea>
                    </div>
                </div>

                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <a href="?page=realisasi&aksi=kelola&id=<?= $dataidrealisasi ?>" class="btn btn-warning">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" name="simpan" class="btn btn-primary">
                            <i class="fa fa-save"></i> Simpan Data Realisasi
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
if (isset($_POST['simpan'])) {
    $now = date("Y-m-d H:i:s");
    // Ambil data POST dan bersihkan
    $tupah = $_POST['tupah'];
    $tshift = $_POST['tshift'];
    $tpottelat = $_POST['tpottelat'];
    $tpotistirahat = $_POST['tpotistirahat'];
    $tpotlainnya = $_POST['tpotlainnya'];
    $tjammasuk = $_POST['tjammasuk'];
    $tjamkeluar = $_POST['tjamkeluar'];
    $tistirahatmasuk = $_POST['tistirahatmasuk'];
    $tistirahatkeluar = $_POST['tistirahatkeluar'];
    $hasilkerjanya = mysqli_real_escape_string($koneksi, $_POST['thasilkerja']);
    $tlembur = $_POST['tlembur'];

    $update = $koneksi->query("UPDATE tb_realisasi_detail SET 
        r_upah = '$tupah', 
        id_jadwal = '$tshift',
        r_potongan_telat = '$tpottelat',
        r_potongan_istirahat = '$tpotistirahat',
        r_potongan_lainnya = '$tpotlainnya',
        ra_masuk = '$tjammasuk',
        ra_keluar = '$tjamkeluar',
        ra_istirahat_masuk = '$tistirahatmasuk',
        ra_istirahat_keluar = '$tistirahatkeluar',
        r_update = '$now',
        status_realisasi_detail = 1,
        hasil_kerja = '$hasilkerjanya',
        lembur = '$tlembur' 
        WHERE id_realisasi_detail = '$id'");

    if ($update) {
        echo "<script>alert('Data Berhasil Disimpan'); window.location.href='?page=realisasi&aksi=kelola&id=$dataidrealisasi';</script>";
    }
}
?>