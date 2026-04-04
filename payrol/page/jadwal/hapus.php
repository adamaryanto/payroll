<?php

    $id = $_GET ['id'];


 $sql =   $koneksi->query("delete from tb_jadwal where id_jadwal = '$id' ");
    if($sql) {
        ?>
                <script type="text/javascript">
                alert("Data Berhasil Di Hapus");
                window.location.href="?page=jadwal";

            </script>
            <?php
    }

?>

