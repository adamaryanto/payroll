<?php
include 'koneksi.php';
$koneksi->query("ALTER TABLE ms_karyawan DROP COLUMN upah_harian");
$koneksi->query("ALTER TABLE ms_karyawan DROP COLUMN upah_mingguan");
$koneksi->query("ALTER TABLE ms_karyawan DROP COLUMN upah_bulanan");
echo "Columns upah_harian, upah_mingguan, upah_bulanan dropped.\n";
?>
