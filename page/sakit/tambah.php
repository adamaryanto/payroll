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
              <h3 class="box-title">Tambah Data Karyawan Sakit</h3>
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
                    <label class="font-weight-bold">Dari Tanggal</label>
                    <input placeholder="*" autocomplete="off" type="date" name="ttgl1" value="<?php echo date("Y-m-d"); ?>" required class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-3">
                    <label class="font-weight-bold">Sampai Tanggal</label>
                    <input placeholder="*" autocomplete="off" type="date" name="ttgl2" value="<?php echo date("Y-m-d"); ?>" required class="form-control"/>
                    
                </div>
                
                 

                </div>

 <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                   <div class="form-group col-md-4">
                    <label class="font-weight-bold">Keterangan</label>
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
$ttgl2 = @$_POST ['ttgl2'];
$tketerangan = @$_POST ['tketerangan'];

$tgldetail = date("Y-m-d-H-i-s");
$tgl = date("Y-m-d");
$tgl1 = strtotime($ttgl1); 
$tgl2 = strtotime($ttgl1); 

$jarak = $tgl2 - $tgl1;

$hari = $jarak / 60 / 60 / 24;
$simpan = @$_POST ['simpan'];
if($simpan) {
if ($idkaryawan_post != "") { $idkaryawan = $idkaryawan_post; }
$sql = $koneksi->query("insert into tb_sia (id_karyawan,tgl_pengajuan,tgl_awal,tgl_akhir,lama,keterangan) values ('$idkaryawan','$tgl','$ttgl1','$ttgl2','$hari','$tketerangan')  ");
if($sql) {
        ?>
                <script type="text/javascript">
                alert("Data Tersimpan");
                window.location.href="?page=sakit";

            </script>
            <?php
    }
}//simpan if
?>