

<div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-primary"  >
                    <div class="box-header with-border" style=" background-color:#5F9EA0; border:1px ; color:white; ">
              <h3 class="box-title">Data Bagian</h3>
            </div>
             <form method="POST"  enctype="multipart/form-data">
                        <div class="panel-body">
                           
                      
              
            <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                 <div class="form-group col-md-6">
                      <a href="?page=bagian&aksi=tambah"  class="btn btn-success">Tambah Data </a> 
                </div>
                </div>

                
                                    </form>
                                    <div class="form-group "></div>

                            <div class="table-responsive">
                                
                                <table class="table table-bordered table-striped" id="dataTables-example">
                                    <thead>
                                        <tr>
                                        <th width="5%">No</th>
                                         <th >Nama Bagian</th>
                                        <th>Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php


$no = 1;


$tampil = $koneksi->query("SELECT *  FROM ms_departmen");
    while ($data=$tampil->fetch_assoc())
    {

?>


                                        <tr>
<td><?php echo $no ?></td>
<td><?php echo $data['nama_departmen'] ?></td>

<td>
    
<a  href="?page=bagian&aksi=ubah&id=<?php echo $data['id_departmen'];?>"  class="btn btn-warning"> Ubah</a>



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