<?php


if(isset($_GET['id'])){
    $idu = $_GET['id'];
$tampil=$koneksi->query("SELECT * from ms_login WHERE id_login = '$idu' ");
$data=$tampil->fetch_assoc();
$nama = $data['user_login'];
$tpassword = $data['lg_password'];
}else{
}


?>
<div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-primary"  >
                    <div class="box-header with-border" style=" background-color:#5F9EA0; border:1px ; color:white; ">
              <h3 class="box-title">Ubah Data User</h3>
            </div>
             <form method="POST"  enctype="multipart/form-data">
                        <div class="panel-body">
                           
                            <div class="row" style="  border:1px ; color:black; "> 
                            <div hidden="hidden"  class="form-group col-md-12">
                    <label class="font-weight-bold">Id Transaksi</label>
                    
                    <input  autocomplete="off" type="text" name="tid"  class="form-control"/>
                </div>
                </div>
              
                <div class="row" style="  border:1px ; color:black; "> 
                 <div class="form-group col-md-12">
                    <label class="font-weight-bold">Username</label>
                    <input placeholder="*" autocomplete="off" type="text" name="tnama" value="<?php echo $nama ; ?>" required class="form-control"/>
                    
                </div>
                </div>
                <div class="row" style=" border:1px ; color:black; "> 
                 <div class="form-group col-md-12">
                    <label class="font-weight-bold">Role</label>
                    <select name="trole" class="form-control select2" required>
                        <option value="owner" <?php if($data['role'] == 'owner') echo 'selected'; ?>>Owner</option>
                        <option value="Admin HRD" <?php if($data['role'] == 'Admin HRD') echo 'selected'; ?>>Admin HRD</option>
                        <option value="Kepala Gudang" <?php if($data['role'] == 'Kepala Gudang') echo 'selected'; ?>>Kepala Gudang</option>
                        <option value="Admin Master" <?php if($data['role'] == 'Admin Master') echo 'selected'; ?>>Admin Master</option>
                    </select>
                 </div>
                </div>
         
                  <div class="row" style="  border:1px ; color:black; "> 
                    <div class="form-group col-md-4">
                 <div>
                                            <input type="submit" name="simpan"  value="Simpan" class="btn btn-primary">
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
$trole = @$_POST ['trole'];
$simpan = @$_POST ['simpan'];
if($simpan) {
$sql = $koneksi->query("update ms_login set user_login='$tnama', role='$trole' where id_login = '$idu'  ");
if($sql) {
        ?>
                <script type="text/javascript">
                alert("Data Tersimpan");
                window.location.href="?page=user";

            </script>
            <?php
    }
}//simpan if

?>