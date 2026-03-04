<?php
include "koneksi.php";  // sesuaikan koneksi

$id = $_GET['id']; // parameter ?tgl=2025-10-30

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=export_realisasi_$id.xls");
header("Pragma: no-cache");
header("Expires: 0");
function rupiah($angka){
    return "Rp " . number_format($angka, 0, ',', '.');
}

// Query join lengkap
$sql = "
SELECT 
    K.nama_karyawan, K.OS_DHK,K.golongan,
    J.jabatan,
    D.nama_departmen,
    R.jam_kerja,
    RD.ra_masuk,
    RD.ra_istirahat_masuk,
    RD.ra_keluar,
    RD.hasil_kerja,
    RD.r_upah,
    RD.r_potongan_telat,
    RD.r_potongan_istirahat,
    RD.r_potongan_lainnya,
    R.tgl_realisasi,
    RD.lembur
FROM tb_realisasi_detail RD
LEFT JOIN tb_realisasi R ON RD.id_realisasi = R.id_realisasi
LEFT JOIN ms_karyawan K ON RD.id_karyawan = K.id_karyawan
LEFT JOIN ms_departmen D ON K.id_departmen = D.id_departmen
LEFT JOIN ms_jabatan J ON K.id_jabatan = J.id_jabatan
WHERE R.id_realisasi = '$id'
ORDER BY D.nama_departmen, K.nama_karyawan ASC
";

$result = $koneksi->query($sql);

// MULAI OUTPUT EXCEL (format tabel HTML)
$row1 = $result->fetch_assoc();
$tglRealisasi = $row1 ? $row1['tgl_realisasi'] : '-';
echo "
<table>
<tr>
 <td colspan= '16'  style='text-align:center;'>Realisasi Absensi Karyawan Tanggal ". $tglRealisasi ."</td>   
</tr>
";

echo "
<tr>
 <td colspan= '16'  style='text-align:center;'>JIKALAU NAMA TERTERA DI ABSEN TETAPI TIDAK HADIR MAKA KENA POTONG SEBESAR RP.50,000!!!</td>   
</tr>
";

echo "

<tr>
 <td colspan= '16'  style='text-align:center;'>JIKALAU ISITIRAHAT KURANG DARI JAM 12:00 DAN MASUK SETELAH ISTIRAHAT LEBIH DARI JAM 13:00 MAKA KENA POTONG SEBESAR RP.50,000!!!                          
</td>   
</tr>

";

echo "

<tr>
 <td colspan= '16'  style='text-align:center;'>MASUK JAM 07:00 ISTIRAHAT JAM 11:50 MASUK JAM 10:00 ISTIRAHAT JAM 13:00.</td>   
</tr>

";

echo "

<tr>
 <td colspan= '16' style='text-align:center;'>MASUK JAM 09:00-10:00 ISTIRAHAT JAM 13:00 MASUK JAM ISTIRAHAT JAM 14:00</td>   
</tr>
</table>
";
if($result->num_rows > 0) {
    $result->data_seek(0);
}
echo "<table border='1'>";
echo "
<tr>
 <th>No.</th>
    <th>Nama Karyawan</th>
    <th>Jabatan</th>
    <th>Bagian</th>
    <th>OS/DHK</th>
    <th>Golongan</th>
    <th>Jam Kerja</th>
    <th>Masuk</th>
    <th>Istirahat Masuk</th>
    <th>Keluar</th>
    <th>Hasil Kerja</th>
    <th>Upah</th>
    <th>Potongan</th>
    <th>Potongan Istirahat</th>
    <th>Potongan lainnya</th>
       <th>Lembur</th>
    <th>Upah Dibayar</th>
   
</tr>
";
$no=1;
while($row = $result->fetch_assoc()){

    echo "
    <tr>
     <td>".$no."</td>
        <td>".$row['nama_karyawan']."</td>
        <td>".$row['jabatan']."</td>
        <td>".$row['nama_departmen']."</td>
         <td>".$row['OS_DHK']."</td>
         <td>".$row['golongan']."</td>
        <td>".$row['jam_kerja']."</td>
        <td>".$row['ra_masuk']."</td>
        <td>".$row['ra_istirahat_masuk']."</td>
        <td>".$row['ra_keluar']."</td>
        <td>".$row['hasil_kerja']."</td>
         <td>".rupiah($row['r_upah'])."</td>
        <td>".rupiah($row['r_potongan_telat'])."</td>
        <td>".rupiah($row['r_potongan_istirahat'])."</td>
        <td>".rupiah($row['r_potongan_lainnya'])."</td>
         <td>".rupiah($row['lembur'])."</td>
<td>".rupiah($row['r_upah'] + $row['lembur']-$row['r_potongan_telat'] - $row['r_potongan_istirahat'] - $row['r_potongan_lainnya'])."</td>
    </tr>
    ";

    $no++;
}

echo "</table>";
?>
