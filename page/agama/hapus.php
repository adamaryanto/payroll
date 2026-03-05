<?php
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = $koneksi->query("DELETE FROM ms_agama WHERE id_agama = '$id'");
    echo "<script>alert('Terhapus'); window.location='?page=agama';</script>";
}
?>
