<?php


if(isset($_GET['id'])){
    $idu = $_GET['id'];
$tampil=$koneksi->query("SELECT ms_karyawan.* from ms_karyawan WHERE id_karyawan = '$idu' ");
$data=$tampil->fetch_assoc();
$idkaryawan =$data['id_karyawan'];
$noabsen = $data['no_absen'];
$namakaryawan = $data['nama_karyawan'];
$tempatlahir = $data['tempat_lahir'];
$tgllahir = $data['tgl_lahir'];
$agama = $data['agama'];
$tos = $data['OS_DHK'];
$golongan = $data['golongan'];
$statuskawin = $data['status_kawin'];
$jeniskelamin = $data['jenis_kelamin'];
$noktp = $data['no_ktp'];
$nonpwp = $data['no_npwp'];
$nobpjs = $data['no_bpjs'];
$nosim = $data['no_sim'];
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
$tempatlahir = "";
$tgllahir = "";
$agama = "";
$tos = "";
$golongan= "";
$statuskawin = "";
$jeniskelamin = "";
$noktp = "";
$nosim = "";
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
              <h3 class="box-title">View Data Karyawan</h3>
            </div>
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
                    <label class="font-weight-bold">OS/DHK </label>
                    <input placeholder="*" autocomplete="off" type="text" name="tnama" value="<?php echo $tos; ?>"  required class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">Golongan</label>
                    <input placeholder="*" autocomplete="off" type="text" name="tnama" value="<?php echo $golongan; ?>"  required class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">No. Absen</label>
                    <input placeholder="*" autocomplete="off" type="text" name="tnoabsen" value="<?php echo $noabsen; ?>" required class="form-control"/>
                    
                </div>
          
             
                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">Tempat Lahir</label>
                    <input placeholder="*" autocomplete="off" type="text" name="ttempatlahir" value="<?php echo $tempatlahir; ?>" required class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">Tanggal Lahir</label>
                    <input placeholder="*" autocomplete="off" type="date" name="ttanggallahir" value="<?php echo $tgllahir; ?>" required class="form-control"/>
                    
                </div>
                  <div class="form-group col-md-4">
                    <label class="font-weight-bold">Agama</label>
                    <select name="tagama" class="form-control" required>
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
                   <select name="tstatuskawin" class="form-control" required>
                  <option value="<?php echo $statuskawin; ?>"><?php echo $statuskawin; ?></option>
                        <option value="Belum Kawin">Belum Kawin</option>
                         <option value="Kawin">Kawin</option>
                          <option value="Janda">Janda</option>
                           <option value="Duda">Duda</option>
                        </select>
                    
                </div>
                  <div class="form-group col-md-4">
                    <label class="font-weight-bold">Jenis Kelamin</label>
                  <select name="tjeniskelamin" class="form-control" required>
                     <option value="<?php echo $jeniskelamin; ?>"><?php echo $jeniskelamin; ?></option>
                        <option value="Laki-laki">Laki-laki</option>
                         <option value="Perempuan">Perempuan</option>
                        </select>
                    
                </div>
                  <div class="form-group col-md-4">
                    <label class="font-weight-bold">No.KTP</label>
                    <input placeholder="*" autocomplete="off" type="text" name="tnoktp" value="<?php echo $noktp; ?>"  required class="form-control"/>
                    
                </div>
                  <div class="form-group col-md-4">
                    <label class="font-weight-bold">No. SIM</label>
                    <input placeholder="*" autocomplete="off" type="text" name="tnosim" value="<?php echo $nosim; ?>" required class="form-control"/>
                    
                </div>
                  <div class="form-group col-md-4">
                    <label class="font-weight-bold">Alamat KTP</label>
                    <input placeholder="*" autocomplete="off" type="text" name="talamatktp" value="<?php echo $alamatktp; ?>" required class="form-control"/>
                    
                </div>
                  <div class="form-group col-md-4">
                    <label class="font-weight-bold">Alamat Tinggal</label>
                    <input placeholder="*" autocomplete="off" type="text" name="talamattinggal" value="<?php echo $alamattinggal; ?>" required class="form-control"/>
                    
                </div>
                  <div class="form-group col-md-4">
                    <label class="font-weight-bold">Tanggal Bergabung</label>
                    <input placeholder="*" autocomplete="off" type="date" name="ttanggalbergabung" value="<?php echo $tglaktif; ?>" required class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">No. NPWP</label>
                    <input  autocomplete="off" type="number" name="tnpwp" value="<?php echo $nonpwp; ?>" class="form-control"/>
                    
                </div>
                <div class="form-group col-md-4">
                    <label class="font-weight-bold">No. BPJS</label>
                    <input  autocomplete="off" type="number" name="tbpjs" value="<?php echo $nobpjs; ?>" class="form-control"/>
                    
                </div>
                  
                </div>







                            </div>
                          
                           

                    </div>
                </div>
        </div>

<?php

$tid = @$_POST['tid'] ;
$tnama = @$_POST ['tnama'];
$tnoabsen = @$_POST ['tnoabsen'];
$tjabatan = @$_POST ['tjabatan'];
$ttempatlahir = @$_POST ['ttempatlahir'];
$ttanggallahir = @$_POST ['ttanggallahir'];
$tagama = @$_POST ['tagama'];
$tstatuskawin = @$_POST ['tstatuskawin'];
$tjeniskelamin = @$_POST ['tjeniskelamin'];
$tnoktp = @$_POST ['tnoktp'];
$tnosim = @$_POST ['tnosim'];
$talamatktp = @$_POST ['talamatktp'];
$talamattinggal = @$_POST ['talamattinggal'];
$ttanggalbergabung = @$_POST ['ttanggalbergabung'];
$tbpjs = @$_POST ['tbpjs'];
$tnpwp = @$_POST ['tnpwp'];
$simpan = @$_POST ['simpan'];
$update = @$_POST ['update'];
$iduser = $_SESSION['iduser'];
if($simpan) {
$sql = $koneksi->query("insert into ms_karyawan(id_departmen,id_jabatan,no_absen,nama_karyawan,tempat_lahir,tgl_lahir,agama,status_kawin,jenis_kelamin,no_ktp,no_sim,alamat_ktp,alamat_tinggal,status_karyawan,tgl_aktif,no_npwp,no_bpjs) values('0','$tjabatan','$tnoabsen','$tnama','$ttempatlahir','$ttanggallahir','$tagama','$tstatuskawin','$tjeniskelamin','$tnoktp','$tnosim','$talamatktp','$talamattinggal','Aktif','$ttanggalbergabung','$tnpwp','$tbpjs')  ");
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
$sql = $koneksi->query("update ms_karyawan set no_absen ='$tnoabsen' ,nama_karyawan = '$tnama',tempat_lahir = '$ttempatlahir' ,tgl_lahir = '$ttanggallahir',agama ='$tagama' ,status_kawin = '$tstatuskawin',jenis_kelamin = '$tjeniskelamin',no_ktp = '$tnoktp' ,no_sim = '$tnosim',alamat_ktp = '$talamatktp',alamat_tinggal = '$talamattinggal' , tgl_aktif = '$ttanggalbergabung' where id_karyawan = '$idu'  ");
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