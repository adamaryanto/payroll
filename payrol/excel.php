<?php
header("Content-type: application/vnd-ms-excel");
  header("Content-Disposition: attachment; filename=Payroll.xls");
include "koneksi.php";
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
 (detail_waktu LIKE '%$jammasuk%' OR status = '0'
 ) ORDER BY detail_waktu ASC LIMIT 1) AS 'masukkonvert' , 
 CASE WHEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) < '0'
 THEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) ELSE '' END AS 'masuklebihawal' ,
 
 CASE WHEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) > '0'
 THEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) ELSE '' END AS 'terlambat' ,
 
 (SELECT TIME_FORMAT(istirahat_keluar, '%H:%i') FROM tb_jadwal where id_jadwal = '$idshift') AS istirahatkeluar ,
(SELECT TIME_FORMAT(istirahat_masuk, '%H:%i') FROM tb_jadwal where id_jadwal = '$idshift') AS istirahatmasuk ,

(SELECT  TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE tgl = A.tgl AND userid = A.userid
 AND (detail_waktu LIKE '%$istirahatkeluar%' OR STATUS = '2')  ORDER BY detail_waktu ASC LIMIT 1) AS 'istirahatkeluarkonvert' ,

(SELECT  TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE tgl = A.tgl AND userid = A.userid
 AND (detail_waktu LIKE '%$istirahatmasuk%' OR status = '3')  ORDER BY detail_waktu ASC LIMIT 1) AS 'istirahatkonvert' ,
 
 (SELECT  TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE tgl = A.tgl AND userid = A.userid
 AND (detail_waktu LIKE '%$jamkeluar%' OR status = '1')  ORDER BY detail_waktu DESC LIMIT 1) AS 'pulangkonvert' ,
  CASE WHEN (SELECT TIMEDIFF(pulangkonvert,pulangkerja)) < '0'
 THEN (SELECT TIMEDIFF(pulangkonvert,pulangkerja)) ELSE '' END AS 'pulanglebihawal' ,
 CASE WHEN (SELECT TIMEDIFF(pulangkonvert,pulangkerja)) > '0'
 THEN (SELECT TIMEDIFF(pulangkonvert,pulangkerja)) ELSE '' END AS 'lembur' ,
  CASE WHEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) > '0'
 THEN (SELECT denda_masuk FROM tb_denda) ELSE '0' END AS 'dendamasuk' ,
 
 CASE WHEN (SELECT TIMEDIFF(istirahatkonvert,istirahatmasuk)) > '0'
 THEN (SELECT denda_istirahat FROM tb_denda) ELSE '0' END AS 'dendaistirahat' ,
  
  B.nama_karyawan , B.upah_harian,BB.nama_sub_department,
(select nama_departmen from ms_departmen WHERE id_departmen = B.id_departmen) as namadepartmen
   FROM tb_record A LEFT JOIN ms_karyawan B ON A.userid = B.no_absen
      left join ms_sub_department BB on B.id_sub_department = BB.id_sub_department
 WHERE A.tgl BETWEEN '$ttgl1' AND '$ttgl2'  AND B.nama_karyawan <> ''
 and B.id_jadwal='$idshift'
 ORDER BY namadepartmen , A.tgl ASC
 
 
");
  
}else{
  $tampil = $koneksi->query("
  
  SELECT DISTINCT A.tgl , A.userid , 
  
  (SELECT TIME_FORMAT(jam_masuk, '%H:%i') FROM tb_jadwal where id_jadwal = '$idshift') AS masukkerja  , 
  (SELECT TIME_FORMAT(jam_keluar, '%H:%i') FROM tb_jadwal where id_jadwal = '$idshift') AS pulangkerja ,
  
  (SELECT  TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE tgl = A.tgl AND userid = A.userid AND
 (detail_waktu LIKE '%$jammasuk%' OR status = '0'
 ) ORDER BY detail_waktu ASC LIMIT 1) AS 'masukkonvert' , 
 CASE WHEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) < '0'
 THEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) ELSE '' END AS 'masuklebihawal' ,
 
 CASE WHEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) > '0'
 THEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) ELSE '' END AS 'terlambat' ,
 
 (SELECT TIME_FORMAT(istirahat_keluar, '%H:%i') FROM tb_jadwal where id_jadwal = '$idshift') AS istirahatkeluar ,
(SELECT TIME_FORMAT(istirahat_masuk, '%H:%i') FROM tb_jadwal where id_jadwal = '$idshift') AS istirahatmasuk ,

(SELECT  TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE tgl = A.tgl AND userid = A.userid
 AND (detail_waktu LIKE '%$istirahatkeluar%' OR STATUS = '2')  ORDER BY detail_waktu ASC LIMIT 1) AS 'istirahatkeluarkonvert' ,
(SELECT  TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE tgl = A.tgl AND userid = A.userid
 AND (detail_waktu LIKE '%$istirahatmasuk%' OR status = '3')  ORDER BY detail_waktu ASC LIMIT 1) AS 'istirahatkonvert' ,
 
 (SELECT  TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE tgl = A.tgl AND userid = A.userid
 AND (detail_waktu LIKE '%$jamkeluar%' OR status = '1')  ORDER BY detail_waktu DESC LIMIT 1) AS 'pulangkonvert' ,
  CASE WHEN (SELECT TIMEDIFF(pulangkonvert,pulangkerja)) < '0'
 THEN (SELECT TIMEDIFF(pulangkonvert,pulangkerja)) ELSE '' END AS 'pulanglebihawal' ,
 CASE WHEN (SELECT TIMEDIFF(pulangkonvert,pulangkerja)) > '0'
 THEN (SELECT TIMEDIFF(pulangkonvert,pulangkerja)) ELSE '' END AS 'lembur' ,
  CASE WHEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) > '0'
 THEN (SELECT denda_masuk FROM tb_denda) ELSE '0' END AS 'dendamasuk' ,
 
 CASE WHEN (SELECT TIMEDIFF(istirahatkonvert,istirahatmasuk)) > '0'
 THEN (SELECT denda_istirahat FROM tb_denda) ELSE '0' END AS 'dendaistirahat' ,
  
  B.nama_karyawan , B.upah_harian,BB.nama_sub_department,
(select nama_departmen from ms_departmen WHERE id_departmen = B.id_departmen) as namadepartmen
   FROM tb_record A LEFT JOIN ms_karyawan B ON A.userid = B.no_absen
      left join ms_sub_department BB on B.id_sub_department = BB.id_sub_department
 WHERE A.tgl BETWEEN '$ttgl1' AND '$ttgl2'  AND B.nama_karyawan <> '' and B.id_departmen = '$tiddepartmen'
 and B.id_jadwal='$idshift'
 ORDER BY A.tgl , namadepartmen ASC
 
 
");
}

}else{
     $ttgl1 = '';
    $ttgl2 = '';

$tampil = $koneksi->query("SELECT DISTINCT A.tgl , A.userid , 
  
  (SELECT TIME_FORMAT(jam_masuk, '%H:%i') FROM tb_jadwal where id_jadwal = '$idshift') AS masukkerja  , 
  (SELECT TIME_FORMAT(jam_keluar, '%H:%i') FROM tb_jadwal where id_jadwal = '$idshift') AS pulangkerja ,
  
  (SELECT  TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE tgl = A.tgl AND userid = A.userid AND
 (detail_waktu LIKE '%$jammasuk%' OR status = '0'
 ) ORDER BY detail_waktu ASC LIMIT 1) AS 'masukkonvert' , 
 CASE WHEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) < '0'
 THEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) ELSE '' END AS 'masuklebihawal' ,
 
 CASE WHEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) > '0'
 THEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) ELSE '' END AS 'terlambat' ,
 
 (SELECT TIME_FORMAT(istirahat_keluar, '%H:%i') FROM tb_jadwal where id_jadwal = '$idshift') AS istirahatkeluar ,
(SELECT TIME_FORMAT(istirahat_masuk, '%H:%i') FROM tb_jadwal where id_jadwal = '$idshift') AS istirahatmasuk ,

(SELECT  TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE tgl = A.tgl AND userid = A.userid
 AND (detail_waktu LIKE '%$istirahatkeluar%' OR STATUS = '2')  ORDER BY detail_waktu ASC LIMIT 1) AS 'istirahatkeluarkonvert' ,

(SELECT  TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE tgl = A.tgl AND userid = A.userid
 AND (detail_waktu LIKE '%$istirahatmasuk%' OR status = '3') ORDER BY detail_waktu ASC LIMIT 1) AS 'istirahatkonvert' ,
 
 (SELECT  TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE tgl = A.tgl AND userid = A.userid
 AND (detail_waktu LIKE '%$jamkeluar%' OR status = '1')  ORDER BY detail_waktu DESC LIMIT 1) AS 'pulangkonvert' ,
  CASE WHEN (SELECT TIMEDIFF(pulangkonvert,pulangkerja)) < '0'
 THEN (SELECT TIMEDIFF(pulangkonvert,pulangkerja)) ELSE '' END AS 'pulanglebihawal' ,
 CASE WHEN (SELECT TIMEDIFF(pulangkonvert,pulangkerja)) > '0'
 THEN (SELECT TIMEDIFF(pulangkonvert,pulangkerja)) ELSE '' END AS 'lembur' ,
  CASE WHEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) > '0'
 THEN (SELECT denda_masuk FROM tb_denda) ELSE '0' END AS 'dendamasuk' ,
 
 CASE WHEN (SELECT TIMEDIFF(istirahatkonvert,istirahatmasuk)) > '0'
 THEN (SELECT denda_istirahat FROM tb_denda) ELSE '0' END AS 'dendaistirahat' ,
 
  
  B.nama_karyawan , B.upah_harian,BB.nama_sub_department,
  (select nama_departmen from ms_departmen WHERE id_departmen = B.id_departmen) as namadepartmen


   FROM tb_record A LEFT JOIN ms_karyawan B ON A.userid = B.no_absen
      left join ms_sub_department BB on B.id_sub_department = BB.id_sub_department
 WHERE A.tgl BETWEEN '$ttgl1' AND '$ttgl2'  AND B.nama_karyawan <> ''
 and B.id_jadwal='$idshift'
 ORDER BY A.tgl Asc
 ");
}

    ?>
<html>
<head>
<title hidden="hidden">Payroll</title>
<style>
 
#tabel
{
font-size:15px;
border-collapse:collapse;
}
#tabel  td
{
padding-left:5px;
border: 1px solid black;
}
</style>
</head>
<body onload="window.print() " style='font-family:tahoma; font-size:8pt;'>
<center><table style='width:700px; font-size:16pt; font-family:calibri; border-collapse: collapse;' border = '0'>



  <span style='font-size:12pt; text-align:center'><?php echo "Payroll"; ?></span></br>


<tr>
  <td><br></br></td>
  

</tr>
  <tr>


<td colspan='3' >

<span style='font-size:12pt'><?php echo "Tanggal : " . $ttgl1 . "Sampai " . $ttgl2 ; ?></span></br>



</td>
</tr>
 <tr>


<td colspan='3' >

<span style='font-size:12pt'><?php echo "keterangan : " . $keterangan ; ?></span></br>



</td>
</tr>
</table>
<style>
hr { 
    display: block;
    margin-top: 0.5em;
    margin-bottom: 0.5em;
    margin-left: auto;
    margin-right: auto;
    border-style: inset;
    border-width: 1px;
} 
</style>
<hr></hr>

<table cellspacing='0' cellpadding='0' style='width:700px; font-size:12pt; font-family:calibri;  border-collapse: collapse;' border='0'>
 
<tr align='center'>
   
                                        <th width="5%">No</th>
                                           <th >NIK</th>
                                            <th >Nama Karyawan</th>
                                            <th >Nama Bagian</th>
                                            <th >Sub Bagian</th>
                                            
                                              <th >Tanggal</th>
                                             <th >Jam Masuk</th>
                                             <th >Jam Pulang</th>
                                                <th >Absen Masuk</th>

                                        <th >Istirahat Keluar</th>
                                        <th >Istirahat Masuk</th>

                                        
                                        <th >Absen Pulang</th>
                                        <th >Lembur</th>
                                         <th >Upah</th>
                                        <th >Potongan</th>
                                         <th >Upah Dibayar</th>
                        

                                        
<tr>
<td colspan='20'><hr></td></tr>
</tr>

<?php 
$tot1=0;
$tot2=0;
$subtotal=0;
$no=1;


    while ($data=$tampil->fetch_assoc())
    {
?>
     <tr>
<td><?php echo $no ?></td>
<td><?php echo $data['userid'] ?></td>
<td><?php echo $data['nama_karyawan'] ?></td>
<td><?php echo $data['namadepartmen'] ?></td>
<td><?php echo $data['nama_sub_department'] ?></td>
<td><?php echo $data['tgl'] ?></td>
<td><?php echo $data['masukkerja'] ?></td>
<td><?php echo $data['pulangkerja'] ?></td>
<td
style=" background-color:<?php

if ($data['terlambat'] != ''){echo "#FFEBCD";}
 ?>"
><?php echo $data['masukkonvert'] ?></td>
<td><?php echo $data['istirahatkeluarkonvert'] ?></td>
<td><?php echo $data['istirahatkonvert'] ?></td>


<td
style=" background-color:<?php

if ($data['pulanglebihawal'] != ''){echo "#FFEBCD";}
 ?>"
><?php echo $data['pulangkonvert'] ?></td>
<td><?php echo $data['lembur'] ?></td>

<td><?php echo number_format( $data['upah_harian'],0,',',',') ?></td>
<td><?php echo number_format( $data['dendamasuk'] +  $data['dendaistirahat'],0,',',',') ?></td>
<td><?php echo number_format( $data['upah_harian']-$data['dendaistirahat'] - $data['dendaistirahat'],0,',',',') ;
$tot2 = $data['upah_harian']-$data['dendamasuk'] - $data['dendaistirahat'];
$tot1 += $tot2;

?></td>

<!--
<td>
    
<a  href="?page=order&id=<?php echo $data['id_transaksi'];?>"  class="btn btn-success"> Update</a>


</td>
-->
                                      
                                            
                                        </tr>






<tr>




<td colspan='20'><hr></td>
</tr>

<?php 
$no +=1 ;
   }



?>
<tr>
  <td><br></br></td>
  

</tr>

<tr>
  <td colspan = '17'></td>
<td colspan = '2'><div style='text-align:right; color:black'>Total  : </div></td>
<td style='text-align:right; font-size:16pt; color:black'><?php echo "Rp" . number_format($tot1 ,0,',','.')  ;
 ?></td>
</tr>

</table>
</center></body>
</html>
<style type="text/css" media="print">
  @media print {
    Header {
      display: none;
    }
    Footer {
      display: none !important;
    }
    #lrno {
      font-size: 20pt;
      position: absolute!important;
      top: 25px;
      left: 30px;
    }
    #consignor {
      font-size: 30pt;
      position: relative;
      top: 70px;
      left: 0px;
    }
    #consignee {
      font-size: 33pt;
      position: relative;
      top: 9px;
      left: 590px;
    }
    #header, #nav, .noprint
{
display: none;
}
  }

</style>