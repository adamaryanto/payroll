<?php
$id = $_GET['id'];
$sql = $koneksi->query("DELETE FROM ms_upah WHERE id_upah = '$id'");
if ($sql) {
?>
<script type="text/javascript">
    alert("Data Berhasil Di Hapus");
    window.location.href = "?page=upah";
</script>
<?php
}
?>
