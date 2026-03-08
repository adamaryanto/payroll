<?php
include 'koneksi.php';
$koneksi->query("ALTER TABLE ms_karyawan DROP COLUMN no_sim");
echo "Column no_sim dropped from ms_karyawan.\n";
?>
