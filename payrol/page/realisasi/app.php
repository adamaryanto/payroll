<?php

    $id = $_GET ['id'];
$ttgl2 = date("Y-m-d H:i:s");
    $sql =   $koneksi->query("update tb_realisasi set status_realisasi = 1 , tgl_status= '$ttgl2' where id_realisasi = '$id' ");
    if($sql) {
        ?>
                <script type="text/javascript">
                alert("Status Berhasil Di Perbarui");
                window.location.href="?page=realisasi";

            </script>
            <?php
    }


 

?>

