<?php
$id = $_GET['id'];
$status = $_GET['iddetail'] ?? '';
$tgl = date("Y-m-d H:i:s");

// Direct Approval system with numeric status
if ($status == "unapp" || $status == "unpro") {
    $sql = $koneksi->query("UPDATE tb_realisasi SET status_realisasi = 0, tgl_status = '$tgl' WHERE id_realisasi = '$id'");
} elseif ($status == "pro") {
    $sql = $koneksi->query("UPDATE tb_realisasi SET status_realisasi = 1, tgl_status = '$tgl' WHERE id_realisasi = '$id'");
} elseif ($status == "app") {
    $sql = $koneksi->query("UPDATE tb_realisasi SET status_realisasi = 2, tgl_status = '$tgl' WHERE id_realisasi = '$id'");
} else {
    // Default fallback to Approve (2)
    $sql = $koneksi->query("UPDATE tb_realisasi SET status_realisasi = 2, tgl_status = '$tgl' WHERE id_realisasi = '$id'");
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
