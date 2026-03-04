<?php

    $id = $_GET ['id'];


 $sql =   $koneksi->query("delete from tb_type_produk where id_type_produk = '$id' ");
    if($sql) {
        ?>
                <script type="text/javascript">
                alert("Data Berhasil Di Hapus");
                window.location.href="?page=typeproduk";

            </script>
            <?php
    }

?>

