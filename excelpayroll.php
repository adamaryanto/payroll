<?php
header("Content-type: application/vnd-ms-excel");
  header("Content-Disposition: attachment; filename=Payroll.xls");
include "koneksi.php";
if(isset($_GET['id'])) {
   $idrkk = $_GET['id'];
$tampilrkk = $koneksi->query("select * from tb_rkk where id_rkk = '$idrkk' ");
$datarkk=$tampilrkk->fetch_assoc();
$tglrkk = $datarkk['tgl_rkk'];
$jammasuk = substr($datarkk['jam_masuk'],0,3);
$jamkeluar = substr($datarkk['jam_keluar'],0,3);
$istirahatmasuk = substr($datarkk['istirahat_masuk'],0,3);
$istirahatkeluar = substr($datarkk['istirahat_keluar'],0,3);


$tampil = $koneksi->query("
   SELECT DISTINCT A.tgl , A.userid , 
  
 TIME_FORMAT(D.jam_masuk, '%H:%i')AS masukkerja  , 
 TIME_FORMAT(D.jam_keluar, '%H:%i')AS pulangkerja ,
  
  
  (SELECT  TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE tgl = A.tgl AND userid = A.userid AND
 (detail_waktu LIKE '%$jammasuk%' OR STATUS = '0'
 ) ORDER BY detail_waktu ASC LIMIT 1) AS 'masukkonvert' , 
 
 CASE WHEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) < '0'
 THEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) ELSE '' END AS 'masuklebihawal' ,
 
 CASE WHEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) > '0'
 THEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) ELSE '' END AS 'terlambat' ,

  TIME_FORMAT(D.istirahat_keluar, '%H:%i')AS  istirahatkeluar ,
 TIME_FORMAT(D.istirahat_masuk, '%H:%i')AS  istirahatmasuk ,
 

(SELECT  TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE tgl = A.tgl AND userid = A.userid
 AND (detail_waktu LIKE '%$istirahatkeluar%' OR STATUS = '2')  ORDER BY detail_waktu ASC LIMIT 1) AS 'istirahatkeluarkonvert' ,

(SELECT  TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE tgl = A.tgl AND userid = A.userid
 AND (detail_waktu LIKE '%$istirahatmasuk%' OR STATUS = '3')  ORDER BY detail_waktu ASC LIMIT 1) AS 'istirahatkonvert' ,
 
 (SELECT  TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE tgl = A.tgl AND userid = A.userid
 AND (detail_waktu LIKE '%$jamkeluar%' OR STATUS = '1')  ORDER BY detail_waktu DESC LIMIT 1) AS 'pulangkonvert' ,
 
  CASE WHEN (SELECT TIMEDIFF(pulangkonvert,pulangkerja)) < '0'
 THEN (SELECT TIMEDIFF(pulangkonvert,pulangkerja)) ELSE '' END AS 'pulanglebihawal' ,
 
 CASE WHEN (SELECT TIMEDIFF(pulangkonvert,pulangkerja)) > '0'
 THEN (SELECT TIMEDIFF(pulangkonvert,pulangkerja)) ELSE '' END AS 'lembur' ,
 
  CASE WHEN (SELECT TIMEDIFF(masukkonvert,masukkerja)) > '0'
 THEN (SELECT denda_masuk FROM tb_denda) ELSE '0' END AS 'dendamasuk' ,
 
 CASE WHEN (SELECT TIMEDIFF(istirahatkonvert,istirahatmasuk)) > '0'
 THEN (SELECT denda_istirahat FROM tb_denda) ELSE '0' END AS 'dendaistirahat' ,
  
  B.nama_karyawan , C.upah, C.status_rkk, C.id_rkk_detail, 
(SELECT nama_departmen FROM ms_departmen WHERE id_departmen = B.id_departmen) AS namadepartmen,
BB.nama_sub_department , C.status_rkk

   FROM tb_record A   
   LEFT JOIN ms_karyawan B ON A.userid = B.no_absen
   LEFT JOIN tb_rkk_detail C ON B.id_karyawan = C.id_karyawan
   LEFT JOIN tb_rkk D ON C.id_rkk = D.id_rkk
   
   left join ms_sub_department BB on B.id_sub_department = BB.id_sub_department
 WHERE A.tgl = '$tglrkk'  AND B.nama_karyawan <> ''
 AND C.id_rkk = '$idrkk' AND (C.status_rkk = 'Hadir' OR C.status_rkk = 'Pengganti')
 ORDER BY namadepartmen , A.tgl ASC
 
 
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

<span style='font-size:12pt'><?php echo "Tanggal : " . $tglrkk ; ?></span></br>



</td>
</tr>
 <tr>


<td colspan='3' >





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
                                            <th >Status</th>
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
<td colspan='17'><hr></td></tr>
</tr>

<?php 
$tot1=0;
$tot2=0;
$subtotal=0;
$totpotongan=0;
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
<td><?php echo $data['status_rkk'] ?></td>
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

<td><?php echo number_format( $data['upah'],0,',',',') ?></td>
<td><?php echo number_format( $data['dendamasuk'] + $data['dendaistirahat'],0,',',',') ?></td>
<td><?php echo number_format( $data['upah']-$data['dendamasuk'] - $data['dendaistirahat'],0,',',',') ;
$tot2 = $data['upah']-$data['dendamasuk'] - $data['dendaistirahat'];
$tot1 += $tot2;
$subtotal += $data['upah'];
$totpotongan +=$data['dendamasuk'] + $data['dendaistirahat'];
?></td>

<!--
<td>
    
<a  href="?page=order&id=<?php echo $data['id_transaksi'];?>"  class="btn btn-success"> Update</a>


</td>
-->
                                      
                                            
                                        </tr>






<tr>




<td colspan='17'><hr></td>
</tr>

<?php 
$no +=1 ;
   }



?>
<tr>
  <td><br></br></td>
  

</tr>

<tr>
  <td colspan = '14'></td>
<td colspan = '2'><div style='text-align:right; color:black'>Total Upah  : </div></td>
<td style='text-align:right; font-size:16pt; color:black'><?php echo "Rp" . number_format($subtotal ,0,',','.')  ;
 ?></td>
</tr>

<tr>
  <td colspan = '14'></td>
<td colspan = '2'><div style='text-align:right; color:black'>Total Potongan  : </div></td>
<td style='text-align:right; font-size:16pt; color:black'><?php echo "Rp" . number_format($totpotongan ,0,',','.')  ;
 ?></td>
</tr>

<tr>
  <td colspan = '14'></td>
<td colspan = '2'><div style='text-align:right; color:black'>Total Upah Dibayar : </div></td>
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