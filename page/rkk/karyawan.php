<?php

if(isset($_GET['id'])){
   $idrkk = $_GET['id'];
 }else{$idrkk = "";}

   ?>

<div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-primary"  >
                    <div class="box-header with-border" style=" background-color:#5F9EA0; border:1px ; color:white; ">
              <h3 class="box-title">Kelola Data Karyawan</h3>
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
                                             <th hidden="hidden">Upah Harian</th>
                                             <th hidden="hidden">Upah Harian</th>
                                              <th hidden="hidden">Status</th>
                                              <th hidden="hidden">Kelola</th>
                                              <th hidden="hidden">History</th>
                         
                                        </tr>
                                    </thead>
                                    <tbody>
                                                                <?php


$no = 0;


$tampil = $koneksi->query("SELECT ms_karyawan.* , ms_departmen.nama_departmen FROM ms_karyawan LEFT JOIN ms_departmen on ms_karyawan.id_departmen = ms_departmen.id_departmen  where ms_karyawan.status_karyawan = 'Aktif'
  AND NOT EXISTS (SELECT 1 from tb_rkk_detail WHERE id_rkk = '$idrkk' and tb_rkk_detail.id_karyawan = ms_karyawan.id_karyawan )
 ");
    while ($datakaryawan=$tampil->fetch_assoc())
    {

        $id = $datakaryawan['id_karyawan'] ;

 


?>


                                        <tr>

<td><input type="checkbox"


  name="ck[]" value="<?php echo $no ; ?>" /></td>

<td hidden="hidden"><input type="text" name="tidkaryawan[]" value="<?php echo $datakaryawan['id_karyawan'] ; ?>"/></td>
<td><?php echo $datakaryawan['no_absen'] ?></td>

<td><?php echo $datakaryawan['nama_karyawan'] ?></td>
<td><?php echo $datakaryawan['nama_departmen'] ?></td>

<td><?php echo $datakaryawan['jenis_kelamin'] ?></td>

<td><?php echo $datakaryawan['tgl_aktif'] ?></td>

<td hidden="hidden"><?php echo number_format( $datakaryawan['upah_harian'],0,',','.')  ?></td>
<td hidden="hidden"><input type="text" name="tupah[]" value="<?php echo $datakaryawan['upah_harian'] ; ?>"/></td>
<td hidden="hidden"><?php echo $datahasil2 ?></td>
<td hidden="hidden">
  <div
<?php
if($datahasil2 == "Hadir"){echo "checked";}elseif($datahasil2=="Pengganti"){echo "checked";}else{echo "hidden";}
?>
  ><a  href="?page=rkk&aksi=update&id=<?php echo $datadetail2['id_rkk_detail'];?>"  class="btn btn-warning"> Ganti</a></div>
    
</td>

<td hidden="hidden">
  <div
<?php
if($datahasil2=="Digantikan"){echo "checked";}else{echo "hidden";}
?>
  ><a  href="?page=rkk&aksi=history&id=<?php echo $datadetail2['id_rkk_detail'];?>"  class="btn btn-info"> History</a></div>
    
</td>
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
$tampilcek=$koneksi->query("select COUNT(id_karyawan) as jml from tb_rkk_detail where id_rkk = '$idrkk' and id_karyawan = '$idkaryawan' ");
$datacek=$tampilcek->fetch_assoc();
$hasilcek = $datacek['jml'];

if($hasilcek == "1"){}
else{
  $koneksi->query("insert into tb_rkk_detail (id_rkk,id_karyawan,upah,id_departmen,id_sub_department) select '$idrkk','$idkaryawan',upah_harian,id_departmen,id_sub_department from ms_karyawan where id_karyawan = '$idkaryawan' ");
}
   
}
 ?>
                <script type="text/javascript">
                alert("Data Tersimpan");
                window.location.href="?page=rkk&aksi=kelola&id=<?php echo $idrkk; ?>";

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