<?php
include "koneksi.php";  // sesuaikan koneksi

$id_karyawan = $_GET['id']; // parameter ?tgl=2025-10-30
$tanggal_mulai = $_GET['ttgl1'];
$tanggal_akhir = $_GET['ttgl2'];
$bulanIndo = [
    '01' => 'Januari',
    '02' => 'Februari',
    '03' => 'Maret',
    '04' => 'April',
    '05' => 'Mei',
    '06' => 'Juni',
    '07' => 'Juli',
    '08' => 'Agustus',
    '09' => 'September',
    '10' => 'Oktober',
    '11' => 'November',
    '12' => 'Desember'
];

$bulan_angka = date("m", strtotime($tanggal_mulai));
$bulan = $bulanIndo[$bulan_angka];


header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=export_slip_$id_karyawan.xls");
header("Pragma: no-cache");
header("Expires: 0");
function rupiah($angka){
    return "Rp " . number_format($angka, 0, ',', '.');
}

// Query join lengkap
$sql = "SELECT
    r.tgl_realisasi_detail,
    r.r_upah,
    r.ra_masuk,
    r.ra_keluar,
    r.r_potongan_telat,
    r.r_potongan_istirahat,
    r.r_potongan_lainnya,
    r.lembur,
    j.jabatan,
    d.nama_departmen,
    k.nama_karyawan
FROM tb_realisasi_detail r
JOIN ms_karyawan k ON r.id_karyawan = k.id_karyawan
JOIN ms_jabatan j ON k.id_jabatan = j.id_jabatan
JOIN ms_departmen d ON k.id_departmen = d.id_departmen
WHERE r.id_karyawan = '$id_karyawan'
  AND r.tgl_realisasi_detail BETWEEN '$tanggal_mulai' AND '$tanggal_akhir'
ORDER BY r.tgl_realisasi_detail ASC;

";

$result = $koneksi->query($sql);

// MULAI OUTPUT EXCEL (format tabel HTML)
$row1 = $result->fetch_assoc();


echo "<table border='1'>";
echo '
<tr>
    <th rowspan="2" style="width:80px;">Bulan ' . $bulan . '</th>

    <!-- Header besar SLIP UPAH -->
    <th colspan="8">SLIP UPAH</th>

    <!-- OUTSOURCING : DHK harus merge 2 baris -->
    <th colspan="3" rowspan="2">OUTSOURCING : DHK</th>
</tr>

<tr>
    <!-- NAMA KARYAWAN -->
    <th colspan="8">NAMA :' . $row1['nama_karyawan'] . '</th>
</tr>

<tr>
    <th>Tanggal</th>
    <th>Upah</th>
    <th>Jam Masuk</th>
    <th>Jam Pulang</th>
    <th>Pot Telat</th>
    <th>Pot Istirahat</th>
    <th>Pot Lainnya</th>
    <th>Lembur</th>
    <th>Total</th>

    <!-- Kolom TTD di bawah OUTSOURCING -->
    <th>Karyawan Setuju</th>
    <th>OS</th>
    <th>HRD DHK</th>
</tr>
';
$result->data_seek(0);
$no=1;
while($row = $result->fetch_assoc()){

    echo "
    <tr>
     <td>".$row['tgl_realisasi_detail']."</td>
        <td>".rupiah($row['r_upah'])."</td>
         <td>".$row['ra_masuk']."</td>
        <td>".$row['ra_keluar']."</td>
         <td>".rupiah($row['r_potongan_telat'])."</td>
        <td>".rupiah($row['r_potongan_istirahat'])."</td>
        <td>".rupiah($row['r_potongan_lainnya'])."</td>
         <td>".rupiah($row['lembur'])."</td>
         <td>".rupiah($row['r_upah'] + $row['lembur']-$row['r_potongan_telat'] - $row['r_potongan_istirahat'] - $row['r_potongan_lainnya'])."</td>

        <td></td>
        <td></td>
         <td></td>
        
         
       

    </tr>
    ";

    $no++;
}

echo "</table>";
?>
