<?php
$k = new mysqli('localhost', 'root', '', 'db_hr');
if ($k->connect_error) die($k->connect_error);

echo "--- Karyawan ---\n";
$res = $k->query("SELECT id_karyawan, nama_karyawan FROM ms_karyawan LIMIT 2");
while($row = $res->fetch_assoc()) {
    echo "ID: " . $row['id_karyawan'] . " | Name: " . $row['nama_karyawan'] . "\n";
}

echo "\n--- Realisasi ---\n";
$res2 = $k->query("SELECT id_realisasi FROM tb_realisasi LIMIT 2");
while($row2 = $res2->fetch_assoc()) {
    echo "ID: " . $row2['id_realisasi'] . "\n";
}

echo "\n--- Structure tb_realisasi_detail ---\n";
$res3 = $k->query("DESC tb_realisasi_detail");
while($row3 = $res3->fetch_assoc()) {
    echo $row3['Field'] . " (" . $row3['Type'] . ") | Null: " . $row3['Null'] . " | Key: " . $row3['Key'] . "\n";
}
?>
