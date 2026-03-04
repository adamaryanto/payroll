<?php


if(isset($_GET['id'])){
   $idrkk = $_GET['id'];

  $tampildetail=$koneksi->query("select * from tb_rkk where id_rkk = '$idrkk' ");
$datadetail=$tampildetail->fetch_assoc();
$datatglrkk = $datadetail['tgl_rkk'];
$dataketerangan = $datadetail['keterangan'];
$datadetailrkk   = $datadetail['detail_rkk'];
$datajamkerja   = $datadetail['jam_kerja'];
$datastatusrkk   = $datadetail['status_rkk'];

$tampil = $koneksi->query("SELECT A.id_rkk_detail, B.no_absen , BB.nama_sub_department ,B.nama_karyawan , D.nama_departmen , C.tgl_rkk ,A.jam_masuk , A.jam_keluar , A.istirahat_keluar,
A.istirahat_masuk , A.status_rkk,B.OS_DHK,B.golongan,
 A.upah as upahkaryawan, A.potongan_telat, A.potongan_istirahat, A.potongan_lainnya
FROM tb_rkk_detail A 
LEFT JOIN ms_karyawan B on A.id_karyawan = B.id_karyawan
LEFT JOIN tb_rkk C ON A.id_rkk = C.id_rkk
LEFT JOIN ms_departmen D on C.id_departmen = D.id_departmen
   left join ms_sub_department BB on B.id_sub_department = BB.id_sub_department

WHERE A.id_rkk = '$idrkk'
 
");

}else{
  $datatglrkk = "";
  $dataketerangan = "";
$datadetailrkk   = "";
$datajamkerja   = "";
$datastatusrkk   = 3;
}

if($datastatusrkk == 3){
  $status="Hidden";
}elseif($datastatusrkk == 2){
  if($_SESSION['level'] !="OWNER"){ $status="Hidden";}else{$status="";}
}elseif($datastatusrkk == 1){
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
              <h3 class="box-title">Detail Rencana Upah</h3>
            </div>
             <form method="POST"  enctype="multipart/form-data">
                        <div class="panel-body">
                           <div class="panel-body">


  <div class="box-header with-border" >
              <h3 class="box-title">Rencana Kerja</h3>
            </div>
            <div class="panel-body">
                   <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                            <div hidden="hidden"  class="form-group col-md-4">
                    <label class="font-weight-bold">Id Karyawan</label>
                    
                    <input  autocomplete="off" type="text" name="tid"   class="form-control"/>
                </div>
                <div class="form-group col-md-3">
                    <label class="font-weight-bold">Tanggal</label>
                    <input placeholder="*" autocomplete="off" type="date" name="ttgl1" value="<?php echo $datatglrkk; ?>" required class="form-control"/>
                    
                </div>

                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">Keterangan </label>
                    <input placeholder="*" autocomplete="off" type="text" name="tketerangan" value ="<?php echo $dataketerangan ; ?>" required class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-2">
                    <label class="font-weight-bold">Jam Kerja</label>
                   <input placeholder="*" autocomplete="off" type="number" name="tjamkerja" value="<?php echo $datajamkerja; ?>" required class="form-control"/>
                    
                </div>
                  </div>
                
                        </div>

                           
                           


                  


                  



  <div class="box-header with-border" >
              <h3 class="box-title">List Karyawan</h3>
            </div>
            
                        <div class="panel-body">

 <div class="form-group " <?php echo $status;?>>
  <a  href="?page=rkk&aksi=karyawan&id=<?php echo $idrkk;?>"  class="btn btn-info">Tambah Karyawan</a>
      <a href="?page=rkk"
   class="btn btn-warning" >
   << Kembali
</a>
                 </div>
 

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
                                         <th >Upah</th>
                                         <th >Pot. Telat</th>
                                          <th >Pot. Istirahat</th>
                                           <th >Pot. Lainnya</th>


                                       
                            
                                         
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
<td><?php echo $data['jam_masuk'] ?></td>
<td><?php echo $data['jam_keluar'] ?></td>
<td><?php echo $data['istirahat_keluar'] ?></td>
<td><?php echo $data['istirahat_masuk'] ?></td>

<td><?php echo number_format( $data['upahkaryawan'],0,',','.') ; $total= $total + $data['upahkaryawan'] ;?></td>
<td><?php echo number_format( $data['potongan_telat'],0,',','.') ?></td>
<td><?php echo number_format( $data['potongan_istirahat'],0,',','.') ?></td>
<td><?php echo number_format( $data['potongan_lainnya'],0,',','.') ?></td>
<td <?php echo $status;?>>
  <a href="?page=rkk&aksi=detail&id=<?php echo $data['id_rkk_detail'];?>"
   class="btn btn-warning" >
   Detail
</a>
  <a href="?page=rkk&aksi=hapusdetail&id=<?php echo $idrkk;?>&iddetail=<?php echo $data['id_rkk_detail'];?>"
   class="btn btn-danger"
   onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
   Hapus
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
              <h3 class="box-title">Total Rencana Pengeluarah Upah Karyawan</h3>
            </div>
            
                        <div class="panel-body">
                            <div class="form-group col-md-4">
                  <h1>  <input readonly style="background-color:yellow;  height:60px; font-size:24px; font-weight:bold;text-align: center;" placeholder="*" autocomplete="off" type="text" name="ttol" value ="<?php echo  "Rp. " . number_format( $total,0,',','.') . " / " . $no . " Karyawan" ; ?>" required class="form-control"/></h1>
                    
                </div>
                          </div>


                                    </form>
             
                                    </form>
                                  
                            </div>
                          
                           

                    </div>
                </div>
        </div>
