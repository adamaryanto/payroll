
<?php


  


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
<div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-primary"  >
                    <div class="box-header with-border" style=" background-color:#5F9EA0; border:1px ; color:white; ">
              <h3 class="box-title">Daily Report</h3>
            </div>
             <form method="POST"  enctype="multipart/form-data">
                        <div class="panel-body">
                           
                      
              
            <div class="row" style=" background-color:whitesmoke; border:1px ; color:black; "> 
                 <div class="form-group col-md-2">
                    <label class="font-weight-bold"> Dari Tanggal</label>
                    <input placeholder="*" autocomplete="off" type="date" name="ttgl1" required value="<?php echo $ttgl1 ; ?>" class="form-control"/>
                     <input type="submit" name="simpan"  value="Search" class="btn btn-primary">
                      <input type="submit" name="print"  value="Search" class="btn btn-info">
                </div>
             

               

                </div>

                
                                    </form>
                                    <div class="form-group "></div>

                            <div class="table-responsive">
                                
                                <table class="table Responsive Hover Table" id="dataTables-example">
                                    <thead style="  border-right: : 0px;">
                                        <tr style="  border-right: : 0px;">
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
                                                                

                                        </tr>
                                    </thead>
                                    <tbody>
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
<td><?php echo number_format( $datakaryawan['target'],0,',','.') ?></td>
<td><?php echo number_format( $datakaryawan['hasil'],0,',','.') ?></td>
<td><?php echo number_format( $datakaryawan['upah'],0,',','.') ?></td>
<td><?php echo  number_format( $datakaryawan['potongan'],0,',','.')  ?></td>
<td><?php echo number_format( $datakaryawan['lembur'],0,',','.')  ?></td>

<td><?php echo number_format( $datakaryawan['upah'] +$datakaryawan['lembur']-$datakaryawan['potongan'],0,',','.') ;
$total += $datakaryawan['upah'] + $datakaryawan['lembur']-$datakaryawan['potongan'];
?></td>

                                      
                                            
                                        </tr>

                                       <?php  $no++; } ?>


                                    </tbody>   
                                    <tr>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td>Total Biaya 14 Mobil</td>

<td><?php echo number_format( $total,0,',','.') ; ?></td>

                                      
                                            
                                        </tr>
                                          <tr>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td>Total Biaya Per Mobil</td>

<td><?php echo number_format( $total/14,0,',','.') ; ?></td>

                                      
                                            
                                        </tr>
                                    </table>
                            </div>
                            <div></div>

                             <div class="row" > 
                 <div class="form-group col-md-2">
                    <label class="font-weight-bold"></label>
                  
                </div>
             

               

                </div>

                             <div class="table-responsive">
                                 <label class="font-weight-bold">Biaya Vendor</label>
                                <table class="table table-bordered table-striped" id="dataTables-example1">
                                    <thead style="  border-right: : 0px;">
                                        <tr style="  border-right: : 0px;">
                                        <th width="5%" >No</th>
                                           <th >Keterangan</th>
                                           <th >Hasil</th>
                                           <th >Biaya</th>
                                            <th >Total Biaya</th>
                                                                

                                        </tr>
                                    </thead>
                                    <tbody>
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
<td><?php echo number_format( $data2['biaya'],0,',','.') ?></td>
<td><?php echo number_format( $data2['total_biaya'],0,',','.');$totalbiaya +=  $data2['total_biaya']; ?></td>

                                      
                                            
                                        </tr>

                                       <?php  $non++; } ?>


                                    </tbody>   
                                    <tr>
<td></td>
<td></td>
<td></td>
<td>Total Biaya</td>

<td><?php echo number_format( $totalbiaya,0,',','.') ; ?></td>

                                      
                                            
                                        </tr>
                                         
                                    </table>
                            </div>
                             <div class="table-responsive">
                                 <label class="font-weight-bold">Total Biaya</label>
                                <table class="table table-bordered table-striped" id="dataTables-example1">
                                    <thead style="  border-right: : 0px;">
                                        <tr style="  border-right: : 0px;">
                                        <th width="5%" >No</th>
                                           <th >Biaya Pabrik</th>
                                           <th >Biaya Vendor</th>
                                           <th >Potong</th>
                                            <th >Total Biaya</th>
                                             <th >Biaya Per Mobil</th>
                                                                

                                        </tr>
                                    </thead>
                                    <tbody>
                                                                


                                        <tr>
<td><?php echo "1" ?></td>
<td><?php echo number_format( $total ,0,',','.') ?></td>
<td><?php echo  number_format( $totalbiaya,0,',','.') ?></td>
<td><?php echo  number_format("14",0,',','.') ?></td>
<td><?php echo number_format( $total + $totalbiaya,0,',','.') ?></td>
<td><?php echo number_format( ($total + $totalbiaya)/14 ,0,',','.') ?></td>
                                      
                                            
                                        </tr>

                                 


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
    pageLength: 5,
     paging: false,
    scrollCollapse: true,
    scrollY: '400px'
}
);

} );



</script>

<?php

$ttgl1 = @$_POST ['ttgl1'];

$simpan = @$_POST ['simpan'];$print = @$_POST ['print'];
if($simpan){
    ?><script type="text/javascript">
                 window.location.href="?page=report&aksi=daily&ttgl1=<?php echo $ttgl1 ; ?>";

            </script>
            <?php
}
if($print){
    ?><script type="text/javascript">
                 window.location.href="dailyreport.php?ttgl1=<?php echo $ttgl1 ; ?>";

            </script>
            <?php
}
 


?>