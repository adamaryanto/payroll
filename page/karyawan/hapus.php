<?php

    $id = $_GET ['id'];


 $sql =   $koneksi->query("delete from ms_karyawan where id_karyawan = '$id' ");
    if($sql) {
        ?>
                <script type="text/javascript">
                alert("Data Berhasil Di Hapus");
                window.location.href="?page=karyawan";

            </script>
            <?php
    }

?>

