
<div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
					<div class="panel panel-primary"  >
					<div class="box-header with-border" style=" background-color: #5F9EA0; border:1px ; color:white; ">
              <h3 class="box-title">List Perusahaan</h3>
            </div>
                        <div class="panel-body">
                           
                            <div class="table-responsive">
                                <table class="table table-striped  table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
										<th width="5%">No</th>
                                        <th >NIB</th>
						<th>Nama Perusahaan</th>
						<th>Alamat</th>
						<th>No. Telepon</th>
						<th>Email </th>
                        <th>Direktur</th>
                        <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php


$no = 1;


$tampil = $koneksi->query("SELECT *  FROM ms_perusahaan  ");
	while ($data=$tampil->fetch_assoc())
	{

?>


                                        <tr>
<td><?php echo $no ?></td>
<td><?php echo $data['nib'] ?></td>
<td><?php echo $data['nama_perusahaan']; ?></td>
<td><?php echo $data['alamat_perusahaan'];  ?></td>
<td><?php echo $data['telepon_perusahaan']; ?></td>
<td><?php echo $data['email_perusahaan']; ?></td>
<td><?php echo $data['direktur']; ?></td>

<td>
<a  href="?page=perusahaan&aksi=cht&id=<?php echo $data['id_perusahaan'];?>"  class="btn btn-success"> Update</a>

</td>
                                      
                                            
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