<?php

if(isset($_GET['tdepartmen'])) {
   $idd = $_GET['tdepartmen'];
  if($idd=='99'){
  $tiddepartmen = '99';
$tnamadepartmen = 'All';
  }
    else{
 
  $tampildepartmen = $koneksi->query("select * from ms_departmen where id_departmen = '$idd'");
$datadept=$tampildepartmen->fetch_assoc();
$tiddepartmen = $datadept['id_departmen'];
$tnamadepartmen = $datadept['nama_departmen'];
}
  }
  else{
    $tiddepartmen = '99';
$tnamadepartmen = 'All';
  }
  

  if(isset($_GET['tshift'])) {
    $idsf=$_GET['tshift'];
 $tampilshift = $koneksi->query("select * from tb_jadwal where id_jadwal = '$idsf' ");
$datashift=$tampilshift->fetch_assoc();
$idshift = $datashift['id_jadwal'];
$keterangan = $datashift['keterangan'];
$jammasuk = substr($datashift['jam_masuk'],0,3);
$jamkeluar = substr($datashift['jam_keluar'],0,3);
$istirahatmasuk = substr($datashift['istirahat_masuk'],0,3);
$istirahatkeluar = substr($datashift['istirahat_keluar'],0,3);
echo substr($istirahatkeluar,0,3);
  }else{
    $jammasuk = "";
$jamkeluar = "";
$istirahatmasuk = "";
$istirahatkeluar = "";
    $idshift = "";
$keterangan = "";
  }


if(isset($_GET['ttgl1']) || isset($_GET['ttgl2'])){
    $ttgl1 = $_GET['ttgl1'];
    $ttgl2 = $_GET['ttgl2'];

if ($tiddepartmen == '99'){


$tampil = $koneksi->query("
  
  SELECT DISTINCT A.tgl , A.userid , 
  
  (SELECT TIME_FORMAT(jam_masuk, '%H:%i') FROM tb_jadwal where id_jadwal = '$idshift') AS masukkerja  , 
  (SELECT TIME_FORMAT(jam_keluar, '%H:%i') FROM tb_jadwal where id_jadwal = '$idshift') AS pulangkerja ,
  
  (SELECT  TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE tgl = A.tgl AND userid = A.userid AND
 (detail_waktu LIKE '%06:%' OR detail_waktu LIKE '%07:%' OR detail_waktu LIKE '%08:%'
OR detail_waktu LIKE '%09:%'OR detail_waktu LIKE '%10:%'
 ) ORDER BY detail_waktu ASC LIMIT 1) AS 'masukkonvert' , 
 CASE WHEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) < '0'
 THEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) ELSE '' END AS 'masuklebihawal' ,
 
 CASE WHEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) > '0'
 THEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) ELSE '' END AS 'terlambat' ,
 
 (SELECT TIME_FORMAT(istirahat_keluar, '%H:%i') FROM tb_jadwal where id_jadwal = '$idshift') AS istirahatkeluar ,
(SELECT TIME_FORMAT(istirahat_masuk, '%H:%i') FROM tb_jadwal where id_jadwal = '$idshift') AS istirahatmasuk ,
(SELECT  TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE tgl = A.tgl AND userid = A.userid
 AND detail_waktu LIKE '%13:%' ORDER BY detail_waktu ASC LIMIT 1) AS 'istirahatkonvert' ,
 
 (SELECT  TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE tgl = A.tgl AND userid = A.userid
 AND detail_waktu LIKE '%17:%' ORDER BY detail_waktu DESC LIMIT 1) AS 'pulangkonvert' ,
  CASE WHEN (SELECT TIMEDIFF(pulangkonvert,pulangkerja)) < '0'
 THEN (SELECT TIMEDIFF(pulangkonvert,pulangkerja)) ELSE '' END AS 'pulanglebihawal' ,
 CASE WHEN (SELECT TIMEDIFF(pulangkonvert,pulangkerja)) > '0'
 THEN (SELECT TIMEDIFF(pulangkonvert,pulangkerja)) ELSE '' END AS 'lembur' ,
  CASE WHEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) > '0'
 THEN (SELECT denda_masuk FROM tb_denda) ELSE '0' END AS 'dendamasuk' ,
 
 CASE WHEN (SELECT TIMEDIFF(istirahatkonvert,istirahatmasuk)) > '0'
 THEN (SELECT denda_istirahat FROM tb_denda) ELSE '0' END AS 'dendaistirahat' ,
  
  B.nama_karyawan , B.upah_harian,
(select nama_departmen from ms_departmen WHERE id_departmen = B.id_departmen) as namadepartmen
   FROM tb_record A LEFT JOIN ms_karyawan B ON A.userid = B.no_absen
 WHERE A.tgl BETWEEN '$ttgl1' AND '$ttgl2'  AND B.nama_karyawan <> ''
 ORDER BY namadepartmen , A.tgl ASC
 
 
");
  
}else{
  $tampil = $koneksi->query("
  
  SELECT DISTINCT A.tgl , A.userid , 
  
  (SELECT TIME_FORMAT(jam_masuk, '%H:%i') FROM tb_jadwal where id_jadwal = '$idshift') AS masukkerja  , 
  (SELECT TIME_FORMAT(jam_keluar, '%H:%i') FROM tb_jadwal where id_jadwal = '$idshift') AS pulangkerja ,
  
  (SELECT  TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE tgl = A.tgl AND userid = A.userid AND
 (detail_waktu LIKE '%06:%' OR detail_waktu LIKE '%07:%' OR detail_waktu LIKE '%08:%'
OR detail_waktu LIKE '%09:%'OR detail_waktu LIKE '%10:%'
 ) ORDER BY detail_waktu ASC LIMIT 1) AS 'masukkonvert' , 
 CASE WHEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) < '0'
 THEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) ELSE '' END AS 'masuklebihawal' ,
 
 CASE WHEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) > '0'
 THEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) ELSE '' END AS 'terlambat' ,
 
 (SELECT TIME_FORMAT(istirahat_keluar, '%H:%i') FROM tb_jadwal where id_jadwal = '$idshift') AS istirahatkeluar ,
(SELECT TIME_FORMAT(istirahat_masuk, '%H:%i') FROM tb_jadwal where id_jadwal = '$idshift') AS istirahatmasuk ,
(SELECT  TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE tgl = A.tgl AND userid = A.userid
 AND detail_waktu LIKE '%13:%' ORDER BY detail_waktu ASC LIMIT 1) AS 'istirahatkonvert' ,
 
 (SELECT  TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE tgl = A.tgl AND userid = A.userid
 AND detail_waktu LIKE '%17:%' ORDER BY detail_waktu DESC LIMIT 1) AS 'pulangkonvert' ,
  CASE WHEN (SELECT TIMEDIFF(pulangkonvert,pulangkerja)) < '0'
 THEN (SELECT TIMEDIFF(pulangkonvert,pulangkerja)) ELSE '' END AS 'pulanglebihawal' ,
 CASE WHEN (SELECT TIMEDIFF(pulangkonvert,pulangkerja)) > '0'
 THEN (SELECT TIMEDIFF(pulangkonvert,pulangkerja)) ELSE '' END AS 'lembur' ,
  CASE WHEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) > '0'
 THEN (SELECT denda_masuk FROM tb_denda) ELSE '0' END AS 'dendamasuk' ,
 
 CASE WHEN (SELECT TIMEDIFF(istirahatkonvert,istirahatmasuk)) > '0'
 THEN (SELECT denda_istirahat FROM tb_denda) ELSE '0' END AS 'dendaistirahat' ,
  
  B.nama_karyawan , B.upah_harian,
(select nama_departmen from ms_departmen WHERE id_departmen = B.id_departmen) as namadepartmen
   FROM tb_record A LEFT JOIN ms_karyawan B ON A.userid = B.no_absen
 WHERE A.tgl BETWEEN '$ttgl1' AND '$ttgl2'  AND B.nama_karyawan <> '' and B.id_departmen = '$tiddepartmen'
 ORDER BY A.tgl , namadepartmen ASC
 
 
");
}

}else{
     $ttgl1 = date('Y-m-d');
    $ttgl2 = date('Y-m-d');

$tampil = $koneksi->query("SELECT DISTINCT A.tgl , A.userid , 
  
  (SELECT TIME_FORMAT(jam_masuk, '%H:%i') FROM tb_jadwal where id_jadwal = '$idshift') AS masukkerja  , 
  (SELECT TIME_FORMAT(jam_keluar, '%H:%i') FROM tb_jadwal where id_jadwal = '$idshift') AS pulangkerja ,
  
  (SELECT  TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE tgl = A.tgl AND userid = A.userid AND
 (detail_waktu LIKE '%06:%' OR detail_waktu LIKE '%07:%' OR detail_waktu LIKE '%08:%'
OR detail_waktu LIKE '%09:%'OR detail_waktu LIKE '%10:%'
 ) ORDER BY detail_waktu ASC LIMIT 1) AS 'masukkonvert' , 
 CASE WHEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) < '0'
 THEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) ELSE '' END AS 'masuklebihawal' ,
 
 CASE WHEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) > '0'
 THEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) ELSE '' END AS 'terlambat' ,
 
 (SELECT TIME_FORMAT(istirahat_keluar, '%H:%i') FROM tb_jadwal where id_jadwal = '$idshift') AS istirahatkeluar ,
(SELECT TIME_FORMAT(istirahat_masuk, '%H:%i') FROM tb_jadwal where id_jadwal = '$idshift') AS istirahatmasuk ,
(SELECT  TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE tgl = A.tgl AND userid = A.userid
 AND detail_waktu LIKE '%13:%' ORDER BY detail_waktu ASC LIMIT 1) AS 'istirahatkonvert' ,
 
 (SELECT  TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE tgl = A.tgl AND userid = A.userid
 AND detail_waktu LIKE '%17:%' ORDER BY detail_waktu DESC LIMIT 1) AS 'pulangkonvert' ,
  CASE WHEN (SELECT TIMEDIFF(pulangkonvert,pulangkerja)) < '0'
 THEN (SELECT TIMEDIFF(pulangkonvert,pulangkerja)) ELSE '' END AS 'pulanglebihawal' ,
 CASE WHEN (SELECT TIMEDIFF(pulangkonvert,pulangkerja)) > '0'
 THEN (SELECT TIMEDIFF(pulangkonvert,pulangkerja)) ELSE '' END AS 'lembur' ,
  CASE WHEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) > '0'
 THEN (SELECT denda_masuk FROM tb_denda) ELSE '0' END AS 'dendamasuk' ,
 
 CASE WHEN (SELECT TIMEDIFF(istirahatkonvert,istirahatmasuk)) > '0'
 THEN (SELECT denda_istirahat FROM tb_denda) ELSE '0' END AS 'dendaistirahat' ,
 
  
  B.nama_karyawan , B.upah_harian,
  (select nama_departmen from ms_departmen WHERE id_departmen = B.id_departmen) as namadepartmen


   FROM tb_record A LEFT JOIN ms_karyawan B ON A.userid = B.no_absen
 WHERE A.tgl BETWEEN '$ttgl1' AND '$ttgl2'  AND B.nama_karyawan <> ''
 ORDER BY A.tgl Asc
 ");
}

    ?>

<div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-primary"  >
                    <div class="box-header with-border" style=" background-color:#5F9EA0; border:1px ; color:white; ">
              <h3 class="box-title">Data Payroll</h3>
            </div>
             <form method="POST"  enctype="multipart/form-data">
                        <div class="panel-body">
                           
                      
              
            <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                 <div class="form-group col-md-6">
                     
                       <input type="submit" name="excel"  value="Excel" class="btn btn-success">
                </div>
                </div>

                
                                    </form>
                                    <div class="form-group "></div>

                            <div class="table-responsive">
                                 <div class="row" style=" background-color:whitesmoke; border:1px ; color:black; "> 
                 <div class="form-group col-md-2">
                    <label class="font-weight-bold"> Dari Tanggal</label>
                    <input placeholder="*" autocomplete="off" type="date" name="ttgl1" required value="<?php echo $ttgl1 ; ?>" class="form-control"/>
                     <input type="submit" name="simpan"  value="Search" class="btn btn-primary">
                </div>
                <div class="form-group col-md-2">
                    <label class="font-weight-bold">Sampai Tanggal</label>
                    <input placeholder="*" autocomplete="off" type="date" name="ttgl2" value="<?php echo $ttgl2 ; ?>" required class="form-control"/>
                    
                </div>
 <div class="form-group col-md-4">
                    <label class="font-weight-bold">Bagian</label>

                     <select class="form-control" name="tdepartmen" required>
                      <option value="<?php echo $tiddepartmen ?>"><?php echo $tnamadepartmen ?></option>
                                           <?php 
                        $sql = $koneksi->query("select '99' as id_departmen , 'All' as nama_departmen UNION ALL SELECT A.id_departmen , A.nama_departmen from ms_departmen A");
                            
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

                     <select class="form-control" name="tshift" required>
                      <option value="<?php echo $idshift ?>"><?php echo $keterangan ?></option>
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
               

                </div>
                                <table class="table table-bordered table-striped" id="dataTables-example">
                                    <thead >
                                        <tr>
                                        <th width="5%">No</th>
                                           <th >NIK</th>
                                            <th >Nama Karyawan</th>
                                            <th >Nama Bagian</th>
                                             <th >Upah</th>
                                              <th >Tanggal</th>
                                             <th >Jam Kerja</th>
                                                <th >Masuk</th>
                                        <th >Masuk lebih awal</th>
                                        <th style=" background-color:#FFEBCD; border:1px ; color:black; ">Terlambat</th>

                                        <th >Istirahat Keluar</th>
                                        <th >Istirahat Masuk</th>
                                        <th >Istirahat Masuk</th>

                                        <th >Jam Pulang</th>
                                        <th >Keluar</th>
                                        <th  style=" background-color:#FFEBCD; border:1px ; color:black; ">Pulang Lebih Awal</th>
                                        <th >Lembur</th>
                                        <th >Denda Masuk</th>
                                        <th >Denda Istirahat</th>
                                         <th >Upah</th>
                        

                                        </tr>
                                    </thead>
                                    <tbody>
                                                                <?php


$no = 1;


    while ($data=$tampil->fetch_assoc())
    {

?>


                                        <tr>
<td><?php echo $no ?></td>
<td><?php echo $data['userid'] ?></td>
<td><?php echo $data['nama_karyawan'] ?></td>
<td><?php echo $data['namadepartmen'] ?></td>
<td><?php echo number_format( $data['upah_harian'],0,',','.') ?></td>
<td><?php echo $data['tgl'] ?></td>
<td><?php echo $data['masukkerja'] ?></td>
<td><?php echo $data['masukkonvert'] ?></td>
<td><?php echo $data['masuklebihawal'] ?></td>
<td  style=" background-color:#FFEBCD; border:1px ; color:black; "><?php echo $data['terlambat'] ?></td>
<td><?php echo $data['istirahatkeluar'] ?></td>
<td><?php echo $data['istirahatmasuk'] ?></td>
<td><?php echo $data['istirahatkonvert'] ?></td>

<td><?php echo $data['pulangkerja'] ?></td>
<td><?php echo $data['pulangkonvert'] ?></td>
<td  style=" background-color:#FFEBCD; border:1px ; color:black; "><?php echo $data['pulanglebihawal'] ?></td>
<td><?php echo $data['lembur'] ?></td>
<td><?php echo number_format( $data['dendamasuk'],0,',','.') ?></td>
<td><?php echo number_format( $data['dendaistirahat'],0,',','.') ?></td>
<td><?php echo number_format( $data['upah_harian']-$data['dendaistirahat'] - $data['dendaistirahat'],0,',','.') ?></td>

<!--
<td>
    
<a  href="?page=order&id=<?php echo $data['id_transaksi'];?>"  class="btn btn-success"> Update</a>


</td>
-->
                                      
                                            
                                        </tr>

                                       <?php  $no++; } ?>

                                    </tbody>   
                                    </table>
                            </div>

                           

                    </div>
                </div>
        </div>
    </div>
  
    <script>
 $(document).ready( function () {
$('#dataTables-example').DataTable({
    pageLength: 100,
    "searching": true
}
);

} );
</script>

<?php

$ttgl1 = @$_POST ['ttgl1'];
$ttgl2 = @$_POST ['ttgl2'];
$tdepartmen = @$_POST ['tdepartmen'];
$tshift = @$_POST ['tshift'];

$simpan = @$_POST ['simpan'];
$print = @$_POST ['print'];
$excel = @$_POST ['excel'];
if($simpan){
    ?><script type="text/javascript">
                 window.location.href="?page=payroll&ttgl1=<?php echo $ttgl1 ; ?>&ttgl2=<?php echo$ttgl2 ; ?>&tdepartmen=<?php echo $tdepartmen ?>&tshift=<?php echo $tshift ?>";

            </script>
            <?php
}
if($print){
    ?><script type="text/javascript">
                 window.location.href="laporanpendapatan.php?ttgl1=<?php echo $ttgl1 ; ?>&ttgl2=<?php echo$ttgl2 ; ?>&tdepartmen=<?php echo $tdepartmen ?>";

            </script>
            <?php
}
if($excel){
    ?><script type="text/javascript">
                 window.location.href="excel.php?ttgl1=<?php echo $ttgl1 ; ?>&ttgl2=<?php echo$ttgl2 ; ?>&tdepartmen=<?php echo $tdepartmen ?>";

            </script>
            <?php
}
 


?>