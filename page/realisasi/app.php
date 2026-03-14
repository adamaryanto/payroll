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
    echo '<!DOCTYPE html>
    <html>
    <head>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: "success",
                title: "Berhasil",
                text: "Status Berhasil Di Perbarui",
                confirmButtonColor: "#2563eb",
                confirmButtonText: "OK"
            }).then((result) => {
                window.location.href = "?page=realisasi";
            });
        </script>
    </body>
    </html>';
    exit;
}
?>
