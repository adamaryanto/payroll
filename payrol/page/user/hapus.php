<?php

    $id = $_GET ['id'];


 $sql =   $koneksi->query("delete from ms_login where id_login = '$id' ");
    if($sql) {
        ?>
                <script type="text/javascript">
                alert("Data Berhasil Di Hapus");
                window.location.href="?page=user";

            </script>
            <?php
    }

?>

