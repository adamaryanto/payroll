<?php
$id = $_GET['id'];

// Hapus Detail Terlebih Dahulu
$koneksi->query("DELETE FROM tb_boneless_detail WHERE id_boneless = '$id'");

// Hapus Header
$sql = $koneksi->query("DELETE FROM tb_boneless WHERE id_boneless = '$id'");

if ($sql) {
    ?>
    <script type="text/javascript">
        alert("Data Berhasil Dihapus");
        window.location.href = "?page=boneless";
    </script>
    <?php
}
?>
