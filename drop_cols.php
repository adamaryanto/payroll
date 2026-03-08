<?php
include 'koneksi.php';
$koneksi->query("ALTER TABLE ms_karyawan DROP COLUMN no_bpjs");
$koneksi->query("ALTER TABLE ms_karyawan DROP COLUMN no_npwp");
echo "Columns no_bpjs and no_npwp dropped from ms_karyawan.";
?>
