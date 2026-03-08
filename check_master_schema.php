<?php
include 'koneksi.php';
$tables = ['ms_departmen', 'ms_sub_department', 'ms_jabatan', 'ms_os_dhk', 'ms_golongan', 'ms_agama', 'ms_status_kawin'];
foreach ($tables as $t) {
    echo "--- TABLE: $t ---\n";
    $q = $koneksi->query("DESCRIBE $t");
    while($r = $q->fetch_assoc()) {
        printf("%-20s %-20s %-10s %-10s %-20s\n", $r['Field'], $r['Type'], $r['Null'], $r['Key'], $r['Extra']);
    }
}
?>
