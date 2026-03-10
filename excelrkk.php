<?php
include "koneksi.php";

$id = $_GET['id'];

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=export_rencana_$id.xls");
header("Pragma: no-cache");
header("Expires: 0");
function rupiah($angka)
{
    return "Rp " . number_format($angka, 0, ',', '.');
}

$sql = "
SELECT 
    K.nama_karyawan, K.OS_DHK,K.golongan,
    JB.jabatan,
    D.nama_departmen,
    R.jam_kerja,
    JD.jam_masuk,
    JD.jam_keluar,
    JD.istirahat_keluar,
    JD.istirahat_masuk,
    RD.upah,
    RD.potongan_telat,
    RD.potongan_istirahat,
    RD.potongan_lainnya,
    R.tgl_rkk
FROM tb_rkk_detail RD
LEFT JOIN tb_rkk R ON RD.id_rkk = R.id_rkk
LEFT JOIN tb_jadwal JD ON RD.id_jadwal = JD.id_jadwal
LEFT JOIN ms_karyawan K ON RD.id_karyawan = K.id_karyawan
LEFT JOIN ms_departmen D ON K.id_departmen = D.id_departmen
LEFT JOIN ms_jabatan JB ON K.id_jabatan = JB.id_jabatan
WHERE R.id_rkk = '$id'
ORDER BY D.nama_departmen, K.nama_karyawan ASC
";

$result = $koneksi->query($sql);
$row1 = $result->fetch_assoc();
echo "
<table> <tr><td colspan= '15'  style='text-align:center;'>Realisasi Absensi Karyawan Tanggal " . $row1['tgl_rkk'] . "</td></tr>

<tr><td colspan= '15'  style='text-align:center;'>JIKALAU NAMA TERTERA DI ABSEN TETAPI TIDAK HADIR MAKA KENA POTONG SEBESAR RP.50,000!!!</td></tr>

<tr><td colspan= '15'  style='text-align:center;'>JIKALAU ISITIRAHAT KURANG DARI JAM 12:00 DAN MASUK SETELAH ISTIRAHAT LEBIH DARI JAM 13:00 MAKA KENA POTONG SEBESAR RP.50,000!!!</td></tr>

<tr><td colspan= '15'  style='text-align:center;'>MASUK JAM 07:00 ISTIRAHAT JAM 11:50 MASUK JAM 10:00 ISTIRAHAT JAM 13:00.</td></tr>

<tr><td colspan= '15' style='text-align:center;'>MASUK JAM 09:00-10:00 ISTIRAHAT JAM 13:00 MASUK JAM ISTIRAHAT JAM 14:00</td></tr>

</table>";

$result->data_seek(0);
echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%;'>
<thead style='background-color: #e9ecef;'>
    <tr>
        <th rowspan='2'>No.</th>
        <th rowspan='2'>Nama Karyawan</th>
        <th rowspan='2'>Jabatan</th>
        <th rowspan='2'>Bagian</th>
        <th rowspan='2'>OS/DHK</th>
        <th rowspan='2'>Gol.</th>
        <th colspan='5'>Log Absensi</th>
        <th rowspan='2'>Upah Kotor</th>
        <th colspan='3'>Rincian Potongan</th>
        <th rowspan='2'>Upah Bersih</th>
    </tr>
    <tr>
        <th>J. Kerja</th>
        <th>Masuk</th>
        <th>Pulang</th>
        <th>Ist. Keluar</th>
        <th>Ist. Masuk</th>
        <th>Terlambat</th>
        <th>Istirahat</th>
        <th>Lainnya</th>
    </tr>
</thead>
<tbody>
";

$no = 1;
$total_upah = 0;
$total_potongan_telat = 0;
$total_potongan_istirahat = 0;
$total_potongan_lainnya = 0;
$total_akhir = 0;

while ($row = $result->fetch_assoc()) {
    $upah_bersih = $row['upah'] - ($row['potongan_telat'] + $row['potongan_istirahat'] + $row['potongan_lainnya']);

    echo "
    <tr>
        <td align='center'>" . $no . "</td>
        <td>" . $row['nama_karyawan'] . "</td>
        <td>" . $row['jabatan'] . "</td>
        <td>" . $row['nama_departmen'] . "</td>
        <td align='center'>" . $row['OS_DHK'] . "</td>
        <td align='center'>" . $row['golongan'] . "</td>
        <td align='center'>" . $row['jam_kerja'] . "</td>
        <td align='center'>" . $row['jam_masuk'] . "</td>
        <td align='center'>" . $row['jam_keluar'] . "</td>
        <td align='center'>" . $row['istirahat_keluar'] . "</td>
        <td align='center'>" . $row['istirahat_masuk'] . "</td>
        <td align='right'>" . rupiah($row['upah']) . "</td>
        <td align='right' style='color: red;'>" . rupiah($row['potongan_telat']) . "</td>
        <td align='right' style='color: red;'>" . rupiah($row['potongan_istirahat']) . "</td>
        <td align='right' style='color: red;'>" . rupiah($row['potongan_lainnya']) . "</td>
        <td align='right' style='font-weight: bold;'>" . rupiah($upah_bersih) . "</td>
    </tr>";

    $total_upah += $row['upah'];
    $total_potongan_telat += $row['potongan_telat'];
    $total_potongan_istirahat += $row['potongan_istirahat'];
    $total_potongan_lainnya += $row['potongan_lainnya'];
    $total_akhir += $upah_bersih;
    $no++;
}

echo "
</tbody>
<tfoot>
    <tr style='font-weight:bold; background-color: #f2f2f2;'>
        <td colspan='11' align='right'>TOTAL KESELURUHAN</td>
        <td align='right'>" . rupiah($total_upah) . "</td>
        <td align='right' style='color: red;'>" . rupiah($total_potongan_telat) . "</td>
        <td align='right' style='color: red;'>" . rupiah($total_potongan_istirahat) . "</td>
        <td align='right' style='color: red;'>" . rupiah($total_potongan_lainnya) . "</td>
        <td align='right' style='background-color: #d4edda;'>" . rupiah($total_akhir) . "</td>
    </tr>
</tfoot>
</table>";