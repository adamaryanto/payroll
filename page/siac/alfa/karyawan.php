

<div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-primary"  >
                    <div class="box-header with-border" style=" background-color:#5F9EA0; border:1px ; color:white; ">
              <h3 class="box-title">Data Karyawan</h3>
            </div>
             <form method="POST"  enctype="multipart/form-data">
                        <div class="panel-body">
                           
                      
              
            <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                 <div class="form-group col-md-6">
                     
                </div>
                </div>

                
                                    </form>
                                    <div class="form-group "></div>

                            <div class="table-responsive">
                                
                                <table class="table table-bordered table-striped" id="dataTables-example">
                                    <thead >
                                        <tr>
                                        <th width="5%">No</th>
                                           <th >No. Absen</th>
                                             <th >No. KTP</th>

                                        <th >Nama </th>
                                        <th >Jenis Kelamin</th> 
                                          <th >Alamat Tinggal </th>
                                          <th >Alamat KTP </th>
                                           <th >Agama </th>
                                            <th >Status Kawin</th>
                                          <th >Status Karyawan</th>
                                           <th >Tanggal Aktif </th>
                        
                                        <th>Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                                                <?php


$no = 1;


$tampil = $koneksi->query("SELECT ms_karyawan.* FROM ms_karyawan   ");
    while ($datakaryawan=$tampil->fetch_assoc())
    {

?>


                                        <tr>
<td><?php echo $no ?></td>
<td><?php echo $datakaryawan['no_absen'] ?></td>
<td><?php echo $datakaryawan['no_ktp'] ?></td>
<td><?php echo $datakaryawan['nama_karyawan'] ?></td>
<td><?php echo $datakaryawan['jenis_kelamin'] ?></td>
<td><?php echo $datakaryawan['alamat_tinggal'] ?></td>
<td><?php echo $datakaryawan['alamat_ktp'] ?></td>
<td><?php echo $datakaryawan['agama'] ?></td>
<td><?php echo $datakaryawan['status_kawin'] ?></td>
<td><?php echo $datakaryawan['status_karyawan'] ?></td>
<td><?php echo $datakaryawan['tgl_aktif'] ?></td>
<td>
    <a  href="?page=alfa&aksi=tambah&idkaryawan=<?php echo $datakaryawan['id_karyawan'];?>"  class="btn btn-info"> Pilih</a>
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