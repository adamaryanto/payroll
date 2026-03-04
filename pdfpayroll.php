<?php
//header("Content-type: application/vnd-ms-excel");
 // header("Content-Disposition: attachment; filename=Payroll.xls");
include "koneksi.php";
if(isset($_GET['id'])) {
   $idrkk = $_GET['id'];
$tampilrkk = $koneksi->query("select * from tb_rkk where id_rkk = '$idrkk' ");
$datarkk = $tampilrkk->fetch_assoc();
$tglrkk = $datarkk ? $datarkk['tgl_rkk'] : '-';
$jammasuk = $datarkk ? substr($datarkk['jam_masuk'],0,3) : '';
$jamkeluar = $datarkk ? substr($datarkk['jam_keluar'],0,3) : '';
$istirahatmasuk = $datarkk ? substr($datarkk['istirahat_masuk'],0,3) : '';
$istirahatkeluar = $datarkk ? substr($datarkk['istirahat_keluar'],0,3) : '';


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
  
  B.nama_karyawan , B.upah_harian, C.status_rkk, C.id_rkk_detail, 
(SELECT nama_departmen FROM ms_departmen WHERE id_departmen = D.id_departmen) AS namadepartmen,
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

<table cellspacing='0' cellpadding='0' style='width:100%; font-size:12pt; font-family:calibri;  border-collapse: collapse;' border='0'>
 
<tr align='center'>
   
                                        <th style='width:50px;'>No</th>
                                           <th style='width:150px;text-align: center;'>NIK</th>
                                            <th style='width:150px;'>Nama Karyawan</th>
                                            <th style='width:150px;text-align: center;'>Nama Bagian</th>
                                            <th style='width:150px;text-align: center;'>Sub Bagian</th>
                                            <th style='width:100px;text-align: center;'>Status</th>
                                              <th style='width:100px;text-align: center;'>Tanggal</th>
                                             <th style='width:150px;text-align: center;'>Jam Masuk</th>
                                             <th style='width:150px;text-align: center;'>Jam Pulang</th>

                                                <th style='width:150px;text-align: center;'>Absen Masuk</th>

                                       
                                              <th style='width:150px;text-align: center;'>Istirahat Keluar</th>
                                        <th style='width:150px;text-align: center;'>Istirahat Masuk</th>

                                        
                                        <th style='width:150px;text-align: center;'>Absen Pulang</th>
                                        
                                        <th style='width:150px;text-align: center;'>Lembur</th>
                                         <th style='width:150px;text-align: right;'>Upah</th>
                                        <th style='width:150px;text-align: right;'>Potongan</th>
                                         <th style='width:150px;text-align: right;'>Upah Dibayar</th>
                        

                                        
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
<td style='text-align:center;'><?php echo $no ?></td>
<td style='text-align:center;'><?php echo $data['userid'] ?></td>
<td><?php echo $data['nama_karyawan'] ?></td>
<td style='text-align:center;'><?php echo $data['namadepartmen'] ?></td>
<td style='text-align:center;'><?php echo $data['nama_sub_department'] ?></td>
<td style='text-align:center;'><?php echo $data['status_rkk'] ?></td>
<td style='text-align:center;'><?php echo $data['tgl'] ?></td>
<td style='text-align:center;'><?php echo $data['masukkerja'] ?></td>

<td style='text-align:center;'><?php echo $data['pulangkerja'] ?></td>

<td
style="text-align:center; background-color:<?php

if ($data['terlambat'] != ''){echo "#FFEBCD";}
 ?>"
 ><?php echo $data['masukkonvert'] ?></td>

<td style='text-align:center;'><?php echo $data['istirahatkeluarkonvert'] ?></td>
<td style='text-align:center;'><?php echo $data['istirahatkonvert'] ?></td>

<td
style=" text-align:center;background-color:<?php

if ($data['pulanglebihawal'] != ''){echo "#FFEBCD";}
 ?>"

 ><?php echo $data['pulangkonvert'] ?></td>
<td style='text-align:center;'><?php echo $data['lembur'] ?></td>


<td style='text-align:right;'><?php echo number_format( $data['upah_harian'],0,',',',') ?></td>
<td style='text-align:right;'><?php echo number_format( $data['dendamasuk'] + $data['dendaistirahat'],0,',',',') ?></td>
<td style='text-align:right;'><?php echo number_format( $data['upah_harian']-$data['dendamasuk'] - $data['dendaistirahat'],0,',',',') ;
$tot2 = $data['upah_harian']-$data['dendamasuk'] - $data['dendaistirahat'];
$tot1 += $tot2;
$subtotal += $data['upah_harian'];
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