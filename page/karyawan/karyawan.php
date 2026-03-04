

<div class="row px-3 mt-4">
    <div class="col-md-12">
        <div class="card rounded-2xl shadow-sm border-0">
            <div class="card-header bg-white border-b border-gray-100 py-4 flex justify-between items-center rounded-t-2xl">
                <h3 class="card-title text-xl font-bold text-gray-800 m-0">Data Karyawan</h3>
                <div class="card-tools">
                    <a href="?page=karyawan&aksi=tambah" class="btn btn-primary bg-brand-600 hover:bg-brand-700 border-0 rounded-lg shadow-sm px-4 py-2 font-medium transition-colors">
                        <i class="fas fa-plus mr-2"></i> Tambah Data
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped border-b border-gray-100" id="dataTables-example">
                        <thead class="bg-gray-50 text-gray-600 text-sm">
                                        <tr>
                                        <th width="5%">No</th>
                                           <th >No. Absen</th>
                                           <th >Nama </th>
                                           <th >Bagian</th>
                                            <th >Sub Bagian</th>
                                            <th >Shift</th>
                                            <th >OS/DHK</th>
                                            <th >Golongan</th>
                                           
                                            <th >No. KTP</th>
                                             <th >No. SIM</th>
                                              <th >No. NPWP</th>
                                             <th >No. BPJS</th>

                                        
                                        <th >Jenis Kelamin</th> 
                                          <th >Alamat Tinggal </th>
                                          <th >Alamat KTP </th>
                                           <th >Agama </th>
                                            <th >Status Kawin</th>
                                          <th >Status Karyawan</th>
                                           <th >Tanggal Aktif </th>
                                            <th >Tanggal Nonaktif</th>
                                             <th >Upah Harian</th>
                                              <th >Upah Mingguan</th>
                                               <th >Upah Bulanan</th>
<th class="text-center" width="10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                                                <?php


$no = 1;


$tampil = $koneksi->query("SELECT ms_karyawan.* , ms_sub_department.nama_sub_department , ms_departmen.nama_departmen, tb_jadwal.keterangan FROM ms_karyawan LEFT JOIN ms_departmen on ms_karyawan.id_departmen = ms_departmen.id_departmen 
left join tb_jadwal on ms_karyawan.id_jadwal = tb_jadwal.id_jadwal left join ms_sub_department on ms_karyawan.id_sub_department = ms_sub_department.id_sub_department
 ");
    while ($datakaryawan=$tampil->fetch_assoc())
    {

?>


                                        <tr>
<td><?php echo $no ?></td>
<td><?php echo $datakaryawan['no_absen'] ?></td>
<td><?php echo $datakaryawan['nama_karyawan'] ?></td>
<td><?php echo $datakaryawan['nama_departmen'] ?></td>
<td><?php echo $datakaryawan['nama_sub_department'] ?></td>
<td><?php echo $datakaryawan['keterangan'] ?></td>
<td><?php echo $datakaryawan['OS_DHK'] ?></td>
<td><?php echo $datakaryawan['golongan'] ?></td>
<td><?php echo $datakaryawan['no_ktp'] ?></td>
<td><?php echo $datakaryawan['no_sim'] ?></td>
<td><?php echo $datakaryawan['no_npwp'] ?></td>
<td><?php echo $datakaryawan['no_bpjs'] ?></td>

<td><?php echo $datakaryawan['jenis_kelamin'] ?></td>
<td><?php echo $datakaryawan['alamat_tinggal'] ?></td>
<td><?php echo $datakaryawan['alamat_ktp'] ?></td>
<td><?php echo $datakaryawan['agama'] ?></td>
<td><?php echo $datakaryawan['status_kawin'] ?></td>
<td><?php echo $datakaryawan['status_karyawan'] ?></td>
<td><?php echo $datakaryawan['tgl_aktif'] ?></td>
<td><?php echo $datakaryawan['tgl_nonaktif'] ?></td>
<td><?php echo number_format( $datakaryawan['upah_harian'],0,',','.')  ?></td>
<td><?php  echo number_format( $datakaryawan['upah_mingguan'],0,',','.') ?></td>
<td><?php echo number_format( $datakaryawan['upah_bulanan'],0,',','.') ?></td>
<td class="text-center align-middle">
    <div class="flex flex-wrap gap-2 justify-center">
        <a href="?page=karyawan&aksi=shift&id=<?php echo $datakaryawan['id_karyawan'];?>" class="btn btn-sm bg-teal-500 hover:bg-teal-600 text-white border-0 shadow-sm rounded-md transition-colors" title="Atur Shift"><i class="fas fa-clock"></i></a>
        <a href="?page=karyawan&aksi=upah&id=<?php echo $datakaryawan['id_karyawan'];?>" class="btn btn-sm bg-emerald-500 hover:bg-emerald-600 text-white border-0 shadow-sm rounded-md transition-colors" title="Atur Upah"><i class="fas fa-money-bill-wave"></i></a>
        <a href="?page=karyawan&aksi=ubah&id=<?php echo $datakaryawan['id_karyawan'];?>" class="btn btn-sm bg-amber-500 hover:bg-amber-600 text-white border-0 shadow-sm rounded-md transition-colors" title="Ubah Data"><i class="fas fa-edit"></i></a>
        <a href="?page=karyawan&aksi=hapus&id=<?php echo $datakaryawan['id_karyawan'];?>" class="btn btn-sm bg-rose-500 hover:bg-rose-600 text-white border-0 shadow-sm rounded-md transition-colors" title="Hapus Data"><i class="fas fa-trash"></i></a>
        <a href="?page=karyawan&aksi=view&id=<?php echo $datakaryawan['id_karyawan'];?>" class="btn btn-sm bg-blue-500 hover:bg-blue-600 text-white border-0 shadow-sm rounded-md transition-colors" title="Lihat Profil"><i class="fas fa-user"></i></a>
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
  
    <script>
 $(document).ready( function () {
$('#dataTables-example').DataTable({
    pageLength: 100,
    "searching": true
}
);

} );
</script>