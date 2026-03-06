<?php
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = $koneksi->query("DELETE FROM ms_os_dhk WHERE id_os_dhk = '$id'");
    echo "<script>alert('Terhapus'); window.location='?page=os_dhk';</script>";
}
?>
