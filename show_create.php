<?php
include 'koneksi.php';
$q = $koneksi->query("SHOW CREATE TABLE ms_karyawan");
$r = $q->fetch_row();
echo $r[1];
?>
