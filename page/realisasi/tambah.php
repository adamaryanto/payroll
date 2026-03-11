

<?php
 $id = $_GET ['id'];
$ttgl1 = date("Y-m-d");
$ttgl2 = date("Y-m-d H:i:s");



   $koneksi->query("insert into tb_realisasi (id_rkk, tgl_realisasi, jam_kerja, detail_realisasi, keterangan, tgl_status, status_realisasi) 
                    select id_rkk, tgl_rkk, jam_kerja, '$ttgl2', '', CURDATE(), 'pending' 
                    from tb_rkk where id_rkk = '$id' ");

$iddetail = $koneksi->insert_id;

$tampil_tgl = $koneksi->query("SELECT tgl_realisasi FROM tb_realisasi WHERE id_realisasi = '$iddetail'");
$data_tgl = $tampil_tgl->fetch_assoc();
$tglreal = $data_tgl['tgl_realisasi'];

    $sql =  $koneksi->query("insert into tb_realisasi_detail (id_realisasi,id_rkk_detail,id_rkk,id_karyawan,r_upah,r_jam_masuk,r_jam_keluar,r_istirahat_masuk,r_istirahat_keluar,id_jadwal,tgl_realisasi_detail) 
        select '$iddetail', A.id_rkk_detail, A.id_rkk, A.id_karyawan, A.upah, 
               COALESCE(B.jam_masuk, '00:00:00'), 
               COALESCE(B.jam_keluar, '00:00:00'), 
               COALESCE(B.istirahat_masuk, '00:00:00'), 
               COALESCE(B.istirahat_keluar, '00:00:00'), 
               A.id_jadwal, '$tglreal' 
        from tb_rkk_detail A
        LEFT JOIN tb_jadwal B ON A.id_jadwal = B.id_jadwal
        where A.id_rkk = '$id' ");
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

