<?php
include "koneksi.php";

$id = isset($_GET['id']) ? $_GET['id'] : '';


header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=export_realisasi_$id.xls");
header("Pragma: no-cache");
header("Expires: 0");

function rupiah($angka){
    return "Rp " . number_format($angka, 0, ',', '.');
}

// Query join lengkap untuk mendapatkan semua metadata yang diminta
$sql = "
SELECT 
    K.no_absen,
    K.nama_karyawan, 
    K.OS_DHK,
    K.golongan,
    D.nama_departmen,
    R.tgl_realisasi,
    R.jam_kerja,
    RD.ra_masuk,
    RD.ra_keluar,
    RD.ra_istirahat_keluar,
    RD.ra_istirahat_masuk,
    RD.r_jam_masuk,
    RD.r_istirahat_keluar,
    RD.r_istirahat_masuk,
    RD.r_jam_keluar,
    RD.hasil_kerja,
    RD.r_upah,
    RD.r_potongan_lainnya,
    RD.lembur
FROM tb_realisasi_detail RD
LEFT JOIN tb_realisasi R ON RD.id_realisasi = R.id_realisasi
LEFT JOIN ms_karyawan K ON RD.id_karyawan = K.id_karyawan
LEFT JOIN tb_rkk_detail RKD ON RD.id_rkk_detail = RKD.id_rkk_detail
LEFT JOIN ms_departmen D ON RKD.id_departmen = D.id_departmen
WHERE R.id_realisasi = '$id'
ORDER BY D.nama_departmen, K.nama_karyawan ASC
";

$result = $koneksi->query($sql);

// Header Informasi Tambahan (Opsional, tapi bagus untuk konteks)
$tglRealisasi = '-';
if ($result->num_rows > 0) {
    $row_temp = $result->fetch_assoc();
    $tglRealisasi = $row_temp['tgl_realisasi'];
    $result->data_seek(0);
}

// Ambil Data Denda Global
$queryDenda = $koneksi->query("SELECT * FROM tb_denda LIMIT 1");
$dataDenda = $queryDenda->fetch_assoc();
$globalDendaMasuk = $dataDenda['denda_masuk'] ?? 0;
$globalDendaIstirahat = $dataDenda['denda_istirahat'] ?? 0;

echo "
<table border='1'>
    <tr>
        <th colspan='21' style='text-align:center; background-color:#f8f9fa; font-size:16px;'>LAPORAN REALISASI ABSENSI & UPAH TANGGAL: $tglRealisasi</th>
    </tr>
    <tr style='background-color:#5F9EA0; color:white;'>
        <th>No.</th>
        <th>NIK</th>
        <th>Nama Karyawan</th>
        <th>Bagian</th>
        <th>Golongan</th>
        <th>OS/DHK</th>
        <th>Tgl</th>
        <th>Jam Masuk (R)</th>
        <th>Pulang (R)</th>
        <th>Absen Masuk</th>
        <th>Istirahat Keluar</th>
        <th>Istirahat Masuk</th>
        <th>Absen Pulang</th>
        <th>Lembur</th>
        <th>Upah</th>
        <th>Pot. Telat</th>
        <th>Pot. Istirahat</th>
        <th>Pot. Lainnya</th>
        <th>Upah Dibayar</th>
        <th>Hasil Kerja/Ket</th>
    </tr>
";

$no = 1;
$grand_total_dibayar = 0;

while($row = $result->fetch_assoc()){
    // Logika Pelanggaran Dinamis (untuk menyamakan dengan tampilan web)
    $isLate = (!empty($row['r_jam_masuk']) && $row['r_jam_masuk'] != '00:00:00' && !empty($row['ra_masuk']) && $row['ra_masuk'] != '00:00:00' && strtotime($row['r_jam_masuk']) > strtotime($row['ra_masuk']));
    $isEarlyOut = (!empty($row['r_jam_keluar']) && $row['r_jam_keluar'] != '00:00:00' && !empty($row['ra_keluar']) && $row['ra_keluar'] != '00:00:00' && strtotime($row['r_jam_keluar']) < strtotime($row['ra_keluar']));
    $isLateBreak = (!empty($row['r_istirahat_masuk']) && $row['r_istirahat_masuk'] != '00:00:00' && !empty($row['ra_istirahat_masuk']) && $row['ra_istirahat_masuk'] != '00:00:00' && strtotime($row['r_istirahat_masuk']) > strtotime($row['ra_istirahat_masuk']));

    $potTelatValue = $isLate ? $globalDendaMasuk : 0;
    $potIstirahatValue = $isLateBreak ? $globalDendaIstirahat : 0;
    $potPulangValue = $isEarlyOut ? $globalDendaPulang : 0;

    // Hitung Upah Dibayar
    $upah_bersih = ($row['r_upah'] + $row['lembur']) - ($potTelatValue + $potIstirahatValue + $potPulangValue + $row['r_potongan_lainnya']);
    $grand_total_dibayar += $upah_bersih;

    echo "
    <tr>
        <td style='text-align:center;'>".$no."</td>
        <td>'".$row['no_absen']."</td>
        <td>".$row['nama_karyawan']."</td>
        <td>".$row['nama_departmen']."</td>
        <td style='text-align:center;'>".$row['golongan']."</td>
        <td style='text-align:center;'>".$row['OS_DHK']."</td>
        <td style='text-align:center;'>".$row['tgl_realisasi']."</td>
        <td style='text-align:center;'>".$row['ra_masuk']."</td>
        <td style='text-align:center;'>".$row['ra_keluar']."</td>
        <td style='text-align:center;'>".$row['r_jam_masuk']."</td>
        <td style='text-align:center;'>".$row['r_istirahat_keluar']."</td>
        <td style='text-align:center;'>".$row['r_istirahat_masuk']."</td>
        <td style='text-align:center;'>".$row['r_jam_keluar']."</td>
        <td style='text-align:right;'>".number_format($row['lembur'], 0, ',', '.')."</td>
        <td style='text-align:right;'>".number_format($row['r_upah'], 0, ',', '.')."</td>
        <td style='text-align:right; color:red;'>".number_format($potTelatValue, 0, ',', '.')."</td>
        <td style='text-align:right; color:red;'>".number_format($potIstirahatValue, 0, ',', '.')."</td>
        <td style='text-align:right; color:red;'>".number_format($potPulangValue, 0, ',', '.')."</td>
        <td style='text-align:right; color:red;'>".number_format($row['r_potongan_lainnya'], 0, ',', '.')."</td>
        <td style='text-align:right; font-weight:bold;'>".number_format($upah_bersih, 0, ',', '.')."</td>
        <td>".$row['hasil_kerja']."</td>
    </tr>
    ";

    $no++;
}

// Baris Total di Bawah
echo "
    <tr style='background-color:#f1f5f9; font-weight:bold;'>
        <td colspan='18' style='text-align:right; padding:10px;'>TOTAL UPAH DIBAYAR:</td>
        <td style='text-align:right;'>".rupiah($grand_total_dibayar)."</td>
        <td></td>
    </tr>
</table>
";
?>
