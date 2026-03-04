<?php


if(isset($_GET['id'])){
    $idu = $_GET['id'];
$tampil=$koneksi->query("SELECT ms_karyawan.* , ms_departmen.nama_departmen, ms_departmen.id_departmen , tb_jadwal.keterangan from ms_karyawan LEFT JOIN ms_departmen on ms_karyawan.id_departmen = ms_departmen.id_departmen 
left join tb_jadwal on ms_karyawan.id_jadwal = tb_jadwal.id_jadwal
  WHERE id_karyawan = '$idu' ");
$data=$tampil->fetch_assoc();
$idkaryawan =$data['id_karyawan'];
$iddepartmen =$data['id_departmen'];
$namadepartmen =$data['nama_departmen'];
$noabsen = $data['no_absen'];
$namakaryawan = $data['nama_karyawan'];
$tempatlahir = $data['tempat_lahir'];
$tgllahir = $data['tgl_lahir'];
$agama = $data['agama'];
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
$idjadwal = $data['id_jadwal'];
$keterangan = $data['keterangan'];
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
$idjadwal = "";
$keterangan = "";
}


?>
<div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-primary"  >
                    <div class="box-header with-border" style=" background-color:#5F9EA0; border:1px ; color:white; ">
              <h3 class="box-title">Pengaturan Shift</h3>
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
                    <input placeholder="*" autocomplete="off" type="text" name="tnoabsen" value="<?php echo $noabsen; ?>" required class="form-control"/>
                    
                </div>
          
              <div class="form-group col-md-4">
                    <label class="font-weight-bold">Bagian</label>

                     <select class="form-control" name="tdepartmen" required>
                       <option value="<?php echo $iddepartmen; ?>"><?php echo $namadepartmen; ?></option>
                                           <?php 
                        $sql = $koneksi->query("select * from ms_departmen");
                            
                        while ($dataRow =  $sql->fetch_array()) {
                        if ($dataBagian == $dataRow['nama_departmen']) {
                        $cek1 = " selected";
                        } else { $cek1=""; }
                        echo "<option value='$dataRow[id_departmen]' $cek>$dataRow[nama_departmen]</option>";
                        }
                        ?>
                                            </select>
                    
                </div>
                
                  <div class="form-group col-md-4">
                    <label class="font-weight-bold">Shift</label>
                    <select name="tshift" class="form-control" required>
                       <option value="<?php echo $idjadwal; ?>"><?php echo $keterangan; ?></option>
                                           <?php 
                        $sql = $koneksi->query("select * from tb_jadwal");
                            
                        while ($datajadwalRow =  $sql->fetch_array()) {
                        if ($dataBagianjadwal == $datajadwalRow['keterangan']) {
                        $cek1= " selected";
                        } else { $cek=""; }
                        echo "<option value='$datajadwalRow[id_jadwal]' $cek>$datajadwalRow[keterangan]</option>";
                        }
                        ?>
                        </select>
                    
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
$tshift = @$_POST ['tshift'];
$tnoabsen = @$_POST ['tnoabsen'];
$tdepartmen = @$_POST ['tdepartmen'];
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
$sql = $koneksi->query("insert into ms_karyawan(id_departmen,id_jabatan,no_absen,nama_karyawan,tempat_lahir,tgl_lahir,agama,status_kawin,jenis_kelamin,no_ktp,no_sim,alamat_ktp,alamat_tinggal,status_karyawan,tgl_aktif,no_npwp,no_bpjs) values('$tdepartmen','$tjabatan','$tnoabsen','$tnama','$ttempatlahir','$ttanggallahir','$tagama','$tstatuskawin','$tjeniskelamin','$tnoktp','$tnosim','$talamatktp','$talamattinggal','Aktif','$ttanggalbergabung','$tnpwp','$tbpjs')  ");
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
$sql = $koneksi->query("update ms_karyawan set  id_jadwal = '$tshift' where id_karyawan = '$idu'  ");
if($sql) {
        ?>
                <script type="text/javascript">
                alert("Shift Berhasil Di Atur");
                window.location.href="?page=karyawan";

            </script>
            <?php
    }
}//update if
?>