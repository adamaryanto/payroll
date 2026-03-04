<?php


if(isset($_GET['id'])){
   $id = $_GET['id'];

  $tampildetail=$koneksi->query("select A.*, B.keterangan as keterangan_realisasi, B.tgl_realisasi,B.detail_realisasi,B.jam_kerja , C.keterangan as shift 
                                  , BB.no_absen , BC.nama_sub_department ,BB.nama_karyawan , BD.nama_departmen , BB.jenis_kelamin
                                  from 
                                  tb_realisasi_detail A left join
                                  tb_realisasi B on A.id_realisasi = B.id_realisasi
                                  left join tb_jadwal C on A.id_jadwal = C.id_jadwal
                                  LEFT JOIN ms_karyawan BB on A.id_karyawan = BB.id_karyawan
                                 
                                  LEFT JOIN ms_departmen BD on BB.id_departmen = BD.id_departmen

                                     left join ms_sub_department BC on BB.id_sub_department = BC.id_sub_department
                                   where A.id_realisasi_detail = '$id' ");


$datadetail=$tampildetail->fetch_assoc();
$dataidrealisasi = $datadetail['id_realisasi'];
$datatglrealisasi = $datadetail['tgl_realisasi'];
$dataketeranganrealisasi = $datadetail['keterangan_realisasi'];
$datadetailrealisasi   = $datadetail['detail_realisasi'];
$datajamkerja   = $datadetail['jam_kerja'];
$dataidrkkk   = $datadetail['id_rkk_detail'];
$thasilkerja   = $datadetail['hasil_kerja'];

$datajammasuk = $datadetail['r_jam_masuk'];
$datajamkeluar = $datadetail['r_jam_keluar'];
$dataistirahatmasuk = $datadetail['r_istirahat_masuk'];
$datajamistirahatkeluar = $datadetail['r_istirahat_keluar'];

$datajamabsenmasuk = $datadetail['ra_masuk'];
$datajamabsenkeluar = $datadetail['ra_keluar'];
$dataabsenistirahatmasuk = $datadetail['ra_istirahat_masuk'];
$dataabsenjamistirahatkeluar = $datadetail['ra_istirahat_keluar'];


$dataidjadwal =$datadetail['id_jadwal'];
$dataketerangan =$datadetail['shift'];

$datanoabsen =$datadetail['no_absen'];
$datanamakaryawan =$datadetail['nama_karyawan'];
$databagian =$datadetail['nama_departmen'];
$datasubbagian =$datadetail['nama_sub_department'];
$datajenkel =$datadetail['jenis_kelamin'];

$dataupah =$datadetail['r_upah'];
$datalembur =$datadetail['lembur'];
$datapotongantelat =$datadetail['r_potongan_telat'];
$datapotonganistirahat =$datadetail['r_potongan_istirahat'];
$datapotonganlainnya =$datadetail['r_potongan_lainnya'];

$status_realisasi_detail = $datadetail['status_realisasi_detail'];


 $tampildetailrkk=$koneksi->query("select A.*, B.keterangan as keterangan_rkk, B.tgl_rkk,B.detail_rkk,B.jam_kerja , C.keterangan as shift 
                                  , BB.no_absen , BC.nama_sub_department ,BB.nama_karyawan , BD.nama_departmen , BB.jenis_kelamin
                                  from 
                                  tb_rkk_detail A left join
                                  tb_rkk B on A.id_rkk = B.id_rkk
                                  left join tb_jadwal C on A.id_jadwal = C.id_jadwal
                                  LEFT JOIN ms_karyawan BB on A.id_karyawan = BB.id_karyawan
                                 
                                  LEFT JOIN ms_departmen BD on BB.id_departmen = BD.id_departmen

                                     left join ms_sub_department BC on BB.id_sub_department = BC.id_sub_department
                                   where A.id_rkk_detail = '$dataidrkkk' ");


$datadetailrkk = $tampildetailrkk->fetch_assoc();

$dataidrkk              = $datadetailrkk['id_rkk'];
$datatglrkk             = $datadetailrkk['tgl_rkk'];
$dataketeranganrkk      = $datadetailrkk['keterangan_rkk'];
$detail_rkk             = $datadetailrkk['detail_rkk'];  // <-- pakai variabel lain
$datajamkerjarkk        = $datadetailrkk['jam_kerja'];
$datashiftrkk           = $datadetailrkk['shift'];
$datajadwalrkk          = $datadetailrkk['id_jadwal'];
$datajammasukrkk        = $datadetailrkk['jam_masuk'];
$datajamkeluarrkk       = $datadetailrkk['jam_keluar'];
$dataistirahatmasukrkk  = $datadetailrkk['istirahat_masuk'];
$dataistirahatkeluarrkk = $datadetailrkk['istirahat_keluar'];
$datapotongan_telatrkk  = $datadetailrkk['potongan_telat'];
$datapotonganistirahatrkk = $datadetailrkk['potongan_istirahat'];
$datapotonganlainnyarkk = $datadetailrkk['potongan_lainnya'];
$dataupahrkk            = $datadetailrkk['upah'];

$uid = $datanoabsen ;
$ttgl = $datatglrealisasi ;

 $tampildetailabsen =$koneksi->query("SELECT 
    '$uid' AS userid,
    '$ttgl' AS tgl,

    -- ABSEN MASUK (status = 0, ambil paling awal)
    (
        SELECT TIME_FORMAT(detail_waktu, '%H:%i')
        FROM tb_record
        WHERE userid = '$uid'
          AND tgl = '$ttgl'
          AND status = 0
        ORDER BY detail_waktu ASC
        LIMIT 1
    ) AS absen_masuk,

    -- ISTIRAHAT KELUAR (status = 2)
    (
        SELECT TIME_FORMAT(detail_waktu, '%H:%i')
        FROM tb_record
        WHERE userid = '$uid'
          AND tgl = '$ttgl'
          AND status = 2
        ORDER BY detail_waktu ASC
        LIMIT 1
    ) AS istirahat_keluar,

    -- ISTIRAHAT MASUK (status = 3)
    (
        SELECT TIME_FORMAT(detail_waktu, '%H:%i')
        FROM tb_record
        WHERE userid = '$uid'
          AND tgl = '$ttgl'
          AND status = 3
        ORDER BY detail_waktu ASC
        LIMIT 1
    ) AS istirahat_masuk,

    -- ABSEN KELUAR (status = 1, ambil paling akhir)
    (
        SELECT TIME_FORMAT(detail_waktu, '%H:%i')
        FROM tb_record
        WHERE userid = '$uid'
          AND tgl = '$ttgl'
          AND status = 1
        ORDER BY detail_waktu DESC
        LIMIT 1
    ) AS absen_keluar;

 ");


$datadetailabsen =  $tampildetailabsen->fetch_assoc();

$absensimasuk =$datadetailabsen['absen_masuk'];
$absensikeluar =$datadetailabsen['absen_keluar'];
$absensiistirahatmasuk =$datadetailabsen['istirahat_masuk'];
$absensiistirahatkeluar =$datadetailabsen['istirahat_keluar'];

// Konversi jam:menit ke timestamp
$jamMasukAbsensi = strtotime($absensimasuk);
$jamMasukRKK     = strtotime($datajammasuk);

// Hitung hasil potongan telat masuk
if ($jamMasukAbsensi > $jamMasukRKK) {
    $hasil = $datapotongan_telatrkk;
} else {
    $hasil = 0;
}

// Konversi jam:menit ke timestamp
$jamIstirahatMasukAbsensi = strtotime($absensiistirahatmasuk);
$jamIstirahatRKK          = strtotime($dataistirahatmasuk);

// Hitung potongan istirahat
if ($jamIstirahatMasukAbsensi > $jamIstirahatRKK) {
    $adalah = $datapotonganistirahatrkk;
} else {
    $adalah = 0;
}


//Jika status_realisasi_detail = 1 maka ngambil dari database tb_realisasi_detail
//Jika status_realisasi_detail = 0 maka ngambil dari tb_record
if($status_realisasi_detail == 0){
$hasilabsenmasuk = $absensimasuk ;
$hasilabsenkeluar= $absensikeluar ;
$hasilabsenistirahatmasuk = $absensiistirahatmasuk ;
$hasilabsenistirahatkeluar = $absensiistirahatkeluar ;
$hasilpotongantelat = $hasil;
$hasilpotonganistirahat = $adalah;
}else{
$hasilabsenmasuk = $datajamabsenmasuk ;
$hasilabsenkeluar= $datajamabsenkeluar ;
$hasilabsenistirahatmasuk = $dataabsenistirahatmasuk ;
$hasilabsenistirahatkeluar = $dataabsenjamistirahatkeluar ;

$hasilpotongantelat = $datapotongantelat ;
$hasilpotonganistirahat = $datapotonganistirahat ;
}



}else{
  $datatglrealisasi = "";
  $dataketeranganrealisasi = "";
$datadetailrealisasi   = "";
$datajamkerja   = "";

$datajammasuk = "";
$datajamkeluar = "";
$dataistirahatmasuk = "";
$datajamistirahatkeluar = "";
$dataidjadwal = "";
$dataketerangan ="";

$datanoabsen ="";
$datanamakaryawan ="";
$databagian ="";
$datasubbagian ="";
$datajenkel ="";

$dataupah ="";
$datapotongantelat ="";
$datapotonganistirahat ="";
$datapotonganlainnya ="";
}




?>
<div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-primary"  >
                    <div class="box-header with-border" style=" background-color:#5F9EA0; border:1px ; color:white; ">
              <h3 class="box-title">Detail Realisasi Upah</h3>
            </div>
             <form method="POST"  enctype="multipart/form-data">
                        <div class="panel-body">
                           <div class="panel-body">

<div class="box-header with-border" >
              <h3 class="box-title">Rencana Upah</h3>
            </div>
            <div class="panel-body">
                   <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                            <div hidden="hidden"  class="form-group col-md-4">
                    <label class="font-weight-bold">Id Karyawan</label>
                    
                    <input  autocomplete="off" type="text" name="tid"   class="form-control"/>
                </div>
                <div class="form-group col-md-3">
                    <label class="font-weight-bold">Tanggal</label>
                    <input placeholder="*" autocomplete="off" type="date" value="<?php echo $datatglrkk; ?>"  class="form-control"/>
                    
                </div>

                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">Keterangan </label>
                    <input placeholder="*" autocomplete="off" type="text"  value ="<?php echo $dataketeranganrkk ; ?>"  class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-2">
                    <label class="font-weight-bold">Jam Kerja</label>
                   <input placeholder="*" autocomplete="off" type="number" value="<?php echo $datajamkerjarkk; ?>"  class="form-control"/>
                    
                </div>
                <div class="form-group col-md-4">
                    <label class="font-weight-bold">Shift </label>
                    <select class="form-control"  >
                     <option value="<?php echo $datashiftrkk ?>"><?php echo $datajadwalrkk ?></option>
                                           <?php 
                        $sql = $koneksi->query("select * from tb_jadwal ");
                            
                        while ($datashiftRow =  $sql->fetch_array()) {
                        if ($datashiftBagian == $datashiftRow['keterangan']) {
                        $cek1 = " selected";
                        } else { $cek1=""; }
                        echo "<option value='$datashiftRow[id_jadwal]' $cek>$datashiftRow[keterangan]</option>";
                        }
                        ?>
                    </select>
                </div>
                 <div class="form-group col-md-2">
                    <label class="font-weight-bold">Jam Masuk</label>
                   <input placeholder="*" autocomplete="off" type="time" value="<?php echo $datajammasukrkk ?>"  class="form-control"/>
                    
                </div>
                  <div class="form-group col-md-2">
                    <label class="font-weight-bold">Jam Keluar</label>
                   <input placeholder="*" autocomplete="off" type="time"  value="<?php echo $datajamkeluarrkk ?>"  class="form-control"/>
                    
                </div>
                  <div class="form-group col-md-2">
                    <label class="font-weight-bold">Istirahat Masuk</label>
                   <input placeholder="*" autocomplete="off" type="time"  value="<?php echo $dataistirahatmasukrkk ?>"  class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-2">
                    <label class="font-weight-bold">Istirahat Keluar</label>
                   <input placeholder="*" autocomplete="off" type="time"  value="<?php echo $dataistirahatkeluarrkk ?>"  class="form-control"/>
                    
                </div>

 
                 <div class="form-group col-md-2">
                    <label class="font-weight-bold">Upah</label>
                   <input placeholder="*" autocomplete="off" type="text"  value="<?php echo "Rp. " . number_format($dataupahrkk,0,',','.') ?>"    class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-2">
                    <label class="font-weight-bold">Potongan Telat</label>
                   <input placeholder="*" autocomplete="off" type="text"  value="<?php echo "Rp. " . number_format($datapotongan_telatrkk,0,',','.') ?>"   class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-2">
                    <label class="font-weight-bold">Potongan Istirahat</label>
                   <input placeholder="*" autocomplete="off" type="text"  value="<?php echo "Rp. " . number_format($datapotonganistirahatrkk,0,',','.') ?>"   class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-2">
                    <label class="font-weight-bold">Potongan Lainnya</label>
                   <input placeholder="*" autocomplete="off" type="text"  value="<?php echo "Rp. " . number_format($datapotonganlainnyarkk,0,',','.') ?>"   class="form-control"/>
                    
                </div>


                  </div>


                  
                        </div>


  <div class="box-header with-border" >
              <h3 class="box-title">Realisasi Upah</h3>
            </div>
            <div class="panel-body">
                   <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                            <div hidden="hidden"  class="form-group col-md-4">
                    <label class="font-weight-bold">Id Karyawan</label>
                    
                    <input  autocomplete="off" type="text" name="tid"   class="form-control"/>
                </div>
                <div class="form-group col-md-3">
                    <label class="font-weight-bold">Tanggal</label>
                    <input placeholder="*" autocomplete="off" type="date" name="ttgl1" value="<?php echo $datatglrealisasi; ?>"  class="form-control"/>
                    
                </div>

                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">Keterangan </label>
                    <input placeholder="*" autocomplete="off" type="text" name="tketerangan" value ="<?php echo $dataketeranganrealisasi ; ?>"  class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-2">
                    <label class="font-weight-bold">Jam Kerja</label>
                   <input placeholder="*" autocomplete="off" type="number" name="tjamkerja" value="<?php echo $datajamkerja; ?>"  class="form-control"/>
                    
                </div>
                <div class="form-group col-md-4">
                    <label class="font-weight-bold">Shift </label>
                    <select class="form-control"  required>
                     <option value="<?php echo $dataidjadwal ?>"><?php echo $dataketerangan ?></option>
                                           <?php 
                        $sql = $koneksi->query("select * from tb_jadwal ");
                            
                        while ($datashiftRow =  $sql->fetch_array()) {
                        if ($datashiftBagian == $datashiftRow['keterangan']) {
                        $cek1 = " selected";
                        } else { $cek1=""; }
                        echo "<option value='$datashiftRow[id_jadwal]' $cek>$datashiftRow[keterangan]</option>";
                        }
                        ?>
                    </select>
                </div>
                 <div class="form-group col-md-2">
                    <label class="font-weight-bold">Jam Masuk</label>
                   <input placeholder="*" autocomplete="off" type="time" value="<?php echo $datajammasuk ?>"  class="form-control"/>
                    
                </div>
                  <div class="form-group col-md-2">
                    <label class="font-weight-bold">Jam Keluar</label>
                   <input placeholder="*" autocomplete="off" type="time"  value="<?php echo $datajamkeluar ?>"  class="form-control"/>
                    
                </div>
                  <div class="form-group col-md-2">
                    <label class="font-weight-bold">Istirahat Masuk</label>
                   <input placeholder="*" autocomplete="off" type="time"  value="<?php echo $dataistirahatmasuk ?>"  class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-2">
                    <label class="font-weight-bold">Istirahat Keluar</label>
                   <input placeholder="*" autocomplete="off" type="time"  value="<?php echo $datajamistirahatkeluar ?>"  class="form-control"/>
                    
                </div>
                  </div>


                  
                        </div>

                            <div class="box-header with-border" >
              <h3 class="box-title">Data Karyawan</h3>
            </div>
            <div class="panel-body">
              <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                     
                     <div class="form-group col-md-4">
                    <label class="font-weight-bold">No. Absen </label>
                    <input placeholder="*" autocomplete="off" type="text" name="tketerangan" value ="<?php echo $datanoabsen ; ?>"  class="form-control"/>
                    </div>

                     <div class="form-group col-md-4">
                    <label class="font-weight-bold">Nama</label>
                    <input placeholder="*" autocomplete="off" type="text"  value ="<?php echo $datanamakaryawan ; ?>"  class="form-control"/>
                    </div>

                     <div class="form-group col-md-4">
                    <label class="font-weight-bold">Bagian </label>
                    <input placeholder="*" autocomplete="off" type="text" value ="<?php echo $databagian ; ?>"  class="form-control"/>
                    </div>

                     <div class="form-group col-md-4">
                    <label class="font-weight-bold">Sub Bagian</label>
                    <input placeholder="*" autocomplete="off" type="text" value ="<?php echo $datasubbagian ; ?>"  class="form-control"/>
                    </div>

                     <div class="form-group col-md-4">
                    <label class="font-weight-bold">Jenis Kelamin </label>
                    <input placeholder="*" autocomplete="off" type="text" value ="<?php echo $datajenkel ; ?>"  class="form-control"/>
                    </div>

                </div>

            </div>
                           


                  


                  



  <div class="box-header with-border" >
              <h3 class="box-title">Detail</h3>
            </div>
            
                        <div class="panel-body">


  <div class="form-group col-md-4">
                    <label class="font-weight-bold">Shift </label>
                    <select class="form-control" name="tshift" required>
                     <option value="<?php echo $dataidjadwal ?>"><?php echo $dataketerangan ?></option>
                                           <?php 
                        $sql = $koneksi->query("select * from tb_jadwal ");
                            
                        while ($datashiftRow =  $sql->fetch_array()) {
                        if ($datashiftBagian == $datashiftRow['keterangan']) {
                        $cek1 = " selected";
                        } else { $cek1=""; }
                        echo "<option value='$datashiftRow[id_jadwal]' $cek>$datashiftRow[keterangan]</option>";
                        }
                        ?>
                    </select>
                </div>
                 
                 <div class="form-group col-md-2">
                    <label class="font-weight-bold">Absen Masuk</label>
                   <input placeholder="*" autocomplete="off" type="time" name="tjammasuk" value="<?php echo $hasilabsenmasuk ?>" required class="form-control"/>
                    
                </div>
                  <div class="form-group col-md-2">
                    <label class="font-weight-bold">Absen Keluar</label>
                   <input placeholder="*" autocomplete="off" type="time" name="tjamkeluar" value="<?php echo $hasilabsenkeluar ?>" required class="form-control"/>
                    
                </div>
                  <div class="form-group col-md-2">
                    <label class="font-weight-bold">Absen Istirahat Masuk</label>
                   <input placeholder="*" autocomplete="off" type="time" name="tistirahatmasuk" value="<?php echo $hasilabsenistirahatmasuk  ?>" required class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-2">
                    <label class="font-weight-bold">Absen Istirahat Keluar</label>
                   <input placeholder="*" autocomplete="off" type="time" name="tistirahatkeluar" value="<?php echo $hasilabsenistirahatkeluar ?>" required class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-2">
                    <label class="font-weight-bold">Upah</label>
                   <input placeholder="*" autocomplete="off" type="number" name="tupah" value="<?php echo $dataupah ?>"   required class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-2">
                    <label class="font-weight-bold">Potongan Telat</label>
                   <input placeholder="*" autocomplete="off" type="number" name="tpottelat"  value="<?php echo $hasilpotongantelat ?>"  required class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-2">
                    <label class="font-weight-bold">Potongan Istirahat</label>
                   <input placeholder="*" autocomplete="off" type="number" name="tpotistirahat"  value="<?php echo $hasilpotonganistirahat ?>"  required class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-2">
                    <label class="font-weight-bold">Potongan Lainnya</label>
                   <input placeholder="*" autocomplete="off" type="number" name="tpotlainnya" value="<?php echo $datapotonganlainnya ?>"  required class="form-control"/>
                    
                </div>
                 <div class="form-group col-md-2">
                    <label class="font-weight-bold">Lembur</label>
                   <input placeholder="*" autocomplete="off" type="number" name="tlembur" value="<?php echo $datalembur ?>"  required class="form-control"/>
                    
                </div>

              <div class="form-group col-md-2">
                    <label class="font-weight-bold">Hasil Kerja</label>
                   <input placeholder="*" autocomplete="off" type="text" name="thasilkerja" value="<?php echo $thasilkerja ?>"  required class="form-control"/>
                    
                </div>


</div>

 <div class="panel-body">

<div class="row" style=" background-color:white; border:1px ; color:black; "> 
<div class="form-group col-md-4">
        <a href="?page=realisasi&aksi=kelola&id=<?php echo $dataidrealisasi ?>"
   class="btn btn-warning" >
   << Kembali
</a>
                     
                       <input type="submit" name="simpan"  value="Simpan" class="btn btn-primary">
                      
                    </div>              
                    
                </div>

                </div>

 

   

                                    </form>
             
                                    </form>
                                  
                            </div>
                          
                           

                    </div>
                </div>
        </div>

<?php
$ttgl2 = date("Y-m-d H:i:s");
$tshift = @$_POST ['tshift'];
$tjammasuk = @$_POST ['tjammasuk'];
$tjamkeluar = @$_POST ['tjamkeluar'];
$tistirahatmasuk = @$_POST ['tistirahatmasuk'];
$tistirahatkeluar = @$_POST ['tistirahatkeluar'];
$tupah = @$_POST ['tupah'];
$tlembur = @$_POST ['tlembur'];
$tpottelat = @$_POST ['tpottelat'];
$tpotistirahat = @$_POST ['tpotistirahat'];
$tpotlainnya = @$_POST ['tpotlainnya'];
$simpan = @$_POST ['simpan'];
$hasilkerjanya = @$_POST ['thasilkerja'];
$kembali = @$_POST ['kembali'];


if($simpan) {

$sql = $koneksi->query("update tb_realisasi_detail set r_upah = '$tupah', id_jadwal='$tshift',r_potongan_telat='$tpottelat',r_potongan_istirahat='$tpotistirahat',r_potongan_lainnya='$tpotlainnya' , ra_masuk ='$tjammasuk' , ra_keluar ='$tjamkeluar' , ra_istirahat_masuk ='$tistirahatmasuk' , ra_istirahat_keluar = '$tistirahatkeluar' , r_update='$ttgl2' , status_realisasi_detail  = 1 , hasil_kerja = '$hasilkerjanya',lembur = '$tlembur' where id_realisasi_detail = '$id' ");
if($sql) {
        ?>
                <script type="text/javascript">
                alert("Data Tersimpan");
                window.location.href="?page=realisasi&aksi=kelola&id=<?php echo $dataidrealisasi ?>";

            </script>
            <?php
    }
}//simpan if
elseif($kembali) {


if($sql) {
        ?>
                <script type="text/javascript">
              
                window.location.href="?page=realisasi&aksi=kelola&id=<?php echo $dataidrealisasi ?>";

            </script>
            <?php
    }
}//simpan if
?>