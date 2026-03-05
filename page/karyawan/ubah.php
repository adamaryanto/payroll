<?php


if(isset($_GET['id'])){
    $idu = $_GET['id'];
$tampil=$koneksi->query("SELECT ms_karyawan.* , ms_sub_department.nama_sub_department , ms_sub_department.id_sub_department , ms_departmen.nama_departmen, ms_departmen.id_departmen 
  from ms_karyawan LEFT JOIN ms_departmen on ms_karyawan.id_departmen = ms_departmen.id_departmen 
  LEFT JOIN ms_sub_department on ms_karyawan.id_sub_department = ms_sub_department.id_sub_department 
  WHERE id_karyawan = '$idu' ");
$data=$tampil->fetch_assoc();
$idkaryawan =$data['id_karyawan'];
$iddepartmen =$data['id_departmen'];
$namadepartmen =$data['nama_departmen'];
$iddepartmensub =$data['id_sub_department'];
$namadepartmensub =$data['nama_sub_department'];
$noabsen = $data['no_absen'];
$namakaryawan = $data['nama_karyawan'];
$tempatlahir = $data['tempat_lahir'];
$tgllahir = $data['tgl_lahir'];
$agama = $data['agama'];
$statuskawin = $data['status_kawin'];
$jeniskelamin = $data['jenis_kelamin'];
$noktp = $data['no_ktp'];
$nobpjs = $data['no_bpjs'];
$tos = $data['OS_DHK'];
$golongan = $data['golongan'];
$alamatktp = $data['alamat_ktp'];
$alamattinggal = $data['alamat_tinggal'];
$statuskaryawan = $data['status_karyawan'];
$tglaktif = $data['tgl_aktif'];
$tglnonaktif = $data['tgl_nonaktif'];
$foto = $data['foto'];
$contsimpan="hidden";
$contupdate="";
}else{
  $idkaryawan = "";
$noabsen = "";
$namakaryawan = "";
$tos = "";
$golongan="";
$iddepartmensub ="";
$namadepartmensub ="";
$tempatlahir = "";
$tgllahir = "";
$agama = "";
$statuskawin = "";
$jeniskelamin = "";
$noktp = "";
$alamatktp = "";
$alamattinggal = "";
$statuskaryawan = "";
$tglaktif = "";
$tglnonaktif = "";
$foto = "";
$contsimpan="";
$contupdate="hidden";
}


?>
<div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-primary"  >
                    <div class="box-header with-border" style=" background-color:#5F9EA0; border:1px ; color:white; ">
              <h3 class="box-title">Ubah Data Karyawan</h3>
            </div>
             <form method="POST"  enctype="multipart/form-data">
                        <div class="panel-body">
                           <div class="panel-body">
                           
                            <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                            <div hidden="hidden"  class="form-group col-md-4">
                    <label class="font-weight-bold">Id Karyawan</label>
                    
                    <input  autocomplete="off" type="text" name="tid" value="<?php echo $idkaryawan; ?>"  class="form-control"/>
                </div>

                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">Nama </label>
                    <input placeholder="*" autocomplete="off" type="text" name="tnama" value="<?php echo $namakaryawan; ?>"  required class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">No. Absen</label>
                    <input placeholder="*" autocomplete="off" type="text" name="tnoabsen" value="<?php echo $noabsen; ?>" readonly required class="form-control"/>
                    
                </div>
          


                   <div class="form-group col-md-4">
                    <label class="font-weight-bold">OS/DHK </label>
                    <select name="tos" class="form-control" required>
                      <option value="<?php echo htmlspecialchars($tos); ?>"><?php echo htmlspecialchars($tos); ?></option>
                      <option value="OS">OS</option>
                      <option value="DHK">DHK</option>
                      <option value="-">-</option>
                    </select>
                    
                </div>
                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">Golongan</label>
                    <select name="tgolongan" class="form-control" >
                      <option value="<?php echo $golongan; ?>"><?php echo $golongan; ?></option>
                        <option value="Harian">Harian</option>
                         <option value="Mingguan">Mingguan</option>
                         <option value="Bulanan">Bulanan</option>
                         
                        </select>
                    
                </div>
                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">Tempat Lahir</label>
                    <input placeholder="*" autocomplete="off" type="text" name="ttempatlahir" value="<?php echo $tempatlahir; ?>"  class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">Tanggal Lahir</label>
                    <input placeholder="*" autocomplete="off" type="date" name="ttanggallahir" value="<?php echo $tgllahir; ?>"  class="form-control"/>
                    
                </div>
                  <div class="form-group col-md-4">
                    <label class="font-weight-bold">Agama</label>
                    <select name="tagama" class="form-control" >
                      <option value="<?php echo $agama; ?>"><?php echo $agama; ?></option>
                        <option value="Islam">Islam</option>
                         <option value="Kristen Katolik">Kristen Katolik</option>
                         <option value="Kristen Protestan">Kristen Protestan</option>
                          <option value="Hindu">Hindu</option>
                           <option value="Budha">Budha</option>
                        </select>
                    
                </div>
                  <div class="form-group col-md-4">
                    <label class="font-weight-bold">Status Kawin</label>
                   <select name="tstatuskawin" class="form-control" >
                  <option value="<?php echo $statuskawin; ?>"><?php echo $statuskawin; ?></option>
                        <option value="Belum Kawin">Belum Kawin</option>
                         <option value="Kawin">Kawin</option>
                          <option value="Janda">Janda</option>
                           <option value="Duda">Duda</option>
                        </select>
                    
                </div>
                  <div class="form-group col-md-4">
                    <label class="font-weight-bold">Jenis Kelamin</label>
                  <select name="tjeniskelamin" class="form-control" >
                     <option value="<?php echo $jeniskelamin; ?>"><?php echo $jeniskelamin; ?></option>
                        <option value="Laki-laki">Laki-laki</option>
                         <option value="Perempuan">Perempuan</option>
                        </select>
                    
                </div>
                
                  <div class="form-group col-md-4">
                    <label class="font-weight-bold">No.KTP</label>
                    <input placeholder="*" autocomplete="off" type="text" name="tnoktp" value="<?php echo $noktp; ?>"   class="form-control"/>
                    
                </div>
                  <div class="form-group col-md-4">
                    <label class="font-weight-bold">Alamat KTP</label>
                    <input placeholder="*" autocomplete="off" type="text" name="talamatktp" value="<?php echo $alamatktp; ?>"  class="form-control"/>
                    
                </div>
                  <div class="form-group col-md-4">
                    <label class="font-weight-bold">Alamat Tinggal</label>
                    <input placeholder="*" autocomplete="off" type="text" name="talamattinggal" value="<?php echo $alamattinggal; ?>"  class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">Tanggal Bergabung</label>
                    <input placeholder="*" autocomplete="off" type="date" name="ttanggalbergabung" value="<?php echo $tglaktif; ?>"  class="form-control"/>
                    
                </div>
                <div class="form-group col-md-4">
                    <label class="font-weight-bold">No. BPJS</label>
                    <input  autocomplete="off" type="number" name="tbpjs" value="<?php echo $nobpjs; ?>" class="form-control"/>
                    
                </div>
                  
                </div>







                  <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                    <div class="form-group col-md-4">
                 <div>
                                            
                                              <div class="col"> <h3><label style="color:red ;" >* </label><label>HArus Diisi</label> </h3> </div>
                                        </div>
                                            <div >
                                            <input type="submit" name="update"  value="Update" class="btn btn-primary">
                                          
                                            <a href="?page=karyawan"  class="btn btn-warning">Cancel </a> 
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

$tid = @$_POST['tid'] ;
$tnama = @$_POST ['tnama'];
$tnoabsen = @$_POST ['tnoabsen'];
$ttempatlahir = @$_POST ['ttempatlahir'];
$ttanggallahir = @$_POST ['ttanggallahir'];
$tagama = @$_POST ['tagama'];
$tstatuskawin = @$_POST ['tstatuskawin'];
$tjeniskelamin = @$_POST ['tjeniskelamin'];
$tnoktp = @$_POST ['tnoktp'];
$talamatktp = @$_POST ['talamatktp'];
$talamattinggal = @$_POST ['talamattinggal'];
$ttanggalbergabung = @$_POST ['ttanggalbergabung'];
$tbpjs = @$_POST ['tbpjs'];
$ttos = @$_POST ['tos'];
$tgolongan = @$_POST ['tgolongan'];
$ttanggallahir = empty($ttanggallahir) ? 'NULL' : "'$ttanggallahir'";
$ttanggalbergabung = empty($ttanggalbergabung) ? 'NULL' : "'$ttanggalbergabung'";

$simpan = @$_POST ['simpan'];
$update = @$_POST ['update'];
$iduser = $_SESSION['iduser'];
//$idperusahaan = $_SESSION['idperusahaan'];
if($simpan) {
$sql = $koneksi->query("insert into ms_karyawan(id_departmen,id_jabatan,no_absen,nama_karyawan,tempat_lahir,tgl_lahir,agama,status_kawin,jenis_kelamin,no_ktp,alamat_ktp,alamat_tinggal,status_karyawan,tgl_aktif,no_bpjs,OS_DHK) values('0','0','$tnoabsen','$tnama','$ttempatlahir',$ttanggallahir,'$tagama','$tstatuskawin','$tjeniskelamin','$tnoktp','$talamatktp','$talamattinggal','Aktif',$ttanggalbergabung,'$tbpjs','$ttos')  ");
if($sql) {
        ?>
                <script type="text/javascript">
                alert("Data Tersimpan");
                window.location.href="?page=karyawan";

            </script>
            <?php
    }
}//simpan if
elseif($update) {
$sql = $koneksi->query("update ms_karyawan set  no_absen ='$tnoabsen' ,nama_karyawan = '$tnama',tempat_lahir = '$ttempatlahir' ,tgl_lahir = $ttanggallahir,agama ='$tagama' ,status_kawin = '$tstatuskawin',jenis_kelamin = '$tjeniskelamin',no_ktp = '$tnoktp' ,alamat_ktp = '$talamatktp',alamat_tinggal = '$talamattinggal' , tgl_aktif = $ttanggalbergabung,
  no_bpjs = '$tbpjs',OS_DHK='$ttos',golongan='$tgolongan'
 where id_karyawan = '$idu'  ");
if($sql) {
        ?>
                <script type="text/javascript">
                alert("Data Berhasil Ubah");
                window.location.href="?page=karyawan";

            </script>
            <?php
    }
}//update if
?>