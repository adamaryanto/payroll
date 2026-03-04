<?php
include "koneksi.php";

function dumpTable($koneksi, $table) {
    echo "TABLE: $table\n";
    $result = $koneksi->query("DESCRIBE $table");
    if($result) {
        while ($row = $result->fetch_assoc()) {
            echo "  {$row['Field']} - {$row['Type']}\n";
        }
    } else {
        echo "  [Not found or error]\n";
    }
    echo "\n";
}

$tables = ['ms_karyawan', 'ms_jasa', 'ms_department', 'ms_departmen', 'tb_jadwal', 'ms_osdhk'];
foreach ($tables as $t) {
    dumpTable($koneksi, $t);
}
?>
