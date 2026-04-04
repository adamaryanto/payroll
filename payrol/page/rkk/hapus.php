<?php

    $id = $_GET ['id'];
    $iddetail = $_GET ['iddetail'];


 $sql =   $koneksi->query("delete from tb_rkk_detail where id_rkk_detail = '$iddetail' ");
    if($sql) {
        ?>
                <script type="text/javascript">
                alert("Data Berhasil Di Hapus");
                window.location.href="?page=rkk&aksi=kelola&id=<?php echo $id; ?>";

            </script>
            <?php
    }

?>

