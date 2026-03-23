<?php
include "koneksi.php";
$q = $koneksi->query("SELECT * FROM tb_jadwal");
while ($row = $q->fetch_assoc()) {
    print_r($row);
}
?>
