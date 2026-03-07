<?php


if(isset($_GET['idkaryawan'])){
   $idkaryawan = $_GET['idkaryawan'];

  $tampildetail=$koneksi->query("select ms_karyawan.* , tb_jadwal.keterangan from ms_karyawan left join tb_jadwal on ms_karyawan.id_jadwal = tb_jadwal.id_jadwal where id_karyawan = '$idkaryawan' ");
$datadetail=$tampildetail->fetch_assoc();
$datanamakaryawan = $datadetail['nama_karyawan'];
$datanoabsen   = $datadetail['no_absen'];
$datashift   = $datadetail['keterangan'];
$dataidshift   = $datadetail['id_jadwal'];

}

?>
<div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-primary"  >
                    <div class="box-header with-border" style=" background-color:#5F9EA0; border:1px ; color:white; ">
              <h3 class="box-title">Daily Activity</h3>
            </div>
             <form method="POST"  enctype="multipart/form-data">
                        <div class="panel-body">
                           <div class="panel-body">
                           
                            <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                            <div hidden="hidden"  class="form-group col-md-4">
                    <label class="font-weight-bold">Id Karyawan</label>
                    
                    <input  autocomplete="off" type="text" name="tid"   class="form-control"/>
                </div>
  <div class="form-group col-md-2">
                    <label class="font-weight-bold">No. Absen</label>
                    <input placeholder="*" autocomplete="off" type="text" name="tnoabsen" value ="<?php echo $datanoabsen  ; ?>"  required class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">Nama </label>
                    <input placeholder="*" autocomplete="off" type="text" name="tnama" value ="<?php echo $datanamakaryawan ; ?>" required class="form-control"/>
                   
                </div>
               <div class="form-group col-md-4">
                    <label class="font-weight-bold">Shift</label>

                     <select class="form-control" name="tshift" required>
                      <option value="<?php echo $dataidshift; ?>"><?php echo $datashift; ?></option>
                                           <?php 
                        $sql = $koneksi->query("select * from tb_jadwal");
                            
                        while ($dataRow =  $sql->fetch_array()) {
                        if ($dataBagian == $dataRow['keterangan']) {
                        $cek1 = " selected";
                        } else { $cek1=""; }
                        echo "<option value='$dataRow[id_jadwal]' $cek>$dataRow[keterangan]</option>";
                        }
                        ?>
                                            </select>
                    
                </div>
 
          
             <div class="form-group col-md-3">
                    <label class="font-weight-bold">Dari Tanggal</label>
                    <input placeholder="*" autocomplete="off" type="date" name="ttgl1" value="<?php echo date("Y-m-d"); ?>" required class="form-control"/>
                    
                </div>
                                
                 

                </div>

 <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                   <div class="form-group col-md-4">
                    <label class="font-weight-bold">Target</label>
                    <input placeholder="*" autocomplete="off" type="number" name="ttarget"  required class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">Hasil</label>
                    <input placeholder="*" autocomplete="off" type="number" name="thasil"  required class="form-control"/>
                    
                </div>
                 </div>
                 <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                   <div class="form-group col-md-4">
                    <label class="font-weight-bold">Upah</label>
                    <input placeholder="*" autocomplete="off" type="number" name="tupah" value="" required class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">Lembur</label>
                    <input placeholder="*" autocomplete="off" type="number" name="tlembur"  required class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">Potongan</label>
                    <input placeholder="*" autocomplete="off" type="number" name="tpotongan"  required class="form-control"/>
                    
                </div>
                 </div>
                  



                  <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                    <div class="form-group col-md-4">
                 <div>
                                            <input type="submit" name="simpan"  value="Simpan" class="btn btn-primary">
                                              <div class="col"> <h3><label style="color:red ;" >* </label><label>HArus Diisi</label> </h3> </div>
                                        </div>
                                           
                                        </div></div>
                                    </form>
             
                                    </form>
                                  
                            </div>
                          
<div class="table-responsive">
                                
                                <table class="table table-bordered table-striped" id="dataTables-example">
                                    <thead style="  border-right: : 0px;">
                                        <tr style="  border-right: : 0px;">
                                        <th width="5%" >No</th>
                                           <th >No. Absen</th>
                                           <th >Nama </th>
                                           <th >Bagian</th>
                                            <th >Sub Bagian</th>
                                                                                  
                                        <th >Jenis Kelamin</th> 
                                        <th >Tanggal</th> 
                                        <th >Target</th> 
                                        <th >Hasil</th> 
                                        <th >Upah</th> 
                                        <th >Potongan</th> 
                                        <th >Lembur</th> 
                                        <th >Total</th> 

                          <th style=" width: 10px; ">Daily Activity</th>
                          

                                        </tr>
                                    </thead>
                                    <tbody>
                                                                <?php


$no = 1;


$tampil = $koneksi->query("SELECT aa.* , bb.no_absen, bb.nama_karyawan , bb.jenis_kelamin ,cc.nama_sub_department, dd.nama_departmen from tb_hasil_produksi aa left join ms_karyawan bb on aa.id_karyawan = bb.id_karyawan left join ms_sub_department cc on bb.id_sub_department = cc.id_sub_department LEFT JOIN ms_departmen dd on bb.id_departmen = dd.id_departmen where aa.id_karyawan = '$idkaryawan' order by aa.id_hasil_produksi desc
 ");
    while ($datakaryawan=$tampil->fetch_assoc())
    {

?>


                                        <tr>
<td><?php echo $no ?></td>
<td><?php echo $datakaryawan['no_absen'] ?></td>
<td><?php echo $datakaryawan['nama_karyawan'] ?></td>
<td><?php echo $datakaryawan['nama_departmen'] ?></td>
<td><?php echo $datakaryawan['nama_sub_department'] ?></td>
<td><?php echo $datakaryawan['jenis_kelamin'] ?></td>
<td><?php echo $datakaryawan['tgl'] ?></td>
<td><?php echo  number_format( $datakaryawan['target'],0,',','.')  ?></td>
<td><?php echo  number_format( $datakaryawan['hasil'],0,',','.')  ?></td>
<td><?php echo  number_format( $datakaryawan['upah'],0,',','.')  ?></td>
<td><?php echo  number_format( $datakaryawan['potongan'],0,',','.')  ?></td>
<td><?php echo  number_format( $datakaryawan['lembur'],0,',','.')  ?></td>
<td><?php echo  number_format( $datakaryawan['upah'] + $datakaryawan['lembur']-$datakaryawan['potongan'],0,',','.')  ?></td>
<td style=" width: 200px;">
    <a  href="?page=dailyactivity&aksi=tambah&idkaryawan=<?php echo $datakaryawan['id_karyawan'];?>"  class="btn btn-success"> Daily Activity</a>
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

<?php

$ttgl1 = @$_POST ['ttgl1'];
$ttarget = @$_POST ['ttarget'];
$thasil = @$_POST ['thasil'];
$tupah = @$_POST ['tupah'];
$tlembur = @$_POST ['tlembur'];
$tpotongan = @$_POST ['tpotongan'];
$tgl1 = @$_POST ['ttgl1'];
$tshift = @$_POST ['tshift'];
$simpan = @$_POST ['simpan'];

if($simpan) {
 $tampil=$koneksi->query("select count(id_karyawan) as jml from tb_hasil_produksi where id_karyawan = '$idkaryawan' and tgl ='$ttgl1' ");
$datade=$tampil->fetch_assoc();

if($datade['jml'] >0){

 ?>
                <script type="text/javascript">
                alert("Data Sudah Tersedia");
                

            </script>
            <?php

}else {
  $sql = $koneksi->query("insert into tb_hasil_produksi (id_karyawan,tgl,target,hasil,upah,lembur,potongan,id_jadwal) values ('$idkaryawan','$ttgl1','$ttarget','$thasil','$tupah','$tlembur','$tpotongan','$tshift')  ");
if($sql) {
        ?>
                <script type="text/javascript">
                alert("Data Tersimpan");
                window.location.href="?page=dailyactivity&aksi=tambah&idkaryawan=<?php echo $idkaryawan ;?>";

            </script>
            <?php
    }
}//simpan if
}




?>