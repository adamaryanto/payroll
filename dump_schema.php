<?php
include "koneksi.php";
$query = $koneksi->query("SHOW COLUMNS FROM tb_realisasi_detail");
$output = "";
while ($row = $query->fetch_assoc()) {
    $output .= $row['Field'] . " (" . $row['Type'] . ")\n";
}
file_put_contents("schema_output.txt", $output);
?>
