<?php
include "koneksi.php";
$res = $koneksi->query("DESC ms_karyawan");
while($r = $res->fetch_assoc()) {
    echo $r['Field'] . " (" . $r['Type'] . ")\n";
}
?>
