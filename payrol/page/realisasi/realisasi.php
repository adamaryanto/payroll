<?php


$tampil = $koneksi->query("SELECT A.*, (select count(id_realisasi_detail) from tb_realisasi_detail where id_realisasi = A.id_realisasi ) as jml, (select sum(r_upah) from tb_realisasi_detail where id_realisasi = A.id_realisasi ) as ttl , (select sum(r_potongan_telat) from tb_realisasi_detail where id_realisasi = A.id_realisasi ) as pottelat ,(select sum(r_potongan_istirahat) from tb_realisasi_detail where id_realisasi = A.id_realisasi ) as potistirahat, (select sum(r_potongan_lainnya) from tb_realisasi_detail where id_realisasi = A.id_realisasi ) as potlainnya


  from tb_realisasi A");
if($_SESSION['level'] !="OWNER"){
  $level =  "Hidden"  ;
  // $level =  ""  ;
}else{$level="";}

    ?>

<div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-primary"  >
                    <div class="box-header with-border" style=" background-color:#5F9EA0; border:1px ; color:white; ">
              <h3 class="box-title">List Realisasi Upah</h3>
            </div>
             <form method="POST"  enctype="multipart/form-data">
                        <div class="panel-body">
                           
                      
              
            <div class="row" style=" background-color:white; border:1px ; color:black; "> 
               
                 <div class="form-group col-md-6">
                      <a href="?page=realisasi&aksi=rkk"  class="btn btn-info">Tambah Data </a> 
                </div>
               
                </div>

                
                                    </form>
                                    <div class="form-group "></div>

                            <div class="table-responsive">
                                 <div class="row" style=" background-color:whitesmoke; border:1px ; color:black; "> 
               

               

                </div>
                                <table class="table table-bordered table-striped" id="dataTables-example">
                                    <thead >
                                        <tr>
                                        <th width="5%">No</th>
                                           <th >Tanggal</th>
                                           <th >Tanggal Input</th>
                                            <th >Jam Kerja</th>
                                            <th >Jumlah Karyawan</th>
                                            <th >Total Upah Karyawan</th>
                                              <th >Total Pot. Telat</th>
                                                <th >Total Pot. Istirahat</th>
                                                  <th >Total Pot. Lainnya</th>
                                              <th >Keterangan</th>
                                      
                                       
                                         <th hidden="hidden">Action</th>
                                          <th>Action</th>
                                        

                                        <th <?php
                                         echo $level 
                                         ?>>Action</th>
                                         <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                                                <?php


$no = 1;


    while ($data=$tampil->fetch_assoc())
    {
     if($data['status_realisasi']=="1"){$a = "#F0FFFF"; $app="hidden";$unapp="hidden";$print="";}
      else{$a = "transparent";$app="";$unapp="hidden";$print="hidden";}
     
     

?>


                                         <tr style=" background-color:<?php echo $a ?>; border:1px ; color:black; ">
<td><?php echo $no ?></td>
<td><?php echo $data['tgl_realisasi'] ?></td>
<td><?php echo $data['detail_realisasi'] ?></td>
<td><?php echo $data['jam_kerja'] ?></td>
<td><?php echo $data['jml'] ?></td>
<td><?php echo number_format( $data['ttl'],0,',','.') ?></td>
<td><?php echo number_format( $data['pottelat'],0,',','.') ?></td>
<td><?php echo number_format( $data['potistirahat'],0,',','.') ?></td>
<td><?php echo number_format( $data['potlainnya'],0,',','.') ?></td>
<td><?php echo $data['keterangan'] ?></td>


<td  hidden="hidden">
    <a  href="?page=rkk&aksi=hapus&id=<?php echo $data['id_rkk'];?>"  class="btn btn-danger"> Hapus</a>
</td>
<td>

    <a  href="?page=realisasi&aksi=kelola&id=<?php echo $data['id_realisasi'];?>"  class="btn btn-warning"> Detail</a>
   
</td>



<td <?php 
echo $level 
?>

>

<div <?php echo $app ?>> <a href="?page=realisasi&aksi=accept&id=<?php echo $data['id_realisasi'];?>"
   class="btn btn-info"
   onclick="return confirm('Apakah Anda yakin ingin Approve data ini?');">
   Approve
</a></div>  
   
</td>

<td <?php 
echo $print;
?>

>

<div > <a href="excelrealisasi.php?id=<?php echo $data['id_realisasi'];?>"
   class="btn btn-info"
   >
   Payroll
</a></div>  
   
</td>


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

$simpan = @$_POST ['simpan'];
$print = @$_POST ['print'];
$excel = @$_POST ['excel'];
if($simpan){
    ?><script type="text/javascript">
                 window.location.href="?page=cuti&ttgl1=<?php echo $ttgl1 ; ?>&ttgl2=<?php echo$ttgl2 ; ?>";

            </script>
            <?php
}
if($print){
    ?><script type="text/javascript">
                 window.location.href="laporanpendapatan.php?ttgl1=<?php echo $ttgl1 ; ?>&ttgl2=<?php echo$ttgl2 ; ?>";

            </script>
            <?php
}
if($excel){
    ?><script type="text/javascript">
                 window.location.href="excelpendapatan.php?ttgl1=<?php echo $ttgl1 ; ?>&ttgl2=<?php echo$ttgl2 ; ?>";

            </script>
            <?php
}
 


?>