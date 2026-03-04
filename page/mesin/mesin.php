<?php

$tampil=$koneksi->query("SELECT * from tb_mesin");
$data=$tampil->fetch_assoc();
$idmesin =$data['id_mesin'];
$nomesin = $data['no_mesin'];
$namamesin = $data['nama_mesin'];
$ipmesin = $data['ip_mesin'];
$commkey = $data['comm_key'];
$port = $data['port'];

?>
<div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-primary"  >
                    <div class="box-header with-border" style=" background-color:#5F9EA0; border:1px ; color:white; ">
              <h3 class="box-title">Setting Mesin</h3>
            </div>
             <form method="POST"  enctype="multipart/form-data">
                        <div class="panel-body">
                           <div class="panel-body">
                           
                            <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                          
                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">No Mesin</label>
                    <input placeholder="*" autocomplete="off" type="text" name="tnomesin" value="<?php echo $nomesin; ?>"  required class="form-control"/>
                    
                </div>                       
             
                </div>
                <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                          
                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">Nama Mesin</label>
                    <input placeholder="*" autocomplete="off" type="text" name="tnamamesin" value="<?php echo $namamesin; ?>"  required class="form-control"/>
                    
                </div>                       
             
                </div>

                <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                          
                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">IP Mesin</label>
                    <input placeholder="*" autocomplete="off" type="text" name="tipmesin" value="<?php echo $ipmesin; ?>"  required class="form-control"/>
                    
                </div>                       
             
                </div>


                <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                          
                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">Comm Key</label>
                    <input placeholder="*" autocomplete="off" type="text" name="tcommkey" value="<?php echo $commkey; ?>"  required class="form-control"/>
                    
                </div>                       
             
                </div>
                 <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                          
                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">Port</label>
                    <input placeholder="*" autocomplete="off" type="text" name="tport" value="<?php echo $port; ?>"  required class="form-control"/>
                    
                </div>                       
             
                </div>


                  <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                    <div class="form-group col-md-4">
                 <div>
                                            
                                              <div class="col"> <h3><label style="color:red ;" >* </label><label>HArus Diisi</label> </h3> </div>
                                        </div>
                                            <div >
                                            <input type="submit" name="simpan"  value="Simpan" class="btn btn-primary">
                                          
                                           
                                           
                                        </div>
                                        </div></div>
                                    </form>
             
                                    </form>
                                  
                            </div>
                          
                           

                    </div>
                </div>
        </div>

<?php

$tnomesin = @$_POST['tnomesin'] ;
$tnamamesin = @$_POST ['tnamamesin'];
$tipmesin = @$_POST ['tipmesin'];
$tcommkey = @$_POST ['tcommkey'];
$tport = @$_POST ['tport'];

$simpan = @$_POST ['simpan'];
if($simpan) {
$sql = $koneksi->query("update tb_mesin set no_mesin='$tnomesin' , nama_mesin='$tnamamesin' , ip_mesin='$tipmesin' , comm_key='$tcommkey' , port='$tport' ");
if($sql) {
        ?>
                <script type="text/javascript">
                alert("Data Tersimpan");
                window.location.href="?page=mesin";

            </script>
            <?php
    }
}//simpan if
?>