<?php
include 'koneksi.php';
$koneksi->query("SET sql_mode = ''");

$fixes = [
    'ms_departmen' => 'id_departmen',
    'ms_sub_department' => 'id_sub_department',
    'ms_jabatan' => 'id_jabatan',
    'ms_os_dhk' => 'id_os_dhk',
    'ms_golongan' => 'id_golongan',
    'ms_agama' => 'id_agama',
    'ms_status_kawin' => 'id_status_kawin',
    'ms_upah' => 'id_upah',
    'ms_karyawan' => 'id_karyawan'
];

foreach ($fixes as $table => $pk) {
    echo "Fixing $table ($pk)... ";
    try {
        // Check if PK exists
        $q = $koneksi->query("SHOW INDEX FROM $table WHERE Key_name = 'PRIMARY'");
        if ($q->num_rows == 0) {
            echo "(Adding PK) ";
            $koneksi->query("ALTER TABLE $table ADD PRIMARY KEY ($pk)");
        }
        
        // 2. Try to add auto_increment
        $res = $koneksi->query("ALTER TABLE $table MODIFY $pk INT AUTO_INCREMENT");
        
        if ($res) {
            echo "OK\n";
        } else {
            echo "FAILED: " . $koneksi->error . "\n";
        }
    } catch (Exception $e) {
        echo "EXCEPTION: " . $e->getMessage() . "\n";
    }
}
?>
