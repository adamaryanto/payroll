
<div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-primary"  >
                    <div class="box-header with-border" style=" background-color:#5F9EA0; border:1px ; color:white; ">
              <h3 class="box-title">Daily Activity Vendor</h3>
            </div>
             <form method="POST"  enctype="multipart/form-data">
                        <div class="panel-body">
                           <div class="panel-body">
                           
                            <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                                <div class="form-group col-md-4">
                    <label class="font-weight-bold">Keterangan</label>

                     <select class="form-control" name="tjasa" required>
                                           <?php 
                        $sql = $koneksi->query("select * from ms_jasa");
                            
                        while ($dataRow =  $sql->fetch_array()) {
                        if ($dataBagian == $dataRow['nama_jasa']) {
                        $cek1 = " selected";
                        } else { $cek1=""; }
                        echo "<option value='$dataRow[id_jasa]' $cek>$dataRow[nama_jasa]</option>";
                        }
                        ?>
                                            </select>
                    
                </div>
 
               
          
             <div class="form-group col-md-3">
                    <label class="font-weight-bold">Tanggal</label>
                    <input placeholder="*" autocomplete="off" type="date" name="ttgl1" value="<?php echo date("Y-m-d"); ?>" required class="form-control"/>
                    
                </div>
                                
                 

                </div>
                  <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                   <div class="form-group col-md-4">
                    <label class="font-weight-bold">Hasil</label>
                    <input placeholder="*" autocomplete="off" type="number" name="thasil"  required class="form-control"/>
                    
                </div>
                 </div>
                  

 <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                   <div class="form-group col-md-4">
                    <label class="font-weight-bold">Biaya</label>
                    <input placeholder="*" autocomplete="off" type="number" name="tbiaya"  required class="form-control"/>
                    
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
                          

                    </div>
                </div>
        </div>

<?php

$ttgl1 = @$_POST ['ttgl1'];
$tbiaya = @$_POST ['tbiaya'];
$thasil = @$_POST ['thasil'];
$tjasa = @$_POST ['tjasa'];
$total = $tbiaya * $thasil ;
$simpan = @$_POST ['simpan'];

if($simpan) {
 $tampil=$koneksi->query("select count(id_external) as jml from tb_hasil_external where id_jasa = '$tjasa' and tgl ='$ttgl1' ");
$datade=$tampil->fetch_assoc();

if($datade['jml'] >0){

 ?>
                <script type="text/javascript">
                alert("Data Sudah Tersedia");
                

            </script>
            <?php

}else {
  $sql = $koneksi->query("insert into tb_hasil_external (id_jasa,tgl,hasil,biaya,total_biaya) values ('$tjasa','$ttgl1','$thasil','$tbiaya','$total')  ");
if($sql) {
        ?>
                <script type="text/javascript">
                alert("Data Tersimpan");
                window.location.href="?page=dailyactivityvendor";

            </script>
            <?php
    }
}//simpan if
}




?>