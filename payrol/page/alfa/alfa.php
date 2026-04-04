<?php


  


if(isset($_GET['ttgl1']) || isset($_GET['ttgl2'])){
    $ttgl1 = $_GET['ttgl1'];
    $ttgl2 = $_GET['ttgl2'];

$tampil = $koneksi->query("SELECT A.* , B.nama_karyawan , B.no_absen , B.jenis_kelamin FROM tb_alfa A LEFT JOIN ms_karyawan B on A.id_karyawan = B.id_karyawan where A.tgl_awal_cuti  between '$ttgl1' AND '$ttgl2' ");
  


}else{
     $ttgl1 = '';
    $ttgl2 = '';

$tampil = $koneksi->query("SELECT A.* , B.nama_karyawan , B.no_absen , B.jenis_kelamin FROM tb_alfa A LEFT JOIN ms_karyawan B on A.id_karyawan = B.id_karyawan  ");
}

    ?>

<div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-primary"  >
                    <div class="box-header with-border" style=" background-color:#5F9EA0; border:1px ; color:white; ">
              <h3 class="box-title">Data Karyawan Alfa</h3>
            </div>
             <form method="POST"  enctype="multipart/form-data">
                        <div class="panel-body">
                           
                      
              
            <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                 <div class="form-group col-md-6">
                      <a href="?page=alfa&aksi=tambah"  class="btn btn-success">Tambah Data Alfa</a> 
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

               

                </div>
                                <table class="table table-bordered table-striped" id="dataTables-example">
                                    <thead >
                                        <tr>
                                        <th width="5%">No</th>
                                           <th >No. Absen</th>
                                            <th >Nama Karyawan</th>
                                             <th >Jenis Kelamin</th>
                                              <th >Dari Tanggal</th>
                                             <th >Sampai Tanggal</th>
                                                <th >Lama</th>
                                        <th >Keterangan Alfa</th>
                                        
                        
                                        <th>Action</th>

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
<td><?php echo $data['no_absen'] ?></td>
<td><?php echo $data['nama_karyawan'] ?></td>
<td><?php echo $data['jenis_kelamin'] ?></td>
<td><?php echo $data['tgl_awal_alfa'] ?></td>
<td><?php echo $data['tgl_akhir_alfa'] ?></td>
<td><?php 
$tgl1 = strtotime($data['tgl_awal_alfa']); 
$tgl2 = strtotime($data['tgl_akhir_alfa']); 

$jarak = $tgl2 - $tgl1;

$hari = ($jarak / 60 / 60 / 24) +1;

echo $hari ?></td>
<td><?php echo $data['keterangan_alfa'] ?></td>

<td>
    <a  href="?page=alfa&aksi=hapus&id=<?php echo $data['id_alfa'];?>"  class="btn btn-danger"> Hapus</a>
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