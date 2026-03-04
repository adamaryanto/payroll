

<div class="row px-3 mt-4">
    <div class="col-md-12">
        <div class="card rounded-2xl shadow-sm border-0">
            <div class="card-header bg-white border-b border-gray-100 py-4 flex justify-between items-center rounded-t-2xl">
                <h3 class="card-title text-xl font-bold text-gray-800 m-0">Data Akun User</h3>
                <div class="card-tools">
                    <a href="?page=user&aksi=tambah" class="btn btn-primary bg-brand-600 hover:bg-brand-700 border-0 rounded-lg shadow-sm px-4 py-2 font-medium transition-colors">
                        <i class="fas fa-user-plus mr-2"></i> Tambah User
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped border-b border-gray-100" id="dataTables-example">
                        <thead class="bg-gray-50 text-gray-600 text-sm">
                                        <tr>
                                        <th width="5%">No</th>
                                         <th >Username</th>
                                         <th class="text-center" width="10%">Aksi</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php


$no = 1;


$tampil = $koneksi->query("SELECT *  FROM ms_login");
    while ($data=$tampil->fetch_assoc())
    {

?>


                                        <tr>
<td><?php echo $no ?></td>
<td><?php echo $data['user_login'] ?></td>
<td class="text-center align-middle">
    <div class="flex flex-wrap gap-2 justify-center">
        <a href="?page=user&aksi=ubah&id=<?php echo $data['id_login'];?>" class="btn btn-sm bg-amber-500 hover:bg-amber-600 text-white border-0 shadow-sm rounded-md transition-colors" title="Ubah User"><i class="fas fa-edit"></i></a>
        <a href="?page=user&aksi=hapus&id=<?php echo $data['id_login'];?>" class="btn btn-sm bg-rose-500 hover:bg-rose-600 text-white border-0 shadow-sm rounded-md transition-colors" title="Hapus User"><i class="fas fa-trash"></i></a>
    </div>
</td>
<!-- Header action was repeated in the old table so leaving only 1 TD instead of 2 for better structure since we combined the buttons into one cell -->
                                        </tr>

                                       <?php  $no++; } ?>

                                    </tbody>   
                                    </table>
                            </div>
                        </div><!-- /.card-body -->
                    </div><!-- /.card -->
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