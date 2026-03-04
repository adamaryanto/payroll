<?php


if(isset($_GET['id'])){
    $idu = $_GET['id'];
$tampil=$koneksi->query("SELECT * FROM ms_jasa WHERE id_jasa = '$idu' ");
$data=$tampil->fetch_assoc();
$namajasa = $data['nama_jasa'];

}else{
}


?>
<div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-primary"  >
                    <div class="box-header with-border" style=" background-color:#5F9EA0; border:1px ; color:white; ">
              <h3 class="box-title">Ubah Data Kategori Jasa</h3>
            </div>
             <form method="POST"  enctype="multipart/form-data">
                        <div class="panel-body">
                           
                           
                <div class="row" style="  border:1px ; color:black; "> 
                 <div class="form-group col-md-12">
                    <label class="font-weight-bold">Nama Jasa</label>
                    <input placeholder="*" autocomplete="off" type="text" name="tnama" value="<?php echo $namajasa ; ?>" required class="form-control"/>
                    
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
$sql = $koneksi->query("Update ms_jasa set nama_jasa ='$tnama' where id_jasa ='$idu'  ");
if($sql) {
        ?>
                <script type="text/javascript">
                alert("Data Tersimpan");
                window.location.href="?page=jasa";

            </script>
            <?php
    }
}//simpan if

?>