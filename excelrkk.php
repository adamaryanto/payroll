<?php
include "koneksi.php";

$id = $_GET['id'];

// 1. Ambil data Header RKK
$queryInfo = $koneksi->query("SELECT tgl_rkk FROM tb_rkk WHERE id_rkk = '$id'");
$info = $queryInfo->fetch_assoc();
$tanggal_sql = $info['tgl_rkk'] ?? '';
$tanggal_file = $tanggal_sql ? date('d-m-Y', strtotime($tanggal_sql)) : 'TanpaTanggal';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Rencana_Upah_Cikupa_$tanggal_file.xls");
header("Pragma: no-cache");
header("Expires: 0");

// 2. Data Boneless
$queryBoneless = $koneksi->query("SELECT * FROM tb_boneless WHERE tgl = '$tanggal_sql'");
$bonelessHeader = $queryBoneless->fetch_assoc();
$id_boneless = $bonelessHeader['id_boneless'] ?? 0;
$potong = $bonelessHeader['jumlah_mobil'] ?? 0;

$total_boneless = 0;
$boneless_details = [];
if ($id_boneless > 0) {
    $q_bd = $koneksi->query("SELECT * FROM tb_boneless_detail WHERE id_boneless = '$id_boneless'");
    while ($bd = $q_bd->fetch_assoc()) {
        $total_boneless += $bd['total'];
        $boneless_details[] = $bd;
    }
}

// 3. Query Karyawan - JOIN ke ms_sub_department & perbaikan kolom jadwal
$sql = "SELECT 
            IF(RD.id_karyawan = 0, RD.nama_karyawan_manual, K.nama_karyawan) as nama_karyawan, 
            O.OS_DHK as label_os, 
            G.golongan as label_gol, 
            D.nama_departmen as bagian,
            SD.nama_sub_department as posisi,
            JD.jam_masuk,
            JD.jam_keluar,
            JD.istirahat_masuk,
            JD.istirahat_keluar,
            RD.upah
        FROM tb_rkk_detail RD 
        LEFT JOIN tb_rkk R ON RD.id_rkk = R.id_rkk 
        LEFT JOIN tb_jadwal JD ON RD.id_jadwal = JD.id_jadwal 
        LEFT JOIN ms_karyawan K ON RD.id_karyawan = K.id_karyawan 
        LEFT JOIN ms_departmen D ON RD.id_departmen = D.id_departmen 
        LEFT JOIN ms_sub_department SD ON K.id_sub_department = SD.id_sub_department
        LEFT JOIN ms_os_dhk O ON K.id_os_dhk = O.id_os_dhk 
        LEFT JOIN ms_golongan G ON K.id_golongan = G.id_golongan 
        WHERE R.id_rkk = '$id' 
        ORDER BY D.nama_departmen ASC, nama_karyawan ASC";

$result = $koneksi->query($sql);

$data_per_bagian = [];
$total_upah_pabrik = 0;
while ($row = $result->fetch_assoc()) {
    $data_per_bagian[$row['bagian']][] = $row;
    $total_upah_pabrik += $row['upah'];
}

// --- OUTPUT EXCEL ---
echo "<table><tr><td colspan='10' style='text-align:center; font-weight:bold;'>JIKALAU NAMA YANG TERTERA DIABSEN TELAT MAKA AKAN DIPOTONG RP 25.000 <br> MASUK JAM 07:00 ISTIRAHAT JAM 11:50 MASUK JAM 10:00 ISTIRAHAT JAM 13:00.</td></tr></table>";

foreach ($data_per_bagian as $nama_bagian => $karyawans) {
    echo "<table border='1'>
    <thead>
        <tr style='background-color: #1e3a8a; font-weight:bold;'>
            <th colspan='10' style='text-align:center; color: #fff;'>" . strtoupper($nama_bagian) . "</th>
        </tr>
        <tr style='background-color: #f2f2f2;'>
            <th rowspan='2'>No</th>
            <th rowspan='2'>NAMA SESUAI KTP</th>
            <th rowspan='2'>OS/DHK</th>
            <th rowspan='2'>GOLONGAN</th>
            <th rowspan='2'>POSISI</th>
            <th rowspan='2'>JAM KERJA</th>
            <th rowspan='2'>JAM MASUK</th>
            <th colspan='2'>ISTIRAHAT</th>
            <th rowspan='2'>JAM PULANG</th>
        </tr>
        <tr style='background-color: #f2f2f2;'>
            <th>KELUAR</th>
            <th>MASUK</th>
        </tr>
    </thead>
    <tbody>";

    $no = 1;
    foreach ($karyawans as $k) {
        $jam_kerja = $k['jam_masuk'] . " - " . $k['jam_keluar'];
        echo "<tr>
            <td align='center'>$no</td>
            <td>" . strtoupper($k['nama_karyawan']) . "</td>
            <td align='center'>" . $k['label_os'] . "</td>
            <td align='center'>" . $k['label_gol'] . "</td>
            <td>" . ($k['posisi'] ?? '-') . "</td>
            <td align='center'>$jam_kerja</td>
            <td align='center'>" . $k['jam_masuk'] . "</td>
            <td align='center'>" . $k['istirahat_keluar'] . "</td>
            <td align='center'>" . $k['istirahat_masuk'] . "</td>
            <td align='center'>" . $k['jam_keluar'] . "</td>
        </tr>";
        $no++;
    }
    echo "</tbody></table>";
}

// --- TABEL REKAP BIAYA ---
$combined_total = $total_upah_pabrik + $total_boneless;
$biaya_per_mobil_actual = ($potong > 0) ? ($combined_total / $potong) : 0;

echo "<br><table border='1' style='border-collapse:collapse;'>
    <tr style='background-color:#dbe5f1; font-weight:bold;'>
        <th colspan='7'>REALISASI TOTAL REKAP BIAYA PABRIK CIKUPA " . date('d F Y', strtotime($tanggal_sql)) . "</th>
    </tr>
    <tr style='background-color:#dbe5f1; font-weight:bold;'>
        <td colspan='6' align='center'>Biaya $potong mobil</td>
        <td align='right'>Rp " . number_format($combined_total, 2, '.', ',') . "</td>
    </tr>
    <tr style='background-color:#dbe5f1; font-weight:bold;'>
        <td colspan='6' align='center'>Biaya permobil</td>
        <td align='right'>Rp " . number_format($biaya_per_mobil_actual, 2, '.', ',') . "</td>
    </tr>
</table><br>";

// --- TABEL TIM BONELESS ---
echo "<table border='1' style='border-collapse:collapse;'>
    <thead><tr style='background-color:#dbe5f1; font-weight:bold;'><th colspan='7'>BAYARAN TIM BONLESS</th></tr></thead>
    <tbody>";
$no_b = 1;
foreach ($boneless_details as $item) {
    echo "<tr>
        <td align='center'>$no_b</td>
        <td colspan='4' style='font-weight:bold;'>" . strtoupper($item['nama_item']) . "</td>
        <td align='right'>" . number_format($item['qty'], 1, '.', ',') . "</td>
        <td align='right'>Rp " . number_format($item['total'], 2, '.', ',') . "</td>
    </tr>";
    $no_b++;
}
echo "<tr style='font-weight:bold;'><td colspan='6' align='center'>TOTAL</td><td align='right'>Rp " . number_format($total_boneless, 2, '.', ',') . "</td></tr></tbody></table><br>";

// --- TABEL KUNING ---
echo "<table border='1' style='border-collapse:collapse;'>
    <tr style='background-color:yellow; font-weight:bold; text-align:center;'>
        <th>BIAYA PABRIK</th><th>BONLESS</th><th>POTONG</th><th>TOTAL</th><th>Biaya Per mobil</th>
    </tr>
    <tr style='font-weight:bold; text-align:right;'>
        <td>" . number_format($total_upah_pabrik, 2, '.', ',') . "</td>
        <td>" . number_format($total_boneless, 2, '.', ',') . "</td>
        <td align='center'>$potong</td>
        <td>" . number_format($combined_total, 2, '.', ',') . "</td>
        <td style='background-color:white;'>Rp" . number_format($biaya_per_mobil_actual, 2, '.', ',') . "</td>
    </tr>
</table>";
?>