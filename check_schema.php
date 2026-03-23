<?php
include "koneksi.php";
$q = $koneksi->query("DESC tb_realisasi_detail");
while ($row = $q->fetch_assoc()) {
    echo $row['Field'] . " (" . $row['Type'] . ")\n";
}
?>
