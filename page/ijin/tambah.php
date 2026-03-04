<?php


if(isset($_GET['idkaryawan'])){
   $idkaryawan = $_GET['idkaryawan'];

  $tampildetail=$koneksi->query("select * from ms_karyawan where id_karyawan = '$idkaryawan' ");
$datadetail=$tampildetail->fetch_assoc();
$datanamakaryawan = $datadetail['nama_karyawan'];
$datanoabsen   = $datadetail['no_absen'];

}else{
  $idproduk = "";
$datanamakaryawan = "";
$datanoabsen = "";
}

?>
<div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-primary"  >
                    <div class="box-header with-border" style=" background-color:#5F9EA0; border:1px ; color:white; ">
              <h3 class="box-title">Tambah Data Karyawan Ijin</h3>
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
                    <select name="idkaryawan" id="idkaryawan" class="form-control" required onchange="
                        var selected = this.options[this.selectedIndex];
                        document.getElementById('tnoabsen').value = selected.getAttribute('data-absen');
                        document.getElementById('tnama').value = selected.getAttribute('data-nama');
                    ">
                        <option value="">-- Pilih Karyawan --</option>
                        <?php
                        $sql_kry = $koneksi->query("SELECT * FROM ms_karyawan ORDER BY nama_karyawan ASC");
                        while($dkry = $sql_kry->fetch_assoc()) {
                            $sel = ($idkaryawan == $dkry['id_karyawan']) ? 'selected' : '';
                            echo "<option value='".$dkry['id_karyawan']."' data-absen='".$dkry['no_absen']."' data-nama='".addslashes($dkry['nama_karyawan'])."' $sel>".$dkry['nama_karyawan']." (".$dkry['no_absen'].")</option>";
                        }
                        ?>
                    </select>
                    <input type="hidden" name="tnama" id="tnama" value="<?php echo $datanamakaryawan; ?>">
                </div>
                 <div class="form-group col-md-2">
                    <label class="font-weight-bold">No. Absen</label>
                    <input placeholder="*" autocomplete="off" type="text" name="tnoabsen" id="tnoabsen" value ="<?php echo $datanoabsen; ?>" readonly required class="form-control"/>
                    
                </div>
          
             <div class="form-group col-md-3">
                    <label class="font-weight-bold">Tanggal Ijin</label>
                    <input placeholder="*" autocomplete="off" type="date" name="ttgl1" value="<?php echo date("Y-m-d"); ?>" required class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-3">
                    <label class="font-weight-bold">Jenis Ijin</label>
                   <select name="tjenis" class="form-control" required>
                     
                        <option value="Datang Terlambat">Datang Terlambat</option>
                         <option value="Pulang Lebih Awal">Pulang Lebih Awal</option>
                         <option value="Meninggalkan Tempat Kerja">Meninggalkan Tempat Kerja</option>
                         
                        </select>
                    
                </div>
                
                
                 

                </div>

 <div class="row" style=" background-color:white; border:1px ; color:black; ">
  <div class="form-group col-md-3">
                    <label class="font-weight-bold">Dari Jam</label>
                    <input placeholder="*" autocomplete="off" type="time" name="ttime1" value="<?php echo date("H:i"); ?>" required class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-3">
                    <label class="font-weight-bold">Sampai Jam</label>
                    <input placeholder="*" autocomplete="off" type="time" name="ttime2" value="<?php echo date("H:i"); ?>" required class="form-control"/>
                    
                </div> 
                   <div class="form-group col-md-4">
                    <label class="font-weight-bold">Keterangan Ijin</label>
                    <input placeholder="*" autocomplete="off" type="text" name="tketerangan"  required class="form-control"/>
                    
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

$tid = @$_POST['tid'] ;
$idkaryawan_post = @$_POST['idkaryawan'];
$tnama = @$_POST ['tnama'];
$tnoabsen = @$_POST ['tnoabsen'];
$ttgl1 = @$_POST ['ttgl1'];
$tjenis = @$_POST ['tjenis'];
$ttime1 = @$_POST ['ttime1'];
$ttime2 = @$_POST ['ttime2'];
$tketerangan = @$_POST ['tketerangan'];

$tgldetail = date("Y-m-d-H-i-s");
$tgl = date("Y-m-d");
$simpan = @$_POST ['simpan'];
if($simpan) {
if ($idkaryawan_post != "") { $idkaryawan = $idkaryawan_post; }
$sql = $koneksi->query("insert into tb_ijin (id_karyawan,tgl_pengajuan_ijin,tgl_ijin,waktu_awal,waktu_akhir,keterangan,jenis_ijin) values ('$idkaryawan','$tgl','$ttgl1','$ttime1','$ttime2','$tketerangan','$tjenis')  ");
if($sql) {
        ?>
                <script type="text/javascript">
                alert("Data Tersimpan");
                window.location.href="?page=ijin";

            </script>
            <?php
    }
}//simpan if
?>