<?php

    $id = $_GET ['id'];


 $sql = $koneksi->query("DELETE FROM ms_departmen WHERE id_departmen = '$id'");
    if($sql) {
        ?>
        <script type="text/javascript">
            alert("Data Berhasil Di Hapus");
            window.location.href="?page=bagian";
        </script>
        <?php
    }

?>

