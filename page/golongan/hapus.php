<?php
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = $koneksi->query("DELETE FROM ms_golongan WHERE id_golongan = '$id'");
    echo "<script>alert('Terhapus'); window.location='?page=golongan';</script>";
}
?>
