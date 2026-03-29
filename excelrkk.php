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
$queryBoneless = $koneksi->query("
    SELECT b.*, bm.biaya_mobil 
    FROM tb_boneless b
    LEFT JOIN tb_biayamobil bm ON b.id_biayamobil = bm.id_biayamobil
    WHERE b.tgl = '$tanggal_sql'
");
$bonelessHeader = $queryBoneless->fetch_assoc();
$id_boneless = $bonelessHeader['id_boneless'] ?? 0;
$potong = (float)($bonelessHeader['jumlah_mobil'] ?? 0);
$harga_master_saat_itu = (float)($bonelessHeader['biaya_mobil'] ?? 0); 

$total_boneless = 0; // Pastikan nama variabel konsisten dengan tabel di bawah
$boneless_details = [];
if ($id_boneless > 0) {
    $q_bd = $koneksi->query("SELECT * FROM tb_boneless_detail WHERE id_boneless = '$id_boneless'");
    while ($bd = $q_bd->fetch_assoc()) {
        $total_boneless += (float)$bd['total'];
        $boneless_details[] = $bd;
    }
}

// 3. Query Karyawan
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
    $data_per_bagian[$row['bagian'] ?? 'LAINNYA'][] = $row;
    $total_upah_pabrik += (float)($row['upah'] ?? 0);
}

// --- OUTPUT EXCEL ---
echo "<table><tr><td colspan='11' style='text-align:center; font-weight:bold;'>JIKALAU NAMA YANG TERTERA DIABSEN TELAT MAKA AKAN DIPOTONG <span style='color:red;'>RP 25.000</span> <br> MASUK JAM 07:00 ISTIRAHAT JAM 11:50 MASUK JAM 10:00 ISTIRAHAT JAM 13:00.</td></tr></table>";

// Looping cetak tabel per bagian
foreach ($data_per_bagian as $nama_bagian => $karyawans) {
    echo "<table border='1'>
    <thead>
        <tr style='background-color: #1e3a8a; font-weight:bold;'>
            <th colspan='11' style='text-align:center; color: #fff;'>" . strtoupper($nama_bagian ?? '') . "</th>
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
            <th rowspan='2'>UPAH</th>
        </tr>
        <tr style='background-color: #f2f2f2;'>
            <th>KELUAR</th>
            <th>MASUK</th>
        </tr>
    </thead>
    <tbody>";

    $no = 1;
    foreach ($karyawans as $k) {
        $jam_kerja = ($k['jam_masuk'] ?? '') . " - " . ($k['jam_keluar'] ?? '');
        echo "<tr>
            <td align='center'>$no</td>
            <td>" . strtoupper($k['nama_karyawan'] ?? '') . "</td>
            <td align='center'>" . ($k['label_os'] ?? '') . "</td>
            <td align='center'>" . ($k['label_gol'] ?? '') . "</td>
            <td>" . ($k['posisi'] ?? '-') . "</td>
            <td align='center'>$jam_kerja</td>
            <td align='center'>" . ($k['jam_masuk'] ?? '') . "</td>
            <td align='center'>" . ($k['istirahat_keluar'] ?? '') . "</td>
            <td align='center'>" . ($k['istirahat_masuk'] ?? '') . "</td>
            <td align='center'>" . ($k['jam_keluar'] ?? '') . "</td>
            <td align='right'>Rp " . number_format((float)($k['upah'] ?? 0), 0, '.', ',') . "</td>
        </tr>";
        $no++;
    }
    echo "</tbody></table>";
}

echo "<table border='1'>
    <tr style='background-color: #fbbf24; font-weight:bold;'>
        <td colspan='10' align='center' style='width: 800px;'>GRAND TOTAL UPAH (BIAYA PABRIK)</td>
        <td align='right' style='width: 100px;'>Rp " . number_format($total_upah_pabrik, 0, '.', ',') . "</td>
    </tr>
</table><br>";

// --- TABEL REKAP BIAYA ---
$boneless_calculated = $harga_master_saat_itu * $potong; 
$combined_total_new = $total_upah_pabrik + $boneless_calculated;
$biaya_per_mobil_new = ($potong > 0) ? ($combined_total_new / $potong) : 0;

echo "<br><table border='1' style='border-collapse:collapse;'>
    <tr style='background-color:#dbe5f1; font-weight:bold;'>
        <th colspan='7'>REALISASI TOTAL REKAP BIAYA PABRIK CIKUPA " . date('d F Y', strtotime($tanggal_sql)) . "</th>
    </tr>
    <tr style='background-color:#dbe5f1; font-weight:bold;'>
        <td colspan='6' align='center'>Biaya " . (int)$potong . " mobil</td>
        <td align='right'>Rp " . number_format($boneless_calculated, 2, '.', ',') . "</td>
    </tr>
    <tr style='background-color:#dbe5f1; font-weight:bold;'>
        <td colspan='6' align='center'>Biaya permobil</td>
        <td align='right''>Rp " . number_format($biaya_per_mobil_new, 2, '.', ',') . "</td>
    </tr>
</table><br>";

// --- TABEL TIM BONELESS ---
echo "<table border='1' style='border-collapse:collapse;'>
    <thead><tr style='background-color:#dbe5f1; font-weight:bold;'><th colspan='7'>BAYARAN TIM BONELESS</th></tr></thead>
    <tbody>";
$no_b = 1;
if (!empty($boneless_details)) {
    foreach ($boneless_details as $item) {
        echo "<tr>
            <td align='center'>$no_b</td>
            <td colspan='4' style='font-weight:bold;'>" . strtoupper($item['nama_item'] ?? '') . "</td>
            <td align='right'>" . number_format((float)($item['qty'] ?? 0), 1, '.', ',') . "</td>
            <td align='right'>Rp " . number_format((float)($item['total'] ?? 0), 2, '.', ',') . "</td>
        </tr>";
        $no_b++;
    }
} else {
    echo "<tr><td colspan='7' align='center'>Data boneless tidak ditemukan untuk tanggal ini</td></tr>";
}
echo "<tr style='font-weight:bold;'><td colspan='6' align='center'>TOTAL</td><td align='right'>Rp " . number_format($total_boneless, 2, '.', ',') . "</td></tr></tbody></table><br>";

// --- TABEL KUNING ---
$boneless_calculated = $harga_master_saat_itu * $potong; 
$combined_total_new = $total_upah_pabrik + $boneless_calculated;
$biaya_per_mobil_new = ($potong > 0) ? ($combined_total_new / $potong) : 0;

echo "<table border='1' style='border-collapse:collapse;'>
    <tr style='background-color:yellow; font-weight:bold; text-align:center;'>
        <th>BIAYA PABRIK</th><th>BONELESS</th><th>POTONG</th><th>TOTAL</th><th>Biaya Per mobil</th>
    </tr>
    <tr style='font-weight:bold; text-align:right;'>
        <td align='center'>Rp " . number_format($total_upah_pabrik, 2, '.', ',') . "</td>
        <td align='center'>Rp " . number_format($boneless_calculated, 2, '.', ',') . "</td>
        <td align='center'>" . (int)$potong . "</td>
        <td align='center'>Rp " . number_format($combined_total_new, 2, '.', ',') . "</td>
        <td align='center' style='background-color:white;'>Rp " . number_format($biaya_per_mobil_new, 2, '.', ',') . "</td>
    </tr>
</table>";