

<?php
 $id = $_GET ['id'];
$ttgl1 = date("Y-m-d");
$ttgl2 = date("Y-m-d H:i:s");



   $koneksi->query("insert into tb_realisasi (id_rkk, tgl_realisasi, jam_kerja, detail_realisasi, keterangan, tgl_status, status_realisasi) 
                    select id_rkk, tgl_rkk, jam_kerja, '$ttgl2', '', CURDATE(), 'pending' 
                    from tb_rkk where id_rkk = '$id' ");

$tampil=$koneksi->query("sELECT * from tb_realisasi WHERE id_rkk = '$id' ");
$data=$tampil->fetch_assoc();
$iddetail = $data['id_realisasi'];
$tglreal = $data['tgl_realisasi'];

    $sql =  $koneksi->query("insert into tb_realisasi_detail (id_realisasi,id_rkk_detail,id_rkk,id_karyawan,r_upah,r_jam_masuk,r_jam_keluar,r_istirahat_masuk,r_istirahat_keluar,id_jadwal,tgl_realisasi_detail) select '$iddetail', id_rkk_detail,id_rkk,id_karyawan,upah,jam_masuk,jam_keluar,istirahat_masuk,istirahat_keluar,id_jadwal,'$tglreal' from tb_rkk_detail where id_rkk = '$id' ");
$koneksi->query("update tb_rkk set status_rkk = 3 , tgl_status = '$ttgl2' where id_rkk = '$id' ");


if($sql) {
        ?>
                <script type="text/javascript">
                alert("Data Tersimpan");
                window.location.href="?page=realisasi";

            </script>
            <?php
    }


?>

