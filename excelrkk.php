<?php
include "koneksi.php";

$id = $_GET['id'];

// 1. Ambil data tanggal berdasarkan ID
$queryInfo = $koneksi->query("SELECT tgl_rkk FROM tb_rkk WHERE id_rkk = '$id'");
$info = $queryInfo->fetch_assoc();
$tanggal_raw = $info ? $info['tgl_rkk'] : '';
$tanggal = $tanggal_raw ? date('d-m-Y', strtotime($tanggal_raw)) : 'TanpaTanggal';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Rencana_Upah_$tanggal.xls");
header("Pragma: no-cache");
header("Expires: 0");
function rupiah($angka)
{
    return "Rp " . number_format($angka, 0, ',', '.');
}

// Subqueries for replacement info
$subquery_menggantikan = "(SELECT K3.nama_karyawan 
         FROM tb_rkk_update U1 
         JOIN tb_rkk_update U2 ON U1.id_rkk_detail = U2.id_rkk_detail 
         JOIN ms_karyawan K3 ON U2.id_karyawan = K3.id_karyawan 
         JOIN tb_rkk_detail RD2 ON U1.id_rkk_detail = RD2.id_rkk_detail
         WHERE U1.id_karyawan = K.id_karyawan 
         AND U1.status = 'Pengganti' 
         AND U2.status = 'Digantikan' 
         AND RD2.id_rkk = R.id_rkk
         LIMIT 1)";

$subquery_digantikan_oleh = "(SELECT K4.nama_karyawan 
         FROM tb_rkk_update U3 
         JOIN tb_rkk_update U4 ON U3.id_rkk_detail = U4.id_rkk_detail 
         JOIN ms_karyawan K4 ON U4.id_karyawan = K4.id_karyawan 
         WHERE U3.id_rkk_detail = RD.id_rkk_detail 
         AND U3.status = 'Digantikan' 
         AND U4.status = 'Pengganti' 
         LIMIT 1)";

$sql = "
SELECT 
    K.nama_karyawan, 
    O.OS_DHK as label_os,
    G.golongan as label_gol,
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
    R.tgl_rkk,
    $subquery_menggantikan as menggantikan,
    $subquery_digantikan_oleh as digantikan_oleh
FROM tb_rkk_detail RD
LEFT JOIN tb_rkk R ON RD.id_rkk = R.id_rkk
LEFT JOIN tb_jadwal JD ON RD.id_jadwal = JD.id_jadwal
LEFT JOIN ms_karyawan K ON RD.id_karyawan = K.id_karyawan
LEFT JOIN ms_departmen D ON RD.id_departmen = D.id_departmen
LEFT JOIN ms_jabatan JB ON K.id_jabatan = JB.id_jabatan
LEFT JOIN ms_os_dhk O ON K.id_os_dhk = O.id_os_dhk
LEFT JOIN ms_golongan G ON K.id_golongan = G.id_golongan
WHERE R.id_rkk = '$id'
ORDER BY D.nama_departmen, K.nama_karyawan ASC
";

$result = $koneksi->query($sql);
$row1 = $result->fetch_assoc();
echo "
<table> <tr><td colspan= '15'  style='text-align:center;'>Rencana Upah Karyawan Tanggal " . date('d/m/Y', strtotime($row1['tgl_rkk'])) . "</td></tr>

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
$totals_by_os = []; // Dynamic array for OS/DHK totals

while ($row = $result->fetch_assoc()) {
    if (!empty($row['digantikan_oleh'])) {
        $row['upah'] = 0;
        $row['potongan_telat'] = 0;
        $row['potongan_istirahat'] = 0;
        $row['potongan_lainnya'] = 0;
    }
    $upah_bersih = $row['upah'] - ($row['potongan_telat'] + $row['potongan_istirahat'] + $row['potongan_lainnya']);

    echo "
    <tr>
        <td align='center'>" . $no . "</td>
        <td>" . $row['nama_karyawan'] . 
            (!empty($row['menggantikan']) ? " (Menggantikan " . $row['menggantikan'] . ")" : "") . 
            (!empty($row['menggantikan']) && !empty($row['digantikan_oleh']) ? " &" : "") . 
            (!empty($row['digantikan_oleh']) ? " (Digantikan oleh " . $row['digantikan_oleh'] . ")" : "") . "</td>
        <td>" . $row['jabatan'] . "</td>
        <td>" . $row['nama_departmen'] . "</td>
        <td align='center'>" . $row['label_os'] . "</td>
        <td align='center'>" . $row['label_gol'] . "</td>
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
    
    // Track Outsourcing categories dynamically
    $os_label = $row['label_os'];
    if (!isset($totals_by_os[$os_label])) {
        $totals_by_os[$os_label] = 0;
    }
    $totals_by_os[$os_label] += $upah_bersih;
    
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

// REKAP OUTSOURCING (Dynamic from ms_os_dhk)
echo "<br><br>
<table border='1' style='border-collapse:collapse; width:600px;'>
    <thead>
        <tr style='background-color:#dbe5f1; font-weight:bold;'>
            <th colspan='3' style='height:30px; font-size:14px; text-align:center;'>REKAP OUTSOURCING CIKUPA " . date('d/m/Y', strtotime($row1['tgl_rkk'])) . "</th>
        </tr>
    </thead>
    <tbody>";

$grand_tagihan = 0;
$q_os = $koneksi->query("SELECT OS_DHK FROM ms_os_dhk ORDER BY id_os_dhk ASC");
while ($d_os = $q_os->fetch_assoc()) {
    $label = $d_os['OS_DHK'];
    $val = $totals_by_os[$label] ?? 0;
    $grand_tagihan += $val;
    echo "
        <tr style='font-weight:bold;'>
            <td style='width:300px; height:25px;'>BIAYA TAGIHAN $label</td>
            <td style='width:50px; text-align:center;'>Rp</td>
            <td style='width:250px; text-align:right;'>" . number_format($val, 0, ',', '.') . "</td>
        </tr>";
}

echo "
        <tr style='background-color:yellow; font-weight:bold;'>
            <td style='height:30px; font-size:16px;'>Rp</td>
            <td colspan='2' style='text-align:right; font-size:16px;'>" . number_format($grand_tagihan, 0, ',', '.') . "</td>
        </tr>
    </tbody>
</table>";

// 3. Ambil data Boneless untuk tanggal yang sama
$queryRKK = $koneksi->query("SELECT tgl_rkk FROM tb_rkk WHERE id_rkk = '$id'");
$rkkInfo = $queryRKK->fetch_assoc();
$tanggal = $rkkInfo['tgl_rkk'] ?? '';

$queryBoneless = $koneksi->query("SELECT * FROM tb_boneless WHERE tgl = '$tanggal'");
$bonelessHeader = $queryBoneless->fetch_assoc();

if ($bonelessHeader) {
    $id_boneless = $bonelessHeader['id_boneless'];
    $queryBonelessDetail = $koneksi->query("SELECT * FROM tb_boneless_detail WHERE id_boneless = '$id_boneless'");
    
    echo "<br><br>";
    echo "<table border='1' style='border-collapse:collapse; width:900px;'>
            <thead>
                <tr>
                    <th colspan='4' style='background-color:#4f81bd; color:white; text-align:center; font-weight:bold; height:30px; font-size:14px;'>
                        DETAIL BONELESS - " . date('d/m/Y', strtotime($tanggal)) . "
                    </th>
                </tr>
                <tr style='background-color:#dbe5f1; font-weight:bold;'>
                    <th style='width:50px;'>No</th>
                    <th style='width:400px;'>Nama Item</th>
                    <th style='width:150px;'>Qty / Harga</th>
                    <th style='width:200px;'>Total</th>
                </tr>
            </thead>
            <tbody>";
    
    $no_b = 1;
    $total_boneless = 0;
    while ($item = $queryBonelessDetail->fetch_assoc()) {
        $total_boneless += $item['total'];
        echo "<tr>
                <td style='text-align:center;'>$no_b</td>
                <td>" . strtoupper($item['nama_item']) . "</td>
                <td style='text-align:center;'>" . number_format($item['qty'], 1, ',', '.') . " x Rp" . number_format($item['harga'], 0, ',', '.') . "</td>
                <td style='text-align:right;'>Rp " . number_format($item['total'], 0, ',', '.') . "</td>
              </tr>";
        $no_b++;
    }
    
    echo "      <tr style='font-weight:bold; background-color:#f1f5f9;'>
                    <td colspan='3' style='text-align:center;'>TOTAL BONELESS</td>
                    <td style='text-align:right;'>Rp " . number_format($total_boneless, 0, ',', '.') . "</td>
                </tr>
            </tbody>
          </table>";

    // Summary Table
    $biaya_pabrik = $total_akhir;
    $potong = $bonelessHeader['jumlah_mobil'];
    $combined_total = $biaya_pabrik + $total_boneless;
    $biaya_per_mobil = ($potong > 0) ? ($combined_total / $potong) : 0;

    echo "<br><br>
    <table border='1' style='border-collapse:collapse;'>
        <thead>
            <tr style='background-color:yellow; font-weight:bold; text-align:center;'>
                <th style='width:250px; height:25px;'>BIAYA PABRIK</th>
                <th style='width:100px;'></th>
                <th style='width:100px;'></th>
                <th style='width:150px;'>BONLESS</th>
                <th style='width:150px;'>POTONG</th>
                <th style='width:200px;'>TOTAL</th>
                <th style='width:250px;'>Biaya Per mobil</th>
            </tr>
        </thead>
        <tbody>
            <tr style='font-weight:bold; text-align:center; height:30px; font-size:14px;'>
                <td style='text-align:right;'>" . number_format($biaya_pabrik, 0, ',', '.') . "</td>
                <td></td>
                <td></td>
                <td style='text-align:right;'>" . number_format($total_boneless, 0, ',', '.') . "</td>
                <td>$potong</td>
                <td style='text-align:right;'>" . number_format($combined_total, 0, ',', '.') . "</td>
                <td style='text-align:right;'>Rp" . number_format($biaya_per_mobil, 0, ',', '.') . "</td>
            </tr>
        </tbody>
    </table>";
}
