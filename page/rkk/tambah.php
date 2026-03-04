
<div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-primary"  >
                    <div class="box-header with-border" style=" background-color:#5F9EA0; border:1px ; color:white; ">
              <h3 class="box-title">Form Rencana Kerja</h3>
            </div>
             <form method="POST"  enctype="multipart/form-data">
                        <div class="panel-body">
                           <div class="panel-body">
                           
                            <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                            <div hidden="hidden"  class="form-group col-md-4">
                    <label class="font-weight-bold">Id Karyawan</label>
                    
                    <input  autocomplete="off" type="text" name="tid"   class="form-control"/>
                </div>
  <div class="form-group col-md-3">
                    <label class="font-weight-bold">Tanggal</label>
                    <input placeholder="*" autocomplete="off" type="date" name="ttgl1" value="<?php echo date("Y-m-d"); ?>" required class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">Keterangan </label>
                    <input placeholder="*" autocomplete="off" type="text" name="tketerangan"  required class="form-control"/>
                    
                </div>
                  
                 <div class="form-group col-md-2">
                    <label class="font-weight-bold">Jam kerja</label>
                   <input placeholder="*" autocomplete="off" type="number" name="tjamkerja"  required class="form-control"/>
                    
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
$ttgl2 = date("Y-m-d H:i:s");
$tketerangan = @$_POST ['tketerangan'];
$tjamkerja = @$_POST ['tjamkerja'];
$simpan = @$_POST ['simpan'];
if($simpan) {

$tampil=$koneksi->query("sELECT * from tb_rkk WHERE tgl_rkk = '$ttgl1' ");
$data=$tampil->fetch_assoc();

if(isset($data['id_rkk'])){
 ?>
                <script type="text/javascript">
                alert("Data Is Already Exist");

            </script>
            <?php
}else{
  $sql = $koneksi->query("insert into tb_rkk (tgl_rkk,keterangan,jam_kerja,detail_rkk) values('$ttgl1','$tketerangan' ,'$tjamkerja' , '$ttgl2') ");
if($sql) {
        ?>
                <script type="text/javascript">
                alert("Data Tersimpan");
                window.location.href="?page=rkk";

            </script>
            <?php
    }
}


}//simpan if
?>