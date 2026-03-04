
<div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-primary"  >
                    <div class="box-header with-border" style=" background-color:#5F9EA0; border:1px ; color:white; ">
              <h3 class="box-title">Ubah Jadwal</h3>
            </div>
             <form method="POST"  enctype="multipart/form-data">
                        <div class="panel-body">
                           <div class="panel-body">
                           
                            <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                            <div hidden="hidden"  class="form-group col-md-4">
                    <label class="font-weight-bold">Id Karyawan</label>
                    
                    <input  autocomplete="off" type="text" name="tid"   class="form-control"/>
                </div>

                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">Shift </label>
                    <input placeholder="*" autocomplete="off" type="text" name="tketerangan"  required class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-2">
                    <label class="font-weight-bold">Jam Masuk</label>
                   <input placeholder="*" autocomplete="off" type="time" name="tjammasuk"  required class="form-control"/>
                    
                </div>
                  <div class="form-group col-md-2">
                    <label class="font-weight-bold">Jam Keluar</label>
                   <input placeholder="*" autocomplete="off" type="time" name="tjamkeluar"  required class="form-control"/>
                    
                </div>
                  <div class="form-group col-md-2">
                    <label class="font-weight-bold">Istirahat Keluar</label>
                   <input placeholder="*" autocomplete="off" type="time" name="tistirahatkeluar"  required class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-2">
                    <label class="font-weight-bold">Istirahat Masuk</label>
                   <input placeholder="*" autocomplete="off" type="time" name="tistirahatmasuk"  required class="form-control"/>
                    
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

$tketerangan = @$_POST ['tketerangan'];
$tjammasuk = @$_POST ['tjammasuk'];
$tjamkeluar = @$_POST ['tjamkeluar'];
$tistirahatmasuk = @$_POST ['tistirahatmasuk'];
$tistirahatkeluar = @$_POST ['tistirahatkeluar'];
$simpan = @$_POST ['simpan'];
if($simpan) {
$sql = $koneksi->query("insert into tb_jadwal (keterangan,jam_masuk,jam_keluar,istirahat_masuk,istirahat_keluar) values('$tketerangan' ,'$tjammasuk' , '$tjamkeluar' , '$tistirahatmasuk' , '$tistirahatkeluar') ");
if($sql) {
        ?>
                <script type="text/javascript">
                alert("Data Tersimpan");
                window.location.href="?page=jadwal";

            </script>
            <?php
    }
}//simpan if
?>