<?php

    $id = $_GET ['id'];
    $status = $_GET ['iddetail'];

if($status == "pro"){
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

