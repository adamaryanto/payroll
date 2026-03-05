<?php
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = $koneksi->query("DELETE FROM ms_status_kawin WHERE id_status_kawin = '$id'");
    echo "<script>alert('Terhapus'); window.location='?page=statuskawin';</script>";
}
?>
