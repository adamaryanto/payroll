<?php

    $id = $_GET ['id'];


 $sql =   $koneksi->query("delete from tb_pelanggan where id_pelanggan = '$id' ");
    if($sql) {
        ?>
                <script type="text/javascript">
                alert("Data Berhasil Di Hapus");
                window.location.href="?page=pelanggan";

            </script>
            <?php
    }

?>

