<?php


if(isset($_GET['id'])){
   $idrealisasi = $_GET['id'];

  $tampildetail=$koneksi->query("select * from tb_realisasi where id_realisasi = '$idrealisasi' ");
$datadetail=$tampildetail->fetch_assoc();
$datatglrealisasi = $datadetail['tgl_realisasi'];
$dataketerangan = $datadetail['keterangan'];
$datadetailrealisasi   = $datadetail['detail_realisasi'];
$datajamkerja   = $datadetail['jam_kerja'];
$datastatusrealisasi   = $datadetail['status_realisasi'];
$idrkk  = $datadetail['id_rkk'];

$tampil = $koneksi->query("SELECT A.id_realisasi_detail, B.no_absen , BB.nama_sub_department ,B.nama_karyawan , D.nama_departmen , C.tgl_realisasi ,A.r_jam_masuk , A.r_jam_keluar , A.r_istirahat_keluar,
A.r_istirahat_masuk ,A.ra_masuk , A.ra_keluar , A.ra_istirahat_keluar,B.OS_DHK,B.golongan,
A.ra_istirahat_masuk , 
 A.r_upah as upahkaryawan, A.r_potongan_telat, A.r_potongan_istirahat, A.r_potongan_lainnya, A.hasil_kerja
FROM tb_realisasi_detail A 
LEFT JOIN ms_karyawan B on A.id_karyawan = B.id_karyawan
LEFT JOIN tb_realisasi C ON A.id_realisasi = C.id_realisasi
LEFT JOIN tb_rkk_detail E ON A.id_rkk_detail = E.id_rkk_detail
LEFT JOIN ms_departmen D on E.id_departmen = D.id_departmen
   left join ms_sub_department BB on E.id_sub_department = BB.id_sub_department

WHERE A.id_realisasi = '$idrealisasi'
 
");

$tampilrkk=$koneksi->query("select * from tb_rkk where id_rkk = '$idrkk' ");
$datarkk=$tampilrkk->fetch_assoc();
$datatglrkk = $datarkk['tgl_rkk'];
$dataketeranganrkk = $datarkk['keterangan'];
$datajamkerjarkk = $datarkk['jam_kerja'];

}else{
  $datatglrealisasi = "";
  $dataketerangan = "";
$datadetailrealisasi   = "";
$datajamkerja   = "";
$datastatusrealisasi   = 3;
}

if($datastatusrealisasi == 3){
  $status="Hidden";
}elseif($datastatusrealisasi == 2){
  if($_SESSION['level'] !="OWNER"){ $status="Hidden";}else{$status="";}
}elseif($datastatusrealisasi == 1){
  if($_SESSION['level'] !="OWNER"){ $status="Hidden";}else{$status="";}
}

else{

  $status="";}



?>
<div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-primary"  >
                    <div class="box-header with-border" style=" background-color:#5F9EA0; border:1px ; color:white; ">
              <h3 class="box-title">Detail Realisasi Upah</h3>
            </div>
             <form method="POST"  enctype="multipart/form-data">
                        <div class="panel-body">
                           <div class="panel-body">


  <div class="box-header with-border" >
              <h3 class="box-title">Rencana Upah</h3>
            </div>
            <div class="panel-body">
                   <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                            <div hidden="hidden"  class="form-group col-md-4">
                    <label class="font-weight-bold">Id Karyawan</label>
                    
                    <input  autocomplete="off" type="text"    class="form-control"/>
                </div>
                <div class="form-group col-md-3">
                    <label class="font-weight-bold">Tanggal</label>
                    <input readonly placeholder="*" autocomplete="off" type="date" value="<?php echo $datatglrkk; ?>"  class="form-control"/>
                    
                </div>

                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">Keterangan </label>
                    <input placeholder="*" autocomplete="off" type="text" value ="<?php echo $dataketeranganrkk ; ?>"  class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-2">
                    <label class="font-weight-bold">Jam Kerja</label>
                   <input readonly placeholder="*" autocomplete="off" type="number"  value="<?php echo $datajamkerjarkk; ?>"  class="form-control"/>
                    
                </div>

                  </div>
                
                        </div>

                         <div class="box-header with-border" >
              <h3 class="box-title">Realisasi Upah</h3>
            </div>
            <div class="panel-body">
                   <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                            <div hidden="hidden"  class="form-group col-md-4">
                    <label class="font-weight-bold">Id Karyawan</label>
                    
                    <input  autocomplete="off" type="text" name="tid"   class="form-control"/>
                </div>
                <div class="form-group col-md-3">
                    <label class="font-weight-bold">Tanggal</label>
                    <input readonly placeholder="*" autocomplete="off" type="date" name="ttgl1" value="<?php echo $datatglrealisasi; ?>"  class="form-control"/>
                    
                </div>

                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">Keterangan </label>
                    <input placeholder="*" autocomplete="off" type="text" name="tketerangan" value ="<?php echo $dataketerangan ; ?>"  class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-2">
                    <label class="font-weight-bold">Jam Kerja</label>
                   <input readonly placeholder="*" autocomplete="off" type="number" name="tjamkerja" value="<?php echo $datajamkerja; ?>"  class="form-control"/>
                    
                </div>

                  </div>
                  <div class="form-group" <?php echo $status;?>>
      
                      <input type="submit" name="simpan"  value="Simpan" class="btn btn-info">
                       <a href="?page=realisasi"
   class="btn btn-warning" >
   << Kembali
</a>
                    </div>   
                
                        </div>

                           
                           


                  


                  



  <div class="box-header with-border" >
              <h3 class="box-title">List Karyawan</h3>
            </div>
            
                        <div class="panel-body">


 

                                         <div class="table-responsive">
                                 <div class="row" style=" background-color:whitesmoke; border:1px ; color:black; "> 
                                   
               

                </div>
                                <table class="table table-bordered table-striped" id="dataTables-example">
                                    <thead >
                                        <tr>
                                        <th width="5%">No</th>
                                           <th >NIK</th>
                                            <th >Nama Karyawan</th>
                                            <th >Nama Bagian</th>
                                             <th >Nama SUb Bagian</th>
                                             <th >OS/DHK</th>
                                             <th >Golongan</th>
                                           
                                             <th >Jam Masuk</th>
                                              <th >Jam Pulang</th>
                                               <th >Istirahat Keluar</th>
                                        <th >Istirahat Masuk</th>

                                        <th >Absen Masuk</th>
                                              <th >Absen Pulang</th>
                                               <th >Absen Istirahat Keluar</th>
                                        <th >Absen Istirahat Masuk</th>

                                         <th >Upah</th>
                                         <th >Pot. Telat</th>
                                          <th >Pot. Istirahat</th>
                                           <th >Pot. Lainnya</th>
                                            <th >Hasil  Kerja</th>


                                       
                            
                                         
                                           <th <?php echo $status;?> >Action</th>
                        

                                        </tr>
                                    </thead>
                                    <tbody>
                                                                <?php


$no = 1;
$total= 0;

    while ($data=$tampil->fetch_assoc())
    {

?>


                                        <tr>
<td><?php echo $no ?></td>
<td><?php echo $data['no_absen'] ?></td>
<td><?php echo $data['nama_karyawan'] ?></td>
<td><?php echo $data['nama_departmen'] ?></td>
<td><?php echo $data['nama_sub_department'] ?></td>
<td><?php echo $data['OS_DHK'] ?></td>
<td><?php echo $data['golongan'] ?></td>
<td><?php echo $data['r_jam_masuk'] ?></td>
<td><?php echo $data['r_jam_keluar'] ?></td>
<td><?php echo $data['r_istirahat_keluar'] ?></td>
<td><?php echo $data['r_istirahat_masuk'] ?></td>

<td><?php echo $data['ra_masuk'] ?></td>
<td><?php echo $data['ra_keluar'] ?></td>
<td><?php echo $data['ra_istirahat_keluar'] ?></td>
<td><?php echo $data['ra_istirahat_masuk'] ?></td>

<td><?php echo number_format( $data['upahkaryawan'],0,',','.') ; $total= $total + $data['upahkaryawan'] ;?></td>
<td><?php echo number_format( $data['r_potongan_telat'],0,',','.') ?></td>
<td><?php echo number_format( $data['r_potongan_istirahat'],0,',','.') ?></td>
<td><?php echo number_format( $data['r_potongan_lainnya'],0,',','.') ?></td>
<td><?php echo $data['hasil_kerja'] ?></td>
<td <?php echo $status;?>>
  <a href="?page=realisasi&aksi=detail&id=<?php echo $data['id_realisasi_detail'];?>"
   class="btn btn-warning" >
   Detail
</a>
  

</td>
<!--
<td>
    
<a  href="?page=order&id=<?php echo $data['id_transaksi'];?>"  class="btn btn-success"> Update</a>


</td>
-->
                                      
                                            
                                        </tr>

                                       <?php  $no++; } $no= $no-1; ?>

                                    </tbody>   
                                    </table>
                            </div>  


</div>

<div class="box-header with-border" >
              <h3 class="box-title">Total Realisasi Pengeluarah Upah Karyawan</h3>
            </div>
            
                        <div class="panel-body">
                            <div class="form-group col-md-3">
                  <h1>  <input  style="background-color:yellow;  height:60px; font-size:24px; font-weight:bold;text-align: right;" placeholder="*" autocomplete="off" type="text" name="ttol" value ="<?php echo  "Rp. " . number_format( $total,0,',','.') . " / " . $no . " Karyawan" ; ?>" required class="form-control"/></h1>
                    
                </div>
                          </div>


                                    </form>
             
                                    </form>
                                  
                            </div>
                          
                           

                    </div>
                </div>
        </div>

<?php
$ttgl2 = date("Y-m-d H:i:s");
$tketerangan = @$_POST ['tketerangan'];
$simpan = @$_POST ['simpan'];


if($simpan) {

$sql = $koneksi->query("update tb_realisasi set keterangan = '$tketerangan' where id_realisasi = '$idrealisasi' ");
if($sql) {
        ?>
                <script type="text/javascript">
                alert("Data Tersimpan");
                window.location.href="?page=realisasi&aksi=kelola&id=<?php echo $idrealisasi ?>";

            </script>
            <?php
    }
}//simpan if
?>