

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
                      <a href="?page=karyawan&aksi=tambah"  class="btn btn-success">Tambah Data </a> 
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
                          <th>Shifting</th>
                          <th>Upah</th>
                                        <th>Action</th>
                                         <th>Action</th>
                                         <th>Profile</th>

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
<td>
    <a  href="?page=karyawan&aksi=shift&id=<?php echo $datakaryawan['id_karyawan'];?>"  class="btn btn-success"> Atur</a>
</td>
<td>
    <a  href="?page=karyawan&aksi=upah&id=<?php echo $datakaryawan['id_karyawan'];?>"  class="btn btn-success"> Atur</a>
</td>
<td>
    <a  href="?page=karyawan&aksi=ubah&id=<?php echo $datakaryawan['id_karyawan'];?>"  class="btn btn-warning"> Ubah</a>
</td>
<td>
      <a  href="?page=karyawan&aksi=hapus&id=<?php echo $datakaryawan['id_karyawan'];?>"  class="btn btn-danger"> Hapus</a>
</td>
<td>
    <a  href="?page=karyawan&aksi=view&id=<?php echo $datakaryawan['id_karyawan'];?>"  class="btn btn-info"> View</a>
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