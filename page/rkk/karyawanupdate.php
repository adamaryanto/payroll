<?php

if(isset($_GET['id'])){
   $idrkkdetail = $_GET['id'];

  $tampildetail=$koneksi->query("select * from tb_rkk_detail where id_rkk_detail = '$idrkkdetail' ");
$datadetail=$tampildetail->fetch_assoc();
$idrkk = $datadetail['id_rkk'];
$idrkkkaryawan = $datadetail['id_karyawan'];

 }else{$idrkkdetail = "";$idrkk = "";$idrkkkaryawan="";}

   ?>

<div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-primary"  >
                    <div class="box-header with-border" style=" background-color:#5F9EA0; border:1px ; color:white; ">
              <h3 class="box-title">Kelola Data Karyawan Update </h3>
            </div>
             <form method="POST"  enctype="multipart/form-data">
                        <div class="panel-body">
                           
                      
              
            <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                  
                </div>
<input type="submit" name="simpan"  value="Simpan" class="btn btn-primary">
                
                                    </form>
                                    <div class="form-group "></div>

                            <div class="table-responsive">
                                
                                <table class="table table-bordered table-striped" id="dataTables-example">
                                    <thead >
                                        <tr>
                                          <th width="5%">Pilih</th>
                                           <th hidden="hidden" >ID Karyawan</th>
                                            <th >No. Absen</th>
                                           <th >Nama </th>
                                           <th >Bagian</th>

                                        
                                        <th >Jenis Kelamin</th> 
                                          
                                         
                                           <th >Tanggal Aktif </th>
                                             <th >Upah Harian</th>
                                             <th hidden="hidden">Upah Harian</th>
                                              <th >Status</th>
                                           
                         
                                        </tr>
                                    </thead>
                                    <tbody>
                                                                <?php


$no = 0;


$tampil = $koneksi->query("SELECT ms_karyawan.*, ms_departmen.nama_departmen 
FROM ms_karyawan 
LEFT JOIN tb_rkk_detail ON tb_rkk_detail.id_karyawan = ms_karyawan.id_karyawan AND tb_rkk_detail.id_rkk = '$idrkk'
LEFT JOIN tb_rkk ON tb_rkk_detail.id_rkk = tb_rkk.id_rkk
LEFT JOIN ms_departmen ON tb_rkk.id_departmen = ms_departmen.id_departmen 
WHERE ms_karyawan.status_karyawan = 'Aktif'");
    while ($datakaryawan=$tampil->fetch_assoc())
    {

        $id = $datakaryawan['id_karyawan'] ;



  $tampildetail2=$koneksi->query("select * from tb_rkk_detail where id_rkk = '$idrkk' and id_karyawan = '$id' ");
$datadetail2=$tampildetail2->fetch_assoc();
$datahasil2 = $datadetail2['status_rkk'];

?>


                                        <tr>

<td><input type="radio"
<?php
if($datahasil2 == "Hadir"){echo "hidden";}elseif($datahasil2 == "Digantikan"){echo "hidden";}elseif($datahasil2 == "Pengganti"){echo "hidden";}
?>

  name="ck[]" value="<?php echo $no ; ?>" /></td>

<td hidden="hidden"><input type="text" name="tidkaryawan[]" value="<?php echo $datakaryawan['id_karyawan'] ; ?>"/></td>
<td><?php echo $datakaryawan['no_absen'] ?></td>

<td><?php echo $datakaryawan['nama_karyawan'] ?></td>
<td><?php echo $datakaryawan['nama_departmen'] ?></td>

<td><?php echo $datakaryawan['jenis_kelamin'] ?></td>

<td><?php echo $datakaryawan['tgl_aktif'] ?></td>

<td><?php echo number_format( $datakaryawan['upah_harian'],0,',','.')  ?></td>
<td hidden="hidden"><input type="text" name="tupah[]" value="<?php echo $datakaryawan['upah_harian'] ; ?>"/></td>
<td><?php echo $datahasil2 ?></td>

<!--
<td>
    
<a  href="?page=order&id=<?php echo $data['id_transaksi'];?>"  class="btn btn-success"> Update</a>


</td>
-->
                                      
                                            
                                        </tr>

                                       <?php  $no++; } ?>

                                    </tbody>   
                                    </table>
                            </div>

                       

                    </div>
                </div>
        </div>
    </div>
  
    <script>
 $(document).ready( function () {
$('#dataTables-example').DataTable({
    scrollY: true,
    
    scrollY: 400,
    pageLength: 1000,
    "searching": true
}
);

} );
   


</script>

<?php
  
$simpan = @$_POST ['simpan'];
if($simpan) {


$tidkaryawan =$_POST['tidkaryawan'];
$tupah = $_POST['tupah'];


if(!empty($_POST['ck'])){
foreach ($_POST['ck'] as $cek) {
 

$idkaryawan = $tidkaryawan[$cek];
$upah = $tupah[$cek];




  $koneksi->query("insert into tb_rkk_detail (id_rkk,id_karyawan,upah,status_rkk) values('$idrkk','$idkaryawan','$upah','Pengganti') ");
  $koneksi->query("update tb_rkk_detail set status_rkk = 'Digantikan' where id_rkk_detail = '$idrkkdetail' ");

    $koneksi->query("insert into tb_rkk_update (id_rkk_detail,id_karyawan,status) values('$idrkkdetail','$idkaryawan','Pengganti') ");
    $koneksi->query("insert into tb_rkk_update (id_rkk_detail,id_karyawan,status) values('$idrkkdetail','$idrkkkaryawan','Digantikan') ");

   
}
 ?>
                <script type="text/javascript">
                alert("Data Tersimpan");
                window.location.href="?page=rkk";

            </script>
            <?php


 
}else{
 ?>
                <script type="text/javascript">
                alert("Tidak Ada Data Yang Dipilih");

            </script>
            <?php

}

 


}//simpan if


?>