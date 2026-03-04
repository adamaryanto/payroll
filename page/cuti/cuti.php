<?php


  


if(isset($_GET['ttgl1']) || isset($_GET['ttgl2'])){
    $ttgl1 = $_GET['ttgl1'];
    $ttgl2 = $_GET['ttgl2'];

$tampil = $koneksi->query("SELECT A.* , B.nama_karyawan , B.no_absen , B.jenis_kelamin FROM tb_cuti A LEFT JOIN ms_karyawan B on A.id_karyawan = B.id_karyawan where A.tgl_awal_cuti  between '$ttgl1' AND '$ttgl2' ");
  


}else{
     $ttgl1 = '';
    $ttgl2 = '';

$tampil = $koneksi->query("SELECT A.* , B.nama_karyawan , B.no_absen , B.jenis_kelamin FROM tb_cuti A LEFT JOIN ms_karyawan B on A.id_karyawan = B.id_karyawan  ");
}

    ?>

<div class="row px-3 mt-4">
    <div class="col-md-12">
        <div class="card rounded-2xl shadow-sm border-0">
            <div class="card-header bg-white border-b border-gray-100 py-4 flex justify-between items-center rounded-t-2xl">
                <h3 class="card-title text-xl font-bold text-gray-800 m-0">Data Karyawan Cuti</h3>
                <div class="card-tools">
                    <a href="?page=cuti&aksi=tambah" class="btn btn-primary bg-brand-600 hover:bg-brand-700 border-0 rounded-lg shadow-sm px-4 py-2 font-medium transition-colors">
                        <i class="fas fa-plus mr-2"></i> Tambah Data Cuti
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <!-- Search Filter Form -->
                <form method="POST" enctype="multipart/form-data" class="bg-gray-50 rounded-xl p-4 mb-6 border border-gray-100 shadow-sm">
                    <div class="row items-end"> 
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label class="font-medium text-gray-700 text-sm mb-1">Dari Tanggal</label>
                            <input autocomplete="off" type="date" name="ttgl1" required value="<?php echo $ttgl1 ; ?>" class="form-control rounded-lg border-gray-300 focus:border-brand-500 focus:ring focus:ring-brand-200 transition-all shadow-sm"/>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label class="font-medium text-gray-700 text-sm mb-1">Sampai Tanggal</label>
                            <input autocomplete="off" type="date" name="ttgl2" value="<?php echo $ttgl2 ; ?>" required class="form-control rounded-lg border-gray-300 focus:border-brand-500 focus:ring focus:ring-brand-200 transition-all shadow-sm"/>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" name="simpan" value="Search" class="btn btn-primary w-full bg-slate-800 hover:bg-slate-900 border-0 rounded-lg shadow-sm font-medium transition-colors">
                                <i class="fas fa-search mr-1"></i> Tampilkan
                            </button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover table-striped border-b border-gray-100" id="dataTables-example">
                        <thead class="bg-gray-50 text-gray-600 text-sm">
                                        <tr>
                                        <th width="5%">No</th>
                                           <th >No. Absen</th>
                                            <th >Nama Karyawan</th>
                                             <th >Jenis Kelamin</th>
                                              <th >Dari Tanggal</th>
                                             <th >Sampai Tanggal</th>
                                                <th >Lama</th>
                                        <th >Keterangan Cuti</th>
                                        
                        
                                         <th class="text-center" width="10%">Aksi</th>

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
<td><?php echo $data['tgl_awal_cuti'] ?></td>
<td><?php echo $data['tgl_akhir_cuti'] ?></td>
<td><?php 
$tgl1 = strtotime($data['tgl_awal_cuti']); 
$tgl2 = strtotime($data['tgl_akhir_cuti']); 

$jarak = $tgl2 - $tgl1;

$hari = ($jarak / 60 / 60 / 24) +1;

echo $hari ?></td>
<td><?php echo $data['keterangan_cuti'] ?></td>

<td class="text-center align-middle">
    <div class="flex flex-wrap gap-2 justify-center">
        <a href="?page=cuti&aksi=hapus&id=<?php echo $data['id_cuti'];?>" class="btn btn-sm bg-rose-500 hover:bg-rose-600 text-white border-0 shadow-sm rounded-md transition-colors" title="Batal Cuti"><i class="fas fa-trash"></i></a>
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