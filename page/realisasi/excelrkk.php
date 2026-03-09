<?php
include "../../koneksi.php";

$id = isset($_GET['id']) ? $_GET['id'] : '';

// 1. Ambil data tanggal berdasarkan ID
$queryInfo = $koneksi->query("SELECT tgl_realisasi, jam_kerja FROM tb_realisasi WHERE id_realisasi = '$id'");
$info = $queryInfo->fetch_assoc();
$tanggal = $info ? $info['tgl_realisasi'] : 'TanpaTanggal';

// 2. Gunakan variabel $tanggal untuk nama file
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Rencana_Upah_$tanggal.xls");
?>

<table border="1">
    <thead>
        <tr>
            <th colspan="16" style="text-align:center; font-size:20px; font-weight:bold;">LAPORAN RENCANA UPAH</th>
        </tr>
        <tr>
            <th colspan="16" style="text-align:center; font-size:16px;">
                Tanggal Realisasi: <?php echo $info['tgl_realisasi'] ?? '-'; ?> | 
                Jam Kerja: <?php echo $info['jam_kerja'] ?? '-'; ?>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php
        $grand_upah_total = 0; // Initialize grand total
        // 1. Ambil list departemen terlebih dahulu
        $sqlDept = $koneksi->query("SELECT * FROM ms_departmen");
        while ($dept = $sqlDept->fetch_assoc()) {
            $id_dept = $dept['id_departmen'];

            // 2. Tampilkan Header Departemen (Wrapping/Merge)
            echo "<tr>
                    <td colspan='16' style='background-color:#1e3a8a; color:white; font-weight:bold; padding:10px;'>
                        DEPARTEMEN: " . strtoupper($dept['nama_departmen']) . "
                    </td>
                  </tr>";

            // 3. Header Kolom
            echo "<tr style='background-color:#f8f9fa;'>
                    <th>No</th><th>NIK</th><th>Nama Karyawan</th><th>Departemen</th>
                    <th>Sub Bagian</th><th>OS/DHK</th><th>Gol</th><th>Masuk</th>
                    <th>Pulang</th><th>Ist. Keluar</th><th>Ist. Masuk</th>
                    <th>Upah</th><th>Pot. Telat</th><th>Pot. Istirahat</th>
                    <th>Pot. Lain</th><th>Hasil Kerja</th>
                  </tr>";

            // 4. Ambil data karyawan di departemen ini saja
            $tampil = $koneksi->query("SELECT 
    A.*, 
    B.no_absen, 
    B.nama_karyawan, 
    B.OS_DHK, 
    B.golongan, 
    D.nama_departmen, 
    S.nama_sub_department, 
    RD.upah as upah
    FROM tb_realisasi_detail A 
    JOIN ms_karyawan B ON A.id_karyawan = B.id_karyawan
    JOIN ms_departmen D ON B.id_departmen = D.id_departmen
    LEFT JOIN ms_sub_department S ON B.id_sub_department = S.id_sub_department
    /* JOIN ke tabel tb_rkk_detail agar bisa akses field upah */
    LEFT JOIN tb_rkk_detail RD ON A.id_rkk_detail = RD.id_rkk_detail
    WHERE A.id_realisasi = '$id' 
    AND B.id_departmen = '$id_dept'");

            $no = 1;
            $total_dept = 0; // Use a specific variable for department total
            $jml_karyawan = 0;
            while ($data = $tampil->fetch_assoc()) {
                $potongan = $data['r_potongan_telat'] + $data['r_potongan_istirahat'] + $data['r_potongan_lainnya'];
                $lembur = $data['lembur'] ?? 0;
                $upah_dibayar = $data['upah'] - $potongan + $lembur;
                
                $total_dept += $upah_dibayar;
                $grand_upah_total += $upah_dibayar;
                $jml_karyawan++;

                echo "<tr>
                        <td>{$no}</td>
                        <td>{$data['no_absen']}</td>
                        <td>{$data['nama_karyawan']}</td>
                        <td>{$data['nama_departmen']}</td>
                        <td>{$data['nama_sub_department']}</td>
                        <td>{$data['OS_DHK']}</td>
                        <td>{$data['golongan']}</td>
                        <td>{$data['r_jam_masuk']}</td>
                        <td>{$data['r_jam_keluar']}</td>
                        <td>{$data['r_istirahat_keluar']}</td>
                        <td>{$data['r_istirahat_masuk']}</td>
                        <td>{$data['upah']}</td>
                        <td>{$data['r_potongan_telat']}</td>
                        <td>{$data['r_potongan_istirahat']}</td>
                        <td>{$data['r_potongan_lainnya']}</td>
                        <td>{$data['hasil_kerja']}</td>
                      </tr>";
                $no++;
            }
            echo "<tr>
                    <td colspan='16' style='background:#f1f5f9; font-weight:bold; text-align:right;'>
                        TOTAL UPAH ($jml_karyawan Karyawan) | Rp " . number_format($total_dept, 0, ",", ".") . "
                    </td>
                  </tr>";
            // Tambahkan baris kosong sebagai pemisah antar departemen
            echo "<tr><td colspan='16' style='height:20px;'></td></tr>";
        }
        
        // Grand Total Row for the top tables
        echo "<tr>
                <td colspan='16' style='background:#1e3a8a; color:white; font-weight:bold; font-size:16px; text-align:right; padding:10px;'>
                    GRAND TOTAL UPAH | Rp " . number_format($grand_upah_total, 0, ",", ".") . "
                </td>
              </tr>";
        ?>
    </tbody>
</table>

<?php
// 3. Ambil data Boneless untuk tanggal yang sama
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

    // Summary Table as per user image
    $biaya_pabrik = $grand_upah_total;
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
