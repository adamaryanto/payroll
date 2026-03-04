
<?php


  


if(isset($_GET['ttgl1']) || isset($_GET['ttgl2'])){
    $ttgl1 = $_GET['ttgl1'];
    $ttgl2 = $_GET['ttgl2'];


$tampil = $koneksi->query("SELECT * from tb_hasil_external aa left join ms_jasa bb on aa.id_jasa = bb.id_jasa where aa.tgl between '$ttgl1' AND '$ttgl2' order by aa.id_external desc
 ");



}else{
     $ttgl1 = date("Y-m-d");
    $ttgl2 = date("Y-m-d");


$tampil = $koneksi->query("SELECT * from tb_hasil_external aa left join ms_jasa bb on aa.id_jasa = bb.id_jasa where aa.tgl between '$ttgl1' AND '$ttgl2' order by aa.id_external desc
 ");
}

    ?>
<div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-primary"  >
                    <div class="box-header with-border" style=" background-color:#5F9EA0; border:1px ; color:white; ">
              <h3 class="box-title">List Daily Activity Vendor</h3>
            </div>
             <form method="POST"  enctype="multipart/form-data">
                        <div class="panel-body">
                           
                       <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                 <div class="form-group col-md-6">
                      <a href="?page=dailyactivityvendor&aksi=tambah"  class="btn btn-success">Tambah Data </a> 
                </div>
                </div>
              
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

               

                </div>

                
                                    </form>
                                    <div class="form-group "></div>

                            <div class="table-responsive">
                                
                                <table class="table Responsive Hover Table" id="dataTables-example">
                                    <thead style="  border-right: : 0px;">
                                        <tr style="  border-right: : 0px;">
                                        <th width="5%" >No</th>
                                           <th >Tanggal</th>
                                           <th >Keterangan</th>
                                           <th >Hasil</th>
                                            <th >Biaya</th>
                                                                                  
                                        <th >Total Biaya</th> 
                                                                

                                        </tr>
                                    </thead>
                                    <tbody>
                                                                <?php


$no = 1;


    while ($datakaryawan=$tampil->fetch_assoc())
    {

?>


                                        <tr>
<td><?php echo $no ?></td>
<td><?php echo $datakaryawan['tgl'] ?></td>
<td><?php echo $datakaryawan['nama_jasa'] ?></td>
<td><?php echo  number_format( $datakaryawan['hasil'],0,',','.')  ?></td>
<td><?php echo number_format( $datakaryawan['biaya'],0,',','.')  ?></td>

<td><?php echo number_format( $datakaryawan['total_biaya'],0,',','.') ?></td>

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
$ttgl2 = @$_POST ['ttgl2'];

$simpan = @$_POST ['simpan'];
if($simpan){
    ?><script type="text/javascript">
                 window.location.href="?page=dailyactivityvendor&ttgl1=<?php echo $ttgl1 ; ?>&ttgl2=<?php echo$ttgl2 ; ?>";

            </script>
            <?php
}
 


?>