<?php
$id = $_GET['id'];
$status = $_GET['iddetail'] ?? '';
$tgl = date("Y-m-d H:i:s");

if ($status == "pro") {
    $sql = $koneksi->query("UPDATE tb_realisasi SET status_realisasi = 'propose', tgl_status = '$tgl' WHERE id_realisasi = '$id'");
} elseif ($status == "app") {
    $sql = $koneksi->query("UPDATE tb_realisasi SET status_realisasi = 'approve', tgl_status = '$tgl' WHERE id_realisasi = '$id'");
} elseif ($status == "unpro") {
    $sql = $koneksi->query("UPDATE tb_realisasi SET status_realisasi = 'pending', tgl_status = '$tgl' WHERE id_realisasi = '$id'");
} elseif ($status == "unapp") {
    $sql = $koneksi->query("UPDATE tb_realisasi SET status_realisasi = 'propose', tgl_status = '$tgl' WHERE id_realisasi = '$id'");
} else {
    // Default fallback to old behavior if iddetail is missing (for backward compatibility during transition)
    $sql = $koneksi->query("UPDATE tb_realisasi SET status_realisasi = 'approve', tgl_status = '$tgl' WHERE id_realisasi = '$id'");
}

if ($sql) {
    echo '<script type="text/javascript">
            alert("Status Berhasil Di Perbarui");
            window.location.href="?page=realisasi";
          </script>';
}
?>
