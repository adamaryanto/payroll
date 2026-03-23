<?php
include "koneksi.php";
$q = $koneksi->query("SELECT id_realisasi_detail, ra_masuk, ra_keluar, status_realisasi_detail 
                      FROM tb_realisasi_detail 
                      WHERE status_realisasi_detail = 0 
                      LIMIT 20");
echo "ID | RA_MASUK | RA_KELUAR | STATUS\n";
while ($row = $q->fetch_assoc()) {
    echo $row['id_realisasi_detail'] . " | " . $row['ra_masuk'] . " | " . $row['ra_keluar'] . " | " . $row['status_realisasi_detail'] . "\n";
}
?>
