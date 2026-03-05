<?php
include "koneksi.php";
$tables = ['tb_jadwal', 'ms_sub_department'];
$res = [];
foreach($tables as $t) {
    $q = $koneksi->query("SHOW COLUMNS FROM $t");
    $cols = [];
    while($r = $q->fetch_assoc()) {
        $cols[] = $r;
    }
    $res[$t] = $cols;
}
file_put_contents('schema_tables.json', json_encode($res, JSON_PRETTY_PRINT));
echo "Done";
