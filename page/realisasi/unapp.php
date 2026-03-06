<?php
    $id = $_GET['id'];
    $sql = $koneksi->query("UPDATE tb_realisasi SET status_realisasi = 'pending' WHERE id_realisasi = '$id'");
    
    if($sql) {
        ?>
        <script type="text/javascript">
            alert("Status Berhasil Di Revert (Pending)");
            window.location.href="?page=realisasi";
        </script>
        <?php
    }
?>
