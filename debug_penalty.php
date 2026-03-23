<?php
include "koneksi.php";

$output = "--- TB_DENDA ---\n";
$qDenda = $koneksi->query("SELECT * FROM tb_denda LIMIT 1");
$dDenda = $qDenda->fetch_assoc();
$output .= print_r($dDenda, true);

$output .= "\n--- SAMPLE REALISASI_DETAIL (Last 15) ---\n";
$q = $koneksi->query("SELECT RD.id_realisasi_detail, RD.ra_masuk, J.jam_masuk as shift_masuk, RD.status_realisasi_detail, RD.r_potongan_telat, RD.id_realisasi
                      FROM tb_realisasi_detail RD
                      LEFT JOIN tb_jadwal J ON RD.id_jadwal = J.id_jadwal
                      ORDER BY id_realisasi_detail DESC LIMIT 15");
while ($row = $q->fetch_assoc()) {
    $ra = $row['ra_masuk'];
    $sm = $row['shift_masuk'];
    
    $t_ra = strtotime($ra);
    $t_sm = strtotime($sm);
    
    $isLate = ($t_ra > $t_sm) ? "YES" : "NO";
    
    $output .= "ID: {$row['id_realisasi_detail']} (RealID: {$row['id_realisasi']}) |\n";
    $output .= "  RA: [$ra] (" . ($t_ra ? date('Y-m-d H:i:s', $t_ra) : 'FAIL') . ")\n";
    $output .= "  SM: [$sm] (" . ($t_sm ? date('Y-m-d H:i:s', $t_sm) : 'FAIL') . ")\n";
    $output .= "  isLate: $isLate | Status: {$row['status_realisasi_detail']} | Stored Pot: {$row['r_potongan_telat']}\n";
    $output .= "------------------\n";
}
file_put_contents("debug_penalty_output.txt", $output);
?>
