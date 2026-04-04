
<div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-primary"  >
                    <div class="box-header with-border" style=" background-color:#5F9EA0; border:1px ; color:white; ">
              <h3 class="box-title">Tambah Data Karyawan</h3>
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
                    <label class="font-weight-bold">Nama </label>
                    <input placeholder="*" autocomplete="off" type="text" name="tnama" required class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">No. Absen</label>
                    <input placeholder="*" autocomplete="off" type="text" name="tnoabsen"  required class="form-control"/>
                    
                </div>
          
             <div class="form-group col-md-4">
                    <label class="font-weight-bold">Bagian</label>

                     <select class="form-control" name="tdepartmen" required>
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
                    <label class="font-weight-bold">Sub Bagian</label>

                     <select class="form-control" name="tdepartmensub" required>
                                           <?php 
                        $sql = $koneksi->query("select * from ms_sub_department");
                            
                        while ($dataRow =  $sql->fetch_array()) {
                        if ($dataBagian == $dataRow['nama_sub_department']) {
                        $cek1 = " selected";
                        } else { $cek1=""; }
                        echo "<option value='$dataRow[id_sub_department]' $cek>$dataRow[nama_sub_department]</option>";
                        }
                        ?>
                                            </select>
                    
                </div>
                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">OS/DHK </label>
                    <input placeholder="*" autocomplete="off" type="text" name="tos" required class="form-control"/>
                    
                </div>
                <div class="form-group col-md-4">
                    <label class="font-weight-bold">Golongan</label>
                    <select name="tgolongan" class="form-control" >
                     
                        <option value="Harian">Harian</option>
                         <option value="Mingguan">Mingguan</option>
                         <option value="Bulanan">Bulanan</option>
                         
                        </select>
                    
                </div>
                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">Tempat Lahir</label>
                    <input placeholder="*" autocomplete="off" type="text" name="ttempatlahir"   class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">Tanggal Lahir</label>
                    <input placeholder="*" autocomplete="off" type="date" name="ttanggallahir"   class="form-control"/>
                    
                </div>
                  <div class="form-group col-md-4">
                    <label class="font-weight-bold">Agama</label>
                    <select name="tagama" class="form-control" >
                     
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
                  
                        <option value="Belum Kawin">Belum Kawin</option>
                         <option value="Kawin">Kawin</option>
                          <option value="Janda">Janda</option>
                           <option value="Duda">Duda</option>
                        </select>
                    
                </div>
                  <div class="form-group col-md-4">
                    <label class="font-weight-bold">Jenis Kelamin</label>
                  <select name="tjeniskelamin" class="form-control" >
                   
                        <option value="Laki-laki">Laki-laki</option>
                         <option value="Perempuan">Perempuan</option>
                        </select>
                    
                </div>
                  <div class="form-group col-md-4">
                    <label class="font-weight-bold">No.KTP</label>
                    <input placeholder="*" autocomplete="off" type="text" name="tnoktp"   class="form-control"/>
                    
                </div>
                  <div class="form-group col-md-4">
                    <label class="font-weight-bold">No. SIM</label>
                    <input placeholder="*" autocomplete="off" type="text" name="tnosim"   class="form-control"/>
                    
                </div>
                  <div class="form-group col-md-4">
                    <label class="font-weight-bold">Alamat KTP</label>
                    <input placeholder="*" autocomplete="off" type="text" name="talamatktp"   class="form-control"/>
                    
                </div>
                  <div class="form-group col-md-4">
                    <label class="font-weight-bold">Alamat Tinggal</label>
                    <input placeholder="*" autocomplete="off" type="text" name="talamattinggal"   class="form-control"/>
                    
                </div>
                  <div class="form-group col-md-4">
                    <label class="font-weight-bold">Tanggal Bergabung</label>
                    <input placeholder="*" autocomplete="off" type="date" name="ttanggalbergabung"   class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">No. NPWP</label>
                    <input  autocomplete="off" type="number" name="tnpwp"  class="form-control"/>
                    
                </div>
                <div class="form-group col-md-4">
                    <label class="font-weight-bold">No. BPJS</label>
                    <input  autocomplete="off" type="number" name="tbpjs" class="form-control"/>
                    
                </div>
                   <div class="form-group col-md-4">
                    <label class="font-weight-bold">Shift</label>
                    <select name="tshift" class="form-control" >
                       
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
               <div class="form-group col-md-4">
                    <label class="font-weight-bold">Upah Harian</label>
                    <input placeholder="*" autocomplete="off" type="text" name="tharian" value=""  class="form-control"/>
                    
                </div>

                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">Upah Mingguan</label>
                    <input placeholder="*" autocomplete="off" type="text" name="tmingguan" value=""  class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">Upah Bulanan</label>
                    <input placeholder="*" autocomplete="off" type="text" name="tbulanan" value=""  class="form-control"/>
                    
                </div>
                
                  
                </div>







                  <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                    <div class="form-group col-md-4">
                 <div>
                                            <input type="submit" name="simpan"  value="Simpan" class="btn btn-primary">
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
$tdepartmen = @$_POST ['tdepartmen'];
$tdepartmensub = @$_POST ['tdepartmensub'];
$tjabatan = @$_POST ['tjabatan'];
$tos = @$_POST ['tos'];
$tgolongan = @$_POST ['tgolongan'];
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
$tshift = @$_POST ['tshift'];
$tharian = @$_POST ['tharian'];
$tmingguan = @$_POST ['tmingguan'];
$tbulanan = @$_POST ['tbulanan'];
$simpan = @$_POST ['simpan'];
$update = @$_POST ['update'];
$iduser = $_SESSION['iduser'];
//$idperusahaan = $_SESSION['idperusahaan'];
if($simpan) {
$sql = $koneksi->query("insert into ms_karyawan(id_departmen,id_jabatan,no_absen,nama_karyawan,tempat_lahir,tgl_lahir,agama,status_kawin,jenis_kelamin,no_ktp,no_sim,alamat_ktp,alamat_tinggal,status_karyawan,tgl_aktif,no_npwp,no_bpjs,id_jadwal,upah_harian,upah_mingguan,upah_bulanan,id_sub_department,OS_DHK,golongan) values('$tdepartmen','$tjabatan','$tnoabsen','$tnama','$ttempatlahir','$ttanggallahir','$tagama','$tstatuskawin','$tjeniskelamin','$tnoktp','$tnosim','$talamatktp','$talamattinggal','Aktif','$ttanggalbergabung','$tnpwp','$tbpjs','$tshift','$tharian','$tmingguan','$tbulanan','$tdepartmensub','$tos','$tgolongan')  ");
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
$sql = $koneksi->query("update ms_karyawan set id_departmen ='$tdepartmen' ,id_jabatan = '$tjabatan' ,no_absen ='$tnoabsen' ,nama_karyawan = '$tnama',tempat_lahir = '$ttempatlahir' ,tgl_lahir = '$ttanggallahir',agama ='$tagama' ,status_kawin = '$tstatuskawin',jenis_kelamin = '$tjeniskelamin',no_ktp = '$tnoktp' ,no_sim = '$tnosim',alamat_ktp = '$talamatktp',alamat_tinggal = '$talamattinggal' , tgl_aktif = '$ttanggalbergabung' where id_karyawan = '$idu'  ");
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