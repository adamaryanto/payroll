<?php
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);

// Query Detail Realisasi
    $queryDetail = "SELECT A.*, 
                           B.keterangan as keterangan_realisasi, 
                           B.tgl_realisasi, 
                           B.detail_realisasi, 
                           B.jam_kerja, 
                           C.keterangan as shift, 
                           C.jam_masuk as shift_masuk,
                           C.istirahat_masuk as shift_istirahat_masuk,
                           BB.no_absen, 
                           BB.nama_karyawan, 
                           BB.jenis_kelamin,
                           D.nama_departmen, 
                           SD.nama_sub_department,
                           RD.upah as upah_master
                    FROM tb_realisasi_detail A 
                    LEFT JOIN tb_realisasi B ON A.id_realisasi = B.id_realisasi
                    LEFT JOIN tb_jadwal C ON A.id_jadwal = C.id_jadwal
                    LEFT JOIN ms_karyawan BB ON A.id_karyawan = BB.id_karyawan
                    LEFT JOIN tb_rkk_detail RD ON A.id_rkk_detail = RD.id_rkk_detail
                    /* Mengambil data Departemen & Sub-Dept langsung dari master karyawan */
                    LEFT JOIN ms_departmen D ON BB.id_departmen = D.id_departmen
                    LEFT JOIN ms_sub_department SD ON BB.id_sub_department = SD.id_sub_department
                    WHERE A.id_realisasi_detail = '$id'";
    
    $tampildetail = $koneksi->query($queryDetail);
    $datadetail = $tampildetail->fetch_assoc();

    if (!$datadetail) {
        echo "<script>alert('Data Detail Realisasi tidak ditemukan!'); window.location.href='?page=realisasi';</script>";
        exit;
    }

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
    $globalDendaPulang = $dataDenda['denda_pulang'] ?? 0;
    $globalDendaTidakLengkap = $dataDenda['denda_tidak_lengkap'] ?? 0;

    // Tentukan data yang digunakan untuk kalkulasi potongan (Input ra_ atau Log Mesin)
    $jamMasukRealisasi = !empty($datadetail['ra_masuk']) ? $datadetail['ra_masuk'] : ($datadetailabsen['absen_masuk'] ?? '');
    $jamKeluarRealisasi = !empty($datadetail['ra_keluar']) ? $datadetail['ra_keluar'] : ($datadetailabsen['absen_keluar'] ?? '');
    $jamIstirahatKeluarRealisasi = !empty($datadetail['ra_istirahat_keluar']) ? $datadetail['ra_istirahat_keluar'] : ($datadetailabsen['istirahat_keluar'] ?? '');
    $jamIstirahatMasukRealisasi = !empty($datadetail['ra_istirahat_masuk']) ? $datadetail['ra_istirahat_masuk'] : ($datadetailabsen['istirahat_masuk'] ?? '');

    // Logika Perhitungan Potongan Otomatis (Sesuai realisasi kelola)
    $isLate = (!empty($jamMasukRealisasi) && !empty($datadetail['shift_masuk']) && strtotime($jamMasukRealisasi) > strtotime($datadetail['shift_masuk']));
    $hasilpotongantelat = $isLate ? $globalDendaMasuk : 0;

    $isLateBreak = (!empty($jamIstirahatMasukRealisasi) && !empty($datadetail['shift_istirahat_masuk']) && strtotime($jamIstirahatMasukRealisasi) > strtotime($datadetail['shift_istirahat_masuk']));
    $hasilpotonganistirahat = $isLateBreak ? $globalDendaIstirahat : 0;

    // Logic Incomplete Log
    $hasIncompleteMain = (
        (!empty($jamMasukRealisasi) && $jamMasukRealisasi != '00:00:00' && (empty($jamKeluarRealisasi) || $jamKeluarRealisasi == '00:00:00')) ||
        ((empty($jamMasukRealisasi) || $jamMasukRealisasi == '00:00:00') && !empty($jamKeluarRealisasi) && $jamKeluarRealisasi != '00:00:00')
    );
    $hasIncompleteBreak = (
        (!empty($jamIstirahatKeluarRealisasi) && $jamIstirahatKeluarRealisasi != '00:00:00' && (empty($jamIstirahatMasukRealisasi) || $jamIstirahatMasukRealisasi == '00:00:00')) ||
        ((empty($jamIstirahatKeluarRealisasi) || $jamIstirahatKeluarRealisasi == '00:00:00') && !empty($jamIstirahatMasukRealisasi) && $jamIstirahatMasukRealisasi != '00:00:00')
    );
    
    $isEarlyOut = (!empty($jamKeluarRealisasi) && !empty($datadetail['r_jam_keluar']) && strtotime($jamKeluarRealisasi) < strtotime($datadetail['r_jam_keluar']));

    // Incomplete if: One is missing OR (Both missing AND status is NOT 'Tidak Hadir')
    $isTotalMissing = (empty($jamMasukRealisasi) || $jamMasukRealisasi == '00:00:00') && (empty($jamKeluarRealisasi) || $jamKeluarRealisasi == '00:00:00');
    $isIncompleteLog = ($hasIncompleteMain || $hasIncompleteBreak || ($isTotalMissing && $datadetail['status_rkk'] != 'Tidak Hadir'));

    // Logic denda: Prioritaskan data tersimpan jika sudah ada (status detail > 0), 
    // Jika belum ada/masih draft, gunakan kalkulasi otomatis sebagai saran.
    if ($datadetail['status_realisasi_detail'] > 0) {
        $hasilpotonganpulang = $datadetail['r_potongan_pulang'];
        $hasilpotongantidaklengkap = $datadetail['r_potongan_tidak_lengkap'];
    } else {
        $hasilpotonganpulang = $isEarlyOut ? $globalDendaPulang : 0;
        $hasilpotongantidaklengkap = ($hasIncompleteMain || $hasIncompleteBreak) ? $globalDendaTidakLengkap : 0;
    }

    // Logika Upah Pokok: Jika Absen Masuk kosong maka Upah jadi 0
    // Gunakan r_upah jika sudah ada, atau upah_master sebagai fallback
    $upahPokokAsli = ($datadetail['r_upah'] > 0) ? $datadetail['r_upah'] : $datadetail['upah_master'];
    $upahPokokTampil = $upahPokokAsli;
    
    if (empty($jamMasukRealisasi) || $jamMasukRealisasi == '00:00:00') {
        $upahPokokTampil = 0;
    }
}

if (!function_exists('rupiah')) {
    function rupiah($angka) {
        if ($angka === null || $angka === "") $angka = 0;
        return "Rp " . number_format($angka, 0, ',', '.');
    }
}
?>

<style>
    /* Styling Dasar Modern */
    .card-modern {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        padding: 25px;
        margin-bottom: 25px;
        border: none;
    }
    .section-divider {
        display: flex;
        align-items: center;
        color: #2563eb;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin: 30px 0 15px 0;
        font-size: 0.9rem;
    }
    .section-divider::after {
        content: "";
        flex: 1;
        height: 1px;
        background: #e5e7eb;
        margin-left: 15px;
    }
    /* Input Styling */
    .form-control {
        border-radius: 8px;
        border: 1px solid #d1d5db;
        padding: 10px 15px;
        height: auto;
        transition: all 0.3s ease;
    }
    .form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
    }
    label { font-size: 0.75rem; color: #6b7280; margin-bottom: 8px; font-weight: 600; }
    
    /* Tombol Modern */
    .btn-custom { padding: 10px 20px; border-radius: 8px; font-weight: 600; transition: all 0.2s; }
    .btn-primary-custom { background-color: #2563eb; color: white; border: none; }
    .btn-primary-custom:hover { background-color: #1d4ed8; color: white; transform: translateY(-1px); }
    .btn-warning-custom { background-color: #f39c12; color: white; border: none; }
    .btn-warning-custom:hover { background-color: #d68910; color: white; }
</style>

<div class="row">
    <div class="col-md-12">
        <form method="POST" enctype="multipart/form-data">
            <div class="card-modern">
                <h3 class="text-blue-600 font-bold"><i class="fas fa-clipboard-check mr-2"></i> Detail Realisasi Upah</h3>

                <div class="section-divider">Rencana Kerja (RKK)</div>
                <div class="row">
                    <div class="form-group col-md-3">
                        <label>TANGGAL RKK</label>
                        <input type="text" readonly value="<?= $datadetailrkk['tgl_rkk'] ?>" class="form-control" style="background: #f9fafb;">
                    </div>
                    <div class="form-group col-md-6">
                        <label>KETERANGAN RKK</label>
                        <input type="text" readonly value="<?= $datadetailrkk['keterangan_rkk'] ?>" class="form-control" style="background: #f9fafb;">
                    </div>
                    <div class="form-group col-md-3">
                        <label>SHIFT RKK</label>
                        <input type="text" readonly value="<?= $datadetailrkk['shift_rkk'] ?>" class="form-control" style="background: #f9fafb;">
                    </div>
                </div>

                <div class="section-divider">Data Karyawan</div>
                <div class="row">
                    <div class="form-group col-md-2">
                        <label>NO. ABSEN</label>
                        <input type="text" readonly value="<?= $datadetail['no_absen'] ?>" class="form-control" style="background: #f9fafb;">
                    </div>
                    <div class="form-group col-md-4">
                        <label>NAMA KARYAWAN</label>
                        <input type="text" readonly value="<?= $datadetail['nama_karyawan'] ?>" class="form-control" style="background: #f9fafb;">
                    </div>
                    <div class="form-group col-md-3">
                        <label>BAGIAN</label>
                        <input type="text" readonly value="<?= $datadetail['nama_departmen'] ?>" class="form-control" style="background: #f9fafb;">
                    </div>
                    <div class="form-group col-md-3">
                        <label>SUB BAGIAN</label>
                        <input type="text" readonly value="<?= $datadetail['nama_sub_department'] ?>" class="form-control" style="background: #f9fafb;">
                    </div>
                </div>

                <div class="section-divider text-danger">Input Realisasi Absensi & Upah</div>
                <div class="row">
                    <div class="form-group col-md-3">
                        <label>SHIFT REALISASI</label>
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
                    <div class="form-group col-md-2"> <label>ABSEN MASUK</label> <input type="time" name="tjammasuk" value="<?= $datadetail['ra_masuk'] ?>" class="form-control"> </div>
                    <div class="form-group col-md-2"> <label>ABSEN KELUAR</label> <input type="time" name="tjamkeluar" value="<?= $datadetail['ra_keluar'] ?>" class="form-control"> </div>
                    <div class="form-group col-md-2"> <label>ISTIRAHAT MASUK</label> <input type="time" name="tistirahatmasuk" value="<?= $datadetail['ra_istirahat_masuk'] ?>" class="form-control"> </div>
                    <div class="form-group col-md-2"> <label>ISTIRAHAT KELUAR</label> <input type="time" name="tistirahatkeluar" value="<?= $datadetail['ra_istirahat_keluar'] ?>" class="form-control"> </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-2"> <label>UPAH (POKOK)</label> <input type="number" name="tupah" value="<?= $upahPokokTampil ?>" class="form-control" readonly style="background: #f9fafb;"> </div>
                    <div class="form-group col-md-2"> <label>POT. TELAT</label> <input type="number" name="tpottelat" value="<?= $hasilpotongantelat ?>" class="form-control" disabled style="background: #f1f5f9;"> </div>
                    <div class="form-group col-md-2"> <label>POT. ISTIRAHAT</label> <input type="number" name="tpotistirahat" value="<?= $hasilpotonganistirahat ?>" class="form-control" disabled style="background: #f1f5f9;"> </div>
                    <div class="form-group col-md-2"> <label>POT. PULANG AWAL</label> <input type="number" name="tpotpulang" value="<?= $hasilpotonganpulang ?>" class="form-control"> </div>
                    <div class="form-group col-md-2"> <label>POT. LOG GAK LENGKAP</label> <input type="number" name="tpotlog" value="<?= $hasilpotongantidaklengkap ?>" class="form-control"> </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-2"> <label>POT. LAINNYA</label> <input type="number" name="tpotlainnya" value="<?= $datadetail['r_potongan_lainnya'] ?>" class="form-control" required> </div>
                    <div class="form-group col-md-2"> <label>LEMBUR</label> <input type="number" name="tlembur" value="<?= $datadetail['lembur'] ?>" class="form-control" required> </div>
                    <div class="form-group col-md-2"> 
                        <label>TOTAL UPAH AKHIR</label> 
                        <?php $totalAkhir = $upahPokokTampil + $datadetail['lembur'] - $hasilpotongantelat - $hasilpotonganistirahat - $datadetail['r_potongan_lainnya'] - $hasilpotonganpulang - $hasilpotongantidaklengkap; ?>
                        <input type="text" value="<?= rupiah($totalAkhir) ?>" class="form-control font-bold text-blue-600" readonly style="background: #eff6ff;"> 
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-12">
                        <label>HASIL KERJA / KETERANGAN DETAIL</label>
                        <textarea name="thasilkerja" class="form-control" rows="3"><?= $datadetail['hasil_kerja'] ?></textarea>
                    </div>
                </div>

                <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #f3f4f6;">
                    <a href="?page=realisasi&aksi=kelola&id=<?= $dataidrealisasi ?>" class="btn btn-warning-custom btn-custom">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" name="simpan" class="btn btn-primary-custom btn-custom">
                        <i class="fa fa-save"></i> Simpan Data Realisasi
                    </button>
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
    $tpotlainnya = $_POST['tpotlainnya'];
    $tpotpulang = $_POST['tpotpulang'] ?? 0;
    $tpotlog = $_POST['tpotlog'] ?? 0;
    $tjammasuk = $_POST['tjammasuk'];
    $tjamkeluar = $_POST['tjamkeluar'];
    $tistirahatmasuk = $_POST['tistirahatmasuk'];
    $tistirahatkeluar = $_POST['tistirahatkeluar'];
    $hasilkerjanya = mysqli_real_escape_string($koneksi, $_POST['thasilkerja']);
    $tlembur = $_POST['tlembur'];

    // Logika Sinkronisasi: Jika Jam Masuk Kosong, maka Upah = 0
    if (empty($tjammasuk) || $tjammasuk == '00:00:00') {
        $tupah = 0;
    } else {
        // Jika ada jam masuk, kembalikan ke upah master jika sebelumnya 0
        if ($tupah == 0) {
            $tupah = $datadetail['upah_master'];
        }
    }

    $update = $koneksi->query("UPDATE tb_realisasi_detail SET 
        r_upah = '$tupah', 
        id_jadwal = '$tshift',
        r_potongan_lainnya = '$tpotlainnya',
        r_potongan_pulang = '$tpotpulang',
        r_potongan_tidak_lengkap = '$tpotlog',
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
        echo "<script>alert('Data Berhasil Disimpan'); window.location.href='?page=realisasi&aksi=detail&id=$id';</script>";
    }
}
?>