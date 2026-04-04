<div class="row">
    <div class="col-md-12">
        <!-- Advanced Tables -->
        <div class="panel panel-primary">
            <div class="box-header with-border" style=" background-color:#5F9EA0; border:1px ; color:white; ">
                <h3 class="box-title">Data Karyawan</h3>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="panel-body">
                    <div class="row" style=" background-color:white; border:1px ; color:black; ">
                    </div>
            </form>
            <div class="form-group "></div>

            <div class="table-responsive">

                <table class="table table-bordered table-striped" id="dataTables-example">
                    <thead style="border-right : 0px;">
                        <tr style="border-right : 0px;">
                            <th width="5%">No</th>
                            <th>No. Absen</th>
                            <th>Nama </th>
                            <th>Bagian</th>
                            <th>Sub Bagian</th>
                            <th>Shift</th>
                            <th>Jenis Kelamin</th>
                            <th style=" width: 10px; ">Daily Activity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $tampil = $koneksi->query("SELECT ms_karyawan.* , ms_sub_department.nama_sub_department , ms_departmen.nama_departmen, tb_jadwal.keterangan FROM ms_karyawan LEFT JOIN ms_departmen on ms_karyawan.id_departmen = ms_departmen.id_departmen 
left join tb_jadwal on ms_karyawan.id_jadwal = tb_jadwal.id_jadwal left join ms_sub_department on ms_karyawan.id_sub_department = ms_sub_department.id_sub_department where ms_karyawan.status_karyawan = 'Aktif'
 ");
                        while ($datakaryawan = $tampil->fetch_assoc()) {
                        ?>

                            <tr>
                                <td><?php echo $no ?></td>
                                <td><?php echo $datakaryawan['no_absen'] ?></td>
                                <td><?php echo $datakaryawan['nama_karyawan'] ?></td>
                                <td><?php echo $datakaryawan['nama_departmen'] ?></td>
                                <td><?php echo $datakaryawan['nama_sub_department'] ?></td>
                                <td><?php echo $datakaryawan['keterangan'] ?></td>
                                <td><?php echo $datakaryawan['jenis_kelamin'] ?></td>
                                <td style=" width: 200px;">
                                    <a href="?page=dailyactivity&aksi=tambah&idkaryawan=<?php echo $datakaryawan['id_karyawan']; ?>" class="btn btn-success"> Daily Activity</a>
                                </td>
                                <!--<td> <a  href="?page=order&id=<?php echo $data['id_transaksi']; ?>"  class="btn btn-success"> Update</a></td>-->
                            </tr>

                        <?php $no++;
                        } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            pageLength: 5,
            paging: false,
            scrollCollapse: true,
            scrollY: '400px'
        });

    });
</script>