

<div class="row px-3 mt-4">
    <div class="col-md-12">
        <div class="card rounded-2xl shadow-sm border-0">
            <div class="card-header bg-white border-b border-gray-100 py-4 flex justify-between items-center rounded-t-2xl">
                <h3 class="card-title text-xl font-bold text-gray-800 m-0">Data Bagian (Departemen)</h3>
                <div class="card-tools">
                    <a href="?page=bagian&aksi=tambah" class="btn btn-primary bg-brand-600 hover:bg-brand-700 border-0 rounded-lg shadow-sm px-4 py-2 font-medium transition-colors">
                        <i class="fas fa-plus mr-2"></i> Tambah Bagian
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped border-b border-gray-100" id="dataTables-example">
                        <thead class="bg-gray-50 text-gray-600 text-sm">
                                        <tr>
                                        <th width="5%">No</th>
                                         <th >Nama Bagian</th>
                                         <th class="text-center" width="10%">Aksi</th>

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

<td class="text-center align-middle">
    <div class="flex flex-wrap gap-2 justify-center">
        <a href="?page=bagian&aksi=ubah&id=<?php echo $data['id_departmen'];?>" class="btn btn-sm bg-amber-500 hover:bg-amber-600 text-white border-0 shadow-sm rounded-md transition-colors" title="Ubah Bagian"><i class="fas fa-edit"></i></a>
    </div>
</td>
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