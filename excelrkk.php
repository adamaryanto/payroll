<?php
include "koneksi.php";  // sesuaikan koneksi

$id = $_GET['id']; // parameter ?tgl=2025-10-30

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=export_rencana_$id.xls");
header("Pragma: no-cache");
header("Expires: 0");
function rupiah($angka){
    return "Rp " . number_format($angka, 0, ',', '.');
}

// Query join lengkap
$sql = "
SELECT 
    K.nama_karyawan, K.OS_DHK,K.golongan,
    JB.jabatan,
    D.nama_departmen,
    R.jam_kerja,
    JD.jam_masuk,
    JD.istirahat_masuk,
    JD.jam_keluar,
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

// MULAI OUTPUT EXCEL (format tabel HTML)
$row1 = $result->fetch_assoc();
echo "
<table>
<tr>
 <td colspan= '14'  style='text-align:center;'>Realisasi Absensi Karyawan Tanggal ". $row1['tgl_rkk'] ."</td>   
</tr>
";

echo "
<tr>
 <td colspan= '14'  style='text-align:center;'>JIKALAU NAMA TERTERA DI ABSEN TETAPI TIDAK HADIR MAKA KENA POTONG SEBESAR RP.50,000!!!</td>   
</tr>
";

echo "

<tr>
 <td colspan= '14'  style='text-align:center;'>JIKALAU ISITIRAHAT KURANG DARI JAM 12:00 DAN MASUK SETELAH ISTIRAHAT LEBIH DARI JAM 13:00 MAKA KENA POTONG SEBESAR RP.50,000!!!                          
</td>   
</tr>

";

echo "

<tr>
 <td colspan= '14'  style='text-align:center;'>MASUK JAM 07:00 ISTIRAHAT JAM 11:50 MASUK JAM 10:00 ISTIRAHAT JAM 13:00.</td>   
</tr>

";

echo "

<tr>
 <td colspan= '16' style='text-align:center;'>MASUK JAM 09:00-10:00 ISTIRAHAT JAM 13:00 MASUK JAM ISTIRAHAT JAM 14:00</td>   
</tr>
</table>
";
$result->data_seek(0);
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
    <th>Upah</th>
    <th>Potongan</th>
    <th>Potongan Istirahat</th>
    <th>Potongan lainnya</th>
    <th>Upah Setelah Potongan</th>
</tr>
";
$no=1;
$total_upah = 0;
$total_potongan_telat = 0;
$total_potongan_istirahat = 0;
$total_potongan_lainnya = 0;
$total_akhir = 0;

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
        <td>".$row['jam_masuk']."</td>
        <td>".$row['istirahat_masuk']."</td>
        <td>".$row['jam_keluar']."</td>
         <td>".rupiah($row['upah'])."</td>
        <td>".rupiah($row['potongan_telat'])."</td>
        <td>".rupiah($row['potongan_istirahat'])."</td>
        <td>".rupiah($row['potongan_lainnya'])."</td>
        <td>".rupiah($row['upah'] - ($row['potongan_telat'] + $row['potongan_istirahat'] + $row['potongan_lainnya']))."</td>

    </tr>
    ";

    $total_upah += $row['upah'];
    $total_potongan_telat += $row['potongan_telat'];
    $total_potongan_istirahat += $row['potongan_istirahat'];
    $total_potongan_lainnya += $row['potongan_lainnya'];
    $total_akhir += ($row['upah'] - ($row['potongan_telat'] + $row['potongan_istirahat'] + $row['potongan_lainnya']));


    $no++;
}
echo "
<tr style='font-weight:bold; background-color: #f2f2f2;'>
    <td colspan='13' style='text-align:right;'></td>
    <td style='text-align:right;'>SUBTOTAL</td>
    <td>".rupiah($total_akhir)."</td>
</tr>
";


echo "</table>";


// SEKSI SUMMARY BIAYA
$result->data_seek(0);
$grand_total_upah = 0;
while($row_total = $result->fetch_assoc()){
    $grand_total_upah += ($row_total['upah'] - ($row_total['potongan_telat'] + $row_total['potongan_istirahat'] + $row_total['potongan_lainnya']));
}

$tanggal = $row1['tgl_rkk'];
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
                        DETAIL BONELESS - " . date('d-m-Y', strtotime($tanggal)) . "
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
    $biaya_pabrik = $grand_total_upah;
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
                <td style='text-align:right;'>" . number_format($biaya_pabrik, 2, ',', '.') . "</td>
                <td></td>
                <td></td>
                <td style='text-align:right;'>" . number_format($total_boneless, 2, ',', '.') . "</td>
                <td>$potong</td>
                <td style='text-align:right;'>" . number_format($combined_total, 2, ',', '.') . "</td>
                <td style='text-align:right;'>Rp" . number_format($biaya_per_mobil, 2, ',', '.') . "</td>
            </tr>
        </tbody>
    </table>";
}
?>
