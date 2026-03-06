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
            while ($data = $tampil->fetch_assoc()) {
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
            // Tambahkan baris kosong sebagai pemisah antar departemen
            echo "<tr><td colspan='16' style='height:20px;'></td></tr>";
        }
        ?>
    </tbody>
</table>