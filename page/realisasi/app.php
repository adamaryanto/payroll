<?php
$id = $_GET['id'];
$status = $_GET['iddetail'] ?? '';
$tgl = date("Y-m-d H:i:s");

// Reverted to direct Approval system
if ($status == "unapp") {
    $sql = $koneksi->query("UPDATE tb_realisasi SET status_realisasi = 'pending', tgl_status = '$tgl' WHERE id_realisasi = '$id'");
} else {
    // Default to 'approve' for backward compatibility and direct approval
    $sql = $koneksi->query("UPDATE tb_realisasi SET status_realisasi = 'approve', tgl_status = '$tgl' WHERE id_realisasi = '$id'");
}

if ($sql) {
    echo '<script type="text/javascript">
            alert("Status Berhasil Di Perbarui");
            window.location.href="?page=realisasi";
          </script>';
}
?>
