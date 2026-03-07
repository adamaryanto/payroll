<?php
include "../../koneksi.php";

$id = isset($_GET['id']) ? $_GET['id'] : '';

// 1. Ambil data tanggal berdasarkan ID
$queryInfo = $koneksi->query("
SELECT 
R.tgl_realisasi,
R.jam_kerja,
J.jam_masuk,
J.jam_keluar
FROM tb_realisasi R
LEFT JOIN tb_realisasi_detail RD ON R.id_realisasi = RD.id_realisasi
LEFT JOIN tb_jadwal J ON RD.id_jadwal = J.id_jadwal
WHERE R.id_realisasi = '$id'
LIMIT 1
");
$info = $queryInfo->fetch_assoc();
$tanggal = $info ? $info['tgl_realisasi'] : 'TanpaTanggal';

// 2. Gunakan variabel $tanggal untuk nama file
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Rencana_Upah_$tanggal.xls");
echo "<meta charset='UTF-8'>";
?>

<table border="1" style="border-collapse:collapse;">
    <thead>
        <tr>
            <th colspan="14" style="text-align:center; font-size:20px; font-weight:bold;">LAPORAN RENCANA UPAH</th>
        </tr>
        <tr>
            <th colspan="14" style="text-align:center; font-size:16px;">
                Tanggal Realisasi: <?php echo $info['tgl_realisasi'] ?? '-'; ?> |
                Jam Kerja: <?php echo $info['jam_kerja'] ?? '-'; ?> |
                <?php echo $info['jam_masuk'] . " / " . $info['jam_keluar']; ?></td>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php
        $grand_total = 0;
        $grand_karyawan = 0;
        // 1. Ambil list departemen terlebih dahulu
        $sqlDept = $koneksi->query("SELECT * FROM ms_departmen");
        while ($dept = $sqlDept->fetch_assoc()) {
            $id_dept = $dept['id_departmen'];

            // 2. Tampilkan Header Departemen (Wrapping/Merge)
            echo "<tr>
                    <td colspan='14' style='background-color:#1e3a8a; color:white; font-weight:bold; padding:10px;'>
                        DEPARTEMEN: " . strtoupper($dept['nama_departmen']) . "
                    </td>
                  </tr>";

            // 3. Header Kolom
            echo "<tr>
            <th style='background:#e5e7eb;'>No</th>
            <th style='background:#e5e7eb;'>Nama Sesuai KTP</th>
            <th style='background:#e5e7eb;'>OS/DHK</th>
            <th style='background:#e5e7eb;'>Golongan</th>
            <th style='background:#e5e7eb;'>Posisi</th>
            <th style='background:#e5e7eb;'>Jam Kerja</th>
            <th style='background:#e5e7eb;'>Jam Masuk</th>
            <th style='background:#e5e7eb;'>Istirahat</th>
            <th style='background:#e5e7eb;'>Jam Pulang</th>
            <th style='background:#e5e7eb;'>Hasil Kerja</th>
            <th style='background:#e5e7eb;'>Upah</th>
            <th style='background:#e5e7eb;'>Potongan</th>
            <th style='background:#e5e7eb;'>Lembur</th>
            <th style='background:#e5e7eb;'>Upah Dibayar</th>
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
            RD.upah as upah,
            J.jam_masuk,
            J.jam_keluar,
            J.istirahat_masuk,
            J.istirahat_keluar
            FROM tb_realisasi_detail A 
            JOIN ms_karyawan B ON A.id_karyawan = B.id_karyawan
            JOIN ms_departmen D ON B.id_departmen = D.id_departmen
            LEFT JOIN ms_sub_department S ON B.id_sub_department = S.id_sub_department
            LEFT JOIN tb_rkk_detail RD ON A.id_rkk_detail = RD.id_rkk_detail
            LEFT JOIN tb_jadwal J ON A.id_jadwal = J.id_jadwal
            WHERE A.id_realisasi = '$id' 
            AND B.id_departmen = '$id_dept'");

            $no = 1;
            $total = 0;
            $jml_karyawan = 0;
            while ($data = $tampil->fetch_assoc()) {
                $upah = $data['upah'];
                $total += $upah;
                $jml_karyawan++;

                $grand_total += $upah;
                $grand_karyawan++;

                $potongan = $data['r_potongan_telat'] +
                    $data['r_potongan_istirahat'] +
                    $data['r_potongan_lainnya'];

                $lembur = $data['lembur'] ?? 0;

                $upah_dibayar = $data['upah'] - $potongan + $lembur;
                echo "<tr>
                <td>{$no}</td>
                <td>{$data['nama_karyawan']}</td>
                <td>{$data['OS_DHK']}</td>
                <td>{$data['golongan']}</td>
                <td>{$data['nama_sub_department']}</td>
                <td>{$info['jam_kerja']}</td>
                <td>{$data['r_jam_masuk']}</td>
                <td>{$data['r_istirahat_keluar']} / {$data['r_istirahat_masuk']}</td>
                <td>{$data['r_jam_keluar']}</td>
                <td>{$data['hasil_kerja']}</td>
                <td style='text-align:right'>{$data['upah']}</td>
                <td style='text-align:right'>$potongan</td>
                <td style='text-align:right'>$lembur</td>
                <td style='text-align:right'>$upah_dibayar</td>
                </tr>";
                $no++;
            }
            echo "<tr>
            <td colspan='14' style='background:#f1f5f9; font-weight:bold; text-align:right;'>
            TOTAL UPAH ($jml_karyawan Karyawan) | Rp " . number_format($total, 0, ",", ".") . "
            </td>
            </tr>";

            // pemisah antar departemen
            echo "<tr><td colspan='14' style='height:20px;'></td></tr>";
        }
        echo "<tr>
        <td colspan='14' style='background:#1e3a8a; color:white; font-weight:bold; font-size:16px; text-align:right; padding:10px;'>
        GRAND TOTAL UPAH ($grand_karyawan Karyawan) | Rp " . number_format($grand_total, 0, ",", ".") . "
        </td>
        </tr>";
        ?>
    </tbody>

</table>

<?php
$rekap = $koneksi->query("
SELECT 
B.OS_DHK,
SUM(RD.upah) as total_upah
FROM tb_realisasi_detail A
JOIN ms_karyawan B ON A.id_karyawan = B.id_karyawan
LEFT JOIN tb_rkk_detail RD ON A.id_rkk_detail = RD.id_rkk_detail
WHERE A.id_realisasi = '$id'
GROUP BY B.OS_DHK
");

?>

<br><br><br>

<table border="1" style="border-collapse:collapse; width:900px;">

    <tr>
        <td colspan="3" style="background:#dbe5f1; font-weight:bold; text-align:center;">
            REALISASI TOTAL REKAP OUTSOURCING CIKUPA <?php echo $info['tgl_realisasi']; ?>
        </td>
    </tr>

    <?php
    $total_semua = 0;

    while ($row = $rekap->fetch_assoc()) {

        $total = $row['total_upah'];
        $total_semua += $total;

        echo "<tr>
<td style='font-weight:bold;'>BIAYA TAGIHAN {$row['OS_DHK']}</td>
<td style='text-align:center;'>Rp</td>
<td style='text-align:right;'>" . number_format($total, 0, ",", ".") . "</td>
</tr>";
    }
    ?>

    <tr>
        <td colspan="2" style="background:yellow; font-weight:bold;">Rp</td>
        <td style="background:yellow; font-weight:bold; text-align:right;">
            <?php echo number_format($total_semua, 0, ",", "."); ?>
        </td>
    </tr>

</table>