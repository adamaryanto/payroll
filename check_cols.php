<?php
include 'koneksi.php';
$q = $koneksi->query("DESCRIBE ms_karyawan");
while($r = $q->fetch_assoc()) echo $r['Field'] . "\n";
?>
