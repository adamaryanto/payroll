<?php
include 'koneksi.php';

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
        // First, check if it already has auto_increment
        $check = $koneksi->query("SHOW COLUMNS FROM $table WHERE Field = '$pk'")->fetch_assoc();
        if (strpos($check['Extra'], 'auto_increment') !== false) {
            echo "Already OK\n";
            continue;
        }

        // Try to add auto_increment
        // If it's already PRI, we just modify.
        $res = $koneksi->query("ALTER TABLE $table MODIFY $pk INT AUTO_INCREMENT");
        if ($res) {
            echo "OK (Modify)\n";
        } else {
            // If modify fails, try to drop and re-add PK (caution: might break FK)
            $koneksi->query("ALTER TABLE $table DROP PRIMARY KEY");
            $res2 = $koneksi->query("ALTER TABLE $table MODIFY $pk INT AUTO_INCREMENT PRIMARY KEY");
            if ($res2) {
                echo "OK (Re-add PK)\n";
            } else {
                echo "FAILED: " . $koneksi->error . "\n";
            }
        }
    } catch (Exception $e) {
        echo "EXCEPTION: " . $e->getMessage() . "\n";
    }
}
?>
