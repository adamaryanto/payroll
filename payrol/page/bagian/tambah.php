
<div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-primary"  >
                    <div class="box-header with-border" style=" background-color:#5F9EA0; border:1px ; color:white; ">
              <h3 class="box-title">Tambah Data Bagian</h3>
            </div>
             <form method="POST"  enctype="multipart/form-data">
                        <div class="panel-body">
                           
                           
              
                <div class="row" style="  border:1px ; color:black; "> 
                 <div class="form-group col-md-12">
                    <label class="font-weight-bold">Nama Bagian</label>
                    <input placeholder="*" autocomplete="off" type="text" name="tnama"  required class="form-control"/>
                    
                </div>
                </div>
               
                  <div class="row" style="  border:1px ; color:black; "> 
                    <div class="form-group col-md-4">
                 <div>
                                            <input type="submit" name="simpan"  value="Simpan" class="btn btn-primary">
                                              <a onclick="history.back();" href="#" class="btn btn-danger">Cancel</a>
                                              <div class="col"> <h3><label style="color:red ;" >* </label><label>Harus Diisi</label> </h3> </div>
                                        </div>
                                        
                                        </div></div>
                                    </form>
                                    <div class="form-group "></div>

                          
                           

                    </div>
                </div>
        </div>
    </div>


<?php

$tnama = @$_POST ['tnama'];
$simpan = @$_POST ['simpan'];
if($simpan) {
$sql = $koneksi->query("insert into ms_departmen (nama_departmen) values('$tnama')  ");
if($sql) {
        ?>
                <script type="text/javascript">
                alert("Data Tersimpan");
                window.location.href="?page=bagian";

            </script>
            <?php
    }
}//simpan if

?>