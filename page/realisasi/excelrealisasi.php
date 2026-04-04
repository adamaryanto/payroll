<?php
include "../../koneksi.php";

$id = isset($_GET['id']) ? $_GET['id'] : '';

// 1. Ambil data info utama
$queryInfo = $koneksi->query("SELECT tgl_realisasi, status_realisasi, jam_kerja FROM tb_realisasi WHERE id_realisasi = '$id'");
$info = $queryInfo->fetch_assoc();
$tanggal_raw = $info ? $info['tgl_realisasi'] : '';
$status_realisasi = $info['status_realisasi'] ?? 0;
$tanggal = $tanggal_raw ? date('d-m-Y', strtotime($tanggal_raw)) : 'TanpaTanggal';
$tanggal_sql = $tanggal_raw ? date('Y-m-d', strtotime($tanggal_raw)) : '';

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Realisasi_Upah_$tanggal.xls");
echo "<meta charset='UTF-8'>";

// LOGIKA STEMPEL STATUS
$is_approved = ($status_realisasi >= 2 || strtolower((string)$status_realisasi) === 'approved' || strtolower((string)$status_realisasi) === 'approve');
$stamp_text = $is_approved ? "APPROVED" : "UNAPPROVED";
$stamp_color = $is_approved ? "#008000" : "#FF0000"; // Hijau untuk Approve, Merah untuk Unapproved
?>

<table border="0">
    <tr>
        <th colspan="14" style="text-align:center; font-size:20px; font-weight:bold;">LAPORAN REALISASI UPAH</th>
    </tr>
    <tr>
        <th colspan="14" style="text-align:center; font-size:16px;">
            Tanggal Realisasi: <?php echo $tanggal_raw ? date('d/m/Y', strtotime($tanggal_raw)) : '-'; ?>
        </th>
    </tr>
    <tr>
        <td colspan="14" style="text-align:center; color:red; font-weight:bold; padding-top:10px;">JIKALAU NAMA YANG TERTERA DIABSEN TELAT MAKA AKAN DIPOTONG RP 25.000</td>
    </tr>
    <tr>
        <td colspan="14" style="text-align:center;">MASUK JAM 07:00 ISTIRAHAT JAM 11:50 MASUK JAM 10:00 ISTIRAHAT JAM 13:00.</td>
    </tr>
    <tr>
        <td colspan="14" style="height:15px;"></td>
    </tr>
</table>

<table border="1" style="border-collapse:collapse;">
    <tbody>
        <?php
        $grand_total = 0;
        $grand_karyawan = 0;
        $totals_by_os = [];

        $sqlDept = $koneksi->query("SELECT DISTINCT D.* FROM ms_departmen D 
                                     JOIN tb_realisasi_detail AD ON AD.id_realisasi = '$id'
                                     LEFT JOIN tb_rkk_detail RD ON AD.id_rkk_detail = RD.id_rkk_detail
                                     WHERE D.id_departmen = COALESCE(NULLIF(AD.id_departmen, 0), RD.id_departmen)
                                     ORDER BY D.id_departmen ASC");

        while ($dept = $sqlDept->fetch_assoc()) {
            $id_dept = $dept['id_departmen'];

            echo "<tr>
                    <td colspan='14' align='center' style='background-color:#1e3a8a; color:white; font-weight:bold;'>
                        " . strtoupper($dept['nama_departmen'] ?? '') . "
                    </td>
                  </tr>";

            echo "<tr>
                <th style='background:#e5e7eb;'>No</th>
                <th style='background:#e5e7eb;'>NAMA SESUAI KTP</th>
                <th style='background:#e5e7eb;'>OS/DHK</th>
                <th style='background:#e5e7eb;'>GOLONGAN</th>
                <th style='background:#e5e7eb;'>POSISI</th>
                <th style='background:#e5e7eb;'>JAM KERJA</th>
                <th style='background:#e5e7eb;'>JAM MASUK</th>
                <th style='background:#e5e7eb;'>ISTIRAHAT MASUK</th>
                <th style='background:#e5e7eb;'>JAM PULANG</th>
                <th style='background:#e5e7eb;'>HASIL KERJA</th>
                <th style='background:#e5e7eb;'>UPAH (Rp)</th>
                <th style='background:#e5e7eb;'>POTONGAN (Rp)</th>
                <th style='background:#e5e7eb;'>LEMBUR (Rp)</th>
                <th style='background:#e5e7eb;'>UPAH DIBAYAR (Rp)</th>
            </tr>";

            $tampil = $koneksi->query("SELECT A.*, IF(A.id_karyawan = 0, A.nama_karyawan_manual, B.nama_karyawan) as nama_karyawan, 
                O.OS_DHK as label_os, G.golongan as label_gol, S.nama_sub_department as posisi, J.jam_masuk as j_masuk
                FROM tb_realisasi_detail A 
                LEFT JOIN ms_karyawan B ON A.id_karyawan = B.id_karyawan
                LEFT JOIN tb_rkk_detail RD ON A.id_rkk_detail = RD.id_rkk_detail
                LEFT JOIN ms_os_dhk O ON COALESCE(NULLIF(A.id_os_dhk, 0), B.id_os_dhk) = O.id_os_dhk
                LEFT JOIN ms_golongan G ON COALESCE(NULLIF(A.id_golongan, 0), B.id_golongan) = G.id_golongan
                LEFT JOIN ms_sub_department S ON COALESCE(NULLIF(A.id_sub_department, 0), RD.id_sub_department) = S.id_sub_department
                LEFT JOIN tb_jadwal J ON A.id_jadwal = J.id_jadwal
                WHERE A.id_realisasi = '$id' AND COALESCE(NULLIF(A.id_departmen, 0), RD.id_departmen) = '$id_dept'");

            $no = 1;
            while ($data = $tampil->fetch_assoc()) {
                $total_potongan = ($data['r_potongan_telat'] ?? 0) + ($data['r_potongan_istirahat_awal'] ?? 0) +
                    ($data['r_potongan_istirahat_telat'] ?? 0) + ($data['r_potongan_pulang'] ?? 0) +
                    ($data['r_potongan_tidak_lengkap'] ?? 0) + ($data['r_potongan_lainnya'] ?? 0);

                $upah_dibayar = ($data['r_upah'] ?? 0) - $total_potongan + ($data['lembur'] ?? 0);

                $grand_total += $upah_dibayar;
                $grand_karyawan++;

                $os_label = $data['label_os'] ?: 'LAIN-LAIN';
                $totals_by_os[$os_label] = ($totals_by_os[$os_label] ?? 0) + $upah_dibayar;

                echo "<tr>
                    <td align='center'>$no</td>
                    <td>" . strtoupper($data['nama_karyawan'] ?? '') . "</td>
                    <td align='center'>{$data['label_os']}</td>
                    <td align='center'>{$data['label_gol']}</td>
                    <td>{$data['posisi']}</td>
                    <td align='center'>{$data['j_masuk']}</td>
                    <td align='center'>" . ($data['ra_masuk'] != '00:00:00' ? $data['ra_masuk'] : '') . "</td>
                    <td align='center'>" . ($data['ra_istirahat_masuk'] != '00:00:00' ? $data['ra_istirahat_masuk'] : '') . "</td>
                    <td align='center'>" . ($data['ra_keluar'] != '00:00:00' ? $data['ra_keluar'] : '') . "</td>
                    <td align='center'>{$data['hasil_kerja']}</td>
                    <td align='right' style='mso-number-format:\"\#\,\#\#0\.00\";'>Rp " . number_format($data['r_upah'] ?? 0, 2, '.', ',') . "</td>
                    <td align='right' style='mso-number-format:\"\#\,\#\#0\.00\"; color:red;'>" . ($total_potongan > 0 ? "Rp " . number_format($total_potongan, 2, '.', ',') : "-") . "</td>
                    <td align='right' style='mso-number-format:\"\#\,\#\#0\.00\";'>Rp " . number_format($data['lembur'] ?? 0, 2, '.', ',') . "</td>
                    <td align='right' style='font-weight:bold; mso-number-format:\"\#\,\#\#0\.00\";'>Rp " . number_format($upah_dibayar, 2, '.', ',') . "</td>
                </tr>";
                $no++;
            }
        }
        // GRAND TOTAL UPAH
        echo "<tr><td colspan='14' style='background:#203764; color:white; font-weight:bold; text-align:right; padding:10px;'>GRAND TOTAL UPAH ($grand_karyawan Karyawan) | Rp " . number_format($grand_total, 2, '.', ',') . "</td></tr>";
        ?>
    </tbody>
</table>

<br>
<?php
$total_boneless_final = 0;
$boneless_items = [];
$q_b = $koneksi->query("SELECT * FROM tb_boneless WHERE tgl = '$tanggal_sql'");
$b_head = $q_b->fetch_assoc();
if ($b_head) {
    $q_bd = $koneksi->query("SELECT * FROM tb_boneless_detail WHERE id_boneless = '" . $b_head['id_boneless'] . "'");
    while ($bd = $q_bd->fetch_assoc()) {
        
        // FIX: Gunakan abs() agar terhindar dari double negatif jika angka di database di-input minus
        $nilai_total = abs($bd['total']); 
        
        if (($bd['jenis'] ?? '') == 'minus') {
            $total_boneless_final -= $nilai_total;
        } else {
            $total_boneless_final += $nilai_total;
        }
        $boneless_items[] = $bd;
    }
}
$potong = $b_head['jumlah_mobil'] ?? 0;
$grand_total_all = $grand_total + $total_boneless_final;
$biaya_per_mobil = ($potong > 0) ? ($grand_total_all / $potong) : 0;
$biaya_x_mobil_display = $biaya_per_mobil * $potong;
?>

<table border="0">
    <tr>
        <td width="30"></td>
        <td valign="top">
            <table border="1" style="border-collapse:collapse;">
                <thead>
                    <tr style="background-color: #dbe5f1; font-weight: bold;">
                        <th colspan="2" style="text-align: center;">REALISASI TOTAL REKAP OUTSORCHING  <br> <?php echo date('d F Y', strtotime($tanggal_sql)); ?></th>
                    </tr>
                    <tr style="background-color: #e5e7eb; font-weight: bold;">
                        <th width="150">KATEGORI OS</th>
                        <th width="200">TOTAL UPAH DIBAYAR</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sqlOS = $koneksi->query("SELECT OS_DHK FROM ms_os_dhk ORDER BY id_os_dhk ASC");
                    while ($rowOS = $sqlOS->fetch_assoc()):
                        $label = $rowOS['OS_DHK'];
                        $total_per_label = isset($totals_by_os[$label]) ? $totals_by_os[$label] : 0;
                    ?>
                        <tr>
                            <td style="font-weight: bold;"><?php echo $label; ?></td>
                            <td align="right">
                                Rp <?php echo number_format($total_per_label, 2, '.', ','); ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot>
                    <tr style="background-color: #203764; color: white; font-weight: bold;">
                        <td>GRAND TOTAL</td>
                        <td align="right">Rp <?php echo number_format($grand_total, 2, '.', ','); ?></td>
                    </tr>
                </tfoot>
            </table>
        </td>

        <td width="50"></td>
        <td valign="top">
            <table border="1" style="border-collapse:collapse;">
                <tr style="background-color:#dbe5f1; font-weight:bold;">
                    <th colspan="4">BAYARAN TIM BONELESS</th>
                </tr>
                <?php
                $no_b = 1;
                foreach ($boneless_items as $item) {
                    $is_minus = (($item['jenis'] ?? '') == 'minus');
                    $qty_val = (float)($item['qty'] ?? 0);
                    $qty_disp = ($qty_val == 0) ? "-" : (floor($qty_val) == $qty_val ? number_format($qty_val, 0, '.', ',') : number_format($qty_val, 1, '.', ','));
                    $label_prefix = $is_minus ? "(Pengurangan) " : "";
                    $nilai_tampil = abs($item['total']);

                    echo "<tr>
                        <td align='center'>$no_b</td>
                        <td style='" . ($is_minus ? "color:red;" : "") . "'>{$label_prefix}" . strtoupper($item['nama_item']) . "</td>
                        <td align='center'>$qty_disp</td>
                        <td align='right' style='" . ($is_minus ? "color:red;" : "") . "'>Rp " . (floor($nilai_tampil) == $nilai_tampil ? number_format($nilai_tampil, 0, '.', ',') : number_format($nilai_tampil, 2, '.', ',')) . "</td>
                    </tr>";
                    $no_b++;
                }
                ?>
                <tr style="font-weight:bold;">
                    <td colspan="3" align="center">TOTAL AKHIR BONELESS</td>
                    <td align="right" style="color: <?php echo ($total_boneless_final < 0) ? 'red' : '#008000'; ?>;">
                        Rp <?php
                            // Dynamic decimal formatting: Hide .00 for integers
                            $val_bone = abs($total_boneless_final);
                            $fmt_bone = (floor($val_bone) == $val_bone) ? number_format($val_bone, 0, '.', ',') : number_format($val_bone, 2, '.', ',');
                            // Tulisan (minus) dihapus, hanya menyisakan tanda minus "-"
                            echo ($total_boneless_final < 0) ? "- " . $fmt_bone : $fmt_bone;
                            ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<br><br>

<table border="0">
    <tr>
        <td width="30"></td>
        <td valign="top">
            <table border="1" style="border-collapse:collapse;">
                <tr style="background-color:#dbe5f1; font-weight:bold;">
                    <th colspan="2">REALISASI TOTAL REKAP BIAYA PABRIK <br> <?php echo date('d F Y', strtotime($tanggal_sql)); ?></th>
                </tr>
                <tr style="font-weight:bold;">
                    <td>Biaya <?php echo (int)$potong; ?> mobil</td>
                    <td align="right">Rp <?php echo number_format($biaya_x_mobil_display, 2, '.', ','); ?></td>
                </tr>
                <tr style="font-weight:bold;">
                    <td>Biaya permobil</td>
                    <td align="right">Rp <?php echo number_format($biaya_per_mobil, 2, '.', ','); ?></td>
                </tr>
            </table>
        </td>

        <td width="50"></td>
        <td valign="top">
            <table border="1" style="border-collapse:collapse;">
                <tr style="background-color:yellow; font-weight:bold; text-align:center;">
                    <th>BIAYA PABRIK</th>
                    <th>BONELESS</th>
                    <th>POTONG</th>
                    <th>TOTAL</th>
                    <th>Biaya Per mobil</th>
                </tr>
                <tr style="font-weight:bold; text-align:right;">
                    <td align="center">Rp <?php echo number_format($grand_total, 2, '.', ','); ?></td>
                    <td align="center" style="color: <?php echo ($total_boneless_final < 0) ? 'red' : 'black'; ?>;">
                        Rp <?php
                            // Dynamic decimal formatting: Hide .00 for integers
                            $val_bone_kuning = abs($total_boneless_final);
                            $fmt_bone_kuning = (floor($val_bone_kuning) == $val_bone_kuning) ? number_format($val_bone_kuning, 0, '.', ',') : number_format($val_bone_kuning, 2, '.', ',');
                            // Tulisan (minus) dihapus, hanya menyisakan tanda minus "-"
                            echo ($total_boneless_final < 0) ? "- " . $fmt_bone_kuning : $fmt_bone_kuning;
                            ?>
                    </td>
                    <td align="center"><?php echo (int)$potong; ?></td>
                    <td align="center">Rp <?php echo number_format($grand_total_all, 2, '.', ','); ?></td>
                    <td align="center" style="background-color:white;">Rp <?php echo number_format($biaya_per_mobil, 2, '.', ','); ?></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<br>

<table border="0">
    <tr>
        <td width="30"></td> <td>
            <table border="1" style="border-collapse:collapse; border: 4px solid <?php echo $stamp_color; ?>;">
                <tr>
                    <td align="center" style="font-size:24px; font-weight:bold; color:<?php echo $stamp_color; ?>; padding:5px 30px;">
                        <?php echo $stamp_text; ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>