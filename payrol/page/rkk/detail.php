<?php


if(isset($_GET['id'])){
   $id = $_GET['id'];

  $tampildetail=$koneksi->query("select A.*, B.keterangan as keterangan_rkk, B.tgl_rkk,B.detail_rkk,B.jam_kerja , C.keterangan as shift 
                                  , BB.no_absen , BC.nama_sub_department ,BB.nama_karyawan , BD.nama_departmen , BB.jenis_kelamin
                                  from 
                                  tb_rkk_detail A left join
                                  tb_rkk B on A.id_rkk = B.id_rkk
                                  left join tb_jadwal C on A.id_jadwal = C.id_jadwal
                                  LEFT JOIN ms_karyawan BB on A.id_karyawan = BB.id_karyawan
                                 
                                  LEFT JOIN ms_departmen BD on BB.id_departmen = BD.id_departmen

                                     left join ms_sub_department BC on BB.id_sub_department = BC.id_sub_department
                                   where A.id_rkk_detail = '$id' ");
$datadetail=$tampildetail->fetch_assoc();
$dataidrkk = $datadetail['id_rkk'];
$datatglrkk = $datadetail['tgl_rkk'];
$dataketeranganrkk = $datadetail['keterangan_rkk'];
$datadetailrkk   = $datadetail['detail_rkk'];
$datajamkerja   = $datadetail['jam_kerja'];

$datajammasuk = $datadetail['jam_masuk'];
$datajamkeluar = $datadetail['jam_keluar'];
$dataistirahatmasuk = $datadetail['istirahat_masuk'];
$datajamistirahatkeluar = $datadetail['istirahat_keluar'];
$dataidjadwal =$datadetail['id_jadwal'];
$dataketerangan =$datadetail['shift'];

$datanoabsen =$datadetail['no_absen'];
$datanamakaryawan =$datadetail['nama_karyawan'];
$databagian =$datadetail['nama_departmen'];
$datasubbagian =$datadetail['nama_sub_department'];
$datajenkel =$datadetail['jenis_kelamin'];

$dataupah =$datadetail['upah'];
$datapotongantelat =$datadetail['potongan_telat'];
$datapotonganistirahat =$datadetail['potongan_istirahat'];
$datapotonganlainnya =$datadetail['potongan_lainnya'];


}else{
  $datatglrkk = "";
  $dataketeranganrkk = "";
$datadetailrkk   = "";
$datajamkerja   = "";

$datajammasuk = "";
$datajamkeluar = "";
$dataistirahatmasuk = "";
$datajamistirahatkeluar = "";
$dataidjadwal = "";
$dataketerangan ="";

$datanoabsen ="";
$datanamakaryawan ="";
$databagian ="";
$datasubbagian ="";
$datajenkel ="";

$dataupah ="";
$datapotongantelat ="";
$datapotonganistirahat ="";
$datapotonganlainnya ="";
}




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
                    <input placeholder="*" autocomplete="off" type="date" name="ttgl1" value="<?php echo $datatglrkk; ?>"  class="form-control"/>
                    
                </div>

                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">Keterangan </label>
                    <input placeholder="*" autocomplete="off" type="text" name="tketerangan" value ="<?php echo $dataketeranganrkk ; ?>"  class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-2">
                    <label class="font-weight-bold">Jam Kerja</label>
                   <input placeholder="*" autocomplete="off" type="number" name="tjamkerja" value="<?php echo $datajamkerja; ?>"  class="form-control"/>
                    
                </div>
                  </div>


                  
                        </div>

                            <div class="box-header with-border" >
              <h3 class="box-title">Data Karyawan</h3>
            </div>
            <div class="panel-body">
              <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                     
                     <div class="form-group col-md-4">
                    <label class="font-weight-bold">No. Absen </label>
                    <input placeholder="*" autocomplete="off" type="text" name="tketerangan" value ="<?php echo $datanoabsen ; ?>"  class="form-control"/>
                    </div>

                     <div class="form-group col-md-4">
                    <label class="font-weight-bold">Nama</label>
                    <input placeholder="*" autocomplete="off" type="text" name="tketerangan" value ="<?php echo $datanamakaryawan ; ?>"  class="form-control"/>
                    </div>

                     <div class="form-group col-md-4">
                    <label class="font-weight-bold">Bagian </label>
                    <input placeholder="*" autocomplete="off" type="text" name="tketerangan" value ="<?php echo $databagian ; ?>"  class="form-control"/>
                    </div>

                     <div class="form-group col-md-4">
                    <label class="font-weight-bold">Sub Bagian</label>
                    <input placeholder="*" autocomplete="off" type="text" name="tketerangan" value ="<?php echo $datasubbagian ; ?>"  class="form-control"/>
                    </div>

                     <div class="form-group col-md-4">
                    <label class="font-weight-bold">Jenis Kelamin </label>
                    <input placeholder="*" autocomplete="off" type="text" name="tketerangan" value ="<?php echo $datajenkel ; ?>"  class="form-control"/>
                    </div>

                </div>

            </div>
                           


                  


                  



  <div class="box-header with-border" >
              <h3 class="box-title">Detail</h3>
            </div>
            
                        <div class="panel-body">


  <div class="form-group col-md-4">
                    <label class="font-weight-bold">Shift </label>
                    <select class="form-control" name="tshift" required>
                     <option value="<?php echo $dataidjadwal ?>"><?php echo $dataketerangan ?></option>
                                           <?php 
                        $sql = $koneksi->query("select * from tb_jadwal ");
                            
                        while ($datashiftRow =  $sql->fetch_array()) {
                        if ($datashiftBagian == $datashiftRow['keterangan']) {
                        $cek1 = " selected";
                        } else { $cek1=""; }
                        echo "<option value='$datashiftRow[id_jadwal]' $cek>$datashiftRow[keterangan]</option>";
                        }
                        ?>
                    </select>
                </div>
                 <div hidden class="form-group col-md-2">
                    <label class="font-weight-bold">Jam Masuk</label>
                   <input placeholder="*" autocomplete="off" type="time" name="tjammasuk" value="<?php echo $datajammasuk ?>"  class="form-control"/>
                    
                </div>
                  <div hidden class="form-group col-md-2">
                    <label class="font-weight-bold">Jam Keluar</label>
                   <input placeholder="*" autocomplete="off" type="time" name="tjamkeluar" value="<?php echo $datajamkeluar ?>"  class="form-control"/>
                    
                </div>
                  <div hidden class="form-group col-md-2">
                    <label class="font-weight-bold">Istirahat Masuk</label>
                   <input placeholder="*" autocomplete="off" type="time" name="tistirahatmasuk" value="<?php echo $dataistirahatmasuk ?>"  class="form-control"/>
                    
                </div>
                 <div hidden class="form-group col-md-2">
                    <label class="font-weight-bold">Istirahat Keluar</label>
                   <input placeholder="*" autocomplete="off" type="time" name="tistirahatkeluar" value="<?php echo $datajamistirahatkeluar ?>"  class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-2">
                    <label class="font-weight-bold">Upah</label>
                   <input placeholder="*" autocomplete="off" type="number" name="tupah" value="<?php echo $dataupah ?>"   required class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-2">
                    <label class="font-weight-bold">Potongan Telat</label>
                   <input placeholder="*" autocomplete="off" type="number" name="tpottelat"  value="<?php echo $datapotongantelat ?>"  required class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-2">
                    <label class="font-weight-bold">Potongan Istirahat</label>
                   <input placeholder="*" autocomplete="off" type="number" name="tpotistirahat"  value="<?php echo $datapotonganistirahat ?>"  required class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-2">
                    <label class="font-weight-bold">Potongan Lainnya</label>
                   <input placeholder="*" autocomplete="off" type="number" name="tpotlainnya" value="<?php echo $datapotonganlainnya ?>"  required class="form-control"/>
                    
                </div>


</div>

<div class="row" style=" background-color:white; border:1px ; color:black; "> 
 <div class="form-group">
      
                      <input type="submit" name="simpan"  value="Simpan" class="btn btn-primary">
    <a href="?page=rkk&aksi=kelola&id=<?php echo $dataidrkk ?>"
   class="btn btn-warning" >
   << Kembali
</a>
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
$tshift = @$_POST ['tshift'];
//$tjammasuk = @$_POST ['tjammasuk'];
//$tjamkeluar = @$_POST ['tjamkeluar'];
//$tistirahatmasuk = @$_POST ['tistirahatmasuk'];
//$tistirahatkeluar = @$_POST ['tistirahatkeluar'];
$tupah = @$_POST ['tupah'];
$tpottelat = @$_POST ['tpottelat'];
$tpotistirahat = @$_POST ['tpotistirahat'];
$tpotlainnya = @$_POST ['tpotlainnya'];
$simpan = @$_POST ['simpan'];


if($simpan) {
$tampil=$koneksi->query("sELECT * from tb_jadwal WHERE id_jadwal = '$tshift' ");
$data=$tampil->fetch_assoc();
$tjammasuk = $data['jam_masuk'];
$tjamkeluar = $data['jam_keluar'];
$tistirahatmasuk = $data['istirahat_masuk'];
$tistirahatkeluar = $data['istirahat_keluar'];

$sql = $koneksi->query("update tb_rkk_detail set upah = '$tupah',id_jadwal='$tshift',potongan_telat='$tpottelat',potongan_istirahat='$tpotistirahat',potongan_lainnya='$tpotlainnya' , jam_masuk ='$tjammasuk' , jam_keluar ='$tjamkeluar' , istirahat_masuk ='$tistirahatmasuk' , istirahat_keluar = '$tistirahatkeluar' , tgl_updt='$ttgl2' where id_rkk_detail = '$id' ");
if($sql) {
        ?>
                <script type="text/javascript">
                alert("Data Tersimpan");
                window.location.href="?page=rkk&aksi=kelola&id=<?php echo $dataidrkk ?>";

            </script>
            <?php
    }
}//simpan if
?>