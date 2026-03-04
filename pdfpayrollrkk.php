<?php
//header("Content-type: application/vnd-ms-excel");
//  header("Content-Disposition: attachment; filename=Payroll.xls");
include "koneksi.php";
if(isset($_GET['id'])) {
   $idrkk = $_GET['id'];
$tampilrkk = $koneksi->query("select * from tb_rkk where id_rkk = '$idrkk' ");
$datarkk=$tampilrkk->fetch_assoc();
$tglrkk = $datarkk ? $datarkk['tgl_rkk'] : '-';
$jammasuk = $datarkk ? substr($datarkk['jam_masuk'],0,3) : '';
$jamkeluar = $datarkk ? substr($datarkk['jam_keluar'],0,3) : '';
$istirahatmasuk = $datarkk ? substr($datarkk['istirahat_masuk'],0,3) : '';
$istirahatkeluar = $datarkk ? substr($datarkk['istirahat_keluar'],0,3) : '';


$tampil = $koneksi->query("SELECT B.no_absen , B.nama_karyawan , D.nama_departmen , C.tgl_rkk ,C.jam_masuk , C.jam_keluar , C.istirahat_keluar,
C.istirahat_masuk , A.status_rkk , BB.nama_sub_department ,
case when A.status_rkk = 'Digantikan' then '0'
 when A.status_rkk = 'Tidak Hadir' then '0'
 else A.upah

end as upahkaryawan
FROM tb_rkk_detail A 
LEFT JOIN ms_karyawan B on A.id_karyawan = B.id_karyawan
LEFT JOIN tb_rkk C ON A.id_rkk = C.id_rkk
LEFT JOIN ms_departmen D on C.id_departmen = D.id_departmen

   left join ms_sub_department BB on B.id_sub_department = BB.id_sub_department

WHERE A.id_rkk = '$idrkk'
");
  }

    ?>
<html>
<head>
<title hidden="hidden">Perencanaan Pengeluaran Upah</title>
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

<table cellspacing='0' cellpadding='0' style=' font-size:12pt; font-family:calibri;  border-collapse: collapse;' border='0'>
 
<tr align='center'>
   
                                       <th style='width:50px;'>No</th>
                                           <th style='width:120px;'>NIK</th>
                                            <th style='width:150px;'>Nama Karyawan</th>
                                            <th style='width:150px;'>Nama Bagian</th>
                                             <th style='width:150px;'>Sub Bagian</th>
                                              <th style='width:100px;'>Tanggal</th>
                                              <th style='width:100px;'>Status</th>
                                             <th style='width:150px;'>Jam Masuk</th>
                                              <th style='width:150px;'>Jam Pulang</th>
                                               <th style='width:150px;' >Istirahat Keluar</th>
                                        <th style='width:150px;'>Istirahat Masuk</th>

                                       
                            
                                          <th >Upah</th>
                        
                        

                                        
<tr>
<td colspan='12'><hr></td></tr>
</tr>

<?php 
$tot1=0;
$no=1;


    while ($data=$tampil->fetch_assoc())
    {
?>
     <tr>
<td style='text-align:center;'><?php echo $no ?></td>
<td style='text-align:center;'><?php echo $data['no_absen'] ?></td>
<td><?php echo $data['nama_karyawan'] ?></td>
<td style='text-align:center;'><?php echo $data['nama_departmen'] ?></td>
<td style='text-align:center;'><?php echo $data['nama_sub_department'] ?></td>
<td style='text-align:center;'><?php echo $data['tgl_rkk'] ?></td>
<td style='text-align:center;'><?php echo $data['status_rkk'] ?></td>
<td style='text-align:center;'><?php echo $data['jam_masuk'] ?></td>
<td style='text-align:center;'><?php echo $data['jam_keluar'] ?></td>
<td style='text-align:center;'><?php echo $data['istirahat_keluar'] ?></td>
<td style='text-align:center;'><?php echo $data['istirahat_masuk'] ?></td>





<td style='text-align:right;'><?php 
$tot1 += $data['upahkaryawan'];
 echo number_format( $data['upahkaryawan'],0,',','.') ?></td>

<!--
<td>
    
<a  href="?page=order&id=<?php echo $data['id_transaksi'];?>"  class="btn btn-success"> Update</a>


</td>
-->
                                      
                                            
                                        </tr>






<tr>




<td colspan='12'><hr></td>
</tr>

<?php 
$no +=1 ;
   }



?>
<tr>
  <td><br></br></td>
  

</tr>

<tr>
  <td colspan = '9'></td>
<td colspan = '2'><div style='text-align:right; color:black'>Total Upah  : </div></td>
<td style='text-align:right; font-size:16pt; color:black'><?php echo "Rp" . number_format($tot1,0,',','.')  ;
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