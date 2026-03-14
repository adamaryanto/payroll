<?php

    $id = $_GET ['id'];
    $status = $_GET ['iddetail'];

if($status == "pro"){
    // Validasi Boneless: Ambil tgl_rkk dari RKK
    $cek_rkk = $koneksi->query("SELECT tgl_rkk FROM tb_rkk WHERE id_rkk = '$id'");
    $data_rkk = $cek_rkk->fetch_assoc();
    $tgl_rkk = $data_rkk['tgl_rkk'];

    // Cek apakah ada data Boneless untuk tanggal tersebut
    $cek_boneless = $koneksi->query("SELECT id_boneless FROM tb_boneless WHERE tgl = '$tgl_rkk'");
    
    if ($cek_boneless->num_rows == 0) {
        // Jika tidak ada data Boneless, batalkan proses
        ?>
        <script type="text/javascript">
            alert("Tidak bisa propose data! Silakan lengkapi data Boneless untuk tanggal <?= date('d/m/Y', strtotime($tgl_rkk)) ?> terlebih dahulu.");
            window.location.href="?page=rkk";
        </script>
        <?php
        exit;
    }

    $sql =   $koneksi->query("update tb_rkk set status_rkk = 1 where id_rkk = '$id' ");
    if($sql) {
        ?>
                <script type="text/javascript">
                alert("Status Berhasil Di Perbarui");
                window.location.href="?page=rkk";

            </script>
            <?php
    }
}elseif ($status == "app") {
    $sql =   $koneksi->query("update tb_rkk set status_rkk = 2 where id_rkk = '$id' ");
    if($sql) {
        ?>
                <script type="text/javascript">
                alert("Status Berhasil Di Perbarui");
                window.location.href="?page=rkk";

            </script>
            <?php
    }
    
}elseif ($status == "unpro") {
    $sql =   $koneksi->query("update tb_rkk set status_rkk = 0 where id_rkk = '$id' ");
    if($sql) {
        ?>
                <script type="text/javascript">
                alert("Status Berhasil Di Perbarui");
                window.location.href="?page=rkk";

            </script>
            <?php
    }
    
}elseif ($status == "unapp") {
    $sql =   $koneksi->query("update tb_rkk set status_rkk = 1 where id_rkk = '$id' ");
    if($sql) {
        ?>
                <script type="text/javascript">
                alert("Status Berhasil Di Perbarui");
                window.location.href="?page=rkk";

            </script>
            <?php
    }
    
}

 

?>

