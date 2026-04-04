<?php
header("Content-type: application/vnd-ms-excel");
  header("Content-Disposition: attachment; filename=BiayaProduksi.xls");
include "koneksi.php";


if(isset($_GET['ttgl1']) ){
    $ttgl1 = $_GET['ttgl1'];


$tampil = $koneksi->query("SELECT A.tgl, A.target, A.hasil, A.upah,A.potongan, A.lembur , B.nama_karyawan, B.no_absen, C.jabatan, D.nama_departmen, E.nama_sub_department, F.jam_masuk,

    (SELECT  TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE tgl = A.tgl AND userid = A.id_karyawan AND
 status = '0' ORDER BY detail_waktu ASC LIMIT 1) AS 'absen_masuk',

    (SELECT  TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE tgl = A.tgl AND userid = A.id_karyawan AND
 status = '1' ORDER BY detail_waktu DESC LIMIT 1) AS 'absen_pulang',

    (SELECT  TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE tgl = A.tgl AND userid = A.id_karyawan AND
 status = '2' ORDER BY detail_waktu DESC LIMIT 1) AS 'istirahatkeluar',

     (SELECT  TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE tgl = A.tgl AND userid = A.id_karyawan AND
 status = '3' ORDER BY detail_waktu ASC LIMIT 1) AS 'istirahatmasuk'

  from tb_hasil_produksi A left join ms_karyawan B on A.id_karyawan = B.id_karyawan
  left join ms_jabatan C on B.id_jabatan = C.id_jabatan
  left join ms_departmen D on B.id_departmen = D.id_departmen
  left join ms_sub_department E on B.id_sub_department = E.id_sub_department
  left join tb_jadwal F on A.id_jadwal = F.id_jadwal

 where A.tgl = '$ttgl1'
 ");
$tampil2 = $koneksi->query("SELECT A.tgl, A.biaya,A.hasil, A.total_biaya , B.nama_jasa from tb_hasil_external A left join ms_jasa B on A.id_jasa = B.id_jasa

 where A.tgl = '$ttgl1'
 ");


}else{
     $ttgl1 = date("Y-m-d");



$tampil = $koneksi->query("SELECT A.tgl, A.target, A.hasil, A.upah,A.potongan, A.lembur , B.nama_karyawan, B.no_absen, C.jabatan, D.nama_departmen, E.nama_sub_department, F.jam_masuk,

   (SELECT  TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE tgl = A.tgl AND userid = A.id_karyawan AND
 status = '0' ORDER BY detail_waktu ASC LIMIT 1) AS 'absen_masuk',

    (SELECT  TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE tgl = A.tgl AND userid = A.id_karyawan AND
 status = '1' ORDER BY detail_waktu DESC LIMIT 1) AS 'absen_pulang',

    (SELECT  TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE tgl = A.tgl AND userid = A.id_karyawan AND
 status = '2' ORDER BY detail_waktu DESC LIMIT 1) AS 'istirahatkeluar',
 
     (SELECT  TIME_FORMAT(detail_waktu, '%H:%i') FROM tb_record WHERE tgl = A.tgl AND userid = A.id_karyawan AND
 status = '3' ORDER BY detail_waktu ASC LIMIT 1) AS 'istirahatmasuk'

  from tb_hasil_produksi A left join ms_karyawan B on A.id_karyawan = B.id_karyawan
  left join ms_jabatan C on B.id_jabatan = C.id_jabatan
  left join ms_departmen D on B.id_departmen = D.id_departmen
  left join ms_sub_department E on B.id_sub_department = E.id_sub_department
 left join tb_jadwal F on A.id_jadwal = F.id_jadwal
 where A.tgl = '$ttgl1'

 ");
$tampil2 = $koneksi->query("SELECT A.tgl, A.biaya,A.hasil, A.total_biaya , B.nama_jasa from tb_hasil_external A left join ms_jasa B on A.id_jasa = B.id_jasa

 where A.tgl = '$ttgl1'
 ");

}
    ?>
<html>
<head>
<title hidden="hidden">Biaya Produksi</title>
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



  <span style='font-size:12pt; text-align:center'><?php echo "Biaya Produksi"; ?></span></br>


<tr>
  <td><br></br></td>
  

</tr>
  <tr>


<td colspan='3' >

<span style='font-size:12pt'><?php echo "Tanggal : " . $ttgl1 ; ?></span></br>



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
   
                                           <th width="5%" >No</th>
                                           <th >Nama</th>
                                           <th >Nik</th>
                                           <th >Golongan</th>
                                            <th >Bagian</th>
                                            <th >Jam Masuk</th>
                                            <th >Absen Masuk</th>
                                            <th >Absen Istirahat Keluar</th>
                                            <th >Absen Istirahat Masuk</th>
                                            <th >Absen Pulang</th>
                                            <th >Pencapaian</th>
                                            <th >Hasil Kerja</th>
                                            <th >Upah</th>
                                            <th >Pot.</th>
                                            <th >Lembur</th>
                                                                                  
                                        <th >Upah Dibayar</th> 
                        

                                        
<tr>
<td colspan='16'><hr></td></tr>
</tr>

<?php 
$no = 1;
$total = 0;



    while ($datakaryawan=$tampil->fetch_assoc())
    {
?>
     <tr>
<td><?php echo $no ?></td>
<td><?php echo $datakaryawan['nama_karyawan'] ?></td>
<td><?php echo $datakaryawan['no_absen'] ?></td>
<td><?php echo $datakaryawan['nama_departmen'] ?></td>
<td><?php echo $datakaryawan['nama_sub_department'] ?></td>
<td><?php echo $datakaryawan['jam_masuk'] ?></td>
<td><?php echo $datakaryawan['absen_masuk'] ?></td>
<td><?php echo $datakaryawan['istirahatkeluar'] ?></td>
<td><?php echo $datakaryawan['istirahatmasuk'] ?></td>
<td><?php echo $datakaryawan['absen_pulang'] ?></td>
<td><?php echo number_format($datakaryawan['target'] ,2,',','.') ?></td>
<td><?php echo number_format($datakaryawan['hasil'] ,2,',','.') ?></td>
<td><?php echo number_format($datakaryawan['upah'] ,2,',','.') ?></td>
<td><?php echo  number_format($datakaryawan['potongan'] ,2,',','.')?></td>
<td><?php echo number_format($datakaryawan['lembur'] ,2,',','.') ?></td>

<td><?php echo number_format($datakaryawan['upah'] +$datakaryawan['lembur']-$datakaryawan['potongan'] ,2,',','.') ;
$total += $datakaryawan['upah'] + $datakaryawan['lembur']-$datakaryawan['potongan'];
?></td>
                                      
                                            
                                        </tr>






<tr>




<td colspan='16'><hr></td>
</tr>

<?php 
$no +=1 ;
   }



?>
<tr>
  <td><br></br></td>
  

</tr>

<tr>
  <td colspan = '13'></td>
<td colspan = '2'><div style='text-align:right; color:black'>Biaya 14 Mobils  : </div></td>
<td style='text-align:right; font-size:16pt; color:black'><?php echo "Rp" . number_format($total ,2,',','.')   ;
 ?></td>
</tr>

<tr>
  <td colspan = '13'></td>
<td colspan = '2'><div style='text-align:right; color:black'>Biaya Per Mobil  : </div></td>
<td style='text-align:right; font-size:16pt; color:black'><?php echo "Rp" . number_format( $total/14 ,2,',','.')   ;
 ?></td>
</tr>


</table>

<table cellspacing='0' cellpadding='0' style='width:700px; font-size:12pt; font-family:calibri;  border-collapse: collapse;' border='0'>
 
<tr align='center'>
   
                                           <th width="5%" >No</th>
                                           <th >Keterangan</th>
                                           <th >Hasil</th>
                                           <th >Biaya</th>
                                            <th >Total Biaya</th>
                        

                                        
<tr>
<td colspan='5'><hr></td></tr>
</tr>

<?php 
$non = 1;
$totalbiaya = 0;

  while ($data2=$tampil2->fetch_assoc())
    {
?>
     <tr>
<td><?php echo $non ?></td>
<td><?php echo $data2['nama_jasa'] ?></td>
<td><?php echo $data2['hasil'] ?></td>
<td><?php echo number_format( $data2['biaya'],2,',','.') ?></td>
<td><?php echo number_format( $data2['total_biaya'],2,',','.');$totalbiaya +=  $data2['total_biaya']; ?></td>
                                            
                                        </tr>






<tr>




<td colspan='5'><hr></td>
</tr>

<?php 
$non +=1 ;
   }



?>
<tr>
  <td><br></br></td>
  

</tr>

<tr>
  <td colspan = '2'></td>
<td colspan = '2'><div style='text-align:right; color:black'>Total Biaya : </div></td>
<td style='text-align:right; font-size:16pt; color:black'><?php echo "Rp" . number_format($totalbiaya ,2,',','.')   ;
 ?></td>
</tr>



</table>

<table cellspacing='0' cellpadding='0' style='width:700px; font-size:12pt; font-family:calibri;  border-collapse: collapse;' border='0'>
 
<tr align='center'>
   
                                          <th width="5%" >No</th>
                                           <th >Biaya Pabrik</th>
                                           <th >Biaya Vendor</th>
                                           <th >Potong</th>
                                            <th >Total Biaya</th>
                                             <th >Biaya Per Mobil</th>
                        

                                        
<tr>
<td colspan='6'><hr></td></tr>
</tr>


     <tr>
<td><?php echo "1" ?></td>
<td><?php echo number_format( $total ,2,',','.') ?></td>
<td><?php echo  number_format( $totalbiaya,2,',','.') ?></td>
<td><?php echo  number_format("14",2,',','.') ?></td>
<td><?php echo number_format( $total + $totalbiaya,2,',','.') ?></td>
<td><?php echo number_format( ($total + $totalbiaya)/14 ,2,',','.') ?></td>
                                            
                                        </tr>






<tr>




<td colspan='6'><hr></td>
</tr>


<tr>
  <td><br></br></td>
  

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