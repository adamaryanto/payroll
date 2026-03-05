<?php
include "koneksi.php";
$cols = ['agama', 'status_kawin', 'OS_DHK', 'golongan'];
$res = [];
foreach($cols as $c) {
    $q = $koneksi->query("SHOW COLUMNS FROM ms_karyawan LIKE '$c'");
    $res[$c] = $q->fetch_assoc();
}
file_put_contents('schema_dump.json', json_encode($res, JSON_PRETTY_PRINT));
echo "Done";
