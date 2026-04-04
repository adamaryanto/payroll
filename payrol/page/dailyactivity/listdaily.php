<?php
if (isset($_GET['ttgl1']) || isset($_GET['ttgl2'])) {
    $ttgl1 = $_GET['ttgl1'];
    $ttgl2 = $_GET['ttgl2'];

    $tampil = $koneksi->query("SELECT aa.* , bb.no_absen, bb.nama_karyawan , bb.jenis_kelamin ,cc.nama_sub_department, dd.nama_departmen from tb_hasil_produksi aa left join ms_karyawan bb on aa.id_karyawan = bb.id_karyawan left join ms_sub_department cc on bb.id_sub_department = cc.id_sub_department LEFT JOIN ms_departmen dd on bb.id_departmen = dd.id_departmen where aa.tgl between '$ttgl1' AND '$ttgl2' order by aa.id_hasil_produksi desc
 ");
} else {
    $ttgl1 = date("Y-m-d");
    $ttgl2 = date("Y-m-d");

    $tampil = $koneksi->query("SELECT aa.* , bb.no_absen, bb.nama_karyawan , bb.jenis_kelamin ,cc.nama_sub_department, dd.nama_departmen from tb_hasil_produksi aa left join ms_karyawan bb on aa.id_karyawan = bb.id_karyawan left join ms_sub_department cc on bb.id_sub_department = cc.id_sub_department LEFT JOIN ms_departmen dd on bb.id_departmen = dd.id_departmen where aa.tgl between '$ttgl1' AND '$ttgl2'  order by aa.id_hasil_produksi desc
 ");
}

?>
<div class="row">
    <div class="col-md-12">
        <!-- Advanced Tables -->
        <div class="panel panel-primary">
            <div class="box-header with-border" style=" background-color:#5F9EA0; border:1px ; color:white; ">
                <h3 class="box-title">List Daily Activity</h3>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="panel-body">

                    <div class="row" style=" background-color:whitesmoke; border:1px ; color:black; ">
                        <div class="form-group col-md-2">
                            <label class="font-weight-bold"> Dari Tanggal</label>
                            <input placeholder="*" autocomplete="off" type="date" name="ttgl1" required value="<?php echo $ttgl1; ?>" class="form-control" />
                            <input type="submit" name="simpan" value="Search" class="btn btn-primary">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="font-weight-bold">Sampai Tanggal</label>
                            <input placeholder="*" autocomplete="off" type="date" name="ttgl2" value="<?php echo $ttgl2; ?>" required class="form-control" />

                        </div>

                    </div>


            </form>
            <div class="form-group "></div>

            <div class="table-responsive">

                <table class="table Responsive Hover Table" id="dataTables-example">
                    <thead style="  border-right: 0px;">
                        <tr style="  border-right : 0px;">
                            <th width="5%">No</th>
                            <th>No. Absen</th>
                            <th>Nama </th>
                            <th>Bagian</th>
                            <th>Sub Bagian</th>

                            <th>Jenis Kelamin</th>
                            <th>Tanggal</th>
                            <th>Target</th>
                            <th>Hasil</th>
                            <th>Upah</th>
                            <th>Potongan</th>
                            <th>Lembur</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;

                        while ($datakaryawan = $tampil->fetch_assoc()) {
                        ?>

                            <tr>
                                <td><?php echo $no ?></td>
                                <td><?php echo $datakaryawan['no_absen'] ?></td>
                                <td><?php echo $datakaryawan['nama_karyawan'] ?></td>
                                <td><?php echo $datakaryawan['nama_departmen'] ?></td>
                                <td><?php echo $datakaryawan['nama_sub_department'] ?></td>
                                <td><?php echo $datakaryawan['jenis_kelamin'] ?></td>
                                <td><?php echo $datakaryawan['tgl'] ?></td>
                                <td><?php echo  number_format($datakaryawan['target'], 0, ',', '.')  ?></td>
                                <td><?php echo  number_format($datakaryawan['hasil'], 0, ',', '.')  ?></td>

                                <td><?php echo  number_format($datakaryawan['upah'], 0, ',', '.')  ?></td>
                                <td><?php echo  number_format($datakaryawan['potongan'], 0, ',', '.')  ?></td>
                                <td><?php echo  number_format($datakaryawan['lembur'], 0, ',', '.')  ?></td>
                                <td><?php echo  number_format($datakaryawan['upah'] + $datakaryawan['lembur'] - $datakaryawan['potongan'], 0, ',', '.')  ?></td>

                                <!--<td><a  href="?page=order&id=<?php echo $data['id_transaksi']; ?>"  class="btn btn-success"> Update</a></td>-->

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

<?php

$ttgl1 = @$_POST['ttgl1'];
$ttgl2 = @$_POST['ttgl2'];

$simpan = @$_POST['simpan'];
if ($simpan) {
?><script type="text/javascript">
        window.location.href = "?page=dailyactivity&aksi=list&ttgl1=<?php echo $ttgl1; ?>&ttgl2=<?php echo $ttgl2; ?>";
    </script>
<?php
}



?>