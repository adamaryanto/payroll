<?php


if(isset($_GET['id'])){
    $idu = $_GET['id'];
$tampil=$koneksi->query("SELECT A.* , nama_department FROM tb_user A LEFT JOIN tb_department B on A.id_department = B.id_department WHERE A.id_user = '$idu' ");
$data=$tampil->fetch_assoc();
$namadepartment = $data['nama_department'];
$iddepartment = $data['id_department'];
$nama = $data['nama'];
$fullname = $data['fullname'];
$email = $data['email'];

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
                    <label class="font-weight-bold">Fullname</label>
                    <input placeholder="*" autocomplete="off" type="text" name="tfullname" value="<?php echo $fullname ; ?>" required class="form-control"/>
                    
                </div>
            </div>
             <div class="row" style="border:1px ; color:black; "> 
                 <div class="form-group col-md-12">
                   <label class="font-weight-bold">Departmen</label>

                     <select class="form-control" name="tdepartment" required>
                     <option value="<?php echo $iddepartment; ?>"><?php echo $namadepartment; ?></option>
                                           <?php 
                        $sql = $koneksi->query("select * from tb_department");
                            
                        while ($dataRow =  $sql->fetch_array()) {
                        if ($dataBagian == $dataRow['nama_department']) {
                        $cek1 = " selected";
                        } else { $cek1=""; }
                        echo "<option value='$dataRow[id_department]' $cek>$dataRow[nama_department]</option>";
                        }
                        ?>
                                            </select>
                    
                </div>
                </div>
            <div class="row" style="border:1px ; color:black; "> 
                 <div class="form-group col-md-12">
                    <label class="font-weight-bold">Email</label>
                    <input placeholder="*" autocomplete="off" type="text" name="temail" value="<?php echo $email ; ?>" required class="form-control"/>
                    
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
$temail = @$_POST ['temail'];
$tfullname = @$_POST ['tfullname'];
$tdepartment = @$_POST ['tdepartment'];
$simpan = @$_POST ['simpan'];
if($simpan) {
$sql = $koneksi->query("update tb_user set nama='$tnama', fullname='$tfullname',email='$temail',id_department='$tdepartment' where id_user = '$idu'  ");
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